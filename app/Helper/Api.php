<?php
namespace App\Helper;

class Api {

    public static function apiRespond($code, $data, $message)
    {
        if($code == 500){
            $response['status'] = 500;
            $response['message'] = "An Error Has Occured";
            $response['data'] = $data;
        }else{
            $response['status'] = $code;
            $response['message'] = $message;
            $response['data'] = $data;
        }
        return response()->json($response, $code);
    }

    public static function apiResponseValidationFails($message = null, $errors = null, $status_code = 422) {
        return response()->json([
            'message' => $message,
            'status_code' => $status_code,
            'data' => [
                'errors' => $errors
            ]
        ], $status_code);
    }
}
