<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

class SearchService {
    public function searchByName(string $term): JsonResponse {
        $query = "@name:*{$term}*";

        $results = Redis::connection()->client()->rawCommand(
            'FT.SEARCH',
            'avaliai_idx',
            $query,
            'LIMIT', 0, 10
        );

        $results = $this->parseResults($results);
        return response()->json([
            'status' => 200,
            'data' => $results
        ], 200);
    }

    private function parseResults(array $results): array {
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
