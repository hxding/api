<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class Controller extends BaseController
{

    use \App\Helpers\ApiResponse;
    public $log_separator = ' : '; 

    //成功返回信息
    public function returnSuccess($data= [])
    {
    	return response()->json([
            'http_code'=> Response::HTTP_OK,
            'message'=> Lang::get("messages.200"),
            'data'=> $data
    	]);
    }

}
