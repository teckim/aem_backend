<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory, Sluggable, SoftDeletes;

    protected $fillable = [
        'id',
        'category_id',
        'location_id',
        'team_id',
        'title',
        'slug',
        'description',
        'image',
        'start_at',
        'end_at',
        'publish_at',
        'unpublish_at',
        'tickets_count',
        'suspended',
        'price',
    ];

    protected $appends = ['state', 'published', 'checked_tickets', 'sold_tickets'];
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];
    protected $keyType = 'string';
    public $incrementing = false;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getSoldTicketsAttribute() 
    {
        return $this->tickets()->count();
    }

    public function getCheckedTicketsAttribute() 
    {
        return $this->tickets()->whereNotNull('checkin_at')->count();
    }

    public function getStateAttribute()
    {
        $start = $this->attributes['start_at'];
        $end = $this->attributes['end_at'];
        $now = Carbon::now();

        if ($start > $now) $state = 'soon';
        else if ($end < $now) $state = 'past';
        else $state = 'live';

        return $state;
    }

    public function getPublishedAttribute()
    {
        $publish = $this->attributes['publish_at'];
        $unpublish = $this->attributes['unpublish_at'];
        $now = Carbon::now();

        $published = ($publish < $now) || (isset($unpublish) && $unpublish > $now);
        return $published;
    }
}
