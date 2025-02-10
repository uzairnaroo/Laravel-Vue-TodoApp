<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
     /**
     * Register a new user.
     *
     * @bodyParam name string required The user's name. Example: John Doe
     * @bodyParam email string required The user's email. Example: john.doe@example.com
     * @bodyParam password string required The user's password. Example: password123
     * @bodyParam password_confirmation string required The password confirmation. Example: password123
     * @response 201 {
     *  "access_token": "token123",
     *  "token_type": "Bearer",
     *  "user": {
     *      "id": 1,
     *      "name": "John Doe",
     *      "email": "john.doe@example.com"
     *  }
     * }
     */
    public function register(RegisterUserRequest $request)
    {


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], 201);
    }

  /**
     * Login user.
     *
     * @bodyParam email string required The user's email. Example: john.doe@example.com
     * @bodyParam password string required The user's password. Example: password123
     * @response 200 {
     *  "access_token": "token123",
     *  "token_type": "Bearer",
     *  "user": {
     *      "id": 1,
     *      "name": "John Doe",
     *      "email": "john.doe@example.com"
     *  }
     * }
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], 200);
    }

   /**
     * Logout user.
     *
     * @response 204 {}
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ], 204);
    }
}
