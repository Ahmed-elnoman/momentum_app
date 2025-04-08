<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\ApiHelper;

class AuthController extends Controller
{
    use ApiHelper;
    function __construct() {
        $this->middleware('auth:sanctum')->except(['register', 'login']);
    }

    function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'      => ['required', 'string'],
            'email'     => ['required', 'string', 'email', 'unique:users'],
            'password'  => ['required', 'string', 'confirmed']
        ]);

        $param = [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ];

        $user = User::submit(null, $param);

        return $this->success($user, 'User registered successfully');
    }


    function login(Request $request) : JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            return $this->success([
                'user' => $user,
                'token' => $token
            ], 'Login successful');

        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    function data(Request $request)
    {
        if(!$request->user()) {
            return $this->unauthorized('Unauthorized');
        }
        return $this->success($request->user(), 'User data fetched successfully');
    }

    function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logout successful');
    }



}
