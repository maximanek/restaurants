<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use OpenApi\Annotations as OA;
use Illuminate\Http\JsonResponse;
use App\Service\UserCreateService;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Exceptions\UserNotFoundException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     description="Get all users",
     *     @OA\Parameter(
     *      name="limit",
     *      in="query",
     * ),
     *     @OA\Response(response="200", description="success"),
     * )
     */
    public function index(UserIndexRequest $request): JsonResponse
    {
        $limit = $request->validated('limit');
        return new JsonResponse(User::paginate($limit ?? 15));
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     description="Get specific user",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id of User to show",
     *          required=true
     *     ),
     *     @OA\Response(response="200", description="success"),
     *     @OA\Response(response="404", description="User not found"),
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = User::with('restaurants')->findOrFail($id);
        } catch (Exception $exception) {
            return new JsonResponse($exception->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
        }

        return new JsonResponse($user);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     security={{"sanctum": {}}},
     *     tags={"Users"},
     *     description="Add new user",
     *     @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="User email",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="User password",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="User name",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="surname",
     *          in="query",
     *          description="User surname",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(response="201", description="Created"),
     *     @OA\Response(response="422", description="Unprocessable Content"),
     * )
     */
    public function store(
        CreateUserRequest $request,
        UserCreateService $service
    ): JsonResponse {
        $user = $service->create($request->validated());

        return new JsonResponse($user, ResponseAlias::HTTP_CREATED);
    }

    /**
     * @OA\Patch(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     description="Update user",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="User email",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="User password",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="User name",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="surname",
     *          in="query",
     *          description="User surname",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="User not found"),
     *     @OA\Response(response="422", description="Unprocessable Content"),
     * )
     */
    public function update(
        User              $user,
        UserUpdateRequest $request
    ): JsonResponse {
        try {
            $user = $user->update($request->validated());

            if (!$user) {
                throw new UserNotFoundException();
            }
        } catch (UserNotFoundException $exception) {
            return new JsonResponse($exception->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
        }

        return new JsonResponse($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     description="Delete user",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User id",
     *          required=true
     *     ),
     *     @OA\Response(response="200", description="success"),
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse($exception->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
        }

        return new JsonResponse();
    }
}
