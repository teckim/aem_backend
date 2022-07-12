<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Cviebrock\EloquentSluggable\Sluggable;

class Team extends Model
{
    use HasFactory, Sluggable, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'province',
        'country',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function events() 
    {
        return $this->hasMany(Event::class);
    }
}
