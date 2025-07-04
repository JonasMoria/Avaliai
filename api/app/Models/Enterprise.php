<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Enterprise extends Model {
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'tradename',
        'profile_photo',
        'cnpj',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    public function revokeAllTokens(): void {
        $this->tokens()->delete();
    }

    public function services() {
        return $this->hasMany(\App\Models\EnterpriseService::class);
    }

}
