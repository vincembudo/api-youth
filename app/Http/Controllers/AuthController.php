<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>['required', 'email'],
            'password'=>['required']
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=> 422,
                'errors'=>$validator->messages()
            ],422);
        }else{
            $credentials=[
                'email'=> $request->input('email'),
                'password'=> $request->input('password')
            ];

            if (Auth::attempt($credentials)) {
                $user = User::where('email', $request->email)->first();
                $token = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'status'=>200,
                    'user'=>$user,
                    'token'=>$token,
                    'message'=>'Logged In Successfully',
                ],200);

            }else{
                return response()->json([
                    'status'=>401,
                    'errors'=>'Wrong Credentials',
                ],401);
            }




        }
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name'=> ['required', 'min:3',Rule::unique('users','name')],
            'email'=>['required', 'email', Rule::unique('users','email')],
            'password'=>['required','string','confirmed']
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=> 422,
                'errors'=>$validator->messages()
            ],422);
        }else{
            $password = bcrypt($request->input('password'));
            $user = User::create([
                'name'=> $request->input('name'),
                'email'=> $request->input('email'),
                'password'=>$password
            ]);

            $token = $user->createToken('authToken')->plainTextToken;


            return response()->json([
                'status'=>200,
                'user'=>$user,
                'token'=>$token,
                'message'=>'Registered Successfully',
            ],200);
        }
    }

    public function logout(Request $request){
        $delete=$request>auth()->user()->tokens->each->delete();

        if($delete){
            return response()->json([
                'status'=>200,
                'message'=>'Logged Out Successfully',
            ],200);
        }

    }
}
