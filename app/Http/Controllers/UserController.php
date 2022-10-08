<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse(User::all());
    }

    public function show(User $user): JsonResponse
    {
        return new JsonResponse($user);
    }

    public function store(Request $request): JsonResponse
    {
        $user = User::create($request->toArray());

        return new JsonResponse($user, ResponseAlias::HTTP_CREATED);
    }

    public function update(User $user, Request $request): JsonResponse
    {
        $user->update($request->toArray());

        return new JsonResponse($user);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return new JsonResponse();
    }
}
