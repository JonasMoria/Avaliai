<?php

namespace App\Services\Cache;

use App\Models\SyncQueue;

class SyncQueueService {
    public function add(string $modelType, int $modelId, string $action, array $payload = []) {
        return SyncQueue::create([
            'model_type' => $modelType,
            'model_id' => $modelId,
            'action' => $action,
            'payload' => $payload,
            'synced' => false,
        ]);
    }
}