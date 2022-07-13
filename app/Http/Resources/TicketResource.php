<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'id'=> $this->id,
            'user_id'=> $this->user_id,
            'event_id'=> $this->event_id,
            'paid'=> $this->paid,
            'checkin_at'=> $this->checkin_at,
            'note'=> $this->note,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,

            // RELATIONSHIPS
            'user' => $this->whenLoaded('user'),

            // JOINED USER
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email
        ];
    }
}
