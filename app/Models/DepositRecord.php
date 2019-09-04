<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use Log;
use App\Exceptions\SystemValidationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\QueryException;
use App\Models\Merchant;
use App\Http\Library\Helper;

class DepositRecord extends Model
{
     
	protected $fillable = ['product_id', 'customer_id', 'amount', 'depositor', 'deposit_channel_code', 'order_sn', 'receipt_bank_name', 'receipt_account', 'receipt_depositor', 'status', 'deposit_type', 'currency_type', 'merchant_id', 'updated_at', 'created_at'];
  
  /**
   * 存款订单分隔符
   */
  private $deposit_billno_separator = 'D';
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
   public $currency_type_map = [
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
          $depositModel = new DepositRecord($orderData);
          $depositModel->save();
       }catch(QueryException $e){
           Log::info(__METHOD__ . $e->getMessage());
           throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
       }
       
       return $orderData;
   }

   /**
    * 创建在线存款订单
    */
   public function createOnlineOrder(DepositChannel $depositChannel, Customer $customer, $requestData= [], $payData= [])
   {
       $data_time = date('Y-m-d H:i:s');
       $orderData = [
           'product_id'           => $customer->product_id,
           'customer_id'          => $customer->id,
           'depositor'            => $customer->product_user_id,
           'amount'               => $requestData['amount'],
           'order_sn'             => $payData['billno'],
           'status'               => $this->status_map['wait'],
           'currency_type'        => $this->currency_type_map[$depositChannel->type],
           'deposit_type'         => $depositChannel->type,
           'deposit_channel_code' => $depositChannel->code,
           'merchant_id'          => $depositChannel->merchant_id,
           'updated_at'           => $data_time,
           'created_at'           => $data_time
       ];
       try{
          $depositModel = new DepositRecord($orderData);
          $depositModel->save();
       }catch(QueryException $e){
           Log::channel('business_log')->info(__METHOD__ . json_encode($e->getMessage()));
           throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
       }
       return $orderData;
   }
   

   /**
    * 创建T订单
    */ 
   public  function createTOrder(Merchant $merchant, Customer $customer, DepositChannel $depositChannel, $requestData= [], $orderSn)
   {
       $dataTime = date('Y-m-d H:i:s');
       $orderData = [
           'product_id'           => $customer->product_id,
           'customer_id'          => $customer->id,
           'amount'               => $requestData['amount'],
           'depositor'            => $customer->product_user_id,
           'order_sn'             => $orderSn,
           'status'               => $this->status_map['wait'],
           'currency_type'        => $this->currency_type_map[$depositChannel->type],
           'deposit_type'         => $depositChannel->type,
           'deposit_channel_code' => $depositChannel->code,
           'merchant_id'          => $depositChannel->merchant_id,
           'updated_at'           => $dataTime,
           'created_at'           => $dataTime
       ];
       try{
          $depositModel = new Deposit($orderData);
          if(!$depositModel->save()){
              Log::info(__METHOD__ . " 数据保存失败： " , $orderData);
              throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
          }
          return $orderData;
       }catch(QueryException $e){
           Log::info(__METHOD__ . $e->getMessage());
           throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
       }
   }

   /**
    * 生成T存款参数
    */
   public function generateTparams(Merchant $merchant, Customer $customer, DepositChannel $depositChannel, $requestData= [], $orderSn)
   {
       $requestData = [
           'amount'=> $requestData['amount'],
           'club'=> $merchant->club_id,
           'league'=> $merchant->league_id,
           'currency'=> $this->currency_type_map[$depositChannel->type],
           'ip'=> $_SERVER['REMOTE_ADDR'],
           'login_name'=> $customer->product_user_id,
           'channel'=> $depositChannel->channel,
           'calllback_url'=> $merchant->callback_url,
           'payment_reference'=> $orderSn,
           'product_id'=> $merchant->product_id
       ];
       ksort($requestData);
       $requestData['sign'] = Helper::generateTSing($requestData, $merchant->key);
       $requestData['url'] = $merchant->domain;
       return $requestData;
   }

   //生成存款订单号
   public function generateDepositBillno($merchant_code, $product_id, $user_id)
   {
       $billnoString = substr(date('YmdHis'), 2, 12);
       return $merchant_code . $billnoString . $user_id . $this->deposit_billno_separator . $product_id;
   }

}
