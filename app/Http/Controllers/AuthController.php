<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function user(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function register(Request $request)
    {
        $inputs = $request->all();

        $validation = User::getValidation($inputs);

        if ($validation->fails())
        {
            return response()->json(['errors' => $validation->errors()], Response::HTTP_BAD_REQUEST);
        }

        $user = User::createOne($inputs);

        $token = $this->guard()->login($user);

        return $this->respondWithToken($token);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = $this->guard()->attempt($credentials))
        {
            return response()->json(['error' => '401 Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
    }

    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'refresh_token_error'], Response::HTTP_UNAUTHORIZED);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ]);
    }

    private function guard()
    {
        return Auth::guard();
    }
}
