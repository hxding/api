<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pay\iPay;
use App\Models\DepositChannel;

class UnknownPay extends Controller implements iPay
{

    public function paylimit($request, DepositChannel $depositChannel)
    {
        echo "i m UnknownPay LIMIT";
    }

    public function pay($request, DepositChannel $depositChannel)
    {
        echo "i m UnknownPay PAY";
    }
}