<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    protected function getToken(Request $request) {
        $array = $request->all();
        if (isset($array['token'])) {
            return $array['token'];
        }
        return null;
    }
}
