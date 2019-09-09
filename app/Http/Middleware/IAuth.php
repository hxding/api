<?php
namespace App\Http\Middleware;

use App\Exceptions\ApiValidationException;
use App\Exceptions\SystemValidationException;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use App\Models\MerchantToken;

/**
 * 业务系统验证逻辑
 */
class IAuth
{
    public function handle($request, Closure $next)
    {
    	$merchantToken = new MerchantToken();
    	if(empty($_SERVER['HTTP_AUTHORIZATION'])){
    		throw new SystemValidationException(Response::HTTP_FORBIDDEN, Lang::get("messages.403"));
    	}
        if(!$merchantToken->check($_SERVER['HTTP_AUTHORIZATION'])){
            throw new SystemValidationException(Response::HTTP_FORBIDDEN, Lang::get("messages.403"));
        }
        return $next($request);
    }
}
