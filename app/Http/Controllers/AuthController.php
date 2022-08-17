<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function login (Request $request) {
        $data = $this->validate($request,
        [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        };
        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }
    public function register (Request $request) {
        try {
            $data = $this->validate($request,
            [
                "name"  => "required|string|min:4|max:60",
                "email" => "required|email|unique:users",
                "password" => "required|min:8|max:8|confirmed",
            ]);
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
            return response()->json([
                'status' => true,
                'message' => 'you were registered successfully',
                'token' => $user->createToken("API Token")->plainTextToken
            ] ,200);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
