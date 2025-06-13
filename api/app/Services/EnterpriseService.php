<?php

namespace App\Services;

use App\Mail\WelcomeEnterpriseMail;
use App\Models\Enterprise;
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
}