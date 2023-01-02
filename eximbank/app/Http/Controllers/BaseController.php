<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function responeSuccess($message='success', $data = [], $status = 200)
    {
        $response = ([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ]);
        return response()->json($response, $status);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param  array  $errorMessages
     * @param  int  $code
     *
     * @return JsonResponse
     */
    public function responeFailure($message='error', $data = [], $status = 404)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }
}
