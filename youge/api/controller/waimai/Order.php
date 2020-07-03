<?php

namespace app\api\controller\waimai;

use app\api\controller\Common;
use app\common\model\user\CouponModel;
use app\common\model\user\AddressModel;
use app\common\model\waimai\WaimaisettingModel;
use app\common\model\waimai\ProductModel;
use app\common\model\waimai\OrderModel;
use app\common\model\waimai\OrderproductModel;
use app\common\model\setting\SkinModel;

class Order extends Common {

    protected $checklogin = true;
    protected $status = [
        0 => '等待支付',
        1 => '已经支付',
        2 => '商家已接单',
        4 => '订单已取消',
        8 => '订单已完成',
    ];

    //订单列表
    public function orderList() {
        $where = ['member_miniapp_id' => $this->appid, 'user_id' => $this->user->user_id];
        $type = $this->request->param('type');
        switch ($type) {
            case 1:
                $where['status'] = ['IN', [1, 2, 8]];
                break;
            case 2:
                $where['status'] = 0;
                break;
            case 3:
                $where['status'] = 4;
                break;
            default:
                break;
        }

        $datas = [];
        $list = OrderModel::where($where)->order("order_id desc")->limit($this->limit_bg, $this->limit_num)->select();
        if (!empty($list)) {
            $orderIds = [];
            foreach ($list as $k => $val) {
                $orderIds[$val->order_id] = $val->order_id;
            }
            $orderProduct = OrderproductModel::where(['order_id' => ['IN', $orderIds]])->select();
            $productIds = [];

            foreach ($orderProduct as $val) {
                $productIds[$val->product_id] = $val->product_id;
            }
            $ProductModel = new ProductModel();
            $products = $ProductModel->itemsByIds($productIds);

            $data = [];

            foreach ($orderProduct as $val) {
                $data[$val->order_id][] = [
                    'id' => $val->product_id,
                    'name' => isset($products[$val->product_id]['name']) ? $products[$val->product_id]['name'] : '',
                    'photo' => isset($products[$val->product_id]['photo']) ? IMG_URL . getImg($products[$val->product_id]['photo']) : '',
                    'num' => $val->num,
                    'price' => round($val->price / 100, 2)
                ];
            }

            foreach ($list as $val) {
                $datas[] = [
                    'id' => $val->order_id,
                    'total_price' => round($val->total_price / 100, 2),
                    'peisong' => round($val->peisong / 100, 2),
                    'dabao' => round($val->dabao / 100, 2),
                    'pay_hongbao' => round($val->pay_hongbao / 100, 2),
                    'pay_money' => round($val->pay_money / 100, 2),
                    'statusmeans' => $this->status[$val->status],
                    'status' => $val->status,
                    'name' => $val->name,
                    'mobile' => $val->mobile,
                    'address' => $val->address,
                    'gps_addr' => $val->gps_addr,
                    'lng' => (float) $val->lng,
                    'lat' => (float) $val->lat,
                    'products' => empty($data[$val->order_id]) ? [] : $data[$val->order_id]
                ];
            }
        }

        $return = ['list' => $datas];
        $return['more'] = count($datas) > $this->limit_num ? 1 : 0;
        $this->result($return, 200, '数据初始化成功', 'json');
    }

    public function getOrderDetail() {
        $order_id = (int) $this->request->param('id');
        if (empty($order_id)) {
            $this->result([], '400', '没有该订单', 'json');
        }
        $order = OrderModel::get($order_id);
        if (empty($order)) {
            $this->result([], '400', '没有该订单', 'json');
        }
        if ($order['member_miniapp_id'] != $this->appid) {
            $this->result([], '400', '没有该订单', 'json');
        }
        if ($order->user_id != $this->user->user_id) {
            $this->result([], '400', '没有该订单', 'json');
        }
        
        if($order->last_time < time() && $order->status==0){
            $OrderModel = new OrderModel();
            $OrderModel->save(['status'=>4],['order_id'=>$order_id]);
            $order->status = 4; //订单已取消
        }
        
        $orderProduct = OrderproductModel::where(['order_id' => $order_id])->select();

        $productIds = [];

        foreach ($orderProduct as $val) {
            $productIds[$val->product_id] = $val->product_id;
        }
        $ProductModel = new ProductModel();
        $products = $ProductModel->itemsByIds($productIds);

        $data = [];

        foreach ($orderProduct as $val) {
            $data[] = [
                'id' => $val->product_id,
                'name' => isset($products[$val->product_id]['name']) ? $products[$val->product_id]['name'] : '',
                'photo' => isset($products[$val->product_id]['photo']) ? IMG_URL . getImg($products[$val->product_id]['photo']) : '',
                'num' => $val->num,
                'price' => round($val->price / 100, 2)
            ];
        }
        $datas= [
            'id' => $order->order_id,
            'total_price' => round($order->total_price / 100, 2),
            'peisong' => round($order->peisong / 100, 2),
            'dabao' => round($order->dabao / 100, 2),
            'pay_hongbao' => round($order->pay_hongbao / 100, 2),
            'pay_money' => round($order->pay_money / 100, 2),
            'statusmeans' => $this->status[$order->status],
            'status' => $order->status,
            'name' => $order->name,
            'mobile' => $order->mobile,
            'address' => $order->address,
            'gps_addr' => $order->gps_addr,
            'lng' => (float) $order->lng,
            'lat' => (float) $order->lat,
            'last_time' => date('Y-m-d H:i:s',$order->last_time),
            'add_time' => date('Y-m-d H:i:s',$order->add_time),
            'products' => empty($data) ? [] : $data
        ];
        $this->result($datas, 200, '数据初始化成功', 'json');
    }

