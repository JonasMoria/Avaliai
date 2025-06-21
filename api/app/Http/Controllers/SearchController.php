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
        $term = $request->query('term');

        if (!$term) {
            return response()->json(['error' => 'Missing search term'], 400);
        }

        return $this->service->searchByName($term);
    }
}
