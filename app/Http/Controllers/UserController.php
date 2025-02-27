<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        try {
            $user = User::get();
            return ResponseHelper::success(
                'success',
                'Lista de usuarios obtenida con éxito',
                $user,
                200
            );
        } catch (\Throwable $th) {
            Log::error('Error al obtener los usuarios: ' . $th->getMessage());
            return ResponseHelper::error('error', 'No se pudieron obtener los usuarios', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
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
            return ResponseHelper::error('error', 'Error al registrar el Usuario', 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = User::find($id);
            if (!$post) {
                return ResponseHelper::error('error', 'Usuario no encontrado', 404);
            }
            return ResponseHelper::success('success', 'Usuario encontrado con exito',  $post, 200);
        } catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
            return ResponseHelper::error('error', 'Error al buscar el Usuario', 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request -> validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);
        $post = User::find($id);
        $post->update($request->all());
        return ResponseHelper::success('success', 'Usuario actualizado con exito',  $post, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userId = User::find($id);
        $userId->delete();
        return ResponseHelper::success('success', 'Usuario eliminado con exito',  $userId, 200);
    }
    public function changePassword(Request $request)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            // Verificar que la contraseña actual es correcta
            if (!Hash::check($request->current_password, $user->password)) {
                return ResponseHelper::error('error', 'La contraseña actual es incorrecta', 401);
            }

            // Actualizar la contraseña
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            // Opcional: Revocar todos los tokens activos (forzar nuevo inicio de sesión)
            //$user->tokens()->delete();

            return ResponseHelper::success('success', 'Contraseña actualizada correctamente', [], 200);
        } catch (\Throwable $th) {
            Log::error('Error al cambiar la contraseña: ' . $th->getMessage() . ' en la línea ' . $th->getLine());
            return ResponseHelper::error('error', 'No se pudo cambiar la contraseña', 400);
        }
    }

}
