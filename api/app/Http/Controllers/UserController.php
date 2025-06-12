<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

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
}
