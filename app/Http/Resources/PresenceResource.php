<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PresenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'presence_id' => $this->id,
            'week_id' => $this->week_type_id,
            'group_id' => $this->group_id,
            'mahasiswa' => new MahasiswaResource($this->whenLoaded('mahasiswa')),
            'present' => $this->present,
            'date' => $this->date,
        ];
    }
}
