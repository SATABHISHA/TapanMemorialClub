<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Vlog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'body',
        'video_url',
        'image_media_id',
        'menu_id',
        'submenu_id',
        'status',
        'published_at',
        'sort_order',
        'view_count',
        'is_featured',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured'  => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $vlog): void {
            if (empty($vlog->slug)) {
                $base = Str::slug($vlog->title) ?: 'vlog';
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->where('id', '!=', $vlog->id ?? 0)->exists()) {
                    $slug = $base.'-'.(++$i);
                }
                $vlog->slug = $slug;
            }
            if ($vlog->status === 'published' && empty($vlog->published_at)) {
                $vlog->published_at = now();
            }
        });
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(MediaLibrary::class, 'image_media_id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function submenu(): BelongsTo
    {
        return $this->belongsTo(Submenu::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }
}
