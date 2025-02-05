<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){

        $request->validate([

            'name' => 'required|string',
            'email' => 'required|string|email|unique:user',
            'password' => 'required|string',

        ]);

        $user = new\App\Models\Users();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;

        return response()->json(['message' => 'User Created Succesfully'], 201);

    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if(!Auth::attempt($credentials)){
            return response->json(['message'=>'Invalid Credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response->json(['access_token' => $token], 200);

    }

    public function logout(Request $request){
        $request->user()->token()->delete();
        return response->json(['message' => 'Logged out successfully'], 200);
    }

}
