<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $user = User::where(['email' => $request->email])->first();

            if (Hash::check($request->password, $user->password)) {
                return new JsonResponse($user->createToken('API_TOKEN')->plainTextToken);
            }
        } catch (\Exception $exception) {
            return $exception;
        }

        return new JsonResponse('Access Denied', Response::HTTP_FORBIDDEN);
    }
}
