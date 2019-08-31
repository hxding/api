<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\BankCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Exceptions\ApiValidationException;
use App\Exceptions\SystemValidationException;
use Illuminate\Support\Facades\Lang;

class BankCodeController extends Controller
{

    public function list(Request $request, BankCode $bankCode)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'product_id'=> 'required',
            'product_user_id'=> 'required'
        ]);

        if($validator->fails()){
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $bankList = $bankCode->allBankCode();

        return $this->returnSuccess($bankList);
    }
}
