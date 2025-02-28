<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]
            );
            return response()->json(
                $user,
                200
            );
        } catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
            return response()->json(['message' => 'No se pudo'], 422);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Credenciales Incorrectas'], 422);
            }
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return response()->json(
                $token,
                200
            );
        } catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
            return response()->json(['message' => 'No se pudo'], 422);

        }
    }

    public function logout()
    {
        try {
            Auth::user()->tokens()->delete();
            return response()->json(
                200
            );
        } catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
            return response()->json(['message' => 'No se pudo'], 422);
        }
    }
}
