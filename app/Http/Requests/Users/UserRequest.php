<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
                'name' => ['sometimes', 'max:100'],
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
            'password' => ['required', 'string', 'min:8', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'name' => ['required', 'max:100'],
            'detail' => ['sometimes', 'array'],
            'detail.cpf' => [
                'required_with:detail',
                'string',
                'min:11',
                'max:11',
                'regex:/^[0-9]+$/'
            ],
            'detail.nickname' => ['required_with:detalhes', 'string', 'max:100'],
            'detail.position' => ['required_with:detalhes', 'string', 'max:60'],
            'contacts' => ['sometimes', 'array'],
            'contacts.*.type' => ['required', 'string', 'max:10'],
            'contacts.*.contact' => ['required', 'string', 'max:100'],
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
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O campo nome deve ter no máximo 100 caracteres.',
            'interno.boolean' => 'O campo interno deve ser um booleano.',
            'detail.array' => 'O campo detail deve ser um array.',
            'detail.cpf.required_with' => 'O campo CPF é obrigatório.',
            'detail.cpf.string' => 'O campo CPF deve ser uma string.',
            'detail.cpf.min' => 'O campo CPF deve ter no mínimo 11 caracteres.',
            'detail.cpf.max' => 'O campo CPF deve ter no máximo 11 caracteres.',
            'detail.cpf.regex' => 'O campo CPF deve conter apenas números.',
            'detail.nickname.required_with' => 'O campo apelido é obrigatório.',
            'detail.nickname.string' => 'O campo apelido deve ser uma string.',
            'detail.nickname.max' => 'O campo apelido deve ter no máximo 100 caracteres.',
            'detail.position.required_with' => 'O campo função é obrigatório.',
            'detail.position.string' => 'O campo função deve ser uma string.',
            'detail.position.max' => 'O campo função deve ter no máximo 60 caracteres.',
            'contacts.array' => 'O campo contatos deve ser um array.',
            'contacts.*.id.uuid' => 'O campo id deve ser um UUID.',
            'contacts.*.tipo.required' => 'O campo tipo é obrigatório.',
            'contacts.*.tipo.string' => 'O campo tipo deve ser uma string.',
            'contacts.*.tipo.max' => 'O campo tipo deve ter no máximo 10 caracteres.',
            'contacts.*.contact.required' => 'O campo contato é obrigatório.',
            'contacts.*.contact.string' => 'O campo contato deve ser uma string.',
            'contacts.*.contact.max' => 'O campo contato deve ter no máximo 100 caracteres.',
        ];
    }
}
