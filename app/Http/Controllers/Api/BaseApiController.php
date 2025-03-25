<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    protected function sendResponse($data, string $message = "Success", int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function sendError(string $errorMessage, array $errorData = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $errorMessage,
            'errors'  => $errorData,
        ], $status);
    }
}
