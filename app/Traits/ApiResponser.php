<?php

namespace App\Traits;

trait ApiResponser
{


    protected function errorResponse($message = null, $code)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => null
        ], $code);
    }
}
