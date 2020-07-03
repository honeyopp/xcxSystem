<?php

namespace app\api\controller\hotel;

use app\api\controller\Common;
use app\common\model\hotel\CommentModel;
use app\common\model\hotel\CommentphotoModel;
use app\common\model\hotel\HotelModel;
use app\common\model\hotel\HotelorderModel;
use app\common\model\hotel\RoomModel;
use app\common\model\setting\SettingcouponModel;
use app\common\model\user\CouponModel;
use app\common\model\hotel\HotelpriceModel;
use app\common\model\miniapp\MiniappsettingModel;
use app\common\library\MiniApp;
class Order extends Common {

    protected $checklogin = true;
    
    
    public function cancel(){
        $id = (int) $this->request->param('id');
        if(empty($id)){
            $this->result([], '400', '不存在该订单', 'json');
        }
        $HotelorderModel = new HotelorderModel();
        if (!$order = $HotelorderModel->find($id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->user_id != $this->user->user_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->status != 0) {
            $this->result([], '400', '订单状态不可取消', 'json');
        }
        $cancel_info = $this->request->param('cancel_info');
        if(empty($cancel_info)){
             $this->result([], '400', '取消理由不能为空', 'json');
        }
       if ($HotelorderModel->save(['status'=>3,'cancel_type'=>1,'cancel_time'=>  $this->request->time(),'cancel_info'=>$cancel_info],['order_id'=>$id])){
           if(!empty($order->pay_coupon_id)){
               $CouponModel = new CouponModel();
               $CouponModel->save(['is_can'=>0],['coupon_id'=>$order->pay_coupon_id]);
           } 
           
           $hotel = HotelModel::get($order->hotel_id);
             //发送模版消息
           $formId = $this->request->param('formId');
           $Miniapp = new MiniApp($this->appid);
           $Miniapp->sendTemplateMessage('AT0012', $this->user->open_id, $formId,'/pages/hotel/order/detail?id='.$id,[
               $hotel->hotel_name,
               $id,
               date('Y-m-d H:i:s',$order->add_time),
               $cancel_info,
               '未支付订单，无需退款',
           ]);
           
           $HotelpriceModel = new HotelpriceModel();
           $HotelpriceModel->removeBooked($order->room_id,$order->check_in_time,$order->leave_time,$order->room_num);
           $this->result([], '200', '取消成功', 'json');
       }
       $this->result([], '400', '订单状态不可取消', 'json');
    }
    
    public function getPayment(){
        $id = (int) $this->request->param('order_id');
        if(empty($id)){
            $this->result([], '400', '不存在该订单', 'json');
        }
        $HotelorderModel = new HotelorderModel();
        if (!$order = $HotelorderModel->find($id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->user_id != $this->user->user_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->status != 0) {
            $this->result([], '400', '订单状态不可支付', 'json');
        }
        $SettingModel = new MiniappsettingModel();
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
        $input->SetBody("酒店预订".$id);
        $input->SetAttach($id);
        $input->SetOut_trade_no(WX_MCHID.rand(1000,9999).$id);
        $input->SetTotal_fee($order->need_pay);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
       // $input->SetGoods_tag();
        $input->SetNotify_url("https://".$_SERVER['HTTP_HOST']."/api/weixin/notifymini/appid/".$this->appid.'.html');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($this->request->param('openid'));
        $order = \WxPayApi::unifiedOrder($input);
        //var_dump($order);die;
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $this->result(['order'=>  json_decode($jsApiParameters,true)], '200', '创建支付成功！', 'json');
    }
    public function create() {
        $bg_date = date('Y-m-d', strtotime($this->request->param('bg_date')));
        $end_date = date('Y-m-d', strtotime($this->request->param('end_date')));
        $today = date('Y-m-d', $this->request->time());
        if ($bg_date < $today || $end_date < $bg_date) {
            $this->result([], '400', '日期不正确', 'json');
        }
        $num = (int) $this->request->param('num');
        $name = $this->request->param('name');
        $mobile = $this->request->param('mobile');
        $idcard = $this->request->param('idcard');
        $person_num = (int)  $this->request->param('person_num');
        if (empty($name) || empty($mobile) || empty($idcard)) {
            $this->result([], '400', '联系人、手机号码、身份证不能为空', 'json');
        }


        $id = (int) $this->request->param('id');
        if (empty($id)) {
            $this->result([], '400', '没有该房型', 'json');
        }
        $room = RoomModel::get($id);
        if (empty($room)) {
            $this->result([], '400', '没有该房型', 'json');
        }
        if($room->member_miniapp_id != $this->appid){
            $this->result([], '400', '没有该房型', 'json');
        }
        //增加了酒店的查询
        $HotelModel = new HotelModel();
        if(!$hotel = $HotelModel->get($room->hotel_id)){
            $this->result([], '400', '该酒店不存在了', 'json');
        }
       


        $prices = HotelpriceModel::where(['room_id' => $id, 'day' => ['BETWEEN', [$bg_date, $end_date]]])->select();

        $pricesData = [];
        $yudingnum = $maxnum = 0; //已经被预定的最大数量
        $pricesIds = $pricesNum = [];
        foreach ($prices as $val) {
            $pricesData[$val->day] = $val->price;
            $pricesIds[$val->day] = $val->price_id;
            $pricesNum[$val->day] = $val->room_num;
            $yudingnum = $yudingnum > $val->room_num ? $yudingnum : $val->room_num;
        }
        $maxnum = $room->day_num - $yudingnum; //最大可以下单的间数
        if ($num <= 0 || $num > $maxnum) {
            $this->result([], '400', '该日期内房型比较紧张', 'json');
        }

        $bg_time = strtotime($bg_date);
        $end_time = strtotime($end_date);
        $priceList = [];
        $totalprice = 0;
        for ($i = $bg_time; $i < $end_time; $i = $i + 86400) {
            $day = date('Y-m-d', $i);
            $price = isset($pricesData[$day]) ? $pricesData[$day] : $room->price;
            $totalprice+= $price;
        }
        $lijian = 0;
        $setting = MiniappsettingModel::get($this->appid);
        if (!empty($setting->is_pay)) { //开启了支付立减的
            $lijian =$setting->pay_money;
        }
        $lijiannum = (int) ($totalprice / 10000);//立减只支持预定一间房子
        $lijian = $lijian * $lijiannum;
        
        
        //最终的总价格
        $totalprice = $totalprice*$num;    
        
        
        $hongbao = 0;
        $hongbaoId = (int) $this->request->param('hongbaoId');
        if (!empty($hongbaoId)) {
            $coupon = CouponModel::get($hongbaoId);
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
        
        $needpay = $totalprice - $lijian - $hongbao;
        $needpay = $needpay <=0 ? 0 : $needpay;
        
        
        
        $order = [
            'member_miniapp_id' => $this->appid,
            'user_id'           => $this->user->user_id,
            'hotel_id'          => $room->hotel_id,
            'room_id'           => $id,
            'room_num'          => $num,
            'person_num'        =>$person_num,
            'total_price'       => $totalprice,
            'need_pay'          => $needpay,
            'pay_coupon'        => $hongbao,
            'lijian'            => $lijian,
            'pay_coupon_id'     => $hongbaoId,
            'name'  => $name,
            'mobile'    => $mobile,
            'idcard'    => $idcard,
            'check_in_time' => $bg_date,
            'leave_time'  => $end_date,
            'status' => $needpay==0 ? 1: 0, //如果是0元了代表优惠券已经抵扣了！
        ];
       // die;
       $HotelOrderModel = new HotelorderModel();
       if($HotelOrderModel->save($order)){
           if(!empty($coupon)){
              $CouponModel = new CouponModel();
              $CouponModel->save(['is_can'=>1],['coupon_id'=>$hongbaoId]);
           }
           
           //发送模版消息
           $formId = $this->request->param('formId');
           $Miniapp = new MiniApp($this->appid);
           $Miniapp->sendTemplateMessage('AT0008', $this->user->open_id, $formId,'/pages/hotel/order/detail?id='.$HotelOrderModel->order_id,[
               $HotelOrderModel->order_id,
               round($needpay/100,2),
               '等待支付',
               date('Y-m-d H:i:s'),
               $hotel->hotel_name,
               '请在15分中内完成支付，否则订单将会取消'
           ]);
           
           //冻结库存
           $saveAll = [];
            for ($i = $bg_time; $i < $end_time; $i = $i + 86400) {
                $day = date('Y-m-d', $i);
                if(isset($pricesIds[$day])){
                    $data = [
                        'price_id' => $pricesIds[$day],
                        'room_id' => $room->room_id,
                        'hotel_id' => $room->hotel_id,
                        'member_miniapp_id' => $room->member_miniapp_id,
                        'day' => $day,
                        'price' => isset($pricesData[$day]) ? $pricesData[$day]:$room->price,
                        'room_num' => $pricesNum[$day] + $num,
                    ];
                }else{
                    $data = [
                        'room_id' => $room->room_id,
                        'hotel_id' => $room->hotel_id,
                        'member_miniapp_id' => $room->member_miniapp_id,
                        'day' => $day,
                        'price' => $room->price,
                        'room_num' =>  $num,
                    ];
                }
                $saveAll[] = $data;
            }
            $priceModel = new HotelpriceModel();
            $priceModel->saveAll($saveAll);
            
            $HotelModel = new HotelModel();
            $HotelModel->IncDecCol($room->hotel_id,'order_num',1);
            $this->result(['id'=>$HotelOrderModel->order_id], '200', '创建订单成功', 'json');
       }
       $this->result([], '400', '创建订单失败', 'json');
    }

    //进入购买页面先从服务器拉取一下数据
    public function checkOrder() {
        //这个是房屋的ID

        $bg_date = date('Y-m-d', strtotime($this->request->param('bg_date')));
        $end_date = date('Y-m-d', strtotime($this->request->param('end_date')));
        $today = date('Y-m-d', $this->request->time());
        if ($bg_date < $today || $end_date < $bg_date) {
            $this->result([], '400', '日期不正确', 'json');
        }
        $id = (int) $this->request->param('id');
        if (empty($id)) {
            $this->result([], '400', '没有该房型1', 'json');
        }
        $room = RoomModel::get($id);
        if (empty($room)) {
            $this->result([], '400', '没有该房型2', 'json');
        }
        if($room->member_miniapp_id != $this->appid){
            $this->result([], '400', '没有该房型3', 'json');
        }

        $prices = HotelpriceModel::where(['room_id' => $id, 'day' => ['BETWEEN', [$bg_date, $end_date]]])->select();
        ;
        $pricesData = [];
        $yudingnum = $maxnum = 0; //已经被预定的最大数量
        foreach ($prices as $val) {
            $pricesData[$val->day] = $val->price;
            $yudingnum = $yudingnum > $val->room_num ? $yudingnum : $val->room_num;
        }
        $maxnum = $room->day_num - $yudingnum; //最大可以下单的间数
        $bg_time = strtotime($bg_date);
        $end_time = strtotime($end_date);
        $priceList = [];
        $totalprice = 0;
        for ($i = $bg_time; $i < $end_time; $i = $i + 86400) {
            $day = date('Y-m-d', $i);
            $price = isset($pricesData[$day]) ? $pricesData[$day] : $room->price;
            $totalprice+= $price;
            $priceList[] = [
                'day' => $day,
                'price' => round($price / 100, 2),
            ];
        }
        $lijian = 0;
        $setting = MiniappsettingModel::get($this->appid);
        if (!empty($setting->is_pay)) { //开启了支付立减的
            $lijian = round($setting->pay_money / 100, 2);
        }
        $config = config('dataattr.hotelbedtype');
        $roomtype = config('dataattr.hotelroomtypenames');
        $num = (int) ($totalprice / 10000);
        $return = [
            'lijian' => (int) ($lijian * $num), //立减只减1间的钱，不减第二间的
            'priceList' => $priceList,
            'totalprice' => round($totalprice / 100, 2),
            'room' => [
                'id' => $room->room_id,
                'title' => $room->title,
                'area' => $room->area,
                'photo' => IMG_URL . getImg($room->photo),
                'bed_type' => isset($config[$room->bed_type]) ? $config[$room->bed_type] : '',
                'bed_width' => $room->bed_width,
                'bed_logn' => $room->bed_logn,
                'bed_num' => $room->bed_num,
                'room_type' => isset($config[$room->room_type]) ? $config[$room->room_type] : '',
                'appropriate_num' => $room->appropriate_num,
                'is_wifi' => $room->is_wifi,
                'price' => round($room->price / 100, 2),
            ],
            'maxnum' => $maxnum,
        ];
        $this->result($return, '200', '加载成功', 'json');
    }

    /*
     * 获取用户订单列表
     * type 0 全部 1 有效单 2待支付 3退款单 4 带评论；
     */

    public function geTorder() {
        $type = (int) $this->request->param('type');
        $where['is_delete'] = 0;
        $where['user_id'] = $this->user->user_id;
        switch ($type) {
            case 1:
                $where['status'] = [['=', 2], ['=', 8], 'or'];
                break;
            case 2:
                $where['status'] = 0;
                break;
            case 3:
                $where['status'] = 3;
                break;
            case 4:
                $where['status'] = 8;
                $where['is_comment'] = 0;
                break;
        }

        $HotelorderModel = new HotelorderModel();
        $totalNum = $HotelorderModel->where($where)->count();
        $list = $HotelorderModel->where($where)->order("order_id desc")->limit($this->limit_bg, $this->limit_num)->select();
        if (empty($list)) {
            $data['totalNum'] = $totalNum;
            $data['list'] = [];
            $this->result($data, '200', '没有数据了', 'json');
        }
        $hotelIds = $roomIds = [];
        foreach ($list as $val) {
            $hotelIds[$val->hotel_id] = $val->hotel_id;
            $roomIds[$val->room_id] = $val->room_id;
        }
        $RoomModel = new RoomModel();
        $rooms = $RoomModel->itemsByIds($roomIds);
        $HotelModel = new HotelModel();
        $hotels = $HotelModel->itemsByIds($hotelIds);
        $data['totalNum'] = $totalNum;
        $data['list'] = [];
        foreach ($list as $val) {
            $data['list'][] = [
                'order_id' => $val->order_id,
                'hotel_id' => $val->hotel_id,
                'room_id' => $val->room_id,
                'user_id' => $val->user_id,
                'hotel_name' => empty($hotels[$val->hotel_id]) ? '' : $hotels[$val->hotel_id]->hotel_name,
                'address' => empty($hotels[$val->hotel_id]) ? '' : $hotels[$val->hotel_id]->address,
                'total_price' => sprintf("%.2f", $val->total_price / 100),
                'check_in_time' =>  $val->check_in_time,
                'leave_time' =>  $val->leave_time,
                'room_num' => $val->room_num,
                'person_num' => $val->person_num,
                'room_title' => empty($rooms[$val->room_id]) ? '' : $rooms[$val->room_id]->title,
                'name' => $val->name,
                'mobile' => $val->mobile,
                'status' => $val->status,
                'status_mean' => empty(config('dataattr.orderstatus')[$val->status]) ? '' : config('dataattr.orderstatus')[$val->status],
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data, '200', '数据初始化成功', 'json');
    }

    /**
     * 订单详情；
     * */
    public function orderDetail() {
        $order_id = (int) $this->request->param('order_id');
        if(empty($order_id)){
            $this->result([], '400', '不存在该订单', 'json');
        }
        $HotelorderModel = new HotelorderModel();
        if (!$order = $HotelorderModel->find($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->user_id != $this->user->user_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->member_miniapp_id != $this->appid) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        $hotel = HotelModel::find($order->hotel_id);
        $room = RoomModel::find($order->room_id);
        $config = config('dataattr.orderstatus');
        $bedtype = config('dataattr.hotelbedtype');
        $data = [
            'order_id' => $order->order_id,
            'total_price' => sprintf("%.2f", $order->total_price / 100),
            'need_pay' => sprintf("%.2f", ($order->need_pay) / 100),
            'lijian' => sprintf("%.2f", ($order->lijian) / 100),
            'pay_coupon' => sprintf("%.2f", ($order->pay_coupon) / 100),
            'hotel_name' => empty($hotel) ? '' : $hotel->hotel_name,
            'address' => empty($hotel) ? '' : $hotel->address,
            'lat' => empty($hotel) ? '' : $hotel->lat,
            'lng' => empty($hotel) ? '' : $hotel->lng,
            'room_title' => empty($room) ? '' : $room->title,
            'room_num' => $order->room_num,
            'check_in_time' => $order->check_in_time,
            'leave_time' => $order->leave_time,
            'day' => (int)((strtotime($order->leave_time) - strtotime($order->check_in_time))/86400),    
            'is_wifi' => $room->is_wifi,
            'is_breakfast' => $room->is_breakfast,
            'bed_type' => empty($bedtype[$room->bed_type])? '' :$bedtype[$room->bed_type],
            'name' => $order->name,
            'mobile' => $order->mobile,
            'idcard' => $order->idcard,
            'hotel_id' => $hotel->hotel_id,
            'tel' => empty($hotel) ? '' : $hotel->hotel_tel,
            'add_time' => date("Y-m-d H:i:s", $order->add_time),
            'status' => $order->status,
            'is_comment' => $order->is_comment,
            'status_mean' => empty($config[$order->status]) ? '' : $config[$order->status],
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    /*
     * @param score = 评分；
     * commnert = 内容；
     * img 图片
     */


    public function comment() {
        $order_id = (int) $this->request->param('order_id');
        $HotelorderModel = new HotelorderModel();
        if (!$order = $HotelorderModel->find($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->user_id != $this->user->user_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->status != 8) {
            $this->result([], '400', '订单未完成', 'json');
        } else if ($order->is_comment == 1) {
            $this->result([], '400', '该订单以评论', 'json');
        }
        $data['score'] = ((float) $this->request->param('score')) * 10;
        if ($data['score'] <= 0 || $data['score'] > 50) {
            $this->result([], 400, '请评分', 'json');
        }
        $data['content'] = $this->request->param('content');
        if (empty($data['content'])) {
            $this->result([], 400, '请评论内容', 'json');
        }
        $CommentModel = new CommentModel();
        $CommentModel->save([
            'member_miniapp_id' => $this->appid,
            'hotel_id' => $order->hotel_id,
            'user_id' => $order->user_id,
            'order_id' => $order->order_id,
            'room_id' => $order->room_id,
            'score' => $data['score'],
            'content' => $data['content'],
        ]);

        $_img  = file_get_contents("php://input");
        $_img =  json_decode($_img,true);
        $img = $_img['photo'];
        if(!empty($img)){
            $photo = [];
            foreach ($img as $val) {
                $photo[] = [
                    'comment_id' => $CommentModel->comment_id,
                    'member_miniapp_id' => $this->appid,
                    'photo' => $val,
                ];
            }
            $CommentphotoModel = new CommentphotoModel();
            $CommentphotoModel->saveAll($photo);
        }
        $HotelModel = new HotelModel();
        switch ($data['score']) {
            case $data['score'] < 25;
                $HotelModel->where('hotel_id', $order->hotel_id)->setInc('bad_num');
                break;
            case $data['score'] > 35;
                $HotelModel->where('hotel_id', $order->hotel_id)->setInc('praise_num');
                break;
        }
        $HotelorderModel->save(['is_comment' => 1], ['order_id' => $order_id]);
        $ordernum = $HotelorderModel->where(['user_id' => $this->user->user_id, 'member_miniapp_id' => $this->appid])->count();
        //首单赠送红包；
        $coupon_data = [];
        if ($ordernum <= 1) {
            $settngCouponModel = new SettingcouponModel();
            $coupondetail = $settngCouponModel->find($this->appid);
            $coupon = unserialize($coupondetail->value)['order'];
            if ($coupon['money'] > 0) {
                $CouponModel = new CouponModel();
                $coupon_data = [
                    'user_id' => $this->user->user_id,
                    'member_miniapp_id' => $this->appid,
                    'way' => 3,
                    'need_money' => $coupon['need_money'],
                    'money' => $coupon['money'],
                    'expir_time' => $coupon['expire_day'] <= 0 ? 7 * 86400 + time() : $coupon['expire_day'] * 86400 + time(),
                    'can_use_time' => $coupon['use_day'] <= 0 ? $this->request->time() : $coupon['use_day'] * 86400 + time(),
                ];
                $CouponModel->save($coupon_data);
            }
        }
        $this->result($coupon_data, 200, '评论成功', 'json');
    }



}
