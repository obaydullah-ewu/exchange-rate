<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiStatusTrait;
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            $response['message'] = 'Invalid login credentials';
            return $this->notAllowedApiResponse($response);
        }

        $response['user'] = Auth::user();
        $response['token'] = $response['user']->createToken('API Token')->plainTextToken;
        return $this->successApiResponse($response);
    }

    public function logout()
    {
        Auth::logout();
        $response['message'] = 'Logout Successfully';
        return $this->successApiResponse($response);
    }
}
