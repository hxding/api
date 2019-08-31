<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankCode extends Model
{

    public function allBankCode()
    {
        return self::all();
    }

}