<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateRequest;
use App\Services\ServiceRatingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ServiceRatingController extends Controller {

    private ServiceRatingService $service;
    public function __construct(ServiceRatingService $service) {
        $this->service = $service;
    }

    public function makeRate(RateRequest $request): JsonResponse {
        $userId = Auth::user()->id;
        return $this->service->makeRate($userId, $request->validated());
    }
}
