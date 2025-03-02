<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    protected $fillable = [
        'destination_id',
        'type',
        'departure_location',
        'arrival_location',
        'departure_time',
        'arrival_time',
        'company',
        'booking_reference',
        'notes',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
