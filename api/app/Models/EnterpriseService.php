<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnterpriseService extends Model {
    use HasFactory;

    protected $fillable = [
        'enterprise_id',
        'name',
        'image',
        'type',
        'street',
        'number',
        'neighborhood',
        'city',
        'state',
        'phone',
        'email',
        'additional',
        'website',
    ];

    public function enterprise() {
        return $this->belongsTo(Enterprise::class);
    }
}
