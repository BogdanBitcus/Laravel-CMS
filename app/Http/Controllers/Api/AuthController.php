<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function register(RegisterRequest $request) : JsonResponse
    {
        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken('API')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully.',
                'token' => $token,
                'user' => new UserResource($user),
            ],201);

        } catch (\Throwable $e) {

            Log::error('Register error',[
                'error'=>$e->getMessage()
            ]);

            return response()->json([
                'success'=>false,
                'message'=>'Cannot register user.'
            ],500);

        }

    }



    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if (!Auth::attempt($credentials)) {

            return response()->json([
                'success'=>false,
                'message'=>'Invalid credentials',
            ],401);

        }

        $user = Auth::user();

        $token = $user
            ->createToken('insomnia')
            ->plainTextToken;

        return response()->json([
            'success'=>true,
            'message'=>'Login successful',
            'token'=>$token,
            'user'=>new UserResource($user),
        ]);
    }



    public function logout(Request $request) : JsonResponse
    {
        $request->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Logged out successfully.'
        ]);
    }



    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()
            ->tokens()
            ->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Logged out from all devices.'
        ]);
    }



    public function me(Request $request)
    {
        //dd( $request->user(), auth()->check(), auth()->user() );
        return response()->json(new UserResource($request->user()));
    }



    public function tokens(Request $request): JsonResponse
    {
        return response()->json([
            'success'=>true,
            'data'=>$request->user()
                ->tokens()
                ->get([
                    'id',
                    'name',
                    'last_used_at',
                    'created_at',
                    'expires_at'
                ])
        ]);
    }



    public function deleteToken(Request $request, $id): JsonResponse
    {
        $request->user()
            ->tokens()
            ->where('id',$id)
            ->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Token deleted.'
        ]);
    }



    public function renameToken(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name'=>'required|max:255'
        ]);

        $token = $request->user()
            ->tokens()
            ->findOrFail($id);

        $token->update([
            'name'=>$request->name
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Token updated.'
        ]);
    }




}
