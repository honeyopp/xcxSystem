<?php

namespace app\api\controller\mendian;

use app\api\controller\Common;
use app\common\model\user\CouponModel;
use app\common\model\setting\ActivityModel;
use app\common\model\mendian\SettingModel;
use app\common\model\mendian\PhotoModel;
use app\common\model\mendian\OrderModel;
use app\common\model\setting\SkinModel;
class Index extends Common {

    protected $checklogin = true;

    //创建支付订单并支付
    public function order() {
        $totalprice = (int) ($this->request->param('money') * 100);
        if ($totalprice <= 0) {
            $this->result([], '400', '需要支付金额不正确', 'json');
        }
        $hongbao = 0;
        $coupon_id = (int) $this->request->param('coupon_id');
        if (!empty($coupon_id)) {
            $coupon = CouponModel::get($coupon_id);
            if(empty($coupon)){
                $this->result([], '400', '红包不可用', 'json');
            }
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

        $need_pay = $totalprice - $hongbao;

        $order = [
            'user_id' => $this->user->user_id,
            'member_miniapp_id' => $this->appid,
            'total_money' => $totalprice,
            'pay_coupon' => $hongbao,
            'is_paid' => $need_pay > 0 ? 0 : 1,
        ];

        $OrderModel = new OrderModel();
        if ($OrderModel->save($order)) {
            if($hongbao > 0 ){
                $CouponModel = new CouponModel();
                $CouponModel->save(['is_can' => 1], ['coupon_id' => $coupon_id]);
            }
            
            $order_id = $OrderModel->order_id;
            if ($need_pay > 0) {
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
                $input->SetBody("门店买单订单" . $order_id);
                $input->SetAttach($order_id);
                $input->SetOut_trade_no(WX_MCHID . rand(1000, 9999) . $order_id);
                $input->SetTotal_fee($need_pay);
                $input->SetTime_start(date("YmdHis"));
                $input->SetTime_expire(date("YmdHis", time() + 600));
                // $input->SetGoods_tag();
                $input->SetNotify_url("https://" . $_SERVER['HTTP_HOST'] . "/api/weixin/mendiannotify/appid/" . $this->appid . '.html');
                $input->SetTrade_type("JSAPI");
                $input->SetOpenid($this->request->param('openid'));
                $order = \WxPayApi::unifiedOrder($input);
                //var_dump($order);die;
                $jsApiParameters = $tools->GetJsApiParameters($order);
                $this->result(['status'=>0, 'money'=>round($totalprice / 100, 2), 'order' => json_decode($jsApiParameters, true)], '200', '创建支付成功！', 'json');
            } else {
                $this->result(['status' => 1, 'money' => round($totalprice / 100, 2)], '200', '支付成功', 'json');
            }
        } else {
            $this->result([], '400', '创建订单失败', 'json');
        }
    }

    public function setting() {
        $setting = SettingModel::get($this->appid);
        $set = [
            'name' => empty($setting['name']) ? '' : $setting['name'],
            'banner' => empty($setting['banner']) ? '' : IMG_URL . getImg($setting['banner']),
            'logo' => empty($setting['logo']) ? '' : IMG_URL . getImg($setting['logo']),
            'addr' => empty($setting['addr']) ? '' : $setting['addr'],
            'gps_addr' => empty($setting['gps_addr']) ? '' : $setting['gps_addr'],
            'lng' => empty($setting['lng']) ? 0 : (float) $setting['lng'],
            'lat' => empty($setting['lat']) ? 0 : (float) $setting['lat'],
            'tel' => empty($setting['tel']) ? '' : $setting['tel'],
            'is_wifi' => empty($setting['is_wifi']) ? 0 : $setting['is_wifi'],
            'is_p' => empty($setting['is_p']) ? 0 : $setting['is_p'],
            'is_weixin' => empty($setting['is_weixin']) ? 0 : $setting['is_weixin'],
            'is_alipay' => empty($setting['is_alipay']) ? 0 : $setting['is_alipay'],
            'biz_t' => empty($setting['biz_t']) ? '' : $setting['biz_t'],
            'info' => empty($setting['info']) ? '' : $setting['info'],
        ];
        $this->result($set, 200, '获取数据成功', 'json');
    }

    public function index() {
        $set = [];
        $setting = SettingModel::get($this->appid);
        $set = [
            'name' => empty($setting['name']) ? '' : $setting['name'],
            'banner' => empty($setting['banner']) ? '' : IMG_URL . getImg($setting['banner']),
            'logo' => empty($setting['logo']) ? '' : IMG_URL . getImg($setting['logo']),
            'addr' => empty($setting['addr']) ? '' : $setting['addr'],
            'gps_addr' => empty($setting['gps_addr']) ? '' : $setting['gps_addr'],
            'lng' => empty($setting['lng']) ? 0 : (float) $setting['lng'],
            'lat' => empty($setting['lat']) ? 0 : (float) $setting['lat'],
            'tel' => empty($setting['tel']) ? '' : $setting['tel'],
            'is_wifi' => empty($setting['is_wifi']) ? 0 : $setting['is_wifi'],
            'is_p' => empty($setting['is_p']) ? 0 : $setting['is_p'],
            'is_weixin' => empty($setting['is_weixin']) ? 0 : $setting['is_weixin'],
            'is_alipay' => empty($setting['is_alipay']) ? 0 : $setting['is_alipay'],
            'biz_t' => empty($setting['biz_t']) ? '' : $setting['biz_t'],
            'info' => empty($setting['info']) ? '' : $setting['info'],
        ];

        $photos = PhotoModel::where(['member_miniapp_id' => $this->appid])->order(['orderby' => 'desc'])->select();
        $photoItems = [];
        foreach ($photos as $val) {
            $photoItems[] = IMG_URL . getImg($val->photo);
        }


        $aWhere = [];
        $aWhere['is_online'] = 1;
        $aWhere['member_miniapp_id'] = $this->appid;
        $date = date("Y-m-d");
        $aWhere['bg_date'] = ['<=', $date];
        $aWhere['end_date'] = ['>=', $date];
        $ActivityModel = new ActivityModel();
        $list = $ActivityModel->where($aWhere)->order("orderby desc")->limit(0, 5)->select();
        $activity = [];
        foreach ($list as $val) {
            $activity[] = [
                'activity_id' => $val->activity_id,
                'title' => $val->title,
                'money' => sprintf("%.2f", $val->money / 100),
                'need_money' => sprintf("%.2f", $val->need_money / 100),
                'expire_day' => $val->expire_day,
                'use_day' => $val->use_day,
                'is_newuser' => $val->is_newuser,
                'num' => $val->num,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
            ];
        }

        $return = [
            'photos' => $photoItems,
            'setting' => $set,
            'coupon' => $activity,
        ];

        $this->result($return, 200, '获取数据成功', 'json');
    }

}
