<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseApiController
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->sendResponse($user, "User registered successfully", 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError(null, "Invalid credentials", 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->sendResponse([
            'token' => $token,
            'user' => $user
        ], "Login successful");
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->sendResponse(null, "Logged out successfully");
    }

    public function me(Request $request)
    {
        return $this->sendResponse($request->user(), "User info retrieved successfully");
    }
}
