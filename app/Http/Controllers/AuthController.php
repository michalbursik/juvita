<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            return response()->json([
                'message' => 'Přihlašovací údaje se neshodují.',
            ], 401);
        }

        /** @var User $user */
        $user = auth()->user();

        return response()->json([
            'status' => 'success',
            'success' => true,
            'data' => [
                'access_token' => $user->createToken('API Token')->plainTextToken,
            ],
        ], 200);
    }

    public function logout() {}

    public function register()
    {
        throw new \Exception('TODO: Implement - Class AuthController => Method register()');
    }

    //    public function changePassword(PasswordChangeRequest $request)
    //    {
    //
    //    }
}
