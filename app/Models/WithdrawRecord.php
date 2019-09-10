<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\CustomerBank;
use App\Models\WithdrawChannel;

class WithdrawRecord extends Model
{   
    public $withdraw_billno_separator = 'W';

    protected $fillable = ['product_id', 'platform_id', 'withdraw_channel_code', 'customer_id', 'billno', 'status', 'amount', 'currency_type', 'customer_bank_id', 'product_billno', 'product_notify_status', 'product_notify_remark', 'bank_account_no'];


    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    public function customerBank()
    {
         return $this->belongsTo('App\Models\CustomerBank', 'customer_bank_id', 'id');
    }

    public function merchant()
    {
         return $this->belongsTo('App\Models\Merchant', 'merchant_id', 'id');
    }
  
    public function doWithdraw($requestData ,Customer $customer, CustomerBank $customerBank, WithdrawChannel $withdrawChannel)
    {
    	$merchant = Merchant::where(['id' => $withdrawChannel->merchant_id])->first();
    	$billno = $this->generateWithdrawBillno($merchant->code, $customer->user_id, $customer->product_id);
        $data = [
            'product_id'=> $customer->product_id,
            'platform_id'=> $merchant->platform_id,
            'withdraw_channel_code'=> $withdrawChannel->code,
            'customer_id'=> $customer->id,
            'billno'=> $billno,
            'status'=> config('map.withdraw_status.DEFAULT_WAIT'),
            'product_billno'=> $requestData['product_billno'],
            'amount'=> $requestData['amount'],
            'customer_bank_id'=> $customerBank->id
        ];
        try{
            $withdraw = WithdrawRecord::create($data);
            if(!$withdraw){
                throw new SystemValidationException(Response::HTTP_INTERNAL_SERVER_ERROR, Lang::get("messages.10001"));    
            }
        }catch(QueryException $e){
           Log::channel('business_log')->info(__METHOD__ . $e->getMessage());
           throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
        }
        return $withdraw;
    }

   //生成取款订单号
   public function generateWithdrawBillno($merchant_code, $product_id, $user_id)
   {
       $billnoString = substr(date('YmdHis'), 2, 12);
       return $merchant_code . $billnoString . $user_id . $this->withdraw_billno_separator . $product_id;
   }

   public function approveSuccessStatusWithdrawRecord($data = [])
   {
      $where = [
          'status'=> $data['flag'],
      ];
      $skip = ($data['pageNum'] - 1) * $data['pageSize'];
      $withdrawRecord =  WithdrawRecord::with('customer', 'customerBank', 'merchant')
                         ->where($where)
                         ->skip($skip)
                         ->take($data['pageSize'])
                         ->get();
      
      if(empty($withdrawRecord)) {
          return false;
      }
      
      $iFormatData = [];
      foreach ($withdrawRecord as $key => $value) {
         $iFormatData[$key]['amount'] = $value['amount'];
         $iFormatData[$key]['bank_account_name'] = $value['customer_bank']['bank_account_name'];
         $iFormatData[$key]['bank_account_no'] = $value['customer_bank']['bank_account_no'];
         $iFormatData[$key]['bank_account_type'] = $value['customer_bank']['type'];
         $iFormatData[$key]['bank_province'] = $value['customer_bank']['bank_province'];
         $iFormatData[$key]['bank_city'] = $value['customer_bank']['bank_cities'];
         $iFormatData[$key]['bank_name'] = $value['customer_bank']['bank_name'];
         $iFormatData[$key]['branch_name'] = $value['bank_countries'];
         $iFormatData[$key]['created_date'] = $value['created_at'];
         $iFormatData[$key]['currency'] = $value['currency_type'];
         $iFormatData[$key]['product_id'] = $value['merchant']['code'];
         $iFormatData[$key]['flag'] = $value['status'];
         $iFormatData[$key]['processed_date'] = $value['approved_time'];
         $iFormatData[$key]['remarks'] = $value['approved_remark'];
         $iFormatData[$key]['request_id'] = $value['order_sn'];
         $iFormatData[$key]['login_name'] = $value['customer']['product_user_id'];
         $iFormatData[$key]['customer_level'] = $value['customer']['star_level'];
         $iFormatData[$key]['deposit_level'] = $value['customer']['credit_level'];
         $iFormatData[$key]['priority_level'] = $value['customer']['priority_level'];
      }
      return $iFormatData;
   }

}