<?php

namespace App\Http\Controllers\Intech;

use Log;
use App\Models\DepositChannel;
use Illuminate\Support\Facades\Lang;
use Zttp\Zttp;
use App\Http\Controllers\Controller;

class IDeposit extends Controller
{
    /**
     * BQ 获取限额信息
     */
    public function BQBankList($merchant, $customer)
    {
        $requestData = [
            "product"=> $merchant->code,
            "grade"=> $customer->credit_level,
            "cuslevel"=> $customer->star_level ,
            "loginname"=> $customer->product_user_id,
            "keycode"=> md5($merchant->code . $customer->credit_level . $merchant->key), 
        ];
        Log::channel('business_log')->info( __METHOD__ . $this->log_separator . $merchant->domain . '/BQBankList.do', $requestData);
        $paylimitResult = Zttp::asFormParams()->post($merchant->domain . '/BQBankList.do' , $requestData);
        Log::channel('business_log')->info(__METHOD__ . $this->log_separator . $paylimitResult);
        return $paylimitResult;
    }

    /**
     * BQ 提交订单
     */
    public function BQPayment($merchant, $customer, $requestData)
    {
        $requestPayData = [
            'product'=> $merchant->code,
            'grade'=> $customer->credit_level,
            'cuslevel'=> $customer->star_level ,
            'loginname'=> $customer->product_user_id,
            'amount'=> $requestData['amount'],
            'bankcode'=> $requestData['bankcode'],
            'depositor'=> $requestData['depositor'],
            'customerType'=> config('map.customer.CUSTOMER_TYPE'),
            'backurl'=>  $merchant->callback_url,
            'keycode'=> md5($customer->product_user_id . $merchant->code . $requestData['amount'] . $requestData['bankcode'] .  $customer->credit_level . $merchant->key),
        ];
        Log::info( __METHOD__ . $this->log_separator . $merchant->domain . '/BQPayment.do' . json_encode($requestPayData));
        $payResult = Zttp::asFormParams()->post($merchant->domain . '/BQPayment.do' , $requestPayData);
        Log::info(__METHOD__ . $this->log_separator . $payResult);
        return $payResult;
    }

    /**
     * 在线支付获取限额信息
     */
    public function OnlinePaymentFirst($merchant, $customer,$depositChannel, $requestData)
    {
        $requestOnlineData = [
            'newaccount'=> config('map.intech_deposit_config.NEW_ACCOUNT_FLAG'),
            'product'=> $merchant->code,
            'loginname'=> $requestData['product_user_id'],
            'grade'=> $customer->credit_level,
            'cuslevel'=> $customer->star_level,
            'currency'=> config('map.currency_type_map')[$depositChannel->type],
            'type'=> $requestData['payment_code'],
            'keycode'=> md5($requestData['product_user_id'] . $merchant->code . config('map.intech_deposit_config.NEW_ACCOUNT_FLAG') . $customer->credit_level . $merchant->key)
        ];
        Log::channel('business_log')->info( __METHOD__ . $this->log_separator . $merchant->domain . '/OnlinePaymentFirst.do', $requestOnlineData);
        $paylimitResult = Zttp::asFormParams()->post($merchant->domain . '/OnlinePaymentFirst.do' , $requestOnlineData);
        Log::channel('business_log')->info(__METHOD__ . $this->log_separator . $paylimitResult);
        return $paylimitResult;
    }

    public function OnlinePaymentSecond($merchant, $customer, $depositChannel, $requestData)
    {
        $requestPayData = [
            'product'=> $merchant->code,
            'newaccount'=> config('map.intech_deposit_config.NEW_ACCOUNT_FLAG'),
            'grade'=> $customer->credit_level,
            'cuslevel'=> $customer->star_level ,
            'loginname'=> $customer->product_user_id,
            'amount'=> $requestData['amount'],
            'currency'=> config('map.currency_type_map')[$depositChannel->type],
            'payid'=> $requestData['payid'],
            'backurl'=>  $merchant->callback_url,
            'ip'=> $_SERVER['REMOTE_ADDR'],
            'keycode'=> md5($customer->product_user_id . config('map.intech_deposit_config.NEW_ACCOUNT_FLAG') . $merchant->code . $requestData['amount'] .  $customer->credit_level . $merchant->key),
        ];
        Log::channel('business_log')->info( __METHOD__ . $this->log_separator . $merchant->domain . '/OnlinePaymentSecond.do' . json_encode($requestPayData));
        $payResult = Zttp::asFormParams()->post($merchant->domain . '/OnlinePaymentSecond.do' , $requestPayData);
        Log::channel('business_log')->info(__METHOD__ . $this->log_separator . $payResult);
        return $payResult;
    }
}
