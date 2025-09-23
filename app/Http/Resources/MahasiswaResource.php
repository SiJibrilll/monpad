<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MahasiswaResource extends JsonResource
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
            'username' => $this->name,
            'email' => $this->email,
            'nim' => $this->mahasiswa_data->nim,
            'angkatan' => $this->mahasiswa_data->angkatan,
            'prodi' => $this->mahasiswa_data->prodi,
            'jabatan' => $this->mahasiswa_data->jabatan
        ];
    }
}
