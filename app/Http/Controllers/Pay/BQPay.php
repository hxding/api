<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pay\iPay;
use App\Http\Library\Register;
use App\Models\Customer;
use App\Models\Merchant;
use Zttp\Zttp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Exceptions\ApiValidationException;

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
        $paylimitResult = Zttp::asFormParams()->post($merchant->domain . '/BQBankList.do' , $requestData);
        return json_decode($paylimitResult->getBody()->getContents(), true);

    }

    public function pay()
    {
        echo "i m BQ PAY";    
    }
}