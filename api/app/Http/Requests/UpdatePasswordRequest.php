<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePasswordRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password|same:password',
        ];
    }

    public function messages(): array {
        return [
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
