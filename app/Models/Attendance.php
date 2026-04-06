<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
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

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }
}
