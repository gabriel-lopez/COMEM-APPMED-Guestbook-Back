<?php

namespace App\Http\Controllers;

use App\Http\Resources\Signature as SignatureResource;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SignatureController extends Controller
{
    public function index()
    {
        $signatures = Signature::where('reported_at', "=", null)->paginate(5);

        return SignatureResource::collection($signatures);
    }

    public function store(Request $request)
    {
        if($user = Auth::user())
        {
            $inputs = $request->all();

            $inputs['user_id'] = $user->id;

            $validation = Signature::getValidation($inputs);

            if ($validation->fails())
            {
                return response()->json(['errors' => $validation->errors()], Response::HTTP_BAD_REQUEST);
            }

            $signature = Signature::createOne($inputs);

            return response()->json($signature, Response::HTTP_CREATED);
        }
        else
        {
            return response()->json(["error" => "403 Forbidden"], Response::HTTP_FORBIDDEN);
        }
    }

    public function show($id)
    {
        SignatureResource::withoutWrapping();

        if($signature = Signature::find($id))
        {
            return new SignatureResource($signature);
        }
        else
        {
            return response()->json(["error" => "404 Not Found"], Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {
        if($signature = Signature::find($id))
        {
            if ($signature->user_id == Auth::user()->id)
            {
                $inputs = $request->all();

                $validation = Signature::getValidation($inputs);

                if ($validation->fails())
                {
                    return response()->json(['errors' => $validation->errors()], Response::HTTP_BAD_REQUEST);
                }

                //TODO

                return response()->json(["error" => "200 Ok"], Response::HTTP_OK);
            }

            return response()->json(['error' => '401 Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json(["error" => "404 Not Found"], Response::HTTP_NOT_FOUND);
    }

    public function destroy($id)
    {
        if (Auth::check() && Auth::user()->isAn('admin'))
        {
            if($signature = Signature::find($id))
            {
                $signature->delete();

                return response()->json(["error" => "204 No Content"], Response::HTTP_NO_CONTENT);
            }

            return response()->json(["error" => "404 Not Found"], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['error' => '401 Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    public function report($id)
    {
        if($signature = Signature::find($id))
        {
            $signature->reported_at = now();
            $signature->save();

            return response()->json(["error" => "200 Ok"], Response::HTTP_OK);
        }

        return response()->json(["error" => "404 Not Found"], Response::HTTP_NOT_FOUND);
    }
}