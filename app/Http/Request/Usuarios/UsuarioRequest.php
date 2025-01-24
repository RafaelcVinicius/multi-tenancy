<?php

namespace App\Http\Requests\Usuarios;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
                'nome' => ['sometimes', 'max:100'],
                'detalhes' => ['sometimes', 'array'],
                'detalhes.apelido' => ['sometimes', 'string', 'max:100'],
                'detalhes.funcao' => ['sometimes', 'string', 'max:60'],
                'contatos' => ['sometimes', 'array'],
                'contatos.*.id' => ['sometimes', 'uuid'],
                'contatos.*.tipo' => ['required', 'string', 'max:10'],
                'contatos.*.contato' => ['required', 'string', 'max:100'],
            ];
        }

        return [
            'email' => ['required', 'email', 'max:100'],
            'nome' => ['required', 'max:100'],
            'interno' => ['sometimes', 'boolean'],
            'detalhes' => ['sometimes', 'array'],
            'detalhes.cpf' => [
                'required_with:detalhes',
                'string',
                'min:11',
                'max:11',
                'regex:/^[0-9]+$/'
            ],
            'detalhes.apelido' => ['required_with:detalhes', 'string', 'max:100'],
            'detalhes.funcao' => ['required_with:detalhes', 'string', 'max:60'],
            'contatos' => ['sometimes', 'array'],
            'contatos.*.tipo' => ['required', 'string', 'max:10'],
            'contatos.*.contato' => ['required', 'string', 'max:100'],
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
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O campo e-mail deve ser um e-mail válido.',
            'email.max' => 'O campo e-mail deve ter no máximo 100 caracteres.',
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.max' => 'O campo nome deve ter no máximo 100 caracteres.',
            'interno.boolean' => 'O campo interno deve ser um booleano.',
            'detalhes.array' => 'O campo detalhes deve ser um array.',
            'detalhes.cpf.required_with' => 'O campo CPF é obrigatório.',
            'detalhes.cpf.string' => 'O campo CPF deve ser uma string.',
            'detalhes.cpf.min' => 'O campo CPF deve ter no mínimo 11 caracteres.',
            'detalhes.cpf.max' => 'O campo CPF deve ter no máximo 11 caracteres.',
            'detalhes.cpf.regex' => 'O campo CPF deve conter apenas números.',
            'detalhes.apelido.required_with' => 'O campo apelido é obrigatório.',
            'detalhes.apelido.string' => 'O campo apelido deve ser uma string.',
            'detalhes.apelido.max' => 'O campo apelido deve ter no máximo 100 caracteres.',
            'detalhes.funcao.required_with' => 'O campo função é obrigatório.',
            'detalhes.funcao.string' => 'O campo função deve ser uma string.',
            'detalhes.funcao.max' => 'O campo função deve ter no máximo 60 caracteres.',
            'contatos.array' => 'O campo contatos deve ser um array.',
            'contatos.*.id.uuid' => 'O campo id deve ser um UUID.',
            'contatos.*.tipo.required' => 'O campo tipo é obrigatório.',
            'contatos.*.tipo.string' => 'O campo tipo deve ser uma string.',
            'contatos.*.tipo.max' => 'O campo tipo deve ter no máximo 10 caracteres.',
            'contatos.*.contato.required' => 'O campo contato é obrigatório.',
            'contatos.*.contato.string' => 'O campo contato deve ser uma string.',
            'contatos.*.contato.max' => 'O campo contato deve ter no máximo 100 caracteres.',
        ];
    }
}
