<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pay\iPay;
use App\Http\Library\Register;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\DepositRecord;
use App\Http\Controllers\Intech\IDeposit;
use Zttp\Zttp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Exceptions\ApiValidationException;
use App\Exceptions\SystemValidationException;
use Log;
use App\Models\DepositChannel;
use Illuminate\Support\Facades\Lang;

class BQPay extends Controller implements iPay
{
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
        $IDeposit = new IDeposit();
        $paylimitResult = $IDeposit->BQBankList($merchant, $customer);
        return json_decode($paylimitResult, true);

    }

    public function pay($request,DepositChannel $depositChannel)
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
        $merchant = Merchant::where(['id'=> $depositChannel->merchant_id])->first();
        //获取客户信息
        $customer = Customer::where(['product_id'=> $requestData['product_id'], 'product_user_id'=> $requestData['product_user_id']])->first();
        $IDeposit = new IDeposit();
        $payResult = $IDeposit->BQPayment($merchant, $customer, $requestData);
        $payResult = json_decode($payResult, true);
        if($payResult['success'] !== 0){
            $notice_message = !empty($payResult['message']) ? $payResult['message'] : Lang::get("messages.500");
            throw new SystemValidationException(Response::HTTP_INTERNAL_SERVER_ERROR, $notice_message);
        }
        //保存订单信息
        $depositModel = new DepositRecord();
        $orderData = $depositModel->createBqOrder($depositChannel, $customer, $requestData, $payResult['order']);
        return $orderData;
    }
}