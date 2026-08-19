<?php

if (!function_exists('api_response')) {
    function api_response($data = [], $success = true, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
