<?php
namespace app\common\model\hotelgw;
use app\common\model\CommonModel;
use app\common\model\setting\SkinModel;
class  OrderModel extends CommonModel{
    protected $pk       = 'order_id';
    protected $table    = 'hotelgw_order';
    //订单原路退回  MONEY 代表退回的金额，如果金额大于订单本身的支付金额是不允许的 金额是分
    public function refund($orderid,$money){
        $orderid = (int)$orderid;
        $order = $this->get($orderid);
        if(empty($order)) return false;
        if($money > $order->pay_money) return false;
        if(empty($order->pay_info)) return false;
        $payinfo = json_decode($order->pay_info,true);
        if(empty($payinfo)) return false;
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($order->member_miniapp_id);
        if(empty($setting)) return false;
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxPay.Api.php';
        $input = new \WxPayRefund();
        $input->SetTransaction_id($payinfo['transaction_id']);
        $input->SetTotal_fee($payinfo['total_fee']);
        $input->SetRefund_fee($money);
        $input->SetOut_refund_no(WX_MCHID.date("YmdHis"));
        $input->SetOp_user_id(WX_MCHID);
        \WxPayApi::refund($input);
        return true;
    }
}