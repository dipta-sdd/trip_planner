<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    //
    protected $fillable = [
        'trip_id',
        'name',
        'arrival_date',
        'departure_date',
        'notes',
    ];
   
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function accommodations()
    {
        return $this->hasMany(Accommodation::class);
    }

    public function transportations()
    {
        return $this->hasMany(Transportation::class);
    }
}
