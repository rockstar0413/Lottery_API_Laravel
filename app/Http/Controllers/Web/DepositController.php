<?php
namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Web\Bank;
use Auth;
use Validator;
use App\Models\Web\Sys800;
use App\Models\Web\SysConfig;
use App\Models\User;
class DepositController extends Controller {

    public function __construct(){
        //$this->middleware("auth:api");
    }
    /* Get bank info. */
    public function getBank(Request $request) {
        $bank = Bank::all();
        return response()->json(['success'=>true, 'bankList' => $bank]);
    }
    /* Deposit function. */
    public function addMoney(Request $request) {
        $user = User::where('ID',$request->userId)->first();
        $Order_Code='CK'.date("YmdHis",time()+12*3600).mt_rand(1000,9999);
        $validator = Validator::make($request->all(),[
            'isCrypto' => 'required',
            'money' => 'required',
            'name' => 'required',
            'bankName' => 'required',
            'bankAddress' => 'required',
            'bankNo' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->toArray()
            ], 500);
        }
        error_log($request->isCrypto);
        //$user = Auth::guard("api")->user();
        $date=date("Y-m-d",time());
        $payWay="W";
        $type="S";
        $data = [
            "Payway" => $payWay,
            "Gold" => $request->money,
            "AddDate" => $date,
            "Type" => $type,
            "Type2" => "1",
            "UserName" => $user->UserName,
            "Agents" => $user->Agents,
            $user->World&&"World" => $user->World,
            $user->Corprator&&"Corprator" => $user->Corprator,
            $user->Super&&"Super" => $user->Super,
            $user->Admin&&"Admin" => $user->Admin,
            "CurType" => 'RMB',
            "Name" => $request->isCrypto ? $user->Alias : $request->name,//$user->Alias,
            $user->bankName&&"Bank" => $user->bankName,
            $user->bankAddress&&"Bank_Address" => $request->bankAddress,
            $user->bankNo&&"Bank_Account" => $request->bankNo,
            "Order_Code" => $Order_Code,
        ];
        $deposit = new Sys800;
        if ($deposit->create($data)){
            $ckfanli = SysConfig::select('ckfanli')->first()->ckfanli;
            error_log($ckfanli);
            if($ckfanli>0){
                $money= $request->money*$ckfanli/100;
                $Order_Code='CK'.date("YmdHis",time()+12*3600).mt_rand(1000,9999);
                $data = [
                    "Payway" => $payWay,
                    "Gold" => $money,
                    "AddDate" => $date,
                    "Type" => $type,
                    "Type2" => "2",
                    "UserName" => "test",//$user->UserName,
                    "Agents" => "test",//$user->Agents,
                    "World" => "test",//$user->World,
                    "Corprator" => "test",//$user->Corprator,
                    "Super" => "test",//$user->Super,
                    "Admin" => "test",//$user->Admin,
                    "CurType" => 'RMB',
                    "Name" => "test",//$user->Alias,
                    "Bank" => "彩金",//$user->bankName,
                    "Bank_Address" => "彩金",
                    "Bank_Account" => "彩金",
                    "Order_Code" => $Order_Code,
                ];
                if ($deposit->create($data)){
                    return response()->json(['success'=>true, 'order_code'=> $Order_Code, 'message'=>'deposit successfully.'], 200);
                }else {
                    return response()->json(['success'=>false, 'message'=>'rebate 提款成功!!!']);
                }
            }else{
                return response()->json(['success'=>true, 'order_code'=> $Order_Code, 'message'=>'deposit successfully.'], 200);
            }
        }
        else
            return response()->json(['success'=>false, 'message'=>'提款成功!!!']);
    }
}
