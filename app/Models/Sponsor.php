<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slogan',
        'link',
        'logo',
        'type',
        'is_active',
    ];
}
