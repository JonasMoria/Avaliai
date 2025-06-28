<?php

namespace App\Http\Requests;

use App\Models\Helpers\Sanitizer;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateEnterpriseInformationsRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'tradename' => 'required|string|max:255',
            'cnpj' => ['required', 'string', 'size:14', Rule::unique('enterprises', 'cnpj')->ignore(Auth::user()->id)],
        ];
    }

    public function prepareForValidation(): void {
        $sanitizer = new Sanitizer();

        $this->merge([
            'cnpj' => $sanitizer->sanitizeNumber($this->cnpj),
            'name' => $sanitizer->sanitizeName($this->name),
            'tradename' => $sanitizer->sanitizeName($this->tradename),
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
