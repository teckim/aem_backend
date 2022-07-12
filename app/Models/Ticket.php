<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
    ];
    protected $keyType = 'string';
    protected $casts = [
        'checkin_at' => 'datetime',
    ];

    public $incrementing = false;

    public function event() 
    {
        return $this->belongsTo(Event::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
