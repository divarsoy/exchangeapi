<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExchangeController extends Controller
{

    protected function info(){
        return response(json_encode([
            "error" => 0,
            "msg" => "API written by Dag Ivarsoy"
        ]));
    }
}
