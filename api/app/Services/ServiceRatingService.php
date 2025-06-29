<?php

namespace App\Services;

use App\Models\ServiceRating;
use Illuminate\Http\JsonResponse;

class ServiceRatingService {

    public function makeRate(int $userId, array $data): JsonResponse {
        $rating = ServiceRating::create([
            'user_id' => $userId,
            'enterprise_service_id' => $data['enterprise_service_id'],
            'stars' => $data['stars'],
            'comment' => $data['comment'],
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Avaliação postada com sucesso!',
            'data' => $rating,
        ], 201);
    }
}