<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Iniciar sesion y obtener token
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'mensaje' => 'Las credenciales no son correctas',
            ], 401);
        }

        // Comprobar si el usuario ya tiene un token activo
        if ($user->tokens()->count() > 0) {
            return response()->json([
                'mensaje' => 'El usuario ya tiene sesion iniciada',
            ], 200);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'mensaje' => 'Login correcto',
            'token'   => $token,
            'usuario' => $user,
        ], 200);
    }

    // Mostrar los datos del usuario logeado
    public function perfil(Request $request)
    {
        return response()->json([
            'mensaje' => 'Datos del usuario logeado',
            'usuario' => $request->user(),
        ], 200);
    }

    // Cerrar sesion e invalidar el token
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'mensaje' => 'Sesion cerrada correctamente',
        ], 200);
    }

    // Ruta publica de prueba (accesible sin login)
    public function estado()
    {
        return response()->json([
            'mensaje' => 'La API esta funcionando correctamente',
            'estado'  => 'ok',
        ], 200);
    }
}
