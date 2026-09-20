<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Devuelve una respuesta JSON exitosa estandarizada.
     */
    public function successResponse(mixed $data = null, string $message = 'Operación realizada con éxito', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Devuelve una respuesta JSON de error estandarizada.
     */
    public function errorResponse(string $message = 'Ocurrió un error', int $code = 400, mixed $errors = null): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
