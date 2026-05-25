<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    /** @use HasFactory<\Database\Factories\AchievementFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'performance_id',
        'title',
        'description',
        'achievement_date',
        'year',
        'badge_color',
        'icon',
        'media_library_id',
        'sort_order',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'achievement_date' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public function performance(): BelongsTo
    {
        return $this->belongsTo(Performance::class);
    }
}
