<?php
ini_set('date.timezone', 'Asia/Shanghai');
error_reporting(E_ERROR);

require_once "WxPay.Api.php";
require_once 'WxPay.Notify.php';


class WxOrderNotifyJob extends WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        if (array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS") {
            $order_id = (int)$result['attach'];
            $OrderModel = new \app\common\model\job\OrderModel();
            if (!$order = $OrderModel->get($order_id)) {
                echo 'FAIL';
                die;
            }
            $status = 8;
            $pay_info = json_encode($result);
            $CompanyModel = new \app\common\model\job\CompanyModel();
            if (!$company = $CompanyModel->find($order->company_id)) {
                $status = 2;
                $pay_info = '商家已经支付成功但是未找到正确的公司信息';
            }
            //  如果已过期 则当前时间 + 购买时长  否则  过期时间 + 购买时长
            $vip_expire = $company->vip_expire < time() ? time() +  $order->by_time : $company->vip_expire + $order->by_time;
            //   赠送时长与yip；等级 高级覆盖低级
            $level = $company->vip < $order->vip_level ? $order->vip_level : $company->vip;
            $CompanyModel->save([
                'vip_expire' => $vip_expire,  //$company->vip_expire +
                'vip' => $level,
            ], ['company_id' => $order->company_id]);
            if ($order->status == 0) {
                $OrderModel->save([
                    'status' => $status,
                    'pay_money' => $result['cash_fee'],
                    'pay_info' => $pay_info,
                    'pay_time' => time(),
                ], ['order_id' => $order_id]);
            }
            return true;
        }
        return false;
    }


    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        $notfiyOutput = array();

        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            return false;
        }
        return true;
    }
}


