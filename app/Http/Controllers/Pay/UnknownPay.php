<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pay\iPay;

class UnknownPay extends Controller implements iPay
{

    public function paylimit()
    {
        echo "i m UnknownPay LIMIT";
    }

    public function pay()
    {
        echo "i m UnknownPay PAY";
    }
}