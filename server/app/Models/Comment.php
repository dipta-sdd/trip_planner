<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = [
        'text',
        'user_id',
        'parent_id',
        'trip_id'
    ];
}
