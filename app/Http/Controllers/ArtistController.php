<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ArtistController extends Controller
{
    public function register(Request $request)
    {
        $validator = $request->validate([
            'artist_fullname' => 'required|string|max:255',
            'artist_stagename' => 'required|string|max:255',
            'age' => 'required|integer|max:100',
            'gender' => 'in:MALE,FEMALE',
            'music_genre' => 'required|string|max:255',
            'username' => 'required|string|unique:artists',
            'email' => 'required|string|email|max:255|unique:artists',
            'password' => 'required|string|min:8'
        ]);

        $artist = Artist::create([
            'artist_fullname' => $validator['artist_fullname'],
            'artist_stagename' => $validator['artist_stagename'],
            'age' => $validator['age'],
            'gender' => $validator['gender'],
            'music_genre' => $validator['music_genre'],
            'username' => $validator['username'],
            'email' => $validator['email'],
            'password' => Hash::make($validator['password'])
        ]);

        $token = $artist->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 200,
            'message' => "$artist->name berhasil register",
            'data' => $artist,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 200);

    }

    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8'
        ]);

        $artist = Artist::where('email', $validator['email'])->firstOrFail();
        if(!$artist || !Hash::check($validator['password'], $artist->password)){
            return response()->json([
                'status' => 401,
                'message' => "$artist->username gagal login, mohon cek kembali data",
                'token' => 'null',
                'token_type' => 'null'
            ], 401);
        } else {
            $token = $artist->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'message' => "$artist->username berhasil login",
                'data' => $artist,
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
