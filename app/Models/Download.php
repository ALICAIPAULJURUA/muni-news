<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'file_path',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'download_count' => 'integer',
        ];
    }
}
