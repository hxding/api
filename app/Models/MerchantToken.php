<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MerchantToken extends Model
{
    public $timestamps = false;
    protected  $fillable = ['merchant_code', 'account', 'iat', 'exp', 'ip', 'token'];

    public function check($token)
    {
        return self::where(['token'=> $token])->count();
    }
}
