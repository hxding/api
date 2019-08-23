<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Pay\BQPay;
use App\Http\Controllers\Pay\OnlinePay;
use App\Http\Controllers\Pay\UnknownPay;
use App\Http\Controllers\Pay\TPay;
use App\Http\Controllers\Pay\iPay;
use App\Models\DepositChannel;
use App\Exceptions\SystemValidationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;


class DepositController extends Controller
{

    public function paylimit(Request $request)
    {
        $payment_code = $request->input('payment_code', 0);
        //获取渠道信息
        $channelInfo = DepositChannel::where(['code'=> $payment_code])->first();
        if(empty($channelInfo)){
            throw new SystemValidationException(Response::HTTP_FORBIDDEN, Lang::get("messages.403"));
        }
        switch ($payment_code) {
            case 1:
                $payment = new BQPay();
                break;
            case 2:
            case 5:
            case 6:
            case 15:
                $payment = new OnlinePay();
                break;
            case 101:
                $payment = new TPay();
                break;
            default:
                $payment = new UnknownPay();
                break;
        }
        $paylimitResult = $payment->paylimit($request, $channelInfo);
        return $this->returnSuccess($paylimitResult);
    }


    public function pay(Request $request)
    {
        $payment_code = $request->input('payment_code', 0);
        //获取渠道信息
        $channelInfo = DepositChannel::where(['code'=> $payment_code])->first();
        if(empty($channelInfo)){
            throw new SystemValidationException(Respose::HTTP_FORBIDDEN, Lang::get("messages.403"));
        }
        switch ($payment_code) {
            case 1:
                $payment = new BQPay();
                break;
            case 2:
            case 5:
            case 6:
            case 15:
                $payment = new OnlinePay();
                break;
            case 101:
                $payment = new TPay();
                break;
            default:
                $payment = new UnknownPay();
                break;
        }
        $payResult = $payment->pay($request, $channelInfo);
        return $this->returnSuccess($payResult);
    }

}
