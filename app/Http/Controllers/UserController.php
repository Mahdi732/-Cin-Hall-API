<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        $user = $this->userService->createUser($fields);
        $token = Auth::guard('api')->login($user);

        return response()->json([
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if (!Auth::guard('api')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::guard('api')->user();
        $token = Auth::guard('api')->login($user);

        return response()->json([
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|string|email|unique:users,email,' . $id,
            'password' => 'sometimes|string'
        ]);

        $this->userService->updateUser($fields, $id);

        return response()->json(['message' => 'User updated successfully'], 200);
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ], 200);
    }
}
