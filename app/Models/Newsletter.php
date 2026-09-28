<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'publication_year',
        'cover_image',
        'featured_image',
        'author_id',
        'is_published',
        'file_path',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'download_count' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function image(): ?string
    {
        return $this->featured_image ?: $this->cover_image;
    }

    public function excerpt(int $limit = 120): string
    {
        $text = $this->content ?: $this->description ?: '';
        // Strip <style> / <script> blocks entirely (content, not just tags) so
        // embedded CSS/JS never leaks as visible text in cards.
        $text = preg_replace('/<(style|script)\b[^>]*>.*?<\/\1>/is', ' ', $text);
        // Insert spacing at block-level boundaries so words don't get glued together.
        $text = preg_replace('/<\/(p|div|h[1-6]|li|br|section|article|blockquote)>/i', ' ', $text);
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)));
        return Str::limit($text, $limit);
    }
}