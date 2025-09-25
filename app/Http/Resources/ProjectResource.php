<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'nama_projek' => $this->nama_projek,
            'semester' => $this->semester,
            'deskripsi' => $this->deskripsi,
            'tahun_ajaran' => $this->tahun_ajaran,
            'owner' => new DosenResource($this->whenLoaded('owner')),
        ];
    }
}