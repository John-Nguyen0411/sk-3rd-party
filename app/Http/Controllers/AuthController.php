<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{

    function login(Request $request){

        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) {
            return response(['error_message' => 'Incorrect Details. Please try again', 'status'=> 400]);
        }
        $token = auth()->user()->createToken('sk-softchecker')->accessToken;

        return response(['user' => auth()->user(), 'token' => $token]);
    }
}
