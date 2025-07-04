<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function auth(Request $request)
    {
        $credential = $request->validate([
            'email' => 'required|email|exists:users,email', 
            'password' => 'required',
        ]);

        $user = User::whereEmail($request['email'])->first();

        if(!$user || !Hash::check($request['password'], $user->password)) {
            return response([
                "message" => "Wrong email or password"
            ], 401);
        }

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
        return response([
            "accesstoken" => $token,
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "role_id" => $user->role_id
            ]
        ]);

    }
    
    function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 1
        ]);

        return response([
            "message" => "Register success"
        ], 201);
    }
}
