<?php

namespace App\Helper;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    /**
     * Respuesta Exitosa
     *
     * @param string $status Estado de la respuesta (por defecto 'success')
     * @param string $message Mensaje de respuesta
     * @param mixed $data Datos adicionales
     * @param int $code Código de estado HTTP (por defecto 200)
     * @return JsonResponse
     */
    public static function success(string $status = 'success', string $message = '', mixed $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Respuesta Negativa
     *
     * @param string $status Estado de la respuesta (por defecto 'error')
     * @param string $message Mensaje de error
     * @param mixed $data Datos adicionales opcionales
     * @param int $code Código de estado HTTP (por defecto 400)
     * @return JsonResponse
     */
    public static function error(string $status = 'error', string $message = '', int $code = 400): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }
}
