<?php

namespace App\Http\Requests;

use App\Models\ServiceRating;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RateRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'enterprise_service_id' => 'required|exists:enterprise_services,id',
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function withValidator(Validator $validator): void {
        $validator->after(function ($validator) {
            $user = $this->user();
            $serviceId = intval($this->input('enterprise_service_id'));
            $isEdit = boolval($this->input('isEdit'));

            if (!$isEdit && $user && $serviceId) {
                $alreadyRated = ServiceRating::where('user_id', $user->id)
                    ->where('enterprise_service_id', $serviceId)
                    ->exists();

                if ($alreadyRated) {
                    $validator->errors()->add('enterprise_service_id', 'Você já avaliou este serviço.');
                }
            }
        });
    }

    public function messages(): array {
        return [
            'enterprise_service_id.required' => 'O serviço avaliado é obrigatório.',
            'enterprise_service_id.exists'   => 'Serviço selecionado não encontrado.',

            'stars.required' => 'A avaliação com estrelas é obrigatória.',
            'stars.integer'  => 'O número de estrelas deve ser um valor numérico.',
            'stars.min'      => 'A avaliação mínima é de 1 estrela.',
            'stars.max'      => 'A avaliação máxima é de 5 estrelas.',

            'comment.string'  => 'O comentário deve ser um texto válido.',
            'comment.max'     => 'O comentário não pode exceder 1000 caracteres.',
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
