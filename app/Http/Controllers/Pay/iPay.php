<?php

namespace App\Http\Controllers\Pay;

interface iPay{

    public function paylimit($request);

    public function pay();
}


