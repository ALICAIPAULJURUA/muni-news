<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('newsletters', function (Blueprint $table) {
            $table->longText('content')->nullable()->after('description');
            $table->string('featured_image', 255)->nullable()->after('cover_image');
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete()->after('publication_year');
            $table->boolean('is_published')->default(false)->after('author_id');
        });
    }

    public function down(): void
    {
        Schema::table('newsletters', function (Blueprint $table) {
            $table->dropColumn(['content', 'featured_image', 'author_id', 'is_published']);
        });
    }
};