<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Merchant extends Model
{
    /**
     * 查询一个商户信息
     */
	public function findOne($platform_id, $product_id)
	{
		return self::where(['platform_id'=> $platform_id, 'product_id'=> $product_id])->first();
	}
}