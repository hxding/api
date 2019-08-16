<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pay\iPay;

class OnlinePay extends Controller implements iPay
{

    public function paylimit()
    {
        echo "i m Online LIMIT";
    }

    public function pay()
    {
        echo "i m Online PAY";    
    }
}