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
            'id' => $this->id,
            'user' => new MahasiswaResource($this->whenLoaded('user')),
            'group' => new GroupResource($this->whenLoaded('users.memberShip.group')),
            'member_grade' => $this->memberGrade(),
            'project_grade' => $this->projectGrade(),
            'personal_grade' => $this->personalGrade($this->user_id),
            'final_grade' => $this->finalGrade(),
            'confirmed' => $this->confirmed
        ];
    }
}
