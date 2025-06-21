<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadProfilePhotoRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'image' => 'required|image|max:2048',
        ];
    }

    public function messages(): array {
        return [
            'image.required' => 'É necessário enviar uma imagem',
            'image.image' => 'O arquivo enviado deve ser uma imagem.',
            'image.max' => 'A imagem não pode ter mais que 2MB.',
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
