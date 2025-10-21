<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeekResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'grader' => new AsistenResource($this->whenLoaded('asisten')),
            'project_id' => $this->project_id,
            'date' => $this->date,
            'week_type' => new WeekTypeResource($this->whenLoaded('weekType')),
            'notes' => $this->notes,
            'grades' => GradeResource::collection($this->whenLoaded('grades'))
        ];
    }
}
