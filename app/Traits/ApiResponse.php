<?php

namespace App\Traits;

trait ApiResponse
{
    protected function successResponse(array $data = [],string $message = 'Operation successful',int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function errorResponse(string $message = 'Operation failed',int $statusCode = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}
