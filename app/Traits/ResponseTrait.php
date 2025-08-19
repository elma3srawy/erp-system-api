<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    /**
     * Success Response
     */
    protected function success($data = [], $message = 'Success', $status = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Error Response
     */
    protected function error($message = 'Error', $status = 400, $data = []): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

     /**
     * Resource Created Response
     */
    protected function created($data = [], $message = 'Resource created successfully'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    /**
     * Resource Updated Response
     */
    protected function updated($data = [], $message = 'Resource updated successfully'): JsonResponse
    {
        return $this->success($data, $message, 200);
    }

    /**
     * Resource Deleted Response
     */
    protected function deleted($message = 'Resource deleted successfully'): JsonResponse
    {
        return $this->success([], $message, 200);
    }

    /**
     * Validation Error Response
     */
    protected function validationError($errors = [], $message = 'Validation failed'): JsonResponse
    {
        return $this->error($message, 422, $errors);
    }

    /**
     * Unauthorized Response
     */
    protected function unauthorized($message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, 401);
    }

    /**
     * Forbidden Response
     */
    protected function forbidden($message = 'Forbidden'): JsonResponse
    {
        return $this->error($message, 403);
    }

    /**
     * Not Found Response
     */
    protected function notFound($message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, 404);
    }

    /**
     * Conflict Response
     */
    protected function conflict($message = 'Conflict detected'): JsonResponse
    {
        return $this->error($message, 409);
    }

    /**
     * Server Error Response
     */
    protected function serverError($message = 'Internal server error'): JsonResponse
    {
        return $this->error($message, 500);
    }
}
