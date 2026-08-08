<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SpeakerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'role'             => $this->role,
            'company'          => $this->company,
            'twitter'          => $this->twitter,
            'facebook'         => $this->facebook,
            'linkedin'         => $this->linkedin,
            'description'      => $this->description,
            'full_description' => $this->full_description,
            'photo'            => $this->photo,
            'event_id'         => $this->event_id,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
