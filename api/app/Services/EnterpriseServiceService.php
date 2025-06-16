<?php
namespace App\Services;

use App\Models\EnterpriseService;
use Illuminate\Http\JsonResponse;

class EnterpriseServiceService {
    public function register(array $data, int $enterpriseId): JsonResponse {
        if (isset($data['image'])) {
            $path = $data['image']->store('enterprise_services', 'public');
            $data['image'] = $path;
        }

        $data['enterprise_id'] = $enterpriseId;
        $data['phone'] = $this->numbersOnly($data['phone']);
        $data['postalCode'] = $this->numbersOnly($data['postalCode']);

        $enterpriseService = EnterpriseService::create($data);

        return response()->json([
            'status' => 201,
            'message' => 'Serviço cadastrado com sucesso',
            'data' => $enterpriseService
        ], 201);
    }

    protected function numbersOnly(string $phone): string {
        return preg_replace('/\D/', '', $phone);
    }
}
