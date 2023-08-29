<?php

namespace App\Traits;

trait apiResponse {
    public function successResponse($message, $code = 200, $data) {
        return response()->json([
            'message' => $message,
            'status' => $code,
            'data' => $data
        ]);
    }

    public function errorResponse($message, $code, $data = '') {
        return response()->json([
            'message' => $message,
            'status' => $code,
            'data' => $data
        ]);
    }
}
