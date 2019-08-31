<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    
    /**
     * relation province--cities 
     */
    public function cities()
    {
        return $this->hasMany('App\Models\Cities', 'province_id' , 'province_id');
    }

    public function getCities($code)
    {

    	$province = self::where(['province_id'=> $code]);

    	dd($province->cities()->get());
        return self::find(1)->cities()->where(['province_id'=> $code])->first();
    }

}
