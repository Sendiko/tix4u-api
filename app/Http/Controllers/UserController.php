<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = $request->validate([
            'username' => 'required|string|max:255',
            'role' => 'in:USERS',
            'email' => 'required|email|max:255|string',
            'password' =>'required|string|min:8|confirmed'
        ]);

        $user = User::create([
            'username' => $validator['username'],
            'email' => $validator['email'],
            'password' => Hash::make($validator['password'])
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 200,
            'message' => "$user->name berhasil register",
            'data' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);

    }

    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8'
        ]);
        
        $user = User::where('email', $validator['email'])->firstOrFail();
        if(!$user || !Hash::check($validator['password'], $user->password)){
            return response()->json([
                'status' => 401,
                'message' => "$user->username gagal login, mohon cek kembali data",
                'token' => 'null',
                'token_type' => 'null'
            ], 401);
        } else {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'message' => "$user->username berhasil login",
                'data' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ], 200);
        }

    }

    public function logout()
    {
        auth('sanctum')->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'berhasil logout',
            'token' => 'null',
            'token_type' => 'null'
        ], 200);
    }

}
