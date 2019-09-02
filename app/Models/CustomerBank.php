<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\SystemValidationException;
use Illuminate\Database\QueryException;
use Log;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class CustomerBank extends Model
{

    public $bankAccountNoFilling = ' **** ';

    protected $fillable = ['customer_id', 'bank_account_name', 'bank_account_no', 'bank_name', 'bank_province', 'bank_cities', 'bank_countries', 'branch_name'];


    public function store($data)
    {
    	try{
            $customerBank = CustomerBank::create($data);
            if(!$customerBank){
                throw new SystemValidationException(Response::HTTP_INTERNAL_SERVER_ERROR, Lang::get("messages.10001"));    
            }
    	}catch(QueryException $e){
           Log::channel('business_log')->info(__METHOD__ . $e->getMessage());
           throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
    	}
        return $customerBank;
    }


    public function getCustomerBank($customerId)
    {
        $customerBank = self::where(['customer_id'=> $customerId])->first();
        $customerBank->bank_account_no = $this->formatBankAccountNo($customerBank->bank_account_no);
        $customerBank->bank_account_name = $this->formatBankName($customerBank->bank_account_name);
        return $customerBank;
    }

    public function formatBankAccountNo($bankAccountNo)
    {
        $bankAccountNoPrefix = substr($bankAccountNo, 0, 4);
        $bankAccountNoSuffix = substr($bankAccountNo, -4, 4);
        return $bankAccountNoPrefix . $this->bankAccountNoFilling . $bankAccountNoSuffix;
    }

    public function formatBankName($bankAccountName)
    {
        $bankNameLength = mb_strlen($bankAccountName);
        $bankAccountNamePrefix = mb_substr($bankAccountName, 0, 1, 'utf-8'); 
        $bankAccountNameSuffix = mb_substr($bankAccountName, 0, 1, 'utf-8');
        return $bankNameLength == 2 ? str_repeat('*', mb_strlen($bankAccountName, 'utf-8') - 1) . $bankAccountNameSuffix : $bankAccountNamePrefix . str_repeat('*', $bankNameLength - 2) . $bankAccountNameSuffix;
    }
}
