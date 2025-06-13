<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterEnterpriseRequest;
use App\Services\EnterpriseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnterpriseController extends Controller {

    protected EnterpriseService $enterpriseService;

    public function __construct(EnterpriseService $enterpriseService) {
        $this->enterpriseService = $enterpriseService;
    }

    public function register(RegisterEnterpriseRequest $request): JsonResponse {
        $enterprise = $this->enterpriseService->register($request->validated());

        return response()->json([
            'status' => 201,
            'message' => 'Conta Empresarial criada com sucesso! Por favor, realize seu login.',
            'data' => $enterprise
        ], 201);
    }
}
