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
        if ($message != '') $response["message"] = $message;
        if ($result != null) $response["result"] = $result;
        return response()->json(
            $response,
            $responseCode);
    }

    // Finds string in haystack that's closest (levenshtein) to needle
    protected function closest_match($needle, $haystack) {
        $shortest = INF;
        $closest = null;

        foreach ($haystack as $h) {

            // Calculate the distance between the input word
            // and the current word
            $lev = levenshtein($needle, $h);

            // Check for an exact match
            if ($lev == 0) {
                $closest = $h;
                $shortest = 0;
                break;
            }

            if ($lev <= $shortest) {
                $closest  = $h;
                $shortest = $lev;
            }
        }

        return $closest;
    }

    protected function exact_match($needle, $haystack) {
        $selected = [];
        foreach($haystack as $h) {
            $pos = stripos($h, $needle);
            if ($pos !== false) {
                $selected[] = $h;
            }
        }

        return $selected;
    }

}


