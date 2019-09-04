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

}