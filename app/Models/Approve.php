<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\WithdrawRecord;
use Illuminate\Support\Facades\DB;
use App\Exceptions\SystemValidationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class Approve extends Model
{
	protected $fillable = ['request_type', 'merchant_code', 'request_id', 'old_flag', 'new_flag', 'approve_by', 'user_type', 'remarks', 'ip_address'];

    //存款审核逻辑
	public function depositApprove($data = [])
	{
        return __METHOD__;
	}

    //取款审核逻辑
	public function withdrawApprove($data = [])
	{
        try{
            DB::beginTransaction();
        	$withdrawApproveFlag = WithdrawRecord::where(['billno'=> $data['request_id'], 'status'=> $data['old_flag']])->update(['status'=> $data['new_flag']]);
        	if($withdrawApproveFlag < 1){
        		throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
        	}
            $approveData = [
                'request_type'=> $data['request_type'],
                'merchant_code'=> $data['pid'],
                'request_id'=> $data['request_id'],
                'old_flag'=> $data['old_flag'],
                'new_flag'=> $data['new_flag'],
                'approve_by'=> $data['approve_by'],
                'user_type'=> $data['user_type'],
                'remarks'=> $data['remarks'],
                'ip_address'=> $data['ip_address']
            ];
            Approve::create($approveData);
            DB::commit();
        }catch(QueryException $e){
            DB::rollBack();
            Log::info(__METHOD__ . $e->getMessage());
            throw new SystemValidationException(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("messages.422"));
        }
        return true;
	}
}