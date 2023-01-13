<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    //
    /**
     * login User
     * @param Request $request
     * @return User
     */
    public function login(Request $request){
       
       try {
        $input = $request ->all();
        $validator = Validator::make($input,[
            "email" => "required|email",
            "password" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status"  => false,
                "mesage" => "Erreur de validation !", 
                "errors"  =>  $validator->errors(),
            ],422,);
        }
        if (!Auth::attempt($request->only(['email','message']))) {
            return response() ->json([
                "status"  => false,
                "message" => "Email ou mot de passe incorrect !", 
                "errors"  =>  $validator->errors(),
            ],401,);
        }
        $user = User::where('email',$request->email)->first();
        return response() ->json([
            'status'  => true,
            'message' => "Utlisateur connecté avec succés !", 
            'data'    => [
                'token' => $user->createToken('auth_user')->plainTextToken(),
                'token_type' => 'Barrer',
            ],
        ]);
       } catch (\Throwable $th) {

        //throw $th;
        return response() ->json([
            'status' => false,
            'message' => $th -> getMessage(), 
        ],500,);
       }

    }

    public function register(Request $request){
         
       try {
        $input = $request ->all();
        $validator = Validator::make($input,[
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required|cofirmed",
            "password_confirmation" => "required|required"
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status"  => false,
                "message" => "Erreur de validation !", 
                "errors"  =>  $validator->errors(),
            ],422,);
        }

        $input['password']=Hash::make($request->password);
        $user = User::create($input);
        return response() ->json([
            'status'  => true,
            'message' => "Utlisateur crée avec succés !", 
            'data'  => [
                "token" => $user->createToken('auth_user')->plainTextToken(),
                "token_type" => "Bearer",
            ],
        ]);
       } catch (\Throwable $th) {

        //throw $th;
        return response() ->json([
            'status' => false,
            'message' => $th -> getMessage(), 
        ],500,);
       }

    }
    public function profile(Request $request){
        return response() ->json([
            'status'  => true,
            'message' => "Utlisateur crée avec succés !", 
            'data' => $request->user(),
        ]);
    }

    //la methode edit
    public function edit(Request $request){
       
        try {
         $input = $request ->all();
         $validator = Validator::make($input,[
             "email" => "required|unique:users,email",
             
         ]);
         if ($validator->fails()) {
             return response()->json([
                 "status"  => false,
                 "mesage" => "Erreur de validation !", 
                 "errors"  =>  $validator->errors(),
             ],422,);
         }
         $request->user()->update($input);
         return response() ->json([
             'status'  => true,
             'message' => "Utlisateur crée avec succés !", 
             'data'    => [
                 'token' => $request->user(),
             ],
         ]);
        } catch (\Throwable $th) {
 
         //throw $th;
         return response() ->json([
             'status' => false,
             'message' => $th -> getMessage(), 
         ],500,);
        }
 
     }
     //
     public function updatePassword(Request $request){
       
        try {
         $input = $request ->all();
         $validator = Validator::make($input,[
             "old-password" => "required",
             "new-password" => "required|confirmed", 
             
         ]);
         if ($validator->fails()) {
             return response()->json([
                 "status"  => false,
                 "mesage" => "Erreur de validation !", 
                 "errors"  =>  $validator->errors(),
             ],422,);
         }
         if (!Hash::check($input['old-password'], $request->user()->password)) {
            return response() ->json([
                "status"  => false,
                "message" => "L'ancien mot de passe incorrect !", 
            ],401,);
         }

         $input['password']=Hash::make($input['new-password']);
         $request->user()->update($input);
         return response() ->json([
             'status'  => true,
             'message' => "Mot de passe modifié avec succés !", 
             'data'    => $request->user(),
         ]);
        } catch (\Throwable $th) {
 
         //throw $th;
         return response() ->json([
             'status' => false,
             'message' => $th -> getMessage(), 
         ],500,);
        }
 
     }
    public function logout(Request $request){
        $accesToken = $request->bearerToken();
        $token = PersonalAccessToken::findToken($accesToken);
        $token->delete();
        return response() ->json([
            'status'  => true,
            'message' => "Déconnecté avec succés !", 
            
        ]);
        

    } 
        
}

