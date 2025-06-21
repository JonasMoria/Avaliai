<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncQueue extends Model {
    protected $table = 'sync_queue';

    protected $fillable = [
        'model_type',
        'model_id',
        'action',
        'payload',
        'synced',
    ];

    protected $casts = [
        'payload' => 'array',
        'synced' => 'boolean',
    ];
}
