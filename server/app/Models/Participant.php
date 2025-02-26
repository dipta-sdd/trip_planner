<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{

    //
    protected $fillable = [
        'trip_id',
        'user_id',
        'role',
        'status',
        'can_edit',
    ];

}
