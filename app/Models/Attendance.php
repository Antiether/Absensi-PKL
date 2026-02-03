<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public function participant() {
        return $this->belongsTo(Participant::class);
    }

    protected $fillable = [
        'participant_id',
        'date',
        'checkin_time',
        'checkout_time',
        'checkin_lat',
        'checkin_lng',
        'checkout_lat',
        'checkout_lng',
        'checkin_photo',
        'checkout_photo',
        'status',
    ];
}
