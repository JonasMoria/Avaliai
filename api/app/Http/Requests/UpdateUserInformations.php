<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserInformations extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'cpf' => ['required', 'string', 'size:11', Rule::unique('users', 'cpf')->ignore(Auth::user()->id)],
        ];
    }

    public function prepareForValidation(): void {
        $this->merge([
            'cpf' => preg_replace('/[^\d]/', '', $this->cpf),
        ]);
    }

    public function messages(): array {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'surname.required' => 'O sobrenome é obrigatório.',
            'surname.string' => 'O sobrenome deve ser uma string.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.regex' => 'O CPF deve estar no formato 000.000.000-00.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        $errors = $validator->errors()->all();
        $message = $errors[0] ?? 'Erro de validação';

        throw new HttpResponseException(response()->json([
            'status' => 422,
            'message' => $message,
        ], 422));
    }
}
