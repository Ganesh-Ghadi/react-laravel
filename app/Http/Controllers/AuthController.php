<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
               'email' =>'required',
               'password'=>'required' 
        ]);

       if($validator->fails()){
          return response()->json([ 'success'=>false,"message"=>$validator->errors()], 401);
       }

       if(!$token = JWTAuth::attempt($validator->validated())){
            return response()->json(['success'=>false, 'message'=>'Invalid Credentials'], 401);
       }

       return $this->respondWithToken($token);

    }

       public function respondWithToken($token){
          $user = auth()->user();
          return response()->json(['success'=>true, 'token'=>$token, 'user'=>$user]);
       }


       public function register(Request $request){
           $validator = Validator::make($request->all(),[
            'name'=>'required|min:2|max:100|string',
            'email'=>'required|string|email|max:200|unique:users',
            'password'=>'required|min:8',
             'password_confirmation'=>'required'
           ]);

           if($validator->fails()){
            return response()->json([ 'success'=>false,"message"=>$validator->errors()], 401);
         }
            //    data getting encrypted automatically from User model
          $user = User::create($request->all());

          return response()->json(['success'=>true, 'user'=>$user, 'message'=>'user registered succesfully'], 201);
          
       }




}
