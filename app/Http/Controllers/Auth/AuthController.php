<?php

namespace App\Http\Controllers\Auth;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
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
            return ResponseHelper::success('success', 'Usuario registrado con exito', $user, 201);
        } catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
            return ResponseHelper::error('error', 'Error al registrar el usuario', 400);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return ResponseHelper::error('error', 'Credenciales incorrectas', 401);
            }
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return ResponseHelper::success('success', 'Usuario logueado con exito',  $token, 200);
        } 
        catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());

            return ResponseHelper::error('error', 'Error al logear el usuario');
   
        }
    }

    public function logout()
    {
        try {
            Auth::user()->tokens()->delete();
            return ResponseHelper::success('success', 'Sesión cerrada con exito', [], 200);
        } catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
            return ResponseHelper::error('error', 'Error al cerrar la sesión', 400);
        }
    }

    public function passwordUpdate(Request $request) {

    }
}
