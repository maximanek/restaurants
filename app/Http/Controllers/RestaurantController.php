<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Validator\UserValidator;
use Illuminate\Http\JsonResponse;
use App\Validator\RestaurantValidator;
use App\Http\Requests\ManageUserRequest;
use App\Http\Requests\PaginationRequest;
use App\Service\RestaurantCreateService;
use App\Service\RestaurantUpdateService;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Http\Requests\CreateRestaurantRequest;
use App\Exceptions\RestaurantNotFoundException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RestaurantController extends Controller
{
    /**
     * @param PaginationRequest $request
     * @return JsonResponse
     *
     * @OA\Get(
     *     path="/api/restaurants",
     *     tags={"Restaurants"},
     *     description="Get all Restaurants",
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *      ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function index(PaginationRequest $request): JsonResponse
    {
        $limit = $request->validated('limit');
        return new JsonResponse(Restaurant::paginate($limit ?? 15));
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Get(
     *     path="/api/restaurants/{id}",
     *     tags={"Restaurants"},
     *     description="get a specific restaurant",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id of Restaurant to show",
     *          required=true
     *     ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $restaurant = Restaurant::findOrFail($id);
        } catch (Exception $exception) {
            return new JsonResponse($exception->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
        }
        return new JsonResponse($restaurant);
    }

    /**
     * @param CreateRestaurantRequest $request
     * @param RestaurantCreateService $service
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/restaurants/",
     *     tags={"Restaurants"},
     *     description="create a restaurant",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="Restaurant name",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="422", description="Unprocessable Content"),
     * )
     */
    public function store(
        CreateRestaurantRequest $request,
        RestaurantCreateService $service
    ): JsonResponse {
        $restaurant = $service->create($request->validated());

        return new JsonResponse($restaurant, ResponseAlias::HTTP_CREATED);
    }

    /**
     * @param Restaurant $restaurant
     * @param UpdateRestaurantRequest $request
     * @param RestaurantUpdateService $service
     * @return JsonResponse
     *
     * @OA\Patch(
     *     path="/api/restaurants/{id}",
     *     tags={"Restaurants"},
     *     description="update a restaurant",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Restarurant id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="Restaurant name",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="User not found"),
     * )
     */
    public function update(
        Restaurant $restaurant,
        UpdateRestaurantRequest $request,
        RestaurantUpdateService $service
    ): JsonResponse {
        try {
            $restaurant = $service->update($restaurant, $request->validated());
        } catch (RestaurantNotFoundException $exception) {
            return new JsonResponse($exception->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
        }

        return new JsonResponse($restaurant);
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Delete(
     *     path="/api/restaurants/{id}",
     *     tags={"Restaurants"},
     *     description="a restaurant",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User id",
     *          required=true
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Restaurant not found"),
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $restaurant = Restaurant::findOrFail($id);
            $restaurant->delete();
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse($exception->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
        }

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
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Restaurant id",
     *          required=true,
     *     ),
     *     @OA\Parameter(
     *          name="users",
     *          in="query",
     *          description="Users to attach/detach",
     *          required=true,
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items()
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Restaurant not found"),
     * )
     */
    public function manageUsers(
        Restaurant $restaurant,
        ManageUserRequest $request
    ): JsonResponse {
        if (is_null($restaurant->id))
        {
            return new JsonResponse(
                throw new NotFoundHttpException('Restaurant not found'),
                ResponseAlias::HTTP_NOT_FOUND);
        }
        $data = $request->validated();

        foreach ($data['users'] as $user) {
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
