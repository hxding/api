<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;
use App\Exceptions\ApiValidationException;
use App\Exceptions\SystemValidationException;
use Illuminate\Support\Facades\Lang;

class CustomerController extends Controller
{

    public function createCustomer(Request $request, Customer $customer)
    {
        //验证数据
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
}
