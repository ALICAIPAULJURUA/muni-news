<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $articleType = 'App\Models\Article';

        Schema::disableForeignKeyConstraints();

        DB::statement('ALTER TABLE comments RENAME TO comments_legacy;');

        DB::statement(<<<'SQL'
CREATE TABLE comments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id BIGINT UNSIGNED NULL,
    commentable_id BIGINT UNSIGNED NULL,
    commentable_type VARCHAR(255) NULL,
    author_name VARCHAR(100) NOT NULL,
    author_email VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    is_approved TINYINT(1) NOT NULL DEFAULT '0',
    parent_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT comments_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL,
    CONSTRAINT comments_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES comments (id) ON DELETE CASCADE
)
SQL);

        DB::statement('CREATE INDEX comments_commentable_type_commentable_id_index ON comments (commentable_type, commentable_id);');
        DB::statement('CREATE INDEX comments_user_id_foreign ON comments (user_id);');
        DB::statement('CREATE INDEX comments_parent_id_foreign ON comments (parent_id);');

        $sql = "INSERT INTO comments (id, commentable_id, commentable_type, user_id, author_name, author_email, content, is_approved, parent_id, created_at, updated_at)
                SELECT id, article_id, :type, user_id, author_name, author_email, content, is_approved, parent_id, created_at, updated_at
                FROM comments_legacy";
        DB::statement($sql, ['type' => $articleType]);

        DB::statement('DROP TABLE comments_legacy;');

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::statement('ALTER TABLE comments RENAME TO comments_legacy;');

        DB::statement(<<<'SQL'
CREATE TABLE comments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    article_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    author_name VARCHAR(100) NOT NULL,
    author_email VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    is_approved TINYINT(1) NOT NULL DEFAULT '0',
    parent_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT comments_article_id_foreign FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE,
    CONSTRAINT comments_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL,
    CONSTRAINT comments_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES comments (id) ON DELETE CASCADE
)
SQL);

        DB::statement('CREATE INDEX comments_article_id_foreign ON comments (article_id);');
        DB::statement('CREATE INDEX comments_user_id_foreign ON comments (user_id);');
        DB::statement('CREATE INDEX comments_parent_id_foreign ON comments (parent_id);');

        $sql = "INSERT INTO comments (id, article_id, user_id, author_name, author_email, content, is_approved, parent_id, created_at, updated_at)
                SELECT id, commentable_id, user_id, author_name, author_email, content, is_approved, parent_id, created_at, updated_at
                FROM comments_legacy WHERE commentable_type = :type";
        DB::statement($sql, ['type' => 'App\Models\Article']);

        DB::statement('DELETE FROM comments_legacy;');
        DB::statement('DROP TABLE comments_legacy;');

        Schema::enableForeignKeyConstraints();
    }
};