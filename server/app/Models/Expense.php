<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //
    protected $fillable = [
        'amount',
        'trip_id',
        'note',
        'date',
        'category',
        'created_by',
    ];
}
