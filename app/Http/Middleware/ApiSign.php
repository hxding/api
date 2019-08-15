<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiValidationException;
use App\Exceptions\SystemValidationException;
use Closure;
use App\Models\Product;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use App\Http\Library\Register;

class ApiSign
{

    public $sign;

    public $newSign;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //验证签名的数据
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'sign' => 'required',
            'product_id'=> 'required'
        ]);

        if($validator->fails()){
            //抛出异常信息
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $oProduct = Product::where(['id'=> $requestData['product_id']])->first();
        if(empty($oProduct) || empty($oProduct->key)){
            throw new SystemValidationException(Response::HTTP_FORBIDDEN, Lang::get("messages.500"));
        }

        $this->sign = $requestData['sign'];
        //生成签名信息
        $this->newSign = $this->generateSign($requestData, $oProduct->key);

        //获取产品的key
//        if($this->sign !== $this->newSign){
//            throw new SystemValidationException(Response::HTTP_FORBIDDEN, Lang::get("messages.403"));
//        }

        //将产品信息注册到运行主体中
        Register::set('product', $oProduct);
        return $next($request);
    }


    public function generateSign($requestData, $key)
    {
        unset($requestData['sign']);
        ksort($requestData);

        $sign_str = '';
        foreach($requestData as $key => $val){
            $sign_str .= $key . '=' . $val . '&';
        }
        $sign_str = trim($sign_str, '&');
        return md5($sign_str . $key);
    }
}
