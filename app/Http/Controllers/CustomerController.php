<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerBank;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Exceptions\ApiValidationException;
use App\Exceptions\SystemValidationException;
use Illuminate\Support\Facades\Lang;

class CustomerController extends Controller
{

    public function createCustomer(Request $request, Customer $customer)
    {
        //验证请求数据
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'product_user_id'=> 'required'
        ]);
        if($validator->fails()){
            //抛出异常信息
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //保存数据
        $createData = $customer->store($request->all());
        if(!$createData){
            throw new SystemValidationException(Response::HTTP_INTERNAL_SERVER_ERROR, Lang::get("messages.500"));
        }

        //返回json数组
        return $this->returnSuccess();
    }

    /**
     * 绑定银行卡 
     */
    public function bindCustomerBank(Request $request, CustomerBank $customerBank)
    {
        //验证请求数据
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'product_id' => 'required',
            'product_user_id'=> 'required',
            'bank_account_name'=> 'required',
            'bank_account_no'=> 'required',
            'bank_name'=> 'required',
            'bank_province'=> 'required',
            'bank_cities'=> 'required',
            'bank_countries'=> 'required',
        ]);

        if($validator->fails()){
            //抛出异常信息
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $customer = Customer::where(['product_id'=> $requestData['product_id'], 'product_user_id'=> $requestData['product_user_id']])->first();
        $requestData['customer_id']= $customer->id;
        $customerBank = $customerBank->store($requestData);
        return $this->returnSuccess($customerBank);
    }

    /**
     * 获取客户银行卡信息
     */
    public function getCustomerBank(Request $request, CustomerBank $customerBank)
    {
        $requestData = $request->all();
         //验证请求数据
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'product_user_id'=> 'required'
        ]);
        if($validator->fails()){
            //抛出异常信息
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $customer = Customer::where(['product_id'=> $requestData['product_id'], 'product_user_id'=> $requestData['product_user_id']])->first();
        $customerBank = $customerBank->getCustomerBank($customer->id);
        return $this->returnSuccess($customerBank);
    }

}
