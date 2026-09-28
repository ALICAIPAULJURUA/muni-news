<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionPattern extends Model
{
    protected $fillable = [
        'section_slug',
        'section_name',
        'image_path',
        'opacity',
        'blend_mode',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'opacity' => 'float',
            'is_active' => 'boolean',
        ];
    }
}