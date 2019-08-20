<?php

namespace App\Http\Controllers\Pay;
use App\Models\DepositChannel;

interface iPay{

    public function paylimit($request,DepositChannel $depositChannel);

    public function pay($request,DepositChannel $depositChannel);
}


