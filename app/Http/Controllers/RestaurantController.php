<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use App\Service\RestaurantCreateService;
use App\Service\RestaurantUpdateService;
use App\Validator\RestaurantValidator;
use App\Validator\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RestaurantController extends Controller
{
    /**
     * @return JsonResponse
     *
     * @OA\Get(
     *     path="/api/restaurants",
     *     tags={"Restaurants"},
     *     description="get restaurants",
     *     @OA\Response(response="200", description="success"),
     * )
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(Restaurant::all());
    }

    /**
     * @param Restaurant $restaurant
     * @return JsonResponse
     *
     * @OA\Get(
     *     path="/api/restaurants/{id}",
     *     tags={"Restaurants"},
     *     description="get a specific restaurant",
     *     @OA\Response(response="200", description="success"),
     * )
     */
    public function show(Restaurant $restaurant): JsonResponse
    {
        return new JsonResponse($restaurant);
    }

    /**
     * @param Request $request
     * @param RestaurantCreateService $service
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/restaurants/",
     *     tags={"Restaurants"},
     *     description="create a restaurant",
     *     @OA\Response(response="200", description="success"),
     * )
     */
    public function store(
        Request $request,
        RestaurantCreateService $service
    ): JsonResponse {
        $restaurant = $service->create($request->toArray());

        return new JsonResponse($restaurant, ResponseAlias::HTTP_CREATED);
    }

    /**
     * @param Restaurant $restaurant
     * @param Request $request
     * @param RestaurantUpdateService $service
     * @return JsonResponse
     *
     * @OA\Patch(
     *     path="/api/restaurants/{id}",
     *     tags={"Restaurants"},
     *     description="update a restaurant",
     *     @OA\Response(response="200", description="success"),
     * )
     */
    public function update(
        Restaurant $restaurant,
        Request $request,
        RestaurantUpdateService $service
    ): JsonResponse {
        $data = $request->toArray();

        $restaurant = $service->update($restaurant, $data);

        return new JsonResponse($restaurant);
    }

    /**
     * @param Restaurant $restaurant
     * @return JsonResponse
     *
     * @OA\Delete(
     *     path="/api/restaurants/{id}",
     *     tags={"Restaurants"},
     *     description="a restaurant",
     *     @OA\Response(response="200", description="success"),
     * )
     */
    public function destroy(Restaurant $restaurant): JsonResponse
    {
        $restaurant->delete();

        return new JsonResponse();
    }

    /**
     * @param Restaurant $restaurant
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Patch(
     *     path="/api/restaurants/{id}/user",
     *     tags={"Restaurants"},
     *     description="attach/detach a user to/from restaurant",
     *     @OA\Response(response="200", description="success"),
     * )
     */
    public function manageUsers(Restaurant $restaurant, Request $request): JsonResponse
    {
        $data = $request->getContent();

        foreach ($data as $user) {
            if ($user['action'] == 'Attach') {
                if (RestaurantValidator::checkRestaurantLimits($restaurant) &&
                    UserValidator::checkUserLimits(User::find($user['id']))) {
                    $restaurant->users()->attach($user['id']);
                }
            } else {
                $restaurant->users()->detach($user['id']);
            }
        }

        return new JsonResponse($restaurant);
    }
}
