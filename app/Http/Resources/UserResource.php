<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'image' => $this->image,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'major' => $this->major,
            'phone' => $this->phone,
            'blocked' => $this->blocked,
            'subscribed' => $this->subscribed,
            'permissions' => $this->permissions,
            // RELATIONSHIPS
            'teams' => $this->whenLoaded($this->teams),
            'social' => $this->whenLoaded($this->social),
            'roles' => $this->whenLoaded($this->roles)
        ];
    }
}
