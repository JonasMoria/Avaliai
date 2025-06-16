<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterEnterpriseRequest;
use App\Services\EnterpriseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function login (LoginRequest $request) {
        return $this->enterpriseService->login($request->validated());
    }

    public function me(): JsonResponse {
        return response()->json(Auth::user());
    }

    public function logout (Request $request) {
        return $this->enterpriseService->logout($request->user());
    }
}
