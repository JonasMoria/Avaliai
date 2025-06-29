<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRating extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'enterprise_service_id',
        'stars',
        'comment',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function enterpriseService() {
        return $this->belongsTo(EnterpriseService::class);
    }
}
