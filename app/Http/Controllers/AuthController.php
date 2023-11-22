<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login (LoginRequest $request): JsonResponse{
        $credentials = $request->only(['email','password']);
        if(!auth()->attempt($credentials)){
            return response()->json(['message' => 'could not login user'], 401);
        }
        else{
            $user = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token, 'message' => 'User logged in',], 201);
        }
    }
    public function store (RegisterRequest $request): JsonResponse{
            $validatedData = $request->validated();
            User::create($validatedData);
            return response()->json(['message' => 'User created'], 201);

    }
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json(['message' => 'User logged out'], 200);
    }
}
