<?php
namespace App\Services;

use App\Facades\SyncQueue;
use App\Models\EnterpriseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EnterpriseServiceService {
    public function register(array $data, int $enterpriseId): JsonResponse {
        if (isset($data['image'])) {
            $path = $data['image']->store('enterprise_services', 'public');
            $data['image'] = $path;
        }

        $data['enterprise_id'] = $enterpriseId;
        $data['phone'] = $this->numbersOnly($data['phone']);
        $data['postalCode'] = $this->numbersOnly($data['postalCode']);

        $enterpriseService = EnterpriseService::create($data);

        SyncQueue::add(
            modelType: self::class,
            modelId: $enterpriseService->id,
            action: 'created',
            payload: [
                'id' => $enterpriseService->id,
                'type' => 1, 
                'name' => $enterpriseService->name,
                'imagePath' => asset('storage/' . $enterpriseService->image),
            ]
        );

        return response()->json([
            'status' => 201,
            'message' => 'Serviço cadastrado com sucesso',
            'data' => $enterpriseService
        ], 201);
    }

    public function getServicesByEnterprise($enterprise): JsonResponse {
        $list = $enterprise->services()->get();

        foreach ($list as $obj) {
            if ($obj->image) {
                $obj->image = asset('storage/' . $obj->image);
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Serviços obtidos com sucesso.',
            'data' => $list
        ], 201);
    }

    public function getAnalytics($enterprise): JsonResponse {
        $ranking = [];

        $ranking = EnterpriseService::select([
                'enterprise_services.id',
                'enterprise_services.name',
                DB::raw('COUNT(service_ratings.id) as total_ratings'),
                DB::raw('AVG(service_ratings.stars) as average_stars')
            ])
            ->leftJoin('service_ratings', 'service_ratings.enterprise_service_id', '=', 'enterprise_services.id')
            ->where('enterprise_services.enterprise_id', $enterprise->id)
            ->groupBy('enterprise_services.id', 'enterprise_services.name')
            ->orderByDesc('average_stars')
            ->orderByDesc('total_ratings')
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'Dados obtidos com sucesso.',
            'data' => $ranking
        ], 200);
    }

    protected function numbersOnly(string $phone): string {
        return preg_replace('/\D/', '', $phone);
    }

    public function updateService(int $enterpriseId, int $serviceId, array $data, $imageFile = null): JsonResponse {
        $service = EnterpriseService::where('id', $serviceId)
                                    ->where('enterprise_id', $enterpriseId)
                                    ->first();
        
        if (!$service) {
            return response()->json([
                'status' => 401,
                'message' => 'Item não encontrado.',
            ], 401);
        }

        if ($imageFile) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }

            $imagePath = $imageFile->store('enterprise_services', 'public');
            $data['image'] = $imagePath;
        }

        $service->update($data);

        $service = EnterpriseService::where('id', $serviceId)
                            ->where('enterprise_id', $enterpriseId)
                            ->first();
        
        SyncQueue::add(
            modelType: self::class,
            modelId: $service->id,
            action: 'updated',
            payload: [
                'id' => $service->id,
                'type' => 1, 
                'name' => $service->name,
                'imagePath' => asset('storage/' . $service->image),
            ]
        );

        return response()->json([
            'status' => 200,
            'message' => 'Item atualizado com sucesso.',
            'data' => $service
        ], 200);
    }

    public function deleteService(int $enterpriseId, int $id): JsonResponse {
        $service = EnterpriseService::where('id', $id)
                    ->where('enterprise_id', $enterpriseId)
                    ->first();
        
        if (!$service) {
            return response()->json([
                'status' => 401,
                'message' => 'Item não encontrado.',
            ], 401);
        }

        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        SyncQueue::add(
            modelType: self::class,
            modelId: $service->id,
            action: 'deleted',
            payload: [
                'id' => $service->id,
                'type' => 1, 
                'name' => $service->name,
                'imagePath' => asset('storage/' . $service->image),
            ]
        );

        return response()->json([
            'status' => 200,
            'message' => 'Item removido com sucesso.',
        ], 200);
    }
}
