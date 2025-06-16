<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EnterpriseServiceRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'type' => 'required|string',
            'street' => 'required|string',
            'number' => 'required|string',
            'neighborhood' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'additional' => 'nullable|string',
            'website' => 'nullable|url',
        ];
    }

    public function messages(): array {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser um texto.',
            'name.max' => 'O campo nome não pode ter mais que 255 caracteres.',

            'image.image' => 'O arquivo enviado deve ser uma imagem.',
            'image.max' => 'A imagem não pode ter mais que 2MB.',

            'type.required' => 'O campo tipo é obrigatório.',
            'type.string' => 'O campo tipo deve ser um texto.',

            'street.required' => 'O campo rua é obrigatório.',
            'street.string' => 'O campo rua deve ser um texto.',

            'number.required' => 'O campo número é obrigatório.',
            'number.string' => 'O campo número deve ser um texto.',

            'neighborhood.required' => 'O campo bairro é obrigatório.',
            'neighborhood.string' => 'O campo bairro deve ser um texto.',

            'city.required' => 'O campo cidade é obrigatório.',
            'city.string' => 'O campo cidade deve ser um texto.',

            'state.required' => 'O campo estado é obrigatório.',
            'state.string' => 'O campo estado deve ser um texto.',

            'phone.required' => 'O campo telefone é obrigatório.',
            'phone.string' => 'O campo telefone deve ser um texto.',

            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O campo e-mail deve conter um endereço de e-mail válido.',

            'additional.string' => 'O campo informações adicionais deve ser um texto.',

            'website.url' => 'O campo site deve conter uma URL válida.',
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
