<?php
namespace App\Http\Controllers;

use App\Models\WithdrawChannel;
use App\Exceptions\ApiValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class WithdrawChannelController  extends Controller
{
    public function list(Request $request, WithdrawChannel $withdrawChannel)
    {
    	$requestData = $request->all();
        $validator = Validator::make($requestData, [
            'product_id' => 'required',
            'product_user_id'=> 'required',
    	]);

    	if($validator->fails()){
    		throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
    	}

    	$customer = Customer::where(['product_id' => $requestData['product_id'], 'product_user_id'=> $requestData['product_user_id']])->first();
    	//过滤客户的分组/俱乐部/信用等级/星级等级
        $withdrawChannel = $withdrawChannel->filterRules($customer);

        //对渠道进行排序/成功率/上次10次成功的存款记录/手续费
        return $this->returnSuccess($withdrawChannel);
    }
}