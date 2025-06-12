<?php

namespace App\Services;

use App\Mail\WelcomeUserMail;
use App\Models\User;
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
}