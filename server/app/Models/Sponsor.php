<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{

    protected $fillable = [
        'trip_id',
        'name',
        'website',
        'description',
        'amount',
        'created_by',
        'updated_by',
    ];
}
