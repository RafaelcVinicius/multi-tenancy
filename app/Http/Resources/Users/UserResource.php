<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Users\UserContactResource;
use App\Http\Resources\Users\UserDetailResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->public_id,
            'email' => $this->email,
            'name' => $this->name,
            'detail' => new UserDetailResource($this->whenLoaded('detail')),
            'contacts' => UserContactResource::collection($this->whenLoaded('contacts')),
            'createdAt' => $this->created_at
        ];
    }
}
