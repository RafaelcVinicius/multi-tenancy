<?php

namespace App\Http\Resources\Usuarios;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioDetalheResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cpf' => $this->cpf,
            'apelido' => $this->apelido,
            'funcao' => $this->funcao,
        ];
    }
}
