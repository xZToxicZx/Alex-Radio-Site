<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimetableSlot extends Model
{
    protected $fillable = [
        'user_id', 'timestamp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