    public function getPayment() {
        $order_id = (int) $this->request->param('id');
        if (empty($order_id)) {
            $this->result([], '400', '没有该订单', 'json');
        }
        $order = OrderModel::get($order_id);
        if (empty($order)) {
            $this->result([], '400', '没有该订单', 'json');
        }
        if ($order['member_miniapp_id'] != $this->appid) {
            $this->result([], '400', '没有该订单', 'json');
        }
        if ($order->user_id != $this->user->user_id) {
            $this->result([], '400', '没有该订单', 'json');
        }
        if ($order->status != 0) {
            $this->result([], '400', '没有该订单', 'json');
        }

        $needpay = $order->total_price - $order->pay_hongbao;

        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($this->appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . "/weixinpay/lib/WxPay.Api.php";
        require_once ROOT_PATH . "/weixinpay/example/WxPay.JsApiPay.php";
        $tools = new \JsApiPay();
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("外卖订单" . $order_id);
        $input->SetAttach($order_id);
        $input->SetOut_trade_no(WX_MCHID . rand(1000, 9999) . $order_id);
        $input->SetTotal_fee($needpay);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        // $input->SetGoods_tag();
        $input->SetNotify_url("https://" . $_SERVER['HTTP_HOST'] . "/api/weixin/waimainotify/appid/" . $this->appid . '.html');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($this->request->param('openid'));
        $order = \WxPayApi::unifiedOrder($input);
        //var_dump($order);die;
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $this->result(['order' => json_decode($jsApiParameters, true), 'order_id' => $order_id], '200', '创建支付成功！', 'json');
    }

    //创建订单
    public function create() {
        $setting = WaimaisettingModel::get($this->appid);
        if (empty($setting)) {
            $this->result([], '400', '商家商店配置错误', 'json');
        }

        $address_id = (int) $this->request->param('address_id');
        if (empty($address_id)) {
            $this->result([], '400', '请选择收货地址', 'json');
        }
        $address = AddressModel::get($address_id);
        if (empty($address)) {
            $this->result([], '400', '请选择收货地址', 'json');
        }
        if ($address['member_miniapp_id'] != $this->appid) {
            $this->result([], '400', '请选择收货地址', 'json');
        }
        if ($address['user_id'] != $this->user->user_id) {
            $this->result([], '400', '请选择收货地址', 'json');
        }

        $product = htmlspecialchars_decode($this->request->param('product'));
        //echo $product;die;
        if (empty($product)) {
            $this->result([], '400', '请选择要购买的产品', 'json');
        }
        $product = json_decode($product, true);
        if (empty($product)) {
            $this->result([], '400', '请选择要购买的产品', 'json');
        }
        $productIds = $nums = [];
        // print_r($product);die;
        foreach ($product as $val) {
            if ($val['buynum'] <= 0) {
                $this->result([], '400', '请选择要购买的产品', 'json');
            }
            $nums[$val['id']] = $val['buynum'];
            $productIds[$val['id']] = $val['id'];
        }
        if (empty($productIds)) {
            $this->result([], '400', '请选择要购买的产品', 'json');
        }

        $ProductModel = new ProductModel();
        // print_r($productIds);
        $products = $ProductModel->itemsByIds($productIds);
        // print_r($products);
        //die;
        $productArr = [];
        $totalprice = $dabao = 0;
        foreach ($products as $val) {
            if ($val->member_miniapp_id != $this->appid) {
                $this->result([], '400', '产品信息有误', 'json');
            }
            if ($val->is_online != 1) {
                $this->result([], '400', '产品已经下架请重新选择下单', 'json');
            }
            $productArr[] = [
                'product_id' => $val->product_id,
                'price' => $val->price,
                'num' => $nums[$val->product_id]
            ];
            $totalprice += ($val->price * $nums[$val->product_id]);
            $dabao += ($val->dabao * $nums[$val->product_id]);
        }
        if ($totalprice < $setting['qijia']) {
            $this->result([], '400', '订单商品金额小于起送价格', 'json');
        }
        $totalprice = $totalprice + $dabao + $setting['peisong']; //这个才是总价
        if (empty($productArr)) {
            $this->result([], '400', '请选择要购买的产品', 'json');
        }
        $hongbao = 0;
        $coupon_id = (int) $this->request->param('coupon_id');
        if (!empty($coupon_id)) {
            $coupon = CouponModel::get($coupon_id);
            if ($coupon->user_id != $this->user->user_id) {
                $this->result([], '400', '红包不可用', 'json');
            }
            if ($coupon->type != 1) {
                $this->result([], '400', '红包不可用', 'json');
            }
            if (($coupon->expir_time < $this->request->time()) || ($coupon->can_use_time > $this->request->time() ) || ($coupon->is_can != 0) || ($coupon->need_money > $totalprice)) {
                $this->result([], '400', '红包不可用', 'json');
            }
            $hongbao = $coupon->money;
        }

        $order = [
            'member_miniapp_id' => $this->appid,
            'user_id' => $this->user->user_id,
            'total_price' => $totalprice,
            'peisong' => $setting->peisong,
            'dabao' => $dabao,
            'pay_hongbao' => $hongbao,
            'pay_money' => 0,
            'status' => 0,
            'last_time' => time() + 900, //15分钟必须支付，否者订单不可以支付
            'name' => $address->name,
            'mobile' => $address->mobile,
            'address' => $address->address,
            'gps_addr' => $address->gps_addr,
            'lng' => $address->lng,
            'lat' => $address->lat,
        ];
        $needpay = $totalprice - $hongbao;
        //print_r($productArr);die;
        $OrderModel = new OrderModel();
        if ($OrderModel->save($order)) {
            $order_id = $OrderModel->order_id;
            foreach ($productArr as $k => $val) {
                $productArr[$k]['order_id'] = $order_id;
            }
            $OrderproductModel = new OrderproductModel();
            $OrderproductModel->saveAll($productArr);
            if($hongbao > 0 ){
                $CouponModel = new CouponModel();
                $CouponModel->save(['is_can' => 1], ['coupon_id' => $coupon_id]);
            }
            //支付操作

            $SettingModel = new SkinModel();
            $setting = $SettingModel->get($this->appid);

            define('WX_APPID', $setting['mini_appid']);
            define('WX_MCHID', $setting['mini_mid']);
            define('WX_KEY', $setting['mini_apicode']);
            define('WX_APPSECRET', $setting['mini_appsecrept']);
            define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
            define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
            define('WX_CURL_PROXY_HOST', '0.0.0.0');
            define('WX_CURL_PROXY_PORT', 0);
            define('WX_REPORT_LEVENL', 0);
            require_once ROOT_PATH . "/weixinpay/lib/WxPay.Api.php";
            require_once ROOT_PATH . "/weixinpay/example/WxPay.JsApiPay.php";
            $tools = new \JsApiPay();
            $input = new \WxPayUnifiedOrder();
            $input->SetBody("外卖订单" . $order_id);
            $input->SetAttach($order_id);
            $input->SetOut_trade_no(WX_MCHID . rand(1000, 9999) . $order_id);
            $input->SetTotal_fee($needpay);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            // $input->SetGoods_tag();
            $input->SetNotify_url("https://" . $_SERVER['HTTP_HOST'] . "/api/weixin/waimainotify/appid/" . $this->appid . '.html');
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($this->request->param('openid'));
            $order = \WxPayApi::unifiedOrder($input);
            //var_dump($order);die;
            $jsApiParameters = $tools->GetJsApiParameters($order);
            $this->result(['order' => json_decode($jsApiParameters, true), 'order_id' => $order_id], '200', '创建支付成功！', 'json');
        }
        $this->result([], '400', '创建订单失败了', 'json');
    }

}
