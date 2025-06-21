<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterEnterpriseRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\UpdateEnterpriseInformationsRequest;
use App\Http\Requests\UploadProfilePhotoRequest;
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
        $enterprise = Auth::user();

        if($enterprise->profile_photo) {
            $enterprise->profile_photo = asset('storage/' . $enterprise->profile_photo);
        }

        return response()->json($enterprise);
    }

    public function logout (Request $request) {
        return $this->enterpriseService->logout($request->user());
    }

    public function updateProfilePhoto(UploadProfilePhotoRequest $request): JsonResponse {
        $enterprise = Auth::user();
        return $this->enterpriseService->updateProfilePhoto($enterprise->id, $request->validated(), $request->file('image'));
    }

    public function updateInformations(UpdateEnterpriseInformationsRequest $request): JsonResponse {
        $enterprise = Auth::user();
        return $this->enterpriseService->updateInformations($enterprise->id, $request->validated());
    }

    public function updateEmail(UpdateEmailRequest $request) : JsonResponse {
        $enterprise = Auth::user();
        return $this->enterpriseService->updateEmail($enterprise->id, $request->validated());
    }
}
