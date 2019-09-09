<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MerchantToken;
use App\Exceptions\SystemValidationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\QueryException;

class Merchant extends Model
{
    /**
     * 查询一个商户信息
     */
	public function findOne($platform_id, $product_id)
	{
		return self::where(['platform_id'=> $platform_id, 'product_id'=> $product_id])->first();
	}

	/**
	 * 验证商户信息
	 */
	public function check($data)
	{
        $where = [
            'code'=> $data['pid'],
            'account'=> $data['account'],
            'password'=> $data['pwd']
        ];
        return self::where($where)->count();
	}

	/**
     * 生成TOKEN信息 
     */
    public function generateToken($data)
    {
        $iat = time();
        $exp_time = config('map.merchant.EXP_TIME');
        $token = sha1(uniqid($data['pid'], true));
        $returnData = [
            'merchant_code'=> $data['pid'],
            'account'=> $data['account'],
            'ip' => $_SERVER['REMOTE_ADDR'],
            'iat'=>$iat,
            'exp'=> $iat + $exp_time,
            'token'=> $token,
        ];
        try{
        	MerchantToken::create($returnData);
        	unset($returnData['merchant_code']);
        	unset($returnData['account']);
        	return $returnData;
        }catch(QueryException $e){
            Log::info(__METHOD__ . $e->getMessage());
            throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
        }
    }

}