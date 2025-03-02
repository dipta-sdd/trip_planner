<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'destination_id',
        'name',
        'type',
        'start_time',
        'end_time',
        'description',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
