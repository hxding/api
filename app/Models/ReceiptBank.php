<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\QueryException;
use Log;
use App\Exceptions\SystemValidationException;

class ReceiptBank extends Model
{
	protected $fillable = ['merchant_code', 'bank_code', 'star_level', 'credit_level', 'account_name', 'account_no', 'branch', 'limit_amount', 'bank_name', 'province', 'city', 'area_limit', 'created_by', 'ip_address', 'remarks', 'is_show', 'currency', 'special_member', 'type'];

    public function store($data = [])
    {
        $dataTime = date('Y-m-d H:i:s');
        $receiptBankData = [
            'merchant_code' => $data['product_id'],
            'bank_code'=> $data['bank_code'],
            'star_level'=> $data['customer_level'],
            'credit_level'=> $data['trust_level'],
            'account_name'=> $data['account_name'],
            'account_no'=> $data['account_no'],
            'branch'=> $data['branch'],
            'limit_amount'=> $data['limit_amount'],
            'bank_name'=> $data['bank_name'],
            'province'=> $data['province'],
            'city'=> $data['city'],
            'area_limit'=> $data['area_limit'],
            'created_by'=> $data['created_by'],
            'ip_address'=> $data['ip_address'],
            'remarks'=> $data['remarks'],
            'is_show'=> $data['is_show'],
            'currency'=> $data['currency'],
            'special_member'=> $data['special_member'],
            'type'=> $data['type'],
            'update_time'=> $dataTime,
            'create_time'=> $dataTime
        ];

        try{
            $receiptBankModel = ReceiptBank::create($receiptBankData);
            if(!$receiptBankModel){
                throw new SystemValidationException(Response::HTTP_INTERNAL_SERVER_ERROR, Lang::get("messages.10001"));    
            }
            return  $receiptBankModel;
        }catch(QueryException $e){
            Log::info(__METHOD__ . $e->getMessage());
            throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
        }
    }

    public function delete($data = [])
    {
        $where = [
            'merchant_code' => $data['pid'],
            'type'=> $data['type']
        ];
        try{
            $receiptBankRes = ReceiptBank::where($where)->delete();
            if(!$receiptBankRes){
                throw new SystemValidationException(Response::HTTP_INTERNAL_SERVER_ERROR, Lang::get("messages.10001"));
            }
            return true;
        }catch(QueryException $e){
            Log::info(__METHOD__ . $e->getMessage());
            throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
        }
    }
}