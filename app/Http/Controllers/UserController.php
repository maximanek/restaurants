<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Service\UserCreateService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     description="Get all users",
     *     @OA\Response(response="200", description="success"),
     * )
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(User::all());
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     description="Get specific user",
     *     @OA\Response(response="200", description="success"),
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id of User to show",
     *          required=true
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        return new JsonResponse(User::with('restaurants')->findOrFail($id));
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     description="Add new user",
     *     @OA\Response(response="200", description="success"),
     * )
     */
    public function store(
        Request $request,
        UserCreateService $service
    ): JsonResponse {
        $user = $service->create($request->toArray());

        return new JsonResponse($user, ResponseAlias::HTTP_CREATED);
    }

    /**
     * @OA\Patch(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     description="Update user",
     *     @OA\Response(response="200", description="success"),
     * )
     */
    public function update(User $user, Request $request): JsonResponse
    {
        $user->update($request->toArray());

        return new JsonResponse($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     description="Delete user",
     *     @OA\Response(response="200", description="success"),
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return new JsonResponse();
    }
}
