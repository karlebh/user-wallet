<?php

namespace App\Traits;

trait ResponseTrait
{
    public function successResponse(array $data = [], string $message = 'Operation successful', int $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function errorResponse(string $message = 'An error occurred', int $code = 500, array $errors = [])
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    public function validationErrorResponse($errors, string $message = 'Validation failed')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

    public function notFoundResponse(string $message = 'Resource not found')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 404);
    }

    public function unauthorizedResponse(string $message = 'Unauthorized')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 401);
    }

    public function forbiddenResponse(string $message = 'Forbidden')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 403);
    }

    public function noContentResponse()
    {
        return response()->json(null, 204);
    }
}
