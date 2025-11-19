<?php

namespace App\Http\Resources;

use App\Services\UserResourceService;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' =>  UserResourceService::make($this->resource['user']),
            'role' => $this->resource['user']->getRoleNames()->first(),
            'token' => $this->resource['token']
        ];
    }
}
