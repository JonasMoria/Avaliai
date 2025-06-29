<?php

namespace App\Services;

use App\Models\Cache\RedisManager;
use App\Models\Enterprise;
use App\Models\EnterpriseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SearchService {
    const LIMIT_PER_PAGE = 30;

    public function searchByName(string $term): JsonResponse {
        if (!$term) {
            return response()->json([
                'status' => 400,
                'message' => 'Termo de busca não encontrado'
            ], 200);
        }

        $redis = RedisManager::getInstance();
        $results = $redis->executeLineCommand(
            'FT.SEARCH',
            'avaliai_idx',
            "@name:*{$term}*",
            'LIMIT', 0, $redis::LIMIT_DEFAULT
        );

        $results = $redis->parseResults($results);
        return response()->json([
            'status' => 200,
            'data' => $results
        ], 200);
    }

    public function searchAll(string $term, int $page = 1): JsonResponse {
        if (!$term) {
            return response()->json([
                'status' => 400,
                'message' => 'Termo de busca não encontrado'
            ], 400);
        }

        $offset = ($page - 1) * self::LIMIT_PER_PAGE;

        $enterprisesSearch = DB::table('enterprises')
                                ->select('id', 'tradename as name', DB::raw('2 as type'), 'profile_photo as image')
                                ->where('tradename', 'like', "%$term%");

        $servicesSearch = DB::table('enterprise_services')
                            ->select('id', 'name', DB::raw('1 as type'), 'image')
                            ->where('name', 'like', '%' . $term . '%');

        $union = $enterprisesSearch->unionAll($servicesSearch);
        $results = DB::table(DB::raw("({$union->toSql()}) as sub"))
                    ->mergeBindings($union)
                    ->offset($offset)
                    ->limit(self::LIMIT_PER_PAGE)
                    ->orderBy('name')
                    ->get();

        $total = DB::table(DB::raw("({$union->toSql()}) as sub"))
                    ->mergeBindings($union)
                    ->count();

        foreach ($results as $obj) {
            if ($obj->image) {
                $obj->image = asset('storage/' . $obj->image);
            }
        }

        return response()->json([
            'status' => 200,
            'current_page' => $page,
            'per_page' => self::LIMIT_PER_PAGE,
            'total' => $total,
            'data' => $results
        ], 200);
    }

    public function getEnterpriseById(int $id): JsonResponse {
        if (!$id) {
            return response()->json([
                'status' => 400,
                'message' => 'Empresa não encontrada'
            ], 400);
        }

        $enterprise = Enterprise::where('id', $id)
                        ->first();

        if (!$enterprise) {
            return response()->json([
                'status' => 400,
                'message' => 'Empresa não encontrada'
            ], 400);
        }

        if ($enterprise->profile_photo) {
            $enterprise->profile_photo = asset('storage/' . $enterprise->profile_photo);
        }

        return response()->json([
            'status' => 200,
            'data' => $enterprise
        ], 200); 
    }

    public function getServiceById(int $id): JsonResponse {
        if (!$id) {
            return response()->json([
                'status' => 400,
                'message' => 'Serviço não encontrada'
            ], 400);
        }

        $service = EnterpriseService::with(['enterprise:id,tradename,profile_photo'])
            ->where('id', $id)
            ->first();


        if (!$service) {
            return response()->json([
                'status' => 400,
                'message' => 'Serviço não encontrada'
            ], 400);
        }

        if ($service->image) {
            $service->image = asset('storage/' . $service->image);
        }
        if (optional($service->enterprise)->profile_photo) {
            $service->enterprise->profile_photo = asset('storage/' . $service->enterprise->profile_photo);
        }

        return response()->json([
            'status' => 200,
            'data' => $service
        ], 200); 
    }
}
