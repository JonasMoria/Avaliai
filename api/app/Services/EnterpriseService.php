<?php

namespace App\Services;

use App\Facades\SyncQueue;
use App\Mail\EmailAltered;
use App\Mail\PasswordAltered;
use App\Mail\WelcomeEnterpriseMail;
use App\Models\Enterprise;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

        SyncQueue::add(
            modelType: self::class,
            modelId: $enterprise->id,
            action: 'created',
            payload: [
                'id' => $enterprise->id,
                'type' => 2, 
                'name' => $enterprise->tradename,
                'imagePath' => asset('storage/' . $enterprise->profile_photo),
            ]
        );

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

        if($enterprise->profile_photo) {
            $enterprise->profile_photo = asset('storage/' . $enterprise->profile_photo);
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

    public function updateProfilePhoto(int $enterpriseId, array $data, $fileImage = null) : JsonResponse {
        $enterprise = Enterprise::where('id', $enterpriseId)
                                ->first();
        
        if (!$enterprise) {
            return response()->json([
                'status' => 401,
                'message' => 'Dados de sua empresa não foram encontrados. Por favor, faça login novamente.',
            ], 401);
        }

        if ($fileImage) {
            if ($enterprise->profile_photo) {
                Storage::disk('public')->delete($enterprise->profile_photo);
            }

            $imageSrc = $fileImage->store('enterprises', 'public');
            $toUpload['profile_photo'] = $imageSrc;
            $enterprise->update($toUpload);

            SyncQueue::add(
                modelType: self::class,
                modelId: $enterprise->id,
                action: 'updated',
                payload: [
                    'id' => $enterprise->id,
                    'type' => 2, 
                    'name' => $enterprise->name,
                    'imagePath' => asset('storage/' . $imageSrc),
                ]
            );

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

    public function updateInformations(int $enterpriseId, array $data): JsonResponse {
        $enterprise = Enterprise::where('id', $enterpriseId)
                        ->first();

        if (!$enterprise) {
            return response()->json([
                'status' => 401,
                'message' => 'Dados de sua empresa não foram encontrados. Por favor, faça login novamente.',
            ], 401);
        }

        $data['cnpj'] = $this->sanitizeCnpj($data['cnpj']);
        $enterprise->update($data);

        SyncQueue::add(
            modelType: self::class,
            modelId: $enterprise->id,
            action: 'updated',
            payload: [
                'id' => $enterprise->id,
                'type' => 2, 
                'name' => $data['tradename'],
                'imagePath' => asset('storage/' . $enterprise->profile_photo),
            ]
        );

        return response()->json([
            'status' => 200,
            'message' => 'Dados cadastrais atualizados com sucesso.',
        ], 200);
    }

    public function updateEmail(int $enterpriseId, array $data) : JsonResponse {
        $enterprise = Enterprise::where('id', $enterpriseId)
                ->first();

        if (!$enterprise) {
            return response()->json([
                'status' => 401,
                'message' => 'Dados de sua empresa não foram encontrados. Por favor, faça login novamente.',
            ], 401);
        }

        $enterprise->update($data);
        Mail::to($enterprise->email)->send(new EmailAltered($enterprise->name, $enterprise->email));

        return response()->json([
            'status' => 200,
            'message' => 'E-mail alterado com sucesso!',
        ], 200);
    }

    public function updatePassword(int $enterpriseId, array $data) : JsonResponse {
        $enterprise = Enterprise::where('id', $enterpriseId)
            ->first();

        if (!$enterprise) {
            return response()->json([
                'status' => 401,
                'message' => 'Dados de sua empresa não foram encontrados. Por favor, faça login novamente.',
            ], 401);
        }

        $data['password'] = Hash::make($data['password']);
        $enterprise->update($data);

        Mail::to($enterprise->email)->send(new PasswordAltered($enterprise->name));

        return response()->json([
            'status' => 200,
            'message' => 'Senha de acesso alterada com sucesso!',
        ], 200);
    }
}