<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponseFormatter extends Controller
{
    public static $response = [
        'message' => null,
        'data' => null,
    ];

    public static function success($data, $message = null)
    {
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, 200);
    }

    public static function error($message, $code)
    {
        self::$response['message'] = $message;

        return response()->json(self::$response, $code);
    }
}
