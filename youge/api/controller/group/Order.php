<?php
namespace app\api\controller\group;
use app\api\controller\Common;
use app\common\model\group\CommentModel;
use app\common\model\group\CommentphotoModel;
use app\common\model\group\ContentModel;
use app\common\model\group\GoodsModel;
use app\common\model\group\GroupModel;
use app\common\model\group\OrderModel;
use app\common\model\setting\SkinModel;
use app\common\model\user\AddressModel;
use app\common\model\user\CouponModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\group\Group;
use app\miniapp\controller\love\Im;

class Order extends Common{
    protected $checklogin = true;
    protected $status = [0=>'等待支付',1=>'等待拼团',2=>'待发货',3=>'已发货',4=>'退款申请',5=>'已退款',8=>'完成订单'];
    /*
     * 单独购买；
     */
    public function aloneBy(){
        $goods_id = (int) $this->request->param('goods_id');
        $GoodsModel = new GoodsModel();
        if(!$goods = $GoodsModel->find($goods_id)){
            $this->result('',400,'不存在商品','json');
        }
        if($goods->member_miniapp_id != $this->appid || $goods->is_delete == 1){
            $this->result('',400,'不存在商品','json');
        }
        if($goods->is_online == 0 || $goods->surplus_num == 0){
            $this->result('',400,'产品库存不足已下架','json');
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
       //红包金额
        $hongbao = 0;
        $hongbaoId = (int) $this->request->param('hongbaoId');
        if (!empty($hongbaoId)) {
            $coupon = CouponModel::get($hongbaoId);
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
        $delivery = (int) $this->request->param('delivery');
        //邮费；
        $youfei  = 0;
        if($goods->is_mail == 0){
            $youfei = $goods->mail_price;
        }
        $need_price = ($goods->alone_price - $hongbao) + $youfei;
        $data = [
            'goods_id' => $goods_id,
            'user_id' => $this->user->user_id,
            'member_miniapp_id' => $this->appid,
            'group_id' => 0,
            'total_price' => $goods->group_price,
            'need_pay'  => $need_price,
            'pay_coupon' => $hongbao,
            'pay_coupon_id' => $hongbaoId,
            'delivery'  => $delivery,
            'mail_price' => $youfei,
            'lat' => $address->lat,
            'lng' => $address->lat,
            'name' => $address->name,
            'mobile' => $address->mobile,
            'address' => $address->address,
            'gps_addr' => $address->gps_addr,
            'status'   => 0,   //支付成功以后回调才能改变状态；
        ];

        $OrderModel = new OrderModel();
       if($OrderModel->save($data)){
                     // 库存-1；
           $GoodsModel->where(['goods_id'=>$goods_id])->setDec('surplus_num');
           $order_id = $OrderModel->order_id;
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
           $input->SetBody("团购" . $order_id);
           $input->SetAttach($order_id);
           $input->SetOut_trade_no(WX_MCHID . rand(1000, 9999) . $order_id);
           $input->SetTotal_fee($need_price);
           $input->SetTime_start(date("YmdHis"));
           $input->SetTime_expire(date("YmdHis", time() + 600));
           // $input->SetGoods_tag();
           $input->SetNotify_url("https://" . $_SERVER['HTTP_HOST'] . "/api/weixin/tgnotify/appid/" . $this->appid . '.html');
           $input->SetTrade_type("JSAPI");
           $input->SetOpenid($this->request->param('openid'));
           $order = \WxPayApi::unifiedOrder($input);
           //var_dump($order);die;
           $jsApiParameters = $tools->GetJsApiParameters($order);
           $this->result(['order' => json_decode($jsApiParameters, true), 'order_id' => $order_id], '200', '创建支付成功！', 'json');
       }
    }
    /*
     * 单独购买前下拉数据
     *
     */
   public function checkAlone(){
       $goods_id = (int) $this->request->param('goods_id');
       $GoodsModel = new GoodsModel();
       if(!$goods = $GoodsModel->find($goods_id)){
           $this->result('',400,'不存在商品','json');
       }
       if($goods->member_miniapp_id != $this->appid || $goods->is_delete == 1){
           $this->result('',400,'不存在商品','json');
       }
       if($goods->is_online == 0 || $goods->surplus_num == 0){
           $this->result('',400,'产品库存不足已下架','json');
       }
       $AddressModel = new AddressModel();
       $where['member_miniapp_id'] = $this->appid;
       $where['user_id'] = $this->user->user_id;
       $where['is_delete'] = 0;
       $detail = $AddressModel->where($where)->order("is_default desc,address_id desc")->find();
      $address = [];
       if(!empty($detail)){
           $address = [
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
       }

       $data = [
           'goods_name' => $goods->goods_name,
           'brief' => $goods->brief,
           'photo' => IMG_URL . getImg($goods->photo),
           'alone_price' =>  round($goods->alone_price/100,2),
           'mail_price' =>  round($goods->mail_price/100,2),
           'is_mail' => $goods->is_mail,
           'goods_id' => $goods->goods_id,
           'address' => $address,
       ];
       $this->result($data,200,'数据初始化成功','json');
   }
   /*
    * 参团购买拉下数据
    *
    */
   public function checkGroup(){
       $goods_id = (int) $this->request->param('goods_id');
       $GoodsModel = new GoodsModel();
       if(!$goods = $GoodsModel->find($goods_id)){
           $this->result('',400,'不存在商品','json');
       }
       if($goods->member_miniapp_id != $this->appid || $goods->is_delete == 1){
           $this->result('',400,'不存在商品','json');
       }
       if($goods->is_online == 0 || $goods->surplus_num == 0){
           $this->result('',400,'产品库存不足已下架','json');
       }
       if($goods->end_time < $this->request->time()){
           $this->result('',400,'产品已过期','json');
       }
       $AddressModel = new AddressModel();
       $where['member_miniapp_id'] = $this->appid;
       $where['user_id'] = $this->user->user_id;
       $where['is_delete'] = 0;
       $detail = $AddressModel->where($where)->order("is_default desc,address_id desc")->find();
       $address = [];
       if(!empty($detail)){
           $address = [
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
       }
       $data = [
           'goods_name' => $goods->goods_name,
           'brief' => $goods->brief,
           'photo' => IMG_URL . getImg($goods->photo),
           'alone_price' =>  round($goods->group_price/100,2),
           'mail_price' =>  round($goods->mail_price/100,2),
           'is_mail' => $goods->is_mail,
           'goods_id' => $goods->goods_id,
           'address' => $address,
       ];
       $this->result($data,200,'数据初始化成功','json');
   }
    /*
     * 参团购买
     * 所有状态 以及团购 结束状态 在支付成功以后修改
     */
    public function groupBy(){
        $goods_id = (int) $this->request->param('goods_id');
        $group_id = (int) $this->request->param('group_id');
        if(empty($goods_id) && empty($group_id)){
                $this->result('',400,'操作错误','json');
        }
        if(empty($goods_id) && $goods_id != 0){
            $GroupModel = new GroupModel();
            if(!$group = $GroupModel->find($group_id)){
                $this->result('',400,'不存在参团','json');
            }
            if($group->member_miniapp_id != $this->appid){
                $this->result('',400,'不存在参团','json');
            }
            if($group->expire_time < $this->request->time()){
                $this->result('',400,'该团已过期','json');
            }
            if($group->max_num <= $group->this_num || $group->status == 1 || $group->status == 8 ){
                $this->result('',400,'已经成团','json');
            }
            $goods_id = $group->goods_id;
        }
        //检查当前用户是否已经在团队中；或者已经开了团
        $OrderModel = new OrderModel();
        $order_where['member_miniapp_id'] = $this->appid;
        $order_where['user_id'] = $this->user->user_id;
        $order_where['goods_id'] = $goods_id ;
        $order_where['status'] = ['<','4'];
        $order_where['expire_time'] = ['>',$this->request->time()];
        if($OrderModel->where($order_where)->order('order_id desc')->find()){
            $this->result('',400,'您已参加过团队了','json');
        };
        $GoodsModel = new GoodsModel();
        if(!$goods = $GoodsModel->find($goods_id)){
            $this->result('',400,'不存在商品','json');
        }
        if($goods->member_miniapp_id != $this->appid || $goods->is_delete == 1){
            $this->result('',400,'不存在商品','json');
        }
        if($goods->is_online == 0 || $goods->surplus_num == 0){
            if(!empty($group_id)){
                $GroupModel->save(['status'=>2],['group_id'=>$group_id]);
            }
            $this->result('',400,'产品库存不足已下架','json');
        }

        if(empty($group_id) && $goods_id){
            $GroupModel = new GroupModel();
            $GroupModel->save([
                'member_miniapp_id' => $this->appid,
                'user_id' => $this->user->user_id,
                'max_num' => $goods->group_num,
                'expire_time' => $goods->end_time,
                'goods_id' => $goods_id,
                'this_num' => 0,
                'status' => 0,
            ]);
            $group_id = $GroupModel->group_id;
        }
        $address_id = (int) $this->request->param('address_id');
        if (empty($address_id)) {
            $this->result([], '400', '请选择收货地址', 'json');
        }
        $address = AddressModel::get($address_id);
        if (empty($address)) {
            $this->result([], '400', '请选择收货地址', 'json');
        }
        if ($address['user_id'] != $this->user->user_id) {
            $this->result([], '400', '请选择收货地址', 'json');
        }
        //红包金额
        $hongbao = 0;
        $hongbaoId = (int) $this->request->param('hongbaoId');
        if (!empty($hongbaoId)) {
            $coupon = CouponModel::get($hongbaoId);
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
        $delivery = (int) $this->request->param('delivery');
        //邮费；
        $youfei  = 0;
        if($goods->is_mail == 0){
            $youfei = $goods->mail_price;
        }
        $need_price = ($goods->group_price - $hongbao) + $youfei;
        $data = [
            'goods_id' => $goods_id,
            'user_id' => $this->user->user_id,
            'member_miniapp_id' => $this->appid,
            'group_id' => $group_id,
            'expire_time' => $goods->end_time,
            'total_price' => $goods->group_price,
            'need_pay'  => $need_price,
            'pay_coupon' => $hongbao,
            'pay_coupon_id' => $hongbaoId,
            'delivery'  => $delivery,
            'lat' => $address->lat,
            'lng' => $address->lat,
            'name' => $address->name,
            'mobile' => $address->mobile,
            'address' => $address->address,
            'gps_addr' => $address->gps_addr,
            'mail_price' => $youfei,
            'status'   => 0,   //支付成功以后回调才能改变状态；
        ];
        if($OrderModel->save($data)) {
            $GoodsModel->where(['goods_id'=>$goods_id])->setInc('people_num');
            $order_id = $OrderModel->order_id;
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
            $input->SetBody("团购" . $order_id);
            $input->SetAttach($order_id);
            $input->SetOut_trade_no(WX_MCHID . rand(1000, 9999) . $order_id);
            $input->SetTotal_fee($need_price);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            // $input->SetGoods_tag();
            $input->SetNotify_url("https://" . $_SERVER['HTTP_HOST'] . "/api/weixin/tgnotify/appid/" . $this->appid . '.html');
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($this->request->param('openid'));
            $order = \WxPayApi::unifiedOrder($input);
            //var_dump($order);die;
            $jsApiParameters = $tools->GetJsApiParameters($order);
            $this->result(['order' => json_decode($jsApiParameters, true), 'order_id' => $order_id], '200', '创建支付成功！', 'json');
        }

    }

    /*
     * 订单列表
     * 0 全部订单 1待发货订单 2 待收货订单 3退款单 4已完成订单
     */
    public function getOrder(){
        $type = (int) $this->request->param('type');
        switch ($type){
             case 1:
                $where['status'] = [['eq',1],['eq',2],'or'];
                break;
            case 2:
                $where['status'] = 3;
               break;
            case 3:
                 $where['status'] = [['eq',4],['eq',5],'or'];
                 break;
            case 4:
                 $where['status'] = 8;
        }
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $OrderModel = new OrderModel();
        $list = $OrderModel->where($where)->order('order_id desc')->limit($this->limit_bg,$this->limit_num)->select();
        $goodsIds = [];
        foreach ($list as $val){
            $goodsIds[$val->goods_id] = $val->goods_id;
        }
        $GoodsModel  = new GoodsModel();
        $goods = $GoodsModel->itemsByIds($goodsIds);
        $data['list'] = [];
        foreach ($list as $val){
                $data['list'][] = [
                    'order_id' => $val->order_id,
                    'status' => $val->status,
                    'is_comment'  => $val->is_comment,
                    'status_mean' => empty($this->status[$val->status]) ? '' : $this->status[$val->status],
                    'total_price' => round($val->total_price/100,2),
                    'goods_name' => empty($goods[$val->goods_id]) ? '' : $goods[$val->goods_id]->goods_name,
                    'goods_photo' => empty($goods[$val->goods_id]) ? '' : IMG_URL . getImg($goods[$val->goods_id]->photo),
                ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 订单详情36.5.165.242
     */
    public function orderDetail(){
        $order_id = (int) $this->request->param('order_id');
        if(empty($order_id)){
            $this->result([], '400', '不存在该订单', 'json');
        }
        $HotelorderModel = new OrderModel();
        if (!$order = $HotelorderModel->find($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->user_id != $this->user->user_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->member_miniapp_id != $this->appid) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        $goods = GoodsModel::find($order->goods_id);

        $data = [
            'order_id' => $order->order_id,
            'add_time' => date("Y-m-d H:i:s",$order->add_time),
            'delivery_mean' => empty(config('dataattr.delivery')[ $order->delivery]) ? '' :config('dataattr.delivery')[ $order->delivery] ,
            'name' => $order->name,
            'mobile' => $order->mobile,
            'address' => $order->address,
            'gps_addr' => $order->gps_addr,
            'goods_name' => empty($goods) ? '' : $goods->goods_name,
            'goods_photo' => empty($goods) ? '' : IMG_URL . getImg($goods->photo),
            'status_mean'  => empty($this->status[$order->status]) ? '' : $this->status[$order->status] ,
            'status'  => $order->status,
            'is_comment' => $order->is_comment,
            'mail_number'  => $order->mail_number,
            'mail_price'  => round($order->mail_price/100,2),
            'total_price'  => round($order->total_price/100,2),
            'pay_coupon'  => round($order->pay_coupon/100,2),
            'need_pay'  => round($order->need_pay/100,2),
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }
    /*
     * 评价订单
     */
    public function commetnOrder(){
        $order_id = (int) $this->request->param('order_id');
        $OrderModel = new OrderModel();
        if (!$order = $OrderModel->find($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->user_id != $this->user->user_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->status != 3 && $order->status != 8 ) {
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
            'goods_id' => $order->goods_id,
            'user_id' => $order->user_id,
            'order_id' => $order->order_id,
            'score' => $data['score'],
            'content' => $data['content'],
        ]);
        $OrderModel->save(['is_comment'=>1],['order_id'=>$order_id]);
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
        $this->result('', 200, '评论成功', 'json');
    }
    /*
     * 取消订单
     */
    public function cancelOrder(){
        $order_id = (int) $this->request->param('order_id');
        $HotelorderModel = new OrderModel();
        if (!$order = $HotelorderModel->find($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->user_id != $this->user->user_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->status > 2) {
            $this->result([], '400', '订单状态不可取消', 'json');
        }
        $cancel_info = $this->request->param('cancel_info');
        if(empty($cancel_info)){
            $this->result([], '400', '取消理由不能为空', 'json');
        }
        if ($HotelorderModel->save(['status'=>4,'cancel_type'=>1,'cancel_time'=>  $this->request->time(),'cancel_info'=>$cancel_info],['order_id'=>$order_id])){
            if(!empty($order->pay_coupon_id)){
                $CouponModel = new CouponModel();
                $CouponModel->save(['is_can'=>0],['coupon_id'=>$order->pay_coupon_id]);
            }
          //加库存：
              $GoodsModel = new GoodsModel();
               $GoodsModel->where(['goods_id'=>$order->goods_id])->setInc('surplus_num');
          //团队减少一人
            $GroupModel = new GroupModel();
            $GroupModel->where(['group_id'=>$order->group_id])->setDec('this_num');
            $this->result([], '200', '申请成功', 'json');
        }
        $this->result([], '400', '订单状态不可取消', 'json');
    }

    /**
     * 商品详情
     * 如果page = 2 说明是加载评论； 则不再查询 其他信息 ;
     * open_id  来判断参团信息；
     */
    public function goodsDetail(){
        $goods_id = (int) $this->request->param('goods_id');
        $GoodsModel = new GoodsModel();
        if(!$goods = $GoodsModel->find($goods_id)){
            $this->result('',200,'不存在商品','json');
        }
        if($goods->member_miniapp_id != $this->appid || $goods->is_delete == 1  || $goods->bg_time > $this->request->time()){
            $this->result('',200,'不存在商品','json');
        }
        if($goods->is_online == 0){
            $this->result('',200,'商品下架了','json');
        }

        // 第一次加载才加载这些东西
        if($this->limit_bg  <= 1) {
            $contents = ContentModel::where(['member_miniapp_id' => $this->appid, 'goods_id' => $goods_id])->order(['orderby' => 'asc'])->select();
            $contentArr = [];
            foreach ($contents as $val) {
                $contentArr[] = [
                    'content' => $val->content,
                    'photo' => empty($val->photo) ? '' : IMG_URL . getImg($val->photo)
                ];
            }
            $serviceIds = explode(',',$goods->service_ids);
            $service = [];
            foreach ($serviceIds as $val){
                if(!empty(config('dataattr.group')[$val])){
                    $service[]  = config('dataattr.group')[$val];
                }
            }
            $data['goods'] = [
                'goods_id' => $goods->goods_id,
                'goods_name' => $goods->goods_name,
                'photo'  => IMG_URL .getImg($goods->photo),
                'price' => round($goods->price/100,2),
                'group_price' => round($goods->group_price/100,2),
                'alone_price' => round($goods->alone_price/100,2),
                'mail_price' => round($goods->mail_price/100,2),
                'is_mail'    => $goods->is_mail,
                'surplus_time'    => $goods->end_time - $this->request->time(),
                'end_time'    => $goods->end_time ,
                'group_num'    => $goods->group_num,
                'people_num'    => $goods->people_num,
                'surplus_num'    => $goods->surplus_num,
                'brief'    => $goods->brief,
                'spec'    => $goods->spec,
                'ctn'    => $goods->ctn,
                'service'    => $service,
                'contents' => $contentArr,
            ];
            // 参团状态； 以及团队；
            $OrderModel = new OrderModel();
            $order_where['member_miniapp_id'] = $this->appid;
            $order_where['user_id'] = $this->user->user_id;
            $order_where['goods_id'] = $goods_id;
            $order_where['status'] = ['<','4'];
            $order_where['expire_time'] = ['>',$this->request->time()];
            $order = $OrderModel->where($order_where)->order('order_id desc')->find();
            //当前没有参团
            $data['group'] = [];
            $data['status'] = 0;
            $GroupModel = new GroupModel();
            if(empty($order)){
               $data['status'] = 0;
               $group_where['member_miniapp_id'] = $this->appid;
               $group_where['goods_id'] = $goods_id;
               $group_where['status'] = 0;
               $group_where['expire_time'] = ['>',$this->request->time()];
               $group = $GroupModel->where($group_where)->order('group_id desc')->limit(0,3)->select();
               $headIds = [];
               foreach ($group as $val){
                   $headIds[$val->user_id] = $val->user_id;
               }
               $UserModel = new UserModel();
                $user = $UserModel->itemsByIds($headIds);
                foreach ($group as $val){
                    $data['group'][] = [
                        'group_id' => $val->group_id,
                        'header_name' => empty($user[$val->user_id]) ? '' : $user[$val->user_id]->nick_name,
                        'face' => empty($user[$val->user_id]) ? '' :  getImg($user[$val->user_id]->face),
                        'max_num' => $val->max_num,
                        'this_num' => $val->this_num,
                        'surplus_num' => $val->max_num - $val->this_num,
                        'surplus_time' => $val->expire_time - $this->request->time(),
                    ];
                }
               // var_dump($group);die;
                //以参团； 并且为成团 有两个状态 还在进行中 和 已过期 需要退款；
            }else{
                $group = $GroupModel->find($order->group_id);
                if($group->status == 0 && $group->expire_time > $this->request->time()){
                      $data['status'] = 1;
                      $data['group_id'] = $group->group_id;
                      $data['attend_max_num'] = $group->max_num;
                      $data['attend_this_num'] = $group->this_num;
                      $data['attend_surplus_num'] = $group->max_num -  $group->this_num;
                      $data['attend_expire_time'] = $group->expire_time;
                      $data['attend_surplus_time'] = $group->expire_time - $this->request->time();
                }elseif($group->status == 8 && ($order->status == 2 || $order->status == 1 || $order->status == 3)){
//                     已完成拼团等待收货
                    $data['status'] = 2;
                }elseif ($group->expire_time < $this->request->time()){
                    $data['status'] = 3;
                }
                if($goods->end_time < $this->request->time()){
                    $data['status'] = 3;
                }
            }
        }
        $CommentModel = new CommentModel();
        $_where['member_miniapp_id'] = $this->appid;
        $_where['goods_id'] = $goods_id;
        $list = $CommentModel->where($_where)->order("comment_id desc")->limit($this->limit_bg,$this->limit_num)->select();
        $photoIds = $userIds = $roomIds = $hotelIds = [];
        foreach ($list as $val){
            $photoIds[$val->comment_id] = $val->comment_id;
            $userIds[$val->user_id] = $val->user_id;
        }
        $CommentphotoModel = new CommentphotoModel();
        $UserModel = new UserModel();
        $users = $UserModel->itemsByIds($userIds);
        $photoIds = empty($photoIds) ? 0 : $photoIds;
        $photo_where['comment_id'] = ["IN",$photoIds];
        $photo = $CommentphotoModel->where($photo_where)->select();
        $photos = [];
        foreach ($photo as $val){
            $photos[$val->comment_id][] = IMG_URL . getImg($val->photo);
        }
        $data['comment'] = [];
        foreach ($list as $val){
            $data['comment'] [] = [
                'comment_id' => $val->comment_id,
                'user_id'    => $val->user_id,
                'user_nick_name' => empty($users[$val->user_id])  ? '' : $users[$val->user_id]->nick_name,
                'user_face'  => empty($users[$val->user_id]) ? '' : $users[$val->user_id]->face,
                'score'     => round($val->score/10,1),
                'content'    => $val->content,
                'content_time' => date("Y-m-d",$val->add_time),
                'reply'      => $val->reply,
                'reply_time'  => empty($val->reply_time) ? '' : date("Y-m-d",$val->reply_time),
                'photos'    => empty($photos[$val->comment_id]) ? [] : $photos[$val->comment_id],
            ];
        }
        if($goods->end_time < $this->request->time()){
            $data['status'] = 3;
        }
        $data['more']  = count($data['comment']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 团队详情
     */
    public function groupDetail(){
        $group_id  = (int) $this->request->param('group_id');
        $GrpupModel = new GroupModel();
        if(!$group = $GrpupModel->find($group_id)){
            $this->result('',400,'不存在团购','json');
        }
        if($group->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在团购','json');
        }
        if($group->expire_time < $this->request->time()){
            $this->result('',400,'该团购已过期','json');
        }
        $OrderModel = new OrderModel();
        $GoodsModel = new GoodsModel();
        $goods = $GoodsModel->find($group->goods_id);
        $where['member_miniapp_id'] = $this->appid;
        $where['group_id'] = $group_id;
        $where['status'] = ["IN",[1,2,3,6,8]];
        $orderlist = $OrderModel->where($where)->select();

        $userIds = [];
        $join = 0;
        foreach ($orderlist as $val){
            $userIds[$val->user_id] = $val->user_id;
             if($val->user_id == $this->user->user_id){
                 $join = 1;
             }
        }
        $UserModel = new UserModel();
        $users = $UserModel->itemsByIds($userIds);
        $data = [
            'goods_id' => empty($goods) ? '' : $goods->goods_id,
            'goods_name' => empty($goods) ? '' : $goods->goods_name,
            'photo' => empty($goods) ? '' : IMG_URL . getImg($goods->photo),
            'is_mail' => empty($goods) ? '' : $goods->is_mail,
            'price' => empty($goods) ? '' : round($goods->price/100,2),
            'group_price' => empty($goods) ? '' : round($goods->group_price/100,2),
            'alone_price' => empty($goods) ? '' : round($goods->alone_price/100,2),
            'expire_time' => $group->expire_time,
            'max_num' => $group->max_num,
            'this_num' => $group->this_num,
            'surplus_num' => $group->max_num - $group->this_num,
            'surplus_time' => $group->expire_time  - $this->request->time(),
            'status' => $group->status,
            'is_join' =>$join,
        ];
        $data['user'] = [];
        foreach ($orderlist as $val){
            $data['user'][] = [
                'user_id' => $val->user_id,
                'is_head' => $group->user_id == $val->user_id ? 1 : 0,
                'face'  => empty($users[$val->user_id]) ? '' : $users[$val->user_id]->face,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     *支付
     */
    public function getPayment() {
        $order_id = (int) $this->request->param('order_id');
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

        $needpay = $order->need_pay;

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
        $input->SetBody("团购订单" . $order_id);
        $input->SetAttach($order_id);
        $input->SetOut_trade_no(WX_MCHID . rand(1000, 9999) . $order_id);
        $input->SetTotal_fee($needpay);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        // $input->SetGoods_tag();
        $input->SetNotify_url("https://" . $_SERVER['HTTP_HOST'] . "/api/weixin/tgnotify/appid/" . $this->appid . '.html');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($this->request->param('openid'));
        $order = \WxPayApi::unifiedOrder($input);
        //var_dump($order);die;
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $this->result(['order' => json_decode($jsApiParameters, true), 'order_id' => $order_id], '200', '创建支付成功！', 'json');
    }
}