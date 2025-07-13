<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller {
    protected SearchService $service;

    public function __construct(SearchService $service) {
        $this->service = $service;
    }

    public function search(Request $request): JsonResponse {
        $term = ($request->query('term') ?: '');
        return $this->service->searchByName($term);
    }

    public function searchAll(Request $request): JsonResponse {
        $term = ($request->query('term') ?: '');
        $page = ($request->query('page') ?: 1);

        return $this->service->searchAll($term, $page);
    }

    public function getEnterpriseById($id): JsonResponse {
        $id = (int) filter_var($id, FILTER_VALIDATE_INT);

        return $this->service->getEnterpriseById($id);
    }

    public function getServiceById($id): JsonResponse {
        $id = (int) filter_var($id, FILTER_VALIDATE_INT);

        return $this->service->getServiceById($id);
    }

    public function getServiceReviews($id): JsonResponse {
        $id = (int) filter_var($id, FILTER_VALIDATE_INT);

        return $this->service->getServiceReviews($id);
    }
}
