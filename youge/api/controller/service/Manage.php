<?php
namespace app\api\controller\service;
use app\api\controller\Common;
use app\common\model\service\EnrollModel;
use app\common\model\service\NannyModel;
use app\common\model\service\RepairModel;
use app\common\model\setting\SkinModel;
use app\common\model\shop\TypeModel;
use app\common\model\user\AddressModel;
use app\miniapp\controller\service\Repair;

class Manage extends Common{
    protected $checklogin = true;
    /*
     * 我的预约； 0 全部订单 1有效单 2代付款 3失效单
     */
    public function yuyve(){
        $type = (int) $this->request->param('type');
        switch ($type){
            case 1:
                $where['status'] = ["IN",[2,8]];
                break;
            case 2:
                $where['status'] = 0;
                break;
            case 3:
                $where['status'] = ["IN",[4,5,6]];
        }
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $EnrollModel = new EnrollModel();
        $list = $EnrollModel->where($where)->order("enroll_id desc")->limit($this->limit_bg,$this->limit_num)->select();
        $nannyIds = $repairIds = [];
        foreach ($list as $val){
           if($val->type == 1){
               $repairIds[$val->type_id] = $val->type_id;
           }elseif($val->type == 2){
               $nannyIds[$val->type_id] = $val->type_id;
           }
        }
        $NannyModel = new NannyModel();
        $RepairModel = new RepairModel();
        $nanny = $NannyModel->itemsByIds($nannyIds);
        $repair = $RepairModel->itemsByIds($repairIds);
        $data['list'] = [];
        foreach ($list as $val){
            $name = '';
            if($val->type == 1){
                $name  =  empty($repair[$val->type_id]) ? '' : $repair[$val->type_id]->title;
            }elseif($val->type == 2){
                $name = empty($nanny[$val->type_id]) ? '' : $nanny[$val->type_id]->name;
            }
            $data['list'][] = [
                'enroll_id' => $val->enroll_id,
                'name' => $name,
                'status' => $val->status,
                'add_time' => date("Y-m-d",$val->add_time),
                'price' => round($val->total_money/100,2),
                'date' => $val->date,
                'type' => $val->type == 1 ? '维修服务' : '阿姨服务',
                'status_mean' => empty(config('dataattr.jzorder')[$val->status]) ? '' : config('dataattr.jzorder')[$val->status],
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 预约详情
     *
     */
    public function yuyveDetail(){
        $enroll_id = (int) $this->request->param('enroll_id');
        $EnrollModel = new EnrollModel();
        if(!$enroll = $EnrollModel->find($enroll_id)){
            $this->result('',400,'参数错误','json');
        }
        if($enroll->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        $name = '';
        if($enroll->type == 1){
            $RepairModel = new RepairModel();
            $detail =  $RepairModel->find($enroll->type_id);
            $name = empty($detail->title) ? '' : $detail->title;
        }elseif($enroll->type == 2){
            $NannyModel = new NannyModel();
            $detail = $NannyModel->find($enroll->type_id);
            $name = empty($detail) ? '' : $detail->name;
        }
        $data = [
            'enroll_id' => $enroll_id,
            'name'  => $name,
        ];
        $this->result($data,200,'操作成功','json');

    }


    /*
     * 预约维修
     */
    public function yvyueWeixiu(){
        $id = (int) $this->request->param('id');
        $RepairModel = new RepairModel();
        if(!$weixiu = $RepairModel->find($id)){
            $this->result('',400,'参数错误','json');
        }
        if($weixiu->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        $address_id = (int) $this->request->param('address_id');
        $AddressModel = new AddressModel();
        if(!$addr =  $AddressModel->find($address_id)){
            $this->result('',400,'参数错误','json');
        }
        if($addr->user_id != $this->user->user_id){
            $this->result('',400,'参数错误','json');
        }
        $data = [
            'user_id' => $this->user->user_id,
            'name' => $addr->name,
            'mobile' => $addr->mobile,
            'address' => $addr->gps_addr . $addr->address,
            'type' => 1,
            'type_id' => $id,
            'total_money' => $weixiu->price,
            'member_miniapp_id' => $this->appid,
        ];
        $EnrollModel = new EnrollModel();
        $EnrollModel->save($data);
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($this->appid);
        define('WX_APPID',$setting['mini_appid']);
        define('WX_MCHID',$setting['mini_mid']);
        define('WX_KEY',$setting['mini_apicode'] );
        define('WX_APPSECRET',$setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH',ROOT_PATH.'/attachs/wxapiclient/'.$setting['apiclient_cert']);
        define('WX_SSLKEY_PATH',ROOT_PATH.'/attachs/wxapiclient/'.$setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST','0.0.0.0');
        define('WX_CURL_PROXY_PORT',0);
        define('WX_REPORT_LEVENL',0);
        require_once ROOT_PATH."/weixinpay/lib/WxPay.Api.php";
        require_once ROOT_PATH."/weixinpay/example/WxPay.JsApiPay.php";
        $tools = new \JsApiPay();
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("预约服务".$id);
        $input->SetAttach($id);
        $input->SetOut_trade_no(WX_MCHID.rand(1000,9999).$id);
        $input->SetTotal_fee($weixiu->price);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        // $input->SetGoods_tag();
        $input->SetNotify_url("https://".$_SERVER['HTTP_HOST']."/api/weixin/notifjz/appid/".$this->appid.'.html');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($this->request->param('openid'));
        $order = \WxPayApi::unifiedOrder($input);
        //var_dump($order);die;
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $this->result(['order'=>  json_decode($jsApiParameters,true)], '200', '创建支付成功！', 'json');
    }
    /*
     * 预约阿姨
     */
    public function yvyueAyi(){
        $id = (int) $this->request->param('id');
        $NannyModel = new NannyModel();
        if(!$weixiu = $NannyModel->find($id)){
            $this->result('',400,'参数错误','json');
        }
        if($weixiu->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        $NannyModel->where(['nanny_id'=>$id])->setInc('yvyue_num');
        $address_id = (int) $this->request->param('address_id');
        $AddressModel = new AddressModel();
        if(!$addr =  $AddressModel->find($address_id)){
            $this->result('',400,'参数错误','json');
        }
        if($addr->user_id != $this->user->user_id){
            $this->result('',400,'参数错误','json');
        }
        $date = $this->request->param('date');
        if(empty($date)){
            $this->result('',400,'请选择日期','json');
        }
        $data = [
            'user_id' => $this->user->user_id,
            'name' => $addr->name,
            'mobile' => $addr->mobile,
            'address' => $addr->gps_addr . $addr->address,
            'type' => 2,
            'type_id' => $id,
            'date' => $date,
            'total_money' => $weixiu->yv_price,
            'member_miniapp_id' => $this->appid,
        ];
        $EnrollModel = new EnrollModel();
        $EnrollModel->save($data);
        $order_id = $EnrollModel->enroll_id;
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($this->appid);
        define('WX_APPID',$setting['mini_appid']);
        define('WX_MCHID',$setting['mini_mid']);
        define('WX_KEY',$setting['mini_apicode'] );
        define('WX_APPSECRET',$setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH',ROOT_PATH.'/attachs/wxapiclient/'.$setting['apiclient_cert']);
        define('WX_SSLKEY_PATH',ROOT_PATH.'/attachs/wxapiclient/'.$setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST','0.0.0.0');
        define('WX_CURL_PROXY_PORT',0);
        define('WX_REPORT_LEVENL',0);
        require_once ROOT_PATH."/weixinpay/lib/WxPay.Api.php";
        require_once ROOT_PATH."/weixinpay/example/WxPay.JsApiPay.php";
        $tools = new \JsApiPay();
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("预约服务".$order_id);
        $input->SetAttach($order_id);
        $input->SetOut_trade_no(WX_MCHID.rand(1000,9999).$order_id);
        $input->SetTotal_fee($weixiu->yv_price);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        // $input->SetGoods_tag();
        $input->SetNotify_url("https://".$_SERVER['HTTP_HOST']."/api/weixin/notifjzay/appid/".$this->appid.'.html');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($this->request->param('openid'));
        $order = \WxPayApi::unifiedOrder($input);
        //var_dump($order);die;
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $this->result(['order'=>  json_decode($jsApiParameters,true)], '200', '创建支付成功！', 'json');
    }


    public function pay(){
        $enroll_id = (int) $this->request->param('enroll_id');
        $EnrollModel = new EnrollModel();
        if(!$detail = $EnrollModel->find($enroll_id)){
            $this->result('',400,'参数错误','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        if($detail->status != 0){
            $this->result('',400,'不可支付','json');
        }
        $order_id = $detail->enroll_id;
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($this->appid);
        define('WX_APPID',$setting['mini_appid']);
        define('WX_MCHID',$setting['mini_mid']);
        define('WX_KEY',$setting['mini_apicode'] );
        define('WX_APPSECRET',$setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH',ROOT_PATH.'/attachs/wxapiclient/'.$setting['apiclient_cert']);
        define('WX_SSLKEY_PATH',ROOT_PATH.'/attachs/wxapiclient/'.$setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST','0.0.0.0');
        define('WX_CURL_PROXY_PORT',0);
        define('WX_REPORT_LEVENL',0);
        require_once ROOT_PATH."/weixinpay/lib/WxPay.Api.php";
        require_once ROOT_PATH."/weixinpay/example/WxPay.JsApiPay.php";
        $tools = new \JsApiPay();
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("预约服务".$order_id);
        $input->SetAttach($order_id);
        $input->SetOut_trade_no(WX_MCHID.rand(1000,9999).$order_id);
        $input->SetTotal_fee($detail->total_money);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        // $input->SetGoods_tag();
        $input->SetNotify_url("https://".$_SERVER['HTTP_HOST']."/api/weixin/notifjzay/appid/".$this->appid.'.html');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($this->request->param('openid'));
        $order = \WxPayApi::unifiedOrder($input);
        //var_dump($order);die;
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $this->result(['order'=>  json_decode($jsApiParameters,true)], '200', '创建支付成功！', 'json');
    }


    public function weixiu(){
        $id = (int) $this->request->param('id');
        $RepairModel = new RepairModel();
        if(!$weixiu = $RepairModel->find($id)){
            $this->result('',400,'参数错误','json');
        }
        if($weixiu->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        $AddressModel = new AddressModel();
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $where['is_delete'] = 0;
        $detail = $AddressModel->where($where)->order("is_default desc,address_id desc")->find();
        if(empty($detail)){
            $this->result('',200,'数据初始化成功','json');
        }
        $data['address'] = [
            'address_id' => $detail->address_id,
            'name' => $detail->name,
            'mobile' => $detail->mobile,
            'address' => $detail->address,
            'gps_addr' => $detail->gps_addr,
            'idcard'    => $detail->idcard,
            'merge_addr' => $detail->gps_addr . $detail->address,
            'lng' => (float) $detail->lng,
            'lat' => (float) $detail->lat,
            'is_default' => $detail->is_default,
        ];
        $data['date'] = date("Y-m-d",time());
        $data['price'] = round($weixiu->price/100,2);
        $this->result($data,200,'操作成功','json');
    }

    public function ayi(){
        $id = (int) $this->request->param('id');
        $NannyModel = new NannyModel();
        if(!$weixiu = $NannyModel->find($id)){
            $this->result('',400,'参数错误','json');
        }
        if($weixiu->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }

        $AddressModel = new AddressModel();
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $where['is_delete'] = 0;
        $detail = $AddressModel->where($where)->order("is_default desc,address_id desc")->find();
        if(empty($detail)){
            $this->result('',200,'数据初始化成功','json');
        }
        $data['address'] = [
            'address_id' => $detail->address_id,
            'name' => $detail->name,
            'mobile' => $detail->mobile,
            'address' => $detail->address,
            'gps_addr' => $detail->gps_addr,
            'idcard'    => $detail->idcard,
            'merge_addr' => $detail->gps_addr . $detail->address,
            'lng' => (float) $detail->lng,
            'lat' => (float) $detail->lat,
            'is_default' => $detail->is_default,
        ];
        $data['date'] = date("Y-m-d",time());
        $data['price'] = round($weixiu->yv_price/100,2);
        $this->result($data,200,'操作成功','json');
    }
    /*
     *退款取消申请
     */
    public function cancel(){
        $enroll_id = (int) $this->request->param('enroll_id');
        $EnrollModel = new EnrollModel();
        if (!$enroll = $EnrollModel->find($enroll_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($enroll->user_id != $this->user->user_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if($enroll->status >= 3){
            $this->result([],400,'不可取消','json');
        }
        $data['status'] = $enroll->status == 0 ? 6 : 4;
        $EnrollModel->save($data,['enroll_id'=>$enroll_id]);
        $this->result('',200,'操作成功','json');
    }


}