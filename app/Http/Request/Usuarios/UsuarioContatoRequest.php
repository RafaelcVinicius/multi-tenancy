<?php

namespace App\Http\Requests\Usuarios;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioContatoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            return [
                'tipo' => ['sometimes', 'string', 'max:10', 'in:' . implode(',', config()->get('constants.contact.type'))],
                'contato' => ['sometimes', 'string', 'max:100'],
            ];
        }

        return [
            'tipo' => ['required', 'string', 'max:10', 'in:' . implode(',', config()->get('constants.contact.type'))],
            'contato' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.uuid' => 'O campo id deve ser um UUID.',
            'tipo.required' => 'O campo tipo é obrigatório.',
            'tipo.string' => 'O campo tipo deve ser uma string.',
            'tipo.max' => 'O campo tipo deve ter no máximo 10 caracteres.',
            'tipo.in' => 'O campo tipo deve ser um dos seguintes tipos: ' . implode(', ', config()->get('constants.contact.type')),
            'contato.required' => 'O campo contato é obrigatório.',
            'contato.string' => 'O campo contato deve ser uma string.',
            'contato.max' => 'O campo contato deve ter no máximo 100 caracteres.',
        ];
    }
}
