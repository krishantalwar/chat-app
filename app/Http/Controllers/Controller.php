<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function returnResponse($status = 200, $statusText = true, $message = "Good", $data = [], $validation_error = null)
    {
        $data['message'] = $message;
        $data['status'] = $statusText;
        if (!is_null($validation_error)) {
            $data['validation_error'] = $validation_error;
        }
        return response($data, $status);
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ], 200, [
            'Authorization' => $token
        ]);
    }

}
