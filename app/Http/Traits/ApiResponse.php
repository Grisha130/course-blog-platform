<?php


namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success(mixed $data = null, string $message = '', int $status = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];
        if (!is_null($data)) {
            $response['data'] = $data;
        }
        return response()->json($response, $status);
    }
    protected function error(string $message = '', int $status = 400, mixed $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if(!is_null($errors)){
            $response['errors'] = $errors;
        }
        return response()->json($response, $status);
    }
}
