<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'team_id' => $this->team_id,
            'category_id' => $this->category_id,
            'location_id' => $this->location_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'tickets_count' => $this->tickets_count,
            'suspended' => $this->suspended,
            'price' => $this->when(auth()->user()->hasRole('ADMIN') ,$this->price),

            // CALCULATED
            'sold_tickets'=> $this->sold_tickets,
            'checked_tickets'=> $this->checked_tickets,
            'state'=> $this->state,
            'published'=> $this->published,

            // RELATIONSHIPS
            'tickets' => $this->whenLoaded('tickets'),
            'category'=> $this->whenLoaded('category'),
            'location'=> $this->whenLoaded('location'),
            'team'=> $this->whenLoaded('team')
        ];
    }
}
