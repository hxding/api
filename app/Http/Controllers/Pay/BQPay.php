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

class BQPay extends Controller implements iPay
{

    const PLATFORM_ID = 1; 

    public function paylimit($request)
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
        $merchantModel = new Merchant();
        $merchant = $merchantModel->findOne(self::PLATFORM_ID, $requestData['product_id']);

        //获取客户信息
        $customerModel = new Customer();
        $customer = $customerModel->findOne($requestData['product_id'], $requestData['product_user_id']);
      
        $requestData = [
            "product"=> $merchant->code,
            "grade"=> $customer->credit_level,
            "cuslevel"=> $customer->star_level ,
            "loginname"=> $customer->product_user_id,
            "keycode"=> md5($merchant->code . $customer->credit_level . $merchant->key), 
        ];
        Log::info( __METHOD__ . $this->log_separator . $merchant->domain . '/BQBankList.do' . json_encode($requestData));
        $paylimitResult = Zttp::asFormParams()->post($merchant->domain . '/BQBankList.do' , $requestData);
        Log::info(__METHOD__ . $this->log_separator . $paylimitResult);
        return json_decode($paylimitResult, true);

    }

    public function pay($request)
    {
        $requestData = $request->all();
        //验证数据
        $validator = Validator::make($requestData, [
            'product_id' => 'required',
            'product_user_id'=> 'required',
            'amount' => 'required',
            'bankcode'=> 'required',
            'depositor'=> 'required',
        ]);

        if($validator->fails()){
            //抛出异常
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        //获取商户信息
        $merchantModel = new Merchant();
        $merchant = $merchantModel->findOne(self::PLATFORM_ID, $requestData['product_id']);

        //获取客户信息
        $customerModel = new Customer();
        $customer = $customerModel->findOne($requestData['product_id'], $requestData['product_user_id']);

        $requestPayData = [
            'product'=> $merchant->code,
            'grade'=> $customer->credit_level,
            'cuslevel'=> $customer->star_level ,
            'loginname'=> $customer->product_user_id,
            'amount'=> $requestData['amount'],
            'bankcode'=> $requestData['bankcode'],
            'depositor'=> $requestData['depositor'],
            'customerType'=> 1,
            'backurl'=>  $merchant->callback_url,
            'keycode'=> md5($customer->product_user_id . $merchant->code . $requestData['amount'] . $requestData['bankcode'] .  $customer->credit_level . $merchant->key),
        ];
        Log::info( __METHOD__ . $this->log_separator . $merchant->domain . '/BQPayment.do' . json_encode($requestPayData));
        $payResult = Zttp::asFormParams()->post($merchant->domain . '/BQPayment.do' , $requestPayData);
        Log::info(__METHOD__ . $this->log_separator . $payResult);
        $payResult = json_decode($payResult, true);
        if($payResult['success'] !== 0){
            $notice_message = !empty($payResult['message']) ? $payResult['message'] : Lang::get("messages.500");
            throw new SystemValidationException(Response::HTTP_INTERNAL_SERVER_ERROR, $notice_message);
        }
        //保存订单信息
        $depositModel = new Deposit();
        $orderData = $depositModel->createBqOrder($merchant, $customer, $requestData, $payResult['order']);
        return $orderData;
    }
}