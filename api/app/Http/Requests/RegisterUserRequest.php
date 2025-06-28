<?php

namespace App\Http\Requests;

use App\Models\Helpers\Sanitizer;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUserRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'cpf' => ['required', 'string', 'size:11', 'unique:users,cpf'],
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password|same:password',
        ];
    }

    public function prepareForValidation(): void {
        $sanitizer = new Sanitizer();

        $this->merge([
            'cpf' => $sanitizer->sanitizeNumber($this->cpf),
            'name' => $sanitizer->sanitizeName($this->name),
            'surname' => $sanitizer->sanitizeName($this->surname),
            'email' => trim($this->email),
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
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password_confirm.required' => 'A confirmação de senha é obrigatória.',
            'password_confirm.same' => 'A confirmação de senha deve ser igual à senha.',
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
