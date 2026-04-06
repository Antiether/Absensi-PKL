<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'office_lat',
        'office_lng',
        'radius_meter',
    ];

    protected $casts = [
        'office_lat' => 'float',
        'office_lng' => 'float',
        'radius_meter' => 'integer',
    ];
}