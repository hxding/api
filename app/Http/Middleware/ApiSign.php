<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Product;

class ApiSign
{

    public $sign;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //签名校验数据
        $requestData = $request->all();
        if(empty($requestData['sign']) || empty($requestData['product_id'])){
            return '';
        }

        //获取产品的key
        $oProduct = Product::where(['id'=> $requestData['product_id']])->first();
        if(empty($oProduct) || empty($oProduct->key)){
            return '';
        }

        $this->sign = $requestData['sign'];
        unset($requestData['sign']);
        ksort($requestData);

        $sign_str = '';
        foreach($requestData as $key => $val){
            $sign_str .= $key . '=' . $val . '&';
        }
        $sign_str = chop($sign_str, '&');
        $sign_str .= $oProduct->key;
        if($this->sign !== md5($sign_str)){
            return '';
        }

        return $next($request);
    }
}
