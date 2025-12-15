<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|max:35|regex:/^[a-zA-Z0-9 ]+$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'role' => 'in:user,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'admin', // default role = admin
        ]);

        return response()->json([
            'message' => 'User Registered Successfully',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }

$user = User::where('email', $request->email)->first();

if (!$user || !Hash::check($request->password, $user->password)) {
    return response()->json(['message' => 'Invalid credentials'], 401);
}

// ✅ Use our Mongo-safe token method
$tokenData = $user->createMongoToken('auth_token');
$token = $tokenData['plainTextToken'];

return response()->json([
    'message'      => 'Login successful',
    'user'         => $user,
    'access_token' => $token,
    'token_type'   => 'Bearer',
]);
    }

public function profile(Request $request)
{
    return response()->json($request->user());
}

public function logout(Request $request)
{
    $user = $request->user();

    // Revoke all tokens
    $user->tokens()->delete();

    return response()->json(['message' => 'Logged out successfully']);
}
}