<?php

namespace App\Services;

use App\Models\Enterprise;
use App\Models\ServiceRating;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Mail\ServiceRatingConfirmation;
use App\Mail\NotifyEnterpriseOfRating;
use Illuminate\Support\Facades\Mail;

class ServiceRatingService {

    public function putUserReview(int $userId, array $data): JsonResponse {
        $rating = ServiceRating::create([
            'user_id' => $userId,
            'enterprise_service_id' => $data['enterprise_service_id'],
            'stars' => $data['stars'],
            'comment' => $data['comment'],
        ]);

        $rating->load('enterpriseService.enterprise', 'user');

        $user = User::find($userId);
        $enterprise = Enterprise::whereHas('services', function ($q) use ($rating) {
            $q->where('id', $rating->enterprise_service_id);
        })->first();

        if ($user && $user->email) {
            Mail::to($user->email)->send(new ServiceRatingConfirmation($rating));
        }

        if ($enterprise && $enterprise->email) {
            Mail::to($enterprise->email)->send(new NotifyEnterpriseOfRating($rating));
        }

        return response()->json([
            'status' => 201,
            'message' => 'Avaliação postada com sucesso!',
            'data' => $rating,
        ], 201);
    }

    public function removeUserReview(int $userId, int $reviewId): JsonResponse {
        $review = ServiceRating::where('id', $reviewId)
                    ->where('user_id', $userId)
                    ->first();

        if (!$review) {
            return response()->json([
                'status' => 401,
                'message' => 'Avaliação não encontrada.',
            ], 401);
        }

        $review->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Avaliação removida com sucesso.',
        ], 200);
    }
}