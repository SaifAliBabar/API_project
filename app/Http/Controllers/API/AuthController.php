<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    // This function is for Signup


   public function signup(Request $request) {
    $validateUser = Validator::make(
        $request->all(),
        [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
         ]
        );

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation Fails',
                'errors' => $validateUser->errors()->all(),
            ],401);
        } else {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,

            ]);

            return response()->json([
                'status' => true,
                'messgae' => 'User Reqistered Successfully',
                'user' => $user,
            ],200);
        }


   }

//  Login Function

   public function login(Request $request) {
    $validateUser = Validator::make(
        $request->all(),
        [
            'email' => 'required|email',
            'password' => 'required',
        ]
        );

        if($validateUser->fails()) {
            return response()->json([
              'status' => false,
              'message' => 'Authentication Fails',
              'errors' => $validateUser->errors()->all(),
            ],401);
        }

        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])) {
            $authUser = Auth::user();
            return response()->json([
                'status' => true,
                'message' => 'You have been logged in Successfully',
                'token' => $authUser->createToken("API Token")->plainTextToken,
                'token_type' => 'bearer',
            ],200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email and Password does not matched',
            ],401);
        };

   }


        // Logout Function

        public function logout(Request $request) {
            $user = $request->user();
            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'User Logged out Successfully',
                'user' => $user,
            ],200);
        }


}
