<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'nama' => $this->name,
            'anggota' => MahasiswaResource::collection($this->whenLoaded('members')),
            'project' => new ProjectResource($this->whenLoaded('project')),
            'project_id' => $this->project_id
        ];
    }
}
