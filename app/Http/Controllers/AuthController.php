<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->validated())) {
            return responder()
                ->error(401, 'Přihlašovací údaje se neshodují.')
                ->respond(401);
        }

        return responder()->success()->respond();
    }

    public function logout()
    {

    }

    public function register()
    {
        throw new \Exception('TODO: Implement - Class AuthController => Method register()');
    }
}
