<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnterpriseServiceRequest;
use App\Services\EnterpriseServiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EnterpriseServiceController extends Controller {

    protected $service;
    public function __construct(EnterpriseServiceService $service) {
        $this->service = $service;
    }

    public function register(EnterpriseServiceRequest $request): JsonResponse {
        $enterprise = Auth::user();
        return $this->service->register($request->validated(), $enterprise->id);
    }

    public function listMyServices(): JsonResponse {
        $enterprise = Auth::user();
        return $this->service->getServicesByEnterprise($enterprise);
    }

    public function remove(int $id): JsonResponse {
        $enterprise = Auth::user();
        return $this->service->deleteService($enterprise->id, $id);
    }
}
