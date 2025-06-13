<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterEnterpriseRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'tradename' => 'required|string|max:255',
            'cnpj' => ['required', 'string', 'size:14', 'unique:enterprises,cnpj'],
            'email' => 'required|email|max:255|unique:enterprises,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password|same:password',
        ];
    }

    public function prepareForValidation(): void {
        $this->merge([
            'cnpj' => preg_replace('/[^\d]/', '', $this->cnpj),
        ]);
    }

    public function messages(): array {
        return [
            'name.required' => 'A razão social é obrigatória.',
            'name.string' => 'A razão social deve ser uma string.',
            'tradename.required' => 'O nome fantasia é obrigatório.',
            'tradename.string' => 'O nome fantasia deve ser uma string.',
            'cnpj.required' => 'O CNPJ é obrigatório.',
            'cnpj.unique' => 'Este CNPJ já está cadastrado.',
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
