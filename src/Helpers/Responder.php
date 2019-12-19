<?php
namespace R64\Checkout\Helpers;

class Responder
{
    public static function success($data = [], string $message = '')
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ]);
    }

    public static function error($data = [], string $error = '', $responseCode = 400)
    {
        return response()->json([
            'success' => false,
            'error' => $error,
            'data' => $data,
        ], $responseCode);
    }

    public static function noJsonSuccess($data = [], string $message = '')
    {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];
    }

    public static function noJsonError($data = [], string $error = '')
    {
        return [
            'success' => false,
            'error' => $error,
            'data' => $data,
        ];
    }
}
