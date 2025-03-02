<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    protected $fillable = [
        'destination_id',
        'name',
        'check_in',
        'check_out',
        'address',
        'contact_info',
        'notes',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
