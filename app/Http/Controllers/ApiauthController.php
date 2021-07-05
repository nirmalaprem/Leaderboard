<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class ApiauthController extends Controller
{
    /**
     * store newly registered user information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {

        $requestFields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $requestFields['name'],
            'email' => $requestFields['email'],
            'password' => bcrypt($requestFields['password'])
        ]);

        if($user) {
            $token = $user->createToken('ledgerboard')->plainTextToken;
            $response = response([
                'user' => $user,
                'Token' => $token
            ], 200);
        } else {
            $response = response([
                'message'=> 'User creation has failed',
            ], 403); 
        }
        
        return $response;
    }

    /**
     * verify the user and provide new token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $requestFields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email',$requestFields['email'])->first();
        
        if(!$user || !Hash::check($requestFields['password'], $user->password)) {
            $response = response([
                'message'=> 'Bad Credentials',
            ], 401);
        } else {
            $token = $user->createToken('ledgerboard')->plainTextToken;
            $response = response([
                'user' => $user,
                'Token' => $token
            ], 200);
             
        }
        return $response;
    }

    /**
     * Destroy the user tocken.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];

    }
}
