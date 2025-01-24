<?php

namespace App\Http\Resources\Usuarios;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioContatoResource extends JsonResource
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
            'tipo' => $this->tipo,
            'contato' => $this->contato
        ];
    }
}
