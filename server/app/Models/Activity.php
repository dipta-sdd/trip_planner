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
        'type',
        'cost',
        'details',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'cost' => 'decimal:2',
        'details' => 'json',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    // Helper method to get formatted details based on activity type
    public function getTypeSpecificDetails(): array
    {
        $details = $this->details ?? [];
        
        return match($this->type) {
            'transportation' => [
                'mode' => $details['mode'] ?? null,
                'booking_reference' => $details['booking_reference'] ?? null,
                'provider' => $details['provider'] ?? null,
            ],
            'accommodation' => [
                'check_in' => $details['check_in'] ?? null,
                'check_out' => $details['check_out'] ?? null,
                'property_name' => $details['property_name'] ?? null,
                'booking_reference' => $details['booking_reference'] ?? null,
            ],
            default => $details,
        };
    }
} 