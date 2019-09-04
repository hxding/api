<?php

namespace App\Http\Controllers\Telfa;

use App\Http\Controllers\Controller;
use Log;
use App\Models\DepositChannel;
use Illuminate\Support\Facades\Lang;
use Zttp\Zttp;

class TDeposit extends Controller
{
    /**
     * 获取渠道限额信息
     */
    public function paymentMethods($merchant)
    {
        $requestData = [
            'productId'=>  $merchant->code,
            'league'=> $merchant->league_id,
            'club'=> $merchant->club_id
        ];
        Log::channel('business_log')->info( __METHOD__ . $this->log_separator . $merchant->domain . '/online-pay/payment-methods', $requestData);
        $paylimitResult = Zttp::asFormParams()->get($merchant->domain . '/online-pay/payment-methods' , $requestData);
        Log::channel('business_log')->info(__METHOD__ . $this->log_separator . $paylimitResult);
        return $paylimitResult;
    }

}
