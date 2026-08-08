<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'subtitle'   => $this->subtitle,
            'day_number' => $this->day_number,
            'start_time' => $this->start_time,
            'speaker_id' => $this->speaker_id,
            'desc'       => $this->desc,
            'event_id'   => $this->event_id,
            'speaker'    => $this->whenLoaded('speaker', fn () => new SpeakerResource($this->speaker)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
