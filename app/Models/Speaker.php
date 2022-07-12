<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Cviebrock\EloquentSluggable\Sluggable;

class Speaker extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'major',
        'about',
        'website',
        'facebook',
        'instagram',
        'twitter',
        'email',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
