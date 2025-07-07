<?php

namespace App\Http\Datas;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Success response
     */
    public static function success(mixed $data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        if (!is_array($data) && filled($data?->resource) && $data->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            return response()->json([
                'success' => true,
                'message' => trans($message),
                'data' => [
                    'items' => $data->items(),
                    'pagination' => [
                        'total' => $data->total(),
                        'per_page' => $data->perPage(),
                        'current_page' => $data->currentPage(),
                        'last_page' => $data->lastPage(),
                    ],
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans($message),
            'data' => $data,
        ]);
    }

    /**
     * Error response
     */
    public static function error(string $message = 'Error', int $status = 400, mixed $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => trans($message),
            'errors' => $errors,
        ], $status);
    }
}
