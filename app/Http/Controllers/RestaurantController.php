<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Service\RestaurantCreateService;
use App\Service\RestaurantUpdateService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RestaurantController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse(Restaurant::all());
    }

    public function show(Restaurant $restaurant): JsonResponse
    {
        return new JsonResponse($restaurant);
    }

    public function store(
        Request $request,
        RestaurantCreateService $service
    ): JsonResponse {
        $restaurant = $service->create($request->toArray());

        return new JsonResponse($restaurant, ResponseAlias::HTTP_CREATED);
    }

    public function update(
        Restaurant $restaurant,
        Request $request,
        RestaurantUpdateService $service
    ): JsonResponse {
        $data = $request->toArray();

        $restaurant = $service->update($restaurant, $data);

        return new JsonResponse($restaurant);
    }

    public function destroy(Restaurant $restaurant): JsonResponse
    {
        $restaurant->delete();

        return new JsonResponse();
    }
}
