<?php

namespace App\Services;

use App\Mail\EmailAltered;
use App\Mail\PasswordAltered;
use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

        if($user->profile_photo) {
            $user->profile_photo = asset('storage/' . $user->profile_photo);
        }

        return response()->json([
            'status' => 200,
            'token' => $token->plainTextToken,
            'user' => $user,
        ], 200);
    }

    public function logout(User $user): JsonResponse {
        $user->revokeAllTokens();

        return response()->json([
            'status' => 200,
            'message' => 'Logout realizado com sucesso'
        ], 200);
    }

    public function updateProfilePhoto(int $userId, array $data, $fileImage = null) : JsonResponse {
        $user = User::where('id', $userId)
                ->first();
        
        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Dados de cadastro não foram encontrados. Por favor, faça login novamente.',
            ], 401);
        }

        if ($fileImage) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $imageSrc = $fileImage->store('users', 'public');
            $toUpload['profile_photo'] = $imageSrc;
            $user->update($toUpload);

            return response()->json([
                'status' => 200,
                'message' => 'Foto atualizada com sucesso.',
                'urlImage' => asset('storage/' . $imageSrc),
            ], 200);
        }

        return response()->json([
            'status' => 401,
            'message' => 'Não foi possível atualizar a foto. Por favor, tente novamente mais tarde.',
        ], 200);
    }

    public function updateInformations(int $userId, array $data) : JsonResponse {
        $user = User::where('id', $userId)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Dados de cadastro não foram encontrados. Por favor, faça login novamente.',
            ], 401);
        }

        $data['cpf'] = $this->sanitizeCpf($data['cpf']);
        $user->update($data);

        return response()->json([
            'status' => 200,
            'message' => 'Dados cadastrais atualizados com sucesso.',
        ], 200);
    }

    public function updateEmail(int $userId, array $data) : JsonResponse {
        $user = User::where('id', $userId)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Dados de cadastro não foram encontrados. Por favor, faça login novamente.',
            ], 401);
        }

        $user->update($data);
        Mail::to($user->email)->send(new EmailAltered($user->name, $user->email));

        return response()->json([
            'status' => 200,
            'message' => 'Dados cadastrais atualizados com sucesso.',
        ], 200);
    }

    public function updatePassword(int $userId, array $data) : JsonResponse {
        $user = User::where('id', $userId)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Dados de cadastro não foram encontrados. Por favor, faça login novamente.',
            ], 401);
        }

        $data['password'] = Hash::make($data['password']);
        $user->update($data);

        Mail::to($user->email)->send(new PasswordAltered($user->name));

        return response()->json([
            'status' => 200,
            'message' => 'Senha de acesso alterada com sucesso!',
        ], 200);
    }
}