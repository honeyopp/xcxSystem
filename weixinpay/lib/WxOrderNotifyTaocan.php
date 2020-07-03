<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "WxPay.Api.php";
require_once 'WxPay.Notify.php';


class WxOrderNotifyTaocan extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
                        $order_id = (int)$result['attach'];
                        $OrderModel = new \app\common\model\taocan\OrderModel();
                        if(!$order = $OrderModel->get($order_id)){
                           echo 'FAIL';die;
                        }
                        if($order->status==0){
                            $OrderModel->save([
                                'status' =>1,
                                'pay_money' => $result['cash_fee'],
                                'pay_info' => json_encode($result),
                                'pay_time' => time(),
                            ],['order_id'=>$order_id]);
                        }
			return true;
		}
		return false;
	}


    //重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}


