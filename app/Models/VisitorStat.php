<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorStat extends Model
{
    /** @use HasFactory<\Database\Factories\VisitorStatFactory> */
    use HasFactory;

    protected $fillable = [
        'visit_date',
        'total_visits',
        'unique_visitors',
        'page_views',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
        ];
    }
}
