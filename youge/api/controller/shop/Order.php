<?php
namespace app\api\controller\shop;
use app\api\controller\Common;
use app\common\model\setting\SkinModel;
use app\common\model\shop\CommentModel;
use app\common\model\shop\CommentphotoModel;
use app\common\model\shop\GoodsModel;
use app\common\model\shop\OrderModel;
use app\common\model\shop\SkuModel;
use app\common\model\shop\TypeModel;
use app\common\model\user\AddressModel;
use app\common\model\user\CouponModel;


class Order extends Common{
   protected $checklogin = true;
  protected $status = [
       0 => '等待支付',
       1 => '待发货',
       2 => '已发货',
       3 => '申请取消',
       4 => '已退款',
       8 => '已完成订单',
  ];
   /*
    *  购买前下拉数据
    *   order_id  int|array
    *   $goods_id | sku_id
    */
    //创建订单
    public function create() {
        $jifen = $this->user->integral;
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
        /*
         *  json 字符串： [{goods_id:1,type_id:1,show_num:2},{goods_id:1,type_id:1,show_num:2}]
         */
        $product = htmlspecialchars_decode($this->request->param('goods'));

        if (empty($product)) {
            $this->result([], '400', '1请选择要购买的产品', 'json');
        }
        $product = json_decode($product, true);
        if (empty($product)) {
            $this->result([], '400', '2请选择要购买的产品', 'json');
        }
        $goodsIds = $typeIds = $nums = [];
        foreach ($product as $val) {
            if ($val['show_num'] <= 0) {
                $this->result([], '400', '3请选择要购买的产品', 'json');
            }
            $nums[$val['type_id']] = $val['show_num'];
            $goodsIds[$val['goods_id']] = $val['goods_id'];
            $typeIds[$val['type_id']] = $val['type_id'];
        }
        if (empty($goodsIds)) {
            $this->result([], '400', '4请选择要购买的产品', 'json');
        }
        $GoodsModel = new GoodsModel();
        $goods = $GoodsModel->itemsByIds($goodsIds);
        $mall_price = 0;
        $Maxjifen = 0; //最大积分
        foreach ($goods as $val){
            if($val->member_miniapp_id != $this->appid || $val->is_delete == 1){
                $this->result([],400,'产品信息有误','json');
                break;
            }
            if($val->is_online == 0){
                $this->result([],400,'产品已经下架','json');
            }
            $Maxjifen += $val->user_integral;
            if($val->is_mail == 0){
                $mall_price+=$val->mail_price;
            }
        }

        $jifen =  $Maxjifen >= $jifen ? $jifen : $Maxjifen; //最大使用积分；
        if(count($goodsIds) != count($goods)){
            $this->result('',200,'有不存在的商品','json');
        }
        $TypeModel = new TypeModel();
        $types =  $TypeModel->itemsByIds($typeIds);
        $sku = [];
        $totalprice  =  $_price  = 0;
        foreach ($types as $val){
            if($val->is_delete == 1 || $val->member_miniapp_id != $this->appid){
                $this->result('',400,'不存在商品','json');
            }
            $sku[] = [
                'user_id' => $this->user->user_id,
                'type_id' => $val->type_id,
                'member_miniapp_id' => $this->appid,
                'goods_id' => $val->goods_id,
                'price'   => $val->price,
                'num'    => $nums[$val->type_id],
            ];
            $_price = ($val->price * $nums[$val->type_id]);
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
        $totalprice = $_price + $mall_price -$jifen - $hongbao; // 真实实际支付价格
        $order = [
            'member_miniapp_id' => $this->appid,
            'user_id' => $this->user->user_id,
            'total_price' => $_price + $mall_price,
            'need_pay' => $totalprice,
            'pay_coupon' => $hongbao,
            'pay_integral' => $jifen,
            'pay_coupon_id' => $coupon_id,
            'status' => 0,
            'name' => $address->name,
            'mobile' => $address->mobile,
            'address' => $address->address,
            'gps_addr' => $address->gps_addr,
            'lng' => $address->lng,
            'lat' => $address->lat,
        ];
        $OrderModel = new OrderModel();
        if ($OrderModel->save($order)) {
            $order_id = $OrderModel->order_id;
            foreach ($sku as $k => $val) {
                $sku[$k]['order_id'] = $order_id;
            }
            $SkuModel = new SkuModel();
            $SkuModel->saveAll($sku);
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
            $input->SetBody("商品订购" . $order_id);
            $input->SetAttach($order_id);
            $input->SetOut_trade_no(WX_MCHID . rand(1000, 9999) . $order_id);
            $input->SetTotal_fee($totalprice);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            // $input->SetGoods_tag();
            $input->SetNotify_url("https://" . $_SERVER['HTTP_HOST'] . "/api/weixin/shopnotify/appid/" . $this->appid . '.html');
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($this->request->param('openid'));
            $order = \WxPayApi::unifiedOrder($input);
            //var_dump($order);die;
            $jsApiParameters = $tools->GetJsApiParameters($order);
            $this->result(['order' => json_decode($jsApiParameters, true), 'order_id' => $order_id], '200', '创建支付成功！', 'json');
        }
        $this->result([], '400', '创建订单失败了', 'json');
    }

   /*
    *  支付订单
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
        $input->SetTotal_fee($order->need_pay);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        // $input->SetGoods_tag();
        $input->SetNotify_url("https://" . $_SERVER['HTTP_HOST'] . "/api/weixin/shopnotify/appid/" . $this->appid . '.html');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($this->request->param('openid'));
        $order = \WxPayApi::unifiedOrder($input);
        //var_dump($order);die;
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $this->result(['order' => json_decode($jsApiParameters, true), 'order_id' => $order_id], '200', '创建支付成功！', 'json');
    }
   /*
    * 取消订单
    *
    */
   public function cancelorder(){
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
       if ($HotelorderModel->save(['status'=>3,'cancel_type'=>1,'cancel_time'=>  $this->request->time(),'cancel_info'=>$cancel_info],['order_id'=>$order_id])){
           if(!empty($order->pay_coupon_id)){
               $CouponModel = new CouponModel();
               $CouponModel->save(['is_can'=>0],['coupon_id'=>$order->pay_coupon_id]);
           }
          // $GoodsModel = new GoodsModel();
           //加库存：
//           $GoodsModel = new GoodsModel();
           //$GoodsModel->where(['goods_id'=>$order->goods_id])->setInc('surplus_num');
           $this->result([], '200', '申请成功', 'json');
       }
       $this->result([], '400', '订单状态不可取消', 'json');
   }
   /*
    * 评价订单
    */
   public function comment(){
       $order_id = (int) $this->request->param('order_id');
       $OrderModel = new OrderModel();
       if (!$order = $OrderModel->find($order_id)) {
           $this->result([], '400', '不存在该订单12', 'json');
       }
       if ($order->user_id != $this->user->user_id) {
           $this->result([], '400', '不存在该订单3', 'json');
       }
       if ($order->status != 3 && $order->status != 8 ) {
           $this->result([], '400', '订单未完成', 'json');
       } else if ($order->is_comment == 1) {
           $this->result([], '400', '该订单以评论', 'json');
       }
       $sku_id = (int) $this->request->param('sku_id');
       $SkuModel = new SkuModel();
       if(!$sku = $SkuModel->find($sku_id)){
            $this->result('',400,'不存在商品','json');
       }
       if($sku->member_miniapp_id != $this->appid){
           $this->result('',400,'不存在商品','json');
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
           'goods_id' => $sku->goods_id,
           'user_id' => $order->user_id,
           'order_id' => $order->order_id,
           'score' => $data['score'],
           'content' => $data['content'],
       ]);
       $SkuModel->save(['is_comment'=>1],['sku_id'=>$sku_id]);
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
    * 订单列表； 0全部 1 待发货 2 大收获 3退款单 4已完成
    */
   public function orderList(){
       $where = ['member_miniapp_id' => $this->appid, 'user_id' => $this->user->user_id];
       $type = $this->request->param('type');
       switch ($type) {
           case 1:
               $where['status'] = 1;
               break;
           case 2:
               $where['status'] = 2;
               break;
           case 3:
               $where['status'] = ['IN', [4, 5]];
               break;
           case 4:
               $where['status'] = ['IN',8];
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

           $sku = SkuModel::where(['order_id' => ['IN', $orderIds]])->select();
           $goodsIds = [];
           foreach ($sku as $val) {
               $goodsIds[$val->goods_id] = $val->goods_id;
           }

           $GoodsModel = new GoodsModel();
           $goods = $GoodsModel->itemsByIds($goodsIds);
           $data = [];
           foreach ($sku as $val) {
               $data[$val->order_id][] = [
                   'goods_id' => $val->goods_id,
                   'goods_name' => isset($goods[$val->goods_id]->goods_name) ? $goods[$val->goods_id]->goods_name : '',
                   'photo' => isset($goods[$val->goods_id]->photo) ? IMG_URL . getImg($goods[$val->goods_id]->photo) : '',
                   'num' => $val->num,
                   'price' => round($val->price / 100, 2)
               ];
           }

           foreach ($list as $val) {
               $datas[] = [
                   'order_id' => $val->order_id,
                   'total_price' => round($val->total_price / 100, 2),
                   'need_pay' => round($val->need_pay / 100, 2),
                   'pay_coupon' => round($val->pay_coupon / 100, 2),
                   'pay_integral' => round($val->pay_integral / 100, 2),
                   'statusmeans' => empty($this->status[$val->status]) ? '' : $this->status[$val->status] ,
                   'status' => $val->status,
                   'name' => $val->name,
                   'mobile' => $val->mobile,
                   'address' => $val->address,
                   'gps_addr' => $val->gps_addr,
                   'lng' => (float) $val->lng,
                   'lat' => (float) $val->lat,
                   'goods' => empty($data[$val->order_id]) ? [] : $data[$val->order_id]
               ];
           }
       }
       $return = ['list' => $datas];
       $return['more'] = count($datas) > $this->limit_num ? 1 : 0;
       $this->result($return, 200, '数据初始化成功', 'json');
   }
   /*
    * 订单详情
    */
   public function orderDetail(){
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


       $sku = SkuModel::where(['order_id' => $order_id])->select();

       $goodsIds = [];

       foreach ($sku as $val) {
           $goodsIds[$val->goods_id] = $val->goods_id;
       }
       $GoodsModel = new GoodsModel();
       $goods = $GoodsModel->itemsByIds($goodsIds);
       $data = [];
       foreach ($sku as $val) {
           $data[] = [
               'goods_id' => $val->goods_id,
               'sku_id' => $val->sku_id,
               'goods_name' => isset($goods[$val->goods_id]->goods_name) ? $goods[$val->goods_id]->goods_name : '',
               'photo' => isset($goods[$val->goods_id]->photo) ? IMG_URL . getImg($goods[$val->goods_id]->photo) : '',
               'num' => $val->num,
               'price' => round($val->price / 100, 2),
               'is_comment' => $val->is_comment,
           ];
       }
       $datas= [
           'id' => $order->order_id,
           'order_id' => $order->order_id,
           'total_price' => round($order->total_price / 100, 2),
           'need_pay' => round($order->need_pay / 100, 2),
           'pay_coupon' => round($order->pay_coupon / 100, 2),
           'pay_integral' => round($order->pay_integral / 100, 2),
           'mail_price' => round($order->mail_price / 100, 2),
           'statusmeans' => empty($this->status[$order->status]) ? '' : $this->status[$order->status] ,
           'status' => $order->status,
           'name' => $order->name,
           'mobile' => $order->mobile,
           'address' => $order->address,
           'gps_addr' => $order->gps_addr,
           'mail_number' => $order->mail_number,
           'lng' => (float) $order->lng,
           'lat' => (float) $order->lat,
           'add_time' => date('Y-m-d H:i:s',$order->add_time),
           'goods' => empty($data) ? [] : $data
       ];
       $this->result($datas, 200, '数据初始化成功', 'json');
   }

}