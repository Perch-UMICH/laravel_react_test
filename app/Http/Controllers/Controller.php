<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function outputJSON($result = null, $message = '', $responseCode = 200) {
        if ($message !== '') $response["message"] = $message;
        if ($result !== null) $response["result"] = $result;
        return response()->json(
            $response,
            $responseCode);
    }
}


