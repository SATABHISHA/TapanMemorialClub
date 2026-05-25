<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaLibrary extends Model
{
    /** @use HasFactory<\Database\Factories\MediaLibraryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'module',
        'original_name',
        'mime_type',
        'extension',
        'image_bytes',
        'thumbnail_bytes',
        'webp_bytes',
        'file_path',
        'file_size',
        'compressed_size',
        'width',
        'height',
        'is_blob',
        'hash',
        'upload_date',
    ];

    protected function casts(): array
    {
        return [
            'upload_date' => 'datetime',
            'is_blob' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
