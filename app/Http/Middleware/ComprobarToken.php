<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class ComprobarToken
{
    public function handle(Request $request, Closure $next): Response
    {
        // Obtenemos el token de la cabecera Authorization
        $cabecera = $request->header('Authorization');

        if (!$cabecera || !str_starts_with($cabecera, 'Bearer ')) {
            return response()->json([
                'mensaje' => 'No tienes permiso para acceder. Token no encontrado.',
            ], 401);
        }

        $tokenTexto = substr($cabecera, 7);

        // Buscamos el token en la base de datos
        $tokenEncontrado = PersonalAccessToken::findToken($tokenTexto);

        if (!$tokenEncontrado) {
            return response()->json([
                'mensaje' => 'No tienes permiso para acceder. Token invalido.',
            ], 401);
        }

        // Asociamos el usuario autenticado a la peticion con su token
        $usuario = $tokenEncontrado->tokenable;
        $usuario->withAccessToken($tokenEncontrado);

        $request->setUserResolver(function () use ($usuario) {
            return $usuario;
        });

        return $next($request);
    }
}
