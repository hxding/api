<?php
namespace App\Http\Controllers;

use App\Exceptions\ApiValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Customer;
use App\Models\WithdrawChannel;
use Illuminate\Support\Facades\Validator;
use App\Models\WithdrawRecord;
use App\Models\CustomerBank;

class WithdrawController extends Controller
{

    /**
     * 取款逻辑
     */
    public function doWithdraw(Request $request, WithdrawRecord $withdrawRecord)
    {
        $requestData = $request->all();
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'product_user_id'=> 'required',
            'amount'=> 'required',
            'withdraw_channel_code'=> 'required',
            'product_billno'=> 'required'
        ]);
        if($validator->fails()){
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        //获取客户信息
        $customer = Customer::where(['product_id'=> $requestData['product_id'], 'product_user_id'=> $requestData['product_user_id']])->first();
        //获取渠道信息
        $withdrawChannel = WithdrawChannel::where(['code'=> $requestData['withdraw_channel_code']])->first();
        //获取银行卡信息
        $customerBank = CustomerBank::where(['customer_id' => $customer->id])->first();
        //生成取款订单
        $withdrawRecord = $withdrawRecord->doWithdraw($requestData, $customer,$customerBank, $withdrawChannel);
        return $this->returnSuccess($withdrawRecord);
    }
    
}