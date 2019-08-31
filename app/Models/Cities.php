<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{

    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'province_id' , 'province_id');
    }

    /**
     * 获取指定省份的城市
     */
    public function getCities($code)
    {
        //app('db')->connection()->enableQueryLog();
        self::where(['province_id' => $code])->first();
       // $sql = app('db')->getQueryLog();
        return ;
    }
}