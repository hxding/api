<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Cities;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Exceptions\ApiValidationException;
use App\Exceptions\SystemValidationException;
use Illuminate\Support\Facades\Lang;

class CitiesController extends Controller
{

	public function list(Request $request)
	{
        return $this->returnSuccess(Cities::all());
	}

	public function getCitiesCountry(Request $request, Cities $cities)
	{
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'code'=> 'required'
        ]);

        if($validator->fails()){
        	throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $countryLsit = $cities->getCountry($requestData['code']);

        return $this->returnSuccess($countryLsit);
	}

}