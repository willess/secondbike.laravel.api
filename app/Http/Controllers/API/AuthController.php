<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function Register(Request $request)
    {
        // validating incoming data
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:4'
        ]);

        //hashing password
        $validatedData['password'] = bcrypt($request->password);

        //create the user
        $user = User::create($validatedData);

        // create an access token
        $accessToken = $user->createToken('authToken')->accessToken;

        //return created user with access token and 201(created) statuscode
        return response()->json(['user' => $user, 'access_token' => $accessToken], 201);
    }

    public function Login(Request $request)
    {
        //validating incoming data
        $validatedData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        //check if email and password matches
        if(!auth()->attempt($validatedData)) {
            return response()->json(['message' => 'Invalid Credentials, try again']);
        }

        // create a token
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json(['user' => auth()->user(), 'accessToken' => $accessToken], 200);
    }
}
