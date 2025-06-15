<?php

namespace App\Services;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService {
    public function register(array $data): User {
        $user = User::create([
            'name'     => $data['name'],
            'surname'  => $data['surname'],
            'cpf'      => $this->sanitizeCpf($data['cpf']),
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Mail::to($user->email)->send(new WelcomeUserMail($user));

        return $user;
    }

    protected function sanitizeCpf(string $cpf): string {
        return preg_replace('/\D/', '', $cpf);
    }

    public function login (array $credentials): JsonResponse {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'status' => 401,
                'message' => 'Usuário ou senha inválidos'
            ], 401);
        }

        $token = $user->createToken('auth-token');
        $token->accessToken->expires_at = now()->addHours(2);
        $token->accessToken->save();

        $user->type = 1; // set user login type

        return response()->json([
            'status' => 200,
            'token' => $token->plainTextToken,
            'user' => $user,
        ], 200);
    }
}