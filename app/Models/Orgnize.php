<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Orgnize extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'event_id',
    ];
}
