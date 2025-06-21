<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {

    protected UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function register(RegisterUserRequest $request): JsonResponse {
        $user = $this->userService->register($request->validated());

        return response()->json([
            'status' => 201,
            'message' => 'Conta pessoal criada com sucesso! Por favor, realize seu login.',
            'data' => $user
        ], 201);
    }

    public function login (LoginRequest $request) {
        return $this->userService->login($request->validated());
    }

    public function me(): JsonResponse {
        $user = Auth::user();

        if($user->profile_photo) {
            $user->profile_photo = asset('storage/' . $user->profile_photo);
        }

        return response()->json($user);
    }

    public function logout(Request $request) {
        return $this->userService->logout($request->user());
    }
}
