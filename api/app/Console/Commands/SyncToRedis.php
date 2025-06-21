<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Models\SyncQueue;

class SyncToRedis extends Command {
    protected $signature = 'sync:redis';
    protected $description = 'Sincroniza registros da tabela sync_queue com o Redis';

    public function handle() {
        $queue = SyncQueue::where('synced', false)
            ->orderBy('id')
            ->limit(30)
            ->get();

        if ($queue->isEmpty()) {
            $this->info('Nenhum registro para sincronizar.');
            return Command::SUCCESS;
        }

        foreach ($queue as $item) {
            try {
                $redisKey = "avaliai:search:{$item->payload['type']}:{$item->model_id}";
                $payload = json_encode($item->payload);

                Redis::connection()
                    ->client()
                    ->rawCommand('JSON.SET', $redisKey, '$', $payload);

                $item->update(['synced' => true]);

                $this->info("Enviado para Redis: {$redisKey}");
            } catch (\Exception $e) {
                $this->error("Erro ao sincronizar ID {$item->id}: {$e->getMessage()}");
            }
        }

        return Command::SUCCESS;
    }
}
