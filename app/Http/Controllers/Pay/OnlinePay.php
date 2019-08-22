<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pay\iPay;
use App\Http\Library\Register;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\Deposit;
use Zttp\Zttp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Exceptions\ApiValidationException;
use App\Exceptions\SystemValidationException;
use Log;
use App\Models\DepositChannel;
use Illuminate\Support\Facades\Lang;

class OnlinePay extends Controller implements iPay
{

	const NEW_ACCOUNT_FLAG = 0;

    public function paylimit($request, DepositChannel $depositChannel)
    {
    	$requestData = $request->all();
        //验证数据
        $validator = Validator::make($requestData, [
            'product_id' => 'required',
            'product_user_id'=> 'required'
        ]);
        if($validator->fails()){
            //抛出异常
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        //获取商户信息
        $merchant = Merchant::where(['id'=> $depositChannel->merchant_id])->first();
        //获取客户信息
        $customer = Customer::where(['product_id'=> $requestData['product_id'], 'product_user_id'=> $requestData['product_user_id']])->first();
        $requestOnlineData = [
            'newaccount'=> self::NEW_ACCOUNT_FLAG,
            'product'=> $merchant->code,
            'loginname'=> $requestData['product_user_id'],
            'grade'=> $customer->credit_level,
            'cuslevel'=> $customer->star_level,
            'currency'=> DepositChannel::$currency_type_map[$depositChannel->type],
            'type'=> $requestData['payment_code'],
            'keycode'=> md5($requestData['product_user_id'] . $merchant->code . self::NEW_ACCOUNT_FLAG . $customer->credit_level . $merchant->key)
        ];
        Log::channel('business_log')->info( __METHOD__ . $this->log_separator . $merchant->domain . '/OnlinePaymentFirst.do', $requestOnlineData);
        $paylimitResult = Zttp::asFormParams()->post($merchant->domain . '/OnlinePaymentFirst.do' , $requestOnlineData);
        Log::channel('business_log')->info(__METHOD__ . $this->log_separator . $paylimitResult);
        $paylimitResult = json_decode($paylimitResult, true);
        if(!isset($paylimitResult['status']) || $paylimitResult['status'] !== 0){
            $notice_message = !empty($paylimitResult['message']) ? $paylimitResult['message'] : Lang::get("messages.500");
            throw new SystemValidationException(Response::HTTP_INTERNAL_SERVER_ERROR, $notice_message);
        }
        return $paylimitResult;
    }

    public function pay($request,DepositChannel $depositChannel)
    {
        $requestData = $request->all();
        //验证数据
        $validator = Validator::make($requestData, [
            'product_id' => 'required',
            'product_user_id'=> 'required',
            'amount' => 'required',
            'payid'=> 'required',
        ]);

        if($validator->fails()){
            //抛出异常
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        //获取商户信息
        $merchant = Merchant::where(['id'=> $depositChannel->merchant_id])->first();
        //获取客户信息
        $customer = Customer::where(['product_id'=> $requestData['product_id'], 'product_user_id'=> $requestData['product_user_id']])->first();

        $requestPayData = [
            'product'=> $merchant->code,
            'newaccount'=> self::NEW_ACCOUNT_FLAG,
            'grade'=> $customer->credit_level,
            'cuslevel'=> $customer->star_level ,
            'loginname'=> $customer->product_user_id,
            'amount'=> $requestData['amount'],
            'currency'=> DepositChannel::$currency_type_map[$depositChannel->type],
            'payid'=> $requestData['payid'],
            'backurl'=>  $merchant->callback_url,
            'ip'=> $_SERVER['REMOTE_ADDR'],
            'keycode'=> md5($customer->product_user_id . self::NEW_ACCOUNT_FLAG . $merchant->code . $requestData['amount'] .  $customer->credit_level . $merchant->key),
        ];
        dd($requestPayData);
        Log::channel('business_log')->info( __METHOD__ . $this->log_separator . $merchant->domain . '/OnlinePaymentSecond.do' . json_encode($requestPayData));
        $payResult = Zttp::asFormParams()->post($merchant->domain . '/OnlinePaymentSecond.do' , $requestPayData);
        Log::channel('business_log')->info(__METHOD__ . $this->log_separator . $payResult);
        $payResult = json_decode($payResult, true);
        if($payResult['success'] !== 0){
            $notice_message = !empty($payResult['message']) ? $payResult['message'] : Lang::get("messages.500");
            throw new SystemValidationException(Response::HTTP_INTERNAL_SERVER_ERROR, $notice_message);
        }
        //保存订单信息
        $depositModel = new Deposit();
        $orderData = $depositModel->createBqOrder($depositChannel, $customer, $requestData, $payResult['order']);
        return $orderData;  
    }
}