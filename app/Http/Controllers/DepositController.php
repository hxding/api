<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Pay\BQPay;
use App\Http\Controllers\Pay\OnlinePay;
use App\Http\Controllers\Pay\UnknownPay;
use App\Http\Controllers\Pay\iPay;


class DepositController extends Controller
{

    public function paylimit(Request $request)
    {
        $payment_code = $request->input('payment_code', 0);

        switch ($payment_code) {
            case 1:
                $payment = new BQPay();
                break;
            case 2:
                $payment = new OnlinePay();
                break;
            default:
                $payment = new UnknownPay();
                break;
         }
        $payResult = $payment->paylimit($request);
        return $this->returnSuccess($payResult);
    }


    public function pay()
    {

    }

}
