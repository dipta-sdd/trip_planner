<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trip_id',
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'date',
        'type',
        'cost',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }


} 