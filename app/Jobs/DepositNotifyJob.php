<?php

namespace App\Jobs;
use App\Models\DepositRecord;
use Log;
use App\Events\DepositEvent;
use App\Models\DepositChannel;
use App\Models\Customer;

class DepositNotifyJob extends Job
{

    protected $requestData;

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(DepositRecord $depositRecord)
    {
        $depositRecord = DepositChannel::where(['code'=> $this->requestData['orderType']])->first();
        $customer = Customer::where(['product_user_id'=> $this->requestData['login_name']])->first();

        Log::channel('business_log')->info(__METHOD__, $this->requestData);

        switch ($this->requestData['replace_flag']) {
            case '1':
                $payCallback = $depositRecord->makePayCallback($customer, $depositRecord, $this->requestData);
                $this->requestData['billno'] = $this->requestData['billno'] . '_1';
                break;            
            case '0':
                $payCallback = $depositRecord->payCallback($this->requestData);
                break;
        }

        if($payCallback !== true){
            Log::channel('business_log')->info(__METHOD__, $this->requestData['order_sn'] . "订单已被处理");
            return;
        }

        $where = [
            'status' => config('map.deposit_status.SUCCESS'),
            'order_sn'=> $this->requestData['billno']
        ];

        $successDepositRecord = DepositRecord::where($where)->first();

        event(new DepositEvent($successDepositRecord));
    }
}
