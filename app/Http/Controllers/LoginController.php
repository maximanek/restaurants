<?php

namespace App\Http\Controllers;

use App\Models\User;
use OpenApi\Annotations as OA;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Login"},
     *     description="Get all users",
     *    @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="422", description="uprocesable entity"),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = User::where(['email' => $request->email])->first();

            if (Hash::check($request->password, $user->password)) {
                return new JsonResponse($user->createToken('API_TOKEN')->plainTextToken);
            }
        } catch (\Exception $exception) {
            return new JsonResponse($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse('Access Denied', Response::HTTP_FORBIDDEN);
    }
}
