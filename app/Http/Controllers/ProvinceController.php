<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Province;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Exceptions\ApiValidationException;
use App\Exceptions\SystemValidationException;
use Illuminate\Support\Facades\Lang;

class ProvinceController extends Controller
{

	public function list(Request $request)
	{
        return $this->returnSuccess(Province::all());
	}

	public function getProvinceCities(Request $request, Province $province)
	{
        $requestData = $request->all();

        $validator = Validator::make($requestData, [
            'code' => 'required'
        ]);

        if($validator->fails()){
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $citiesLsit = $province->getCities($requestData['code']);

        return $this->returnSuccess($citiesLsit);
	}
}