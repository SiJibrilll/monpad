<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
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
            'week_id' => $this->week_id,
            'grade_type' => new GradeTypeResource($this->whenLoaded('gradeType')),
            'grade' => $this->grade
        ];
    }
}
