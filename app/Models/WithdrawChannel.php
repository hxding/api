<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class WithdrawChannel extends Model
{

    protected $fillable = ['product_id', 'merchant_id', 'code', 'channel', 'name'];

    public function filterRules(Customer $customer)
    {
        return self::all();
    }
}