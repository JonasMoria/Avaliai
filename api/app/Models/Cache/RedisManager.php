<?php

namespace App\Models\Cache;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class RedisManager extends Model {
    const LIMIT_DEFAULT = 10;

    protected $client;
    private function __construct() {
        $this->client = Redis::connection()->client();
    }

    private static ?RedisManager $instance = null;
    public static function getInstance(): RedisManager {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function client() {
        return $this->client;
    }

    public function executeLineCommand(string ...$queryArgs) {
        if (!$queryArgs) {
            return null;
        }

        return $this->client()->rawCommand(...$queryArgs);
    }

    public function parseResults(array $results): array {
        unset($results[0]);

        $parsed = [];

        foreach (array_chunk($results, 2) as [$key, $json]) {
            $data = json_decode($json[1], true);
            if (is_array($data)) {
                $parsed[] = $data;
            }
        }

        return $parsed;
    }
}
