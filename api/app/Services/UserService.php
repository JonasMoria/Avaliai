<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService {
    public function register(array $data): User {
        return User::create([
            'name'     => $data['name'],
            'surname'  => $data['surname'],
            'cpf'      => $this->sanitizeCpf($data['cpf']),
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function sanitizeCpf(string $cpf): string {
        return preg_replace('/\D/', '', $cpf);
    }
}