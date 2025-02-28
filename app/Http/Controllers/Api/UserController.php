<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
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
            return response()->json(
                $user,
                200
            );
        } catch (\Throwable $th) {
            Log::error('Error al obtener los usuarios: ' . $th->getMessage());
            return response()->json(['message' => 'No se pudo'], 422);
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
            return response()->json(
                $user,
                200
            );
        } catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
            return response()->json(['message' => 'No se pudo'], 422);
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
                return response()->json(['message' => 'No se pudo'], 422);
            }
            return response()->json(
                $post,
                200
            );
        } catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
            return response()->json(['message' => 'No se pudo'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);
        $user = User::find($id);
        $user->update($request->all());
        return response()->json(
            $user,
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userId = User::find($id);
        $userId->delete();
        return response()->json(['message' => 'Se elimino'], 422);
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
                return response()->json(['message' => 'Contraseña mal '], 422);
            }

            // Actualizar la contraseña
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            // Opcional: Revocar todos los tokens activos (forzar nuevo inicio de sesión)
            //$user->tokens()->delete();

            return response()->json(['message' => 'se pudo'], 200);
        } catch (\Throwable $th) {
            Log::error('Error al cambiar la contraseña: ' . $th->getMessage() . ' en la línea ' . $th->getLine());
            return response()->json(['message' => 'se pudo'], 400);
        }
    }

}
