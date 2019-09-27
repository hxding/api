<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiValidationException;
use App\Models\Customer;
use App\Models\DepositChannel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class DepositChannelController extends  Controller
{
    public function list(Request $request, DepositChannel $channel)
    {
        //验证数据
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'product_id' => 'required',
            'product_user_id'=> 'required'
        ]);

        if($validator->fails()){
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $customer = Customer::where(['product_id' => $requestData['product_id'], 'product_user_id'=> $requestData['product_user_id']])->first();

        //过滤客户的分组/俱乐部/信用等级/星级等级
        $depositChannel = $channel->filterRules($customer);

        //对渠道进行排序/成功率/上次10次成功的存款记录/手续费
        return $this->success($depositChannel);

    }
}
