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

/**
 * T支付渠道
 */
class TPay extends Controller implements iPay
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
        $requestData = [
            'productId'=>  $merchant->code,
            'league'=> $merchant->league_id,
            'club'=> $merchant->club_id
        ];
        Log::channel('business_log')->info( __METHOD__ . $this->log_separator . $merchant->domain . '/online-pay/payment-methods', $requestData);
        $paylimitResult = Zttp::asFormParams()->get($merchant->domain . '/online-pay/payment-methods' , $requestData);
        Log::channel('business_log')->info(__METHOD__ . $this->log_separator . $paylimitResult);
        $paylimitResult = json_decode($paylimitResult, true);

        if(empty($paylimitResult['methods']) || !is_array($paylimitResult)){
            throw new SystemValidationException(Response::HTTP_INTERNAL_SERVER_ERROR, Lang::get("messages.500"));
        }
        foreach($paylimitResult['methods'] as $key=> $item){
        	if($item['channel'] === $depositChannel->channel){
        		return $item;
        	}
        }
        throw new SystemValidationException(Response::HTTP_INTERNAL_SERVER_ERROR, Lang::get("messages.500"));
    }

    public function pay($request, DepositChannel $depositChannel)
    {
        $requestData = $request->all();
        //验证数据
        $validator = Validator::make($requestData, [
            'product_id' => 'required',
            'product_user_id'=> 'required',
            'amount' => 'required',
        ]);

        if($validator->fails()){
            //抛出异常
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        //获取商户信息
        $merchant = Merchant::where(['id'=> $depositChannel->merchant_id])->first();
        //获取客户信息
        $customer = Customer::where(['product_id'=> $requestData['product_id'], 'product_user_id'=> $requestData['product_user_id']])->first();
        $depositModel = new Deposit();
        //生成存款订单信息
        $orderSn = $depositModel->generateDepositBillno($merchant->code, $customer->product_id, $customer->user_id);
        $depositModel->createTOrder($merchant, $customer, $depositChannel, $requestData, $orderSn);
        //生成TELFA存款接口数据
        $tRequestData =  $depositModel->generateTparams($merchant, $customer, $depositChannel, $requestData, $orderSn);
    	return $tRequestData;
    }

}