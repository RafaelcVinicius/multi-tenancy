<?php

namespace App\Http\Resources\Usuarios;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
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
            'nome' => $this->nome,
            'detalhes' => new UsuarioDetalheResource($this->whenLoaded('detalhes')),
            'contatos' => UsuarioContatoResource::collection($this->whenLoaded('contatos')),
            'createdAt' => $this->created_at
        ];
    }
}
