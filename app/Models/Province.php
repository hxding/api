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
        return Province::with('cities')->where(['province_id'=> $code])->first();
    }


    public function getAllArea()
    {
    	return Province::with('cities')->get();
    }
}
