<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DepositChannel extends Model
{
  /**
    * 货币类型（注：下标为存款的唯一标识）
    */
   public static $currency_type_map = [
       0 => 'CNY',
       1 => 'CNY',
       2 => 'BTC',
       3 => 'VND'
   ];

    protected  $fillable = ['merchant_id', 'product_id', 'code', 'name', 'recommend', 'min_amount', 'max_amount', 'exchange_rate'];

    public function filterRules(Customer $customer)
    {
        //依次按规则从大到小进行过滤
        return self::all();
    }
}
