<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeFinalizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new MahasiswaResource($this->whenLoaded('users.mahasiswa_data')),
            'group' => '',
            'member_grade' => '',
            'project_grade' => $this->projectGrade(),
            'personal_grade' => $this->personalGrade($this->user_id),
            'final_grade' => '',
            'confirmed' => ''

        ];
    }
}
