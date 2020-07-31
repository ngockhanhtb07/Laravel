<?php
namespace App\Traits;

trait CommonResponse
{
    public function successResponse($data, $message, $code = 200)
    {
        if (is_null($data) || "" == $data)
            $data = new \stdClass();
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public function errorResponse($message, $code, $data = null)
    {
        if (is_null($data))
            $data = new \stdClass();
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], 200);
    }
}
