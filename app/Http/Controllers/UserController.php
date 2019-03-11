<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->isAn('admin'))
        {
            $users  = User::paginate(5);

            return $users;
        }

        return response()->json(['error' => '401 Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    public function ban($id)
    {
        if (Auth::check() && Auth::user()->isAn('admin'))
        {
            if($user = User::find($id))
            {
                $user->banned_at = now();
                $user->save();

                return response()->json(["error" => "200 Ok"], Response::HTTP_OK);
            }

            return response()->json(["error" => "404 Not Found"], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['error' => '401 Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }
}