<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Performance extends Model
{
    /** @use HasFactory<\Database\Factories\PerformanceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'year',
        'tournament',
        'position',
        'matches_played',
        'wins',
        'losses',
        'points',
        'highlight_color',
        'description',
        'stats_json',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'stats_json' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }
}
