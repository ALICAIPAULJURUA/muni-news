<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'publication_year',
        'cover_image',
        'file_path',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'download_count' => 'integer',
        ];
    }
}
