<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'product_user_id'];


    public function store($data = [])
    {
        $model = self::where(['product_id'=>$data['product_id'], 'product_user_id'=> $data['product_user_id']])->first();
        if(!$model){
            $model = new self($data);
            if(!$model->save()) return false;
        }
        return true;
    }


    public function findOne($productId,  $productUserId)
    {
        return self::where(['product_id'=> $productId, 'product_user_id'=> $productUserId])->first();
    }

}
