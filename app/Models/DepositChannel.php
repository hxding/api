<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DepositChannel extends Model
{

    protected  $fillable = ['merchant_id', 'product_id', 'code', 'name', 'recommend', 'min_amount', 'max_amount', 'exchange_rate'];

    public function filterRules(Customer $customer)
    {
        //依次按规则从大到小进行过滤
        return self::all();
    }
}
