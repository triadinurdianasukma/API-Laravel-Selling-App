<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

class UserController extends Controller
{
    //membuat fungsi register, menggunakan block package
    public function register(Request $request)
    {   
        //laravel validation, membuat validasi dari controller
        try
        {
            //validasi data dari name, username, email, password
            //need add validate email only gmail,yahoo etc
            $request->validate(
                [
                'name' => ['required','string','max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'max:255', 'unique:users'],
                'password' => ['required', 'string', new Password],
                'phone' => ['nullable', 'string', 'max:255', 'unique:users'],
                ]
                );

            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
            ]);

            $user = User::where('email', $request->email)->first();
            
            //generate token using HasAPI in model user.
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ],'User Registered' );
        }
        catch(Exception $error){
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication Failed', 500 );
        }
    }

    public function login(Request $request)
    {
        try{
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            //Store credential to var
            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials)){
                return ResponseFormatter::error([
                    'message' => 'Unauthorized',
                ], 'Authentication Failde', 500);
            }

            $user = User::where('email', $request->email)->first();
            
            //Check password credential
            if(! Hash::check($request->password, $user->password,[])){
                throw new \Exception('Invalid Credentials');
            }

            //return user token
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        }
        catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failde', 500);
        }
    }

    public function fetch(Request $request){
        return ResponseFormatter::success($request->user(),'Data Profile user berhasil di ambil');
    }

    //TASK : Request Validate
    public function updateProfile(Request $request){
        $data = $request->all();
        $user = Auth::user();
        $user->update($data);
        return ResponseFormatter::success($user, 'Profile Updated');
    }

    public function logout(Request $request){
        $token = $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success($token, 'Token Revoked');
    }
}
