<?php

namespace app\api\controller;

use app\common\model\taocan\OrderModel;
use think\Controller;
use app\common\model\miniapp\MiniappsettingModel;
use app\common\model\setting\SkinModel;
class Weixin extends Controller
{

    public function notifymini()
    {
        $appid = $this->request->param('appid');
        $SettingModel = new MiniappsettingModel();
        $setting = $SettingModel->get($appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxOrderNotifyCallback.php';
        $WxPay = new \WxOrderNotifyCallback();
        $WxPay->Handle(false);
    }
    
     public function mendiannotify()
    {
        $appid = $this->request->param('appid');
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxOrderNotifyMendian.php';
        $WxPay = new \WxOrderNotifyMendian();
        $WxPay->Handle(false);
    }
    public function waimainotify()
    {
        $appid = $this->request->param('appid');
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxOrderNotifyWaimai.php';
        $WxPay = new \WxOrderNotifyWaimai();
        $WxPay->Handle(false);
    }

    public function taocannotify()
    {
        $appid = $this->request->param('appid');
        $SettingModel = new MiniappsettingModel();
        $setting = $SettingModel->get($appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxOrderNotifyTaocan.php';
        $WxPay = new \WxOrderNotifyTaocan();
        $WxPay->Handle(false);
    }


    public function notifyminsu()
    {
        $appid = $this->request->param('appid');
        $SettingModel = new MiniappsettingModel();
        $setting = $SettingModel->get($appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxOrderNotifyMinsu.php';
        $WxPay = new \WxOrderNotifyMinsu();
        $WxPay->Handle(false);
    }

    public function nongjiale()
    {
        $appid = $this->request->param('appid');
        $SettingModel = new MiniappsettingModel();
        $setting = $SettingModel->get($appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxOrderNotifyNongjialegw.php';
        $WxPay = new \WxOrderNotifyNongjialegw();
        $WxPay->Handle(false);
    }

    public function hotelgw()
    {
        $appid = $this->request->param('appid');
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxOrderNotifyHotelgw.php';
        $WxPay = new \WxOrderNotifyHotelgw();
        $WxPay->Handle(false);

    }
    /*
     *  购买jiobVIp回调
     */
    public function notifjob()
    {
        $appid = $this->request->param('appid');
        $SettingModel = new MiniappsettingModel();
        $setting = $SettingModel->get($appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxOrderNotifyJob.php';
        $WxPay = new \WxOrderNotifyJob();
        $WxPay->Handle(false);
    }


    public function tgnotify()
    {
        $appid = $this->request->param('appid');
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxOrderNotifyTg.php';
        $WxPay = new \WxOrderNotifyTg();
        $WxPay->Handle(false);

    }

    public function shopnotify()
    {
        $appid = $this->request->param('appid');
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxOrderNotifyShop.php';
        $WxPay = new \WxOrderNotifyShop();
        $WxPay->Handle(false);

    }

    public function notifzhiding()
    {
        $appid = $this->request->param('appid');
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxOrderNotifyzhiding.php';
        $WxPay = new \WxOrderNotifyzhiding();
        $WxPay->Handle(false);

    }
    public function notifjzay()
    {
        $appid = $this->request->param('appid');
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($appid);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . '/weixinpay/lib/WxOrderNotifyjzay.php';
        $WxPay = new \WxOrderNotifyjzay();
        $WxPay->Handle(false);

    }



}
