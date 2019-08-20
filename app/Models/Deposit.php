<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use Log;
use App\Exceptions\SystemValidationException;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;

class Deposit extends Model
{

	protected $fillable = ['product_id', 'customer_id', 'amount', 'depositor', 'deposit_channel_code', 'order_sn', 'receipt_bank_name', 'receipt_account', 'receipt_depositor', 'status', 'deposit_type', 'currency_type', 'merchant_id', 'updated_at', 'created_at'];

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
       0 => 'CNY',
       1 => 'CNY',
       2 => 'BTC',
       3 => 'VND'
   ];

   public  function createBqOrder(DepositChannel $depositChannel, Customer $customer, $requestData= [], $payData= [])
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
           'currency_type'        => $this->currency_type_map[$depositChannel->type],
           'deposit_type'         => $depositChannel->type,
           'deposit_channel_code' => $depositChannel->code,
           'merchant_id'          => $depositChannel->merchant_id,
           'updated_at'           => $data_time,
           'created_at'           => $data_time
       ];
       
       try{
          $depositModel = new Deposit($orderData);
          $depositModel->save();
       }catch(QueryException $e){
           Log::info(__METHOD__ . $e->getMessage());
           throw new SystemValidationException(Respose::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"))
       }
       
       return $orderData;
   }
}
