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
use \DB;

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


   public function customer()
   {
       return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
   }

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

   //生成存款补单订单号
   public function generateMakeDepositBillno($billno)
   {
       $suffix = '_1';
       return $billno . $suffix;
   }


   public function defaultStatusDepositRecord($data = [])
   {
      $where = [
          'status'=> $data['flag'],
      ];
      $skip = ($data['pageNum'] - 1) * $data['pageSize'];
      $depositRecordList =  DepositRecord::with('customer')
            ->where($where)
            ->skip($skip)
            ->take($data['pageSize'])
            ->get();

      if(empty($depositRecordList)){
          return false;
      }

      $iFormatData = [];
      foreach ($depositRecordList as $key => $value) {
         $iFormatData[$key]['amount'] = $value['amount'];
         $iFormatData[$key]['bank_account_name'] = $value['receipt_bank_name'];
         $iFormatData[$key]['bank_account_no'] = $value['receipt_account'];
         $iFormatData[$key]['bank_name'] = $value['receipt_depositor'];
         $iFormatData[$key]['created_date'] = $value['created_at'];
         $iFormatData[$key]['currency'] = $value['currency_type'];
         $iFormatData[$key]['deposit_by'] = $value['depositor'];
         $iFormatData[$key]['login_name'] = $value['customer']['product_user_id'];
         $iFormatData[$key]['customer_type'] = $value['customer']['customer_type'];
         $iFormatData[$key]['ip_address'] = $value['customer']['ip'];
         $iFormatData[$key]['request_id'] = $value['order_sn'];
         $iFormatData[$key]['customer_level'] = $value['customer']['star_level'];
         $iFormatData[$key]['deposit_level'] = $value['customer']['credit_level'];
         $iFormatData[$key]['priority_level'] = $value['customer']['priority_level'];
      }
      return $iFormatData;
   }

   public function makePayCallback(Customer $customer,DepositChannel $depositChannel, $data = [])
   {
      try{
          DB::beginTransaction();
          $dataTime = date('Y-m-d H:i:s');
          $orderData = [
             'product_id'           => $customer->product_id,
             'customer_id'          => $customer->id,
             'depositor'            => $customer->product_user_id,
             'order_sn'             => $this->generateMakeDepositBillno($data['billno']),
             'status'               => config('map.deposit_status.SUCCESS'),
             'currency_type'        => $data['currency'],
             'amount'               => $data['amount'],
             'merchant_notification_time'=> date('Y-m-d H:i:s'),
             'merchant_notification_amount'=> $data['amount'],
             'merchant_notification_mark'=> $data['remarks'],
             'deposit_type'         => $depositChannel->type,
             'deposit_channel_code' => $depositChannel->code,
             'merchant_id'          => $depositChannel->merchant_id,
             'updated_at'           => $dataTime,
             'created_at'           => $dataTime
          ];
          DepositRecord::create($orderData);
          //更新原单为补单状态
          DepositRecord::where(['order_sn'=> $data['billno']])->update(['status'=> config('map.deposit_status.MAKE_UP')]);
          DB::commit();
       }catch(QueryException $e){
          DB::rollBack();
          Log::info(__METHOD__ . $e->getMessage());
          throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
       }
       return true;
   }

   public function payCallback($data = [])
   {
       $where = [
          'order_sn' => $data['billno'],
          'status'=> config('map.deposit_status.DEFAULT_WAIT'),
          'amount'=> $data['amount']
       ];
       $update = [
          'status'=> config('map.deposit_status.SUCCESS'),
          'merchant_notification_time'=> date('Y-m-d H:i:s'),
          'merchant_notification_amount'=> $data['amount'],
          'merchant_notification_mark'=> $data['remarks']
       ];
       $depositRecord = DepositRecord::where($where)->update($update);
       if(empty($depositRecord)){
           throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
       }
       return $depositRecord;
   }

}
