<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{

    public function country()
    {
        return $this->hasMany('App\Models\Country', 'city_id' , 'city_id');
    }

    /**
     * 获取指定省份的城市
     */
    public function getCountry($code)
    {
        return Cities::with('country')->where(['city_id'=> $code])->first();
    }
}