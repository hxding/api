<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use Log;

class Deposit extends Model
{

	protected $fillable = ['product_id', 'customer_id', 'amount', 'depositor', 'deposit_channel_code', 'order_sn', 'receipt_bank_name', 'receipt_account', 'receipt_depositor', 'status', 'deposit_type', 'currency_type', 'merchant_id', 'updated_at', 'created_at'];

   /**
    * 存款类型
    */
   private $deposit_type_map = [
        1 => 0,
	    2 => 0,
	    3 => 1,
	    4 => 1,
	    5 => 2,
	    6 => 2,
   ];

   /**
    * 订单存款状态
	*/
   private $status_map = [
       'wait'     => 0,
       'success'  => 2,
       'fail'     => -1,
       'make_up'  => -2,
       'discard'  => -3
   ];

   /**
    * 货币类型（注：下标为存款的唯一标识）
    */
   private $currency_type_map = [
       1 => 'CNY',
       2 => 'CNY',
       3 => 'BTC',
       4 => 'BTC',
       5 => 'USDT',
       6 => 'USDT'
   ];

   public  function createBqOrder(Merchant $merchant, Customer $customer, $requestData= [], $payData= [])
   {
   	   $data_time = date('Y-m-d H:i:s');
       $orderData = [
           'product_id'           => $customer->product_id,
           'customer_id'          => $customer->id,
           'amount'               => $requestData['amount'],
           'depositor'            => $requestData['depositor'],
           'order_sn'             => $payData['billno'],
           'receipt_bank_name'    => $payData['bankname'],
           'receipt_account'      => $payData['accountnumber'],
           'receipt_depositor'    => $payData['accountname'],
           'status'               => $this->status_map['wait'],
           'deposit_type'         => $this->deposit_type_map[$requestData['payment_code']],
           'currency_type'        => $this->currency_type_map[$requestData['payment_code']],
           'deposit_channel_code' => $requestData['payment_code'],
           'merchant_id'          => $merchant->id,
           'updated_at'           => $data_time,
           'created_at'           => $data_time
       ];

       $model = new Deposit($orderData);
       if(!$model->save()){
           //记录报错信息
       }
       return $orderData;
   }
}
