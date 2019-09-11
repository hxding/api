<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\merchant;
use App\Models\ReceiptBank;
use App\Models\DepositRecord;
use App\Models\depositChannel;
use App\Models\Approve;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Exceptions\ApiValidationException;
use App\Exceptions\SystemValidationException;
use Illuminate\Support\Facades\Lang;

class IBusinessController extends Controller
{

    /**
     * 获取TOKEN
     */
	public function generateToken(Merchant $merchant)
	{
		$requestData = json_decode(file_get_contents("php://input"), true);
		$validator = Validator::make($requestData, [
            'pid'=> 'required',
            'account'=> 'required',
            'pwd'=> 'required'
		]);
        if($validator->fails()){
            //抛出异常
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if(!$merchant->check($requestData)){
            throw new SystemValidationException(Response::HTTP_FORBIDDEN, Lang::get("messages.500"));
        }
        return $this->returnSuccess($merchant->generateToken($requestData));
	}

    /**
     * 查询会员信息
     */
    public function customer()
    {
        $requestData = json_decode(file_get_contents("php://input"), true);
        $validator = Validator::make($requestData, [
            'pid'=> 'required',
            'login_name'=> 'required'
        ]);
        if($validator->fails()){
            //抛出异常
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $customer = Customer::where(['product_user_id'=> $requestData['login_name']])->first();
        return $this->returnSuccess($customer);
    }

    /**
     * 创建收款账号
     */
    public function createBankAccount(ReceiptBank $receiptBank)
    {
        $requestData = json_decode(file_get_contents("php://input"), true);
        $validator = Validator::make($requestData['bank'][0], [
            'bank_code'=> 'required',
            'customer_level'=> 'required',
            'trust_level'=> 'required',
            'account_no'=> 'required',
            'branch'=> 'required',
            'product_id'=> 'required',
        ]);
        if($validator->fails()){
            //抛出异常
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $receiptBankRes = $receiptBank->store($requestData['bank'][0]);
        return $this->returnSuccess($receiptBankRes);
    }


    /**
     * 删除收款账号
     */
    public function deleteBankAccount(ReceiptBank $receiptBank)
    {
        $requestData = json_decode(file_get_contents("php://input"), true);
        $validator = Validator::make($requestData, [
            'pid'=> 'required',
            'type'=> 'required',
        ]);
        if($validator->fails()){
            //抛出异常
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $receiptBankRes = $receiptBank->delete($requestData);
        return $this->returnSuccess($receiptBankRes);
    }


    /**
     * 读取存款提案
     */
    public function depositRecord(DepositRecord $depositRecord)
    {
        $requestData = json_decode(file_get_contents("php://input"), true);
        $validator = Validator::make($requestData, [
            'pid'=> 'required',
            'flag'=> 'required',
            'pageNum'=> 'required',
            'pageSize'=> 'required',
        ]);
        if($validator->fails()){
            //抛出异常
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $depositRecord = $depositRecord->defaultStatusDepositRecord($requestData);
        return $this->returnSuccess($depositRecord);
    }
    
     /**
     * 读取取款提案
     */
    public function withdrawRecord(WithdrawRecord $withdrawRecord)
    {
        $requestData = json_decode(file_get_contents("php://input"), true);
        $validator = Validator::make($requestData, [
            'pid'=> 'required',
            'flag'=> 'required',
            'pageNum'=> 'required',
            'pageSize'=> 'required',
        ]);
        if($validator->fails()){
            //抛出异常
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $withdrawRecord = $withdrawRecord->approveSuccessStatusWithdrawRecord($requestData);
        return $this->returnSuccess($withdrawRecord);
    }

    /**
     * 审核存（人工）取款提案
     */
    public function approve(Approve $approve)
    {
        $requestData = json_decode(file_get_contents("php://input"), true);
        $validator = Validator::make($requestData, [
            'pid'=> 'required',
            'request_id'=> 'required',
            'request_type'=> 'required',
            'old_flag'=> 'required',
            'new_flag'=> 'required',
            'approve_by'=> 'required',
            'user_type'=> 'required',
            'remarks'=> 'string',
            'ip_address'=> 'required',
        ]);
        if($validator->fails()){
            //抛出异常
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        switch ($requestData['request_type']) {
            case '01': //存款
                $approveRes = $approve->depositApprove($requestData);
                break;
            case '02': //取款
                $approveRes = $approve->withdrawApprove($requestData);
                break;
        }
        return $this->returnSuccess($approveRes);
    }
    
     /**
     * 在线支付存款
     */
    public function pay(DepositRecord $depositRecord)
    {
        $requestData = json_decode(file_get_contents("php://input"), true);
        $validator = Validator::make($requestData, [
            'product_id'=> 'required',
            'billno'=> 'required',
            'remarks'=> 'required',
            'orderType'=> 'required',
            'amount'=> 'required',
            'replace_flag'=> 'required',
            'created_by'=> 'required',
            'login_name'=> 'required',
            'currency'=> 'required'
        ]);
        if($validator->fails()){
            throw new ApiValidationException($validator, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $depositChannel = DepositChannel::where(['code'=> $requestData['orderType']])->first();
        $customer = Customer::where(['product_user_id'=> $requestData['login_name']])->first();

        switch ($requestData['replace_flag']) {
            case '1': //补单相关逻辑
                $payCallback = $depositRecord->makePayCallback($customer, $depositChannel, $requestData);
                break;
           case '0': //在线支付回调
                $payCallback = $depositRecord->payCallback($requestData);
                break;
        }
        return $this->returnSuccess($payCallback);
    }

}