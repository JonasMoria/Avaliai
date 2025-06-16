<?php

namespace App\Services;

use App\Mail\WelcomeEnterpriseMail;
use App\Models\Enterprise;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EnterpriseService {
    
    public function register(array $data): Enterprise {
        $enterprise = Enterprise::create([
            'name' => $data['name'],
            'tradename' => $data['tradename'],
            'cnpj' => $this->sanitizeCnpj($data['cnpj']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Mail::to($enterprise->email)->send(new WelcomeEnterpriseMail($enterprise));

        return $enterprise;
    }

    protected function sanitizeCnpj(string $cnpj): string {
        return preg_replace('/\D/', '', $cnpj);
    }

    public function login (array $credentials): JsonResponse {
        $enterprise = Enterprise::where('email', $credentials['email'])->first();

        if (!$enterprise || !Hash::check($credentials['password'], $enterprise->password)) {
            return response()->json([
                'status' => 401,
                'message' => 'Usuário ou senha inválidos'
            ], 401);
        }

        $token = $enterprise->createToken('auth-token');
        $token->accessToken->expires_at = now()->addHours(2);
        $token->accessToken->save();

        $enterprise->type = 2; // set enteprise login type

        return response()->json([
            'status' => 200,
            'token' => $token->plainTextToken,
            'user' => $enterprise,
        ], 200);
    }

    public function logout (Enterprise $enterprise): JsonResponse {
        $enterprise->revokeAllTokens();

        return response()->json([
            'status' => 200,
            'message' => 'Logout realizado com sucesso'
        ], 200);
    }
}