<?php

namespace app\api\controller\taocan;

use app\api\controller\Common;
use app\common\library\MiniApp;
use app\common\model\city\CityModel;
use app\common\model\hotel\CouponModel;
use app\common\model\taocan\CommentModel;
use app\common\model\taocan\CommentphotoModel;
use app\common\model\taocan\OrderModel;
use app\common\model\taocan\PackageModel;
use app\common\model\taocan\TaocanDetailModel;
use app\common\model\taocan\TaocanmanageModel;
use app\common\model\taocan\TaocanModel;
use app\common\model\taocan\TaocanpackagepriceModel;
use app\common\model\taocan\TaocanphotoModel;
use app\common\model\user\UserModel;


//酒店商户的所有接口都放在这里
class Manage extends Common {

    protected $store_id = 0;
    //商户管理员信息
    protected $manage = [];

    public function cancel() {
        $this->checklogin();
        $id = (int) $this->request->param('id');
        if (empty($id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        $OrderModel = new OrderModel();
        if (!$order = $OrderModel->find($id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->store_id != $this->store_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->status != 0 && $order->status != 1) {
            $this->result([], '400', '订单状态不可取消', 'json');
        }
        $cancel_info = $this->request->param('cancel_info');
        if (empty($cancel_info)) {
            $this->result([], '400', '取消理由不能为空', 'json');
        }
        if ($OrderModel->save(['status' => 3, 'cancel_type' => 2, 'cancel_time' => $this->request->time(), 'cancel_info' => $cancel_info], ['order_id' => $id])) {
            if (!empty($order->pay_coupon_id)) {
                $CouponModel = new CouponModel();
                $CouponModel->save(['is_can' => 0], ['coupon_id' => $order->pay_coupon_id]);
            }
            $TaocanpackagepriceModel = new TaocanpackagepriceModel();
             $TaocanpackagepriceModel->where(['day'=>$order->play_time,'package_id'=>$order->package_id])->setDec('day_num',$order->package_num);
            if ($order->pay_money > 0) {
                $OrderModel->refund($id, $order->pay_money);
            }
            $taocan = TaocanModel::get($order->taocan_id);
            //发送模版消息
            $formId = $this->request->param('formId');
            $Miniapp = new MiniApp($this->appid);
            $user = UserModel::get($order->user_id);
            $Miniapp->sendTemplateMessage('AT0012', $user->open_id, $formId, '/pages/hotel/order/detail?id=' . $id, [
                $taocan->taocan_name,
                $id,
                date('Y-m-d H:i:s', $order->add_time),
                $cancel_info,
                '订单已经完成退款' . round($order->pay_money / 100, 2) . "，请注意微信余额变动！",
            ]);
            $this->result([], '200', '取消成功', 'json');
        }
        $this->result([], '400', '订单状态不可取消', 'json');
    }

    public function orderYes(){
        $this->checkLogin();
        $order_id = (int) $this->request->param('order_id');
        if (empty($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        $OrderModel = new OrderModel();
        if (!$order = $OrderModel->find($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->store_id != $this->store_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->member_miniapp_id != $this->appid) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->status != 2) {
            $this->result([], '400', '订单状态不正确', 'json');
        }
        $taocan = TaocanModel::get($order->taocan_id);
        //发送模版消息
        $formId = $this->request->param('formId');
        $Miniapp = new MiniApp($this->appid);
        $user = UserModel::get($order->user_id);
        $Miniapp->sendTemplateMessage('AT0257', $user->open_id, $formId, '/pages/hotel/order/detail?id=' . $order_id, [
            $order_id,
            '完成入住',
            "您订购的酒店已经完成入住，评价". $taocan->taocan_name."有机会活动红包",
        ]);

        $OrderModel->save(['status' =>8], ['order_id' => $order_id]);
        $this->result([], '200', '操作成功', 'json');

    }

    //审核订单
    public function orderAudit() {
        $this->checkLogin();
        $order_id = (int) $this->request->param('order_id');
        if (empty($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        $OrderModel = new OrderModel();
        if (!$order = $OrderModel->find($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->store_id != $this->store_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->member_miniapp_id != $this->appid) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->status != 1) {
            $this->result([], '400', '订单状态不正确', 'json');
        }


        $taocan = TaocanModel::get($order->taocan_id);
        //发送模版消息
        $formId = $this->request->param('formId');
        $Miniapp = new MiniApp($this->appid);
        $user = UserModel::get($order->user_id);
        $result = $Miniapp->sendTemplateMessage('AT0011', $user->open_id, $formId, '/pages/hotel/order/detail?id=' . $order_id, [
            $taocan->taocan_name,
            date('Y-m-d H:i:s', $order->add_time),
            round($order->total_price/100,2),
            $order_id,
            $order->play_time,
            $taocan->taocan_tel
        ]);
        $OrderModel->save(['status' => 2], ['order_id' => $order_id]);
        $this->result($result, '200', '操作成功', 'json');
    }

    /**
     * 订单详情；
     * */
    public function orderDetail() {
        $this->checkLogin();
        $order_id = (int) $this->request->param('order_id');
        $OrderModel = new OrderModel();
        if(!$order = $OrderModel->find($order_id)){
            $this->result([],'400','不存在订单','json');
        }
        if($order->member_miniapp_id != $this->appid){
            $this->result([],'400','不存在订单','json');
        }
        if($order->store_id != $this->store_id){
            $this->result([],'400','不存在订单','json');
        }
        $TaocanModel = new TaocanModel();
        $taocan = $TaocanModel->find($order->taocan_id);
        $PackageModel = new PackageModel();
        $package = $PackageModel->find($order->package_id);
        $data = [
            'order_id' => $order->order_id,
            'taocan_id' => $order->taocan_id,
            'store_id' => $order->store_id,
            'package_id' => $order->package_id,
            'package_name' => empty($package) ? '' : $package->title,
            'taocan_name' => empty($taocan) ? '' : $taocan->taocan_name,
            'taocan_tel' =>empty($taocan) ? '' : $taocan->taocan_tel,
            'package_num' => $order->package_num,
            'total_price' => sprintf("%.2f",$order->total_price/100),
            'need_pay' => sprintf("%.2f",$order->need_pay/100) ,
            'pay_money' => sprintf("%.2f", $order->pay_money/100),
            'pay_coupon' => $order->pay_coupon,
            'lijian' =>  sprintf("%.2f",$order->lijian/100),
            'pay_coupon_id' => $order->pay_coupon_id,
            'pay_time' => $order->pay_time,
            'name' => $order->name,
            'mobile' => $order->mobile,
            'idcard' => $order->idcard,
            'status' => $order->status,
            'play_time' => $order->play_time,
            'cancel_time' => $order->cancel_time,
            'cancel_type' => $order->cancel_type,
            'cancel_info' => $order->cancel_info,
            'cancel_type_mean' => $order->cancel_type == 1 ? '客户取消' : '商家拒接',
            'add_time'  => date("Y-m-d H:i:s",$order->add_time),
            'status_mean' => empty(config('dataattr.orderstatus')[$order->status]) ? '' : config('dataattr.orderstatus')[$order->status],
        ];
        $this->result($data,'200','数据初始化成功','json');
    }

    /**
     * 获取商户订单；
     * @papam  int type 0  全部 1 待确认 2 待入住 3 取消订单
     */
    public function order() {
        $this->checkLogin();
        $type = (int) $this->request->param('type');
        $keywords = $this->request->param('keywords');
        if (!empty($keywords)) {
            $where['mobile|idcard|name'] = ["LIKE", "%{$keywords}%"];
        }
        $where['store_id'] = $this->store_id;
        $where['member_miniapp_id'] = $this->appid;
        $OrderModel = new OrderModel();
        switch ($type) {
            case 1:
                $where['status'] = 1;
                break;
            case 2:
                $where['status'] = 2;
                break;
            case 3:
                $where['status'] = 3;
                break;
        }
       $data['totalNum'] = $OrderModel->where($where)->count();
        $list = $OrderModel->where($where)->order("order_id desc")->limit($this->limit_bg, $this->limit_num)->select();
        if (empty($list)) {
            $data['list'] = [];
            $this->result([], 200, '没有数据了', 'json');
        }
        $taocanIds = $packageIds = [];
        foreach ($list as $val) {
            $taocanIds[$val->taocan_id] = $val->taocan_id;
            $packageIds[$val->package_id] = $val->package_id;
        }
        $PackageModel = new PackageModel();
        $TaocanModel= new TaocanModel();
        $taocans = $TaocanModel->itemsByIds($taocanIds);
        $data['list'] = [];
        $packages = $PackageModel->itemsByIds($packageIds);
        foreach ($list as $val) {
            $data['list'][] = [
                'taocan_id' => $val->taocan_id,
                'package_id' => $val->package_id,
                'taocan_name' => empty($taocans[$val->taocan_id]) ? '' : $taocans[$val->taocan_id]->taocan_name,
                'package_name' => empty($packages[$val->package_id]) ? '' : $packages[$val->package_id]->title,
                'user_id' => $val->user_id,
                'order_id' => $val->order_id,
                'address'   => empty($taocans[$val->taocan_id]) ? '' : $taocans[$val->taocan_id]->address,
                'total_price' => sprintf("%.2f", $val->total_price / 100),
                'package_num' => $val->package_num,
                'name' => $val->name,
                'mobile' => $val->mobile,
                'status' => $val->status,
                'play_time' => $val->play_time,
                'status_mean' => empty(config('dataattr.orderstatus')[$val->status]) ? '' : config('dataattr.orderstatus')[$val->status],
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, '200', '数据初始化成功', 'json');
    }

    /*
     * 未处理订单数量;
     */

    public function countOrder() {
        $this->checkLogin();
        $where["store_id"] = $this->store_id;
        $where["member_miniapp_id"] = $this->appid;
        $OrderModel = new OrderModel();
        $where["status"] = 2;  //待入住
        $data["stayin"] = $OrderModel->where($where)->count();
        $where["status"] = 1; //待确认；
        $data['stayok'] = $OrderModel->where($where)->count();
        $where['status'] = 3;
        $data['statout'] = $OrderModel->where($where)->count();
        $this->result($data, 200, '数据初始化成功', 'json');
    }



    /**
     * 根据日期获取所有房源；价格 和  在线状态 使用统一接口；
     * @param date date 时间 default 当天
     */
    public function getPackage() {
        $this->checkLogin();
        $TaocanpackagepriceModel = new TaocanpackagepriceModel();
        $date = $this->request->param('date');
        $date = empty($date) ? date('Y-m-d', time()) : $date;
        $date = strtotime($date) ? date('Y-m-d', strtotime($date)) : date('Y-m-d', time());
        $taocan_id = (int) $this->request->param('taocan_id');
        $TaocanModel = new TaocanModel();
        if(!$taocan = $TaocanModel->find($taocan_id)){
            $this->result('','400','不存在套餐','json');
        }
        if($taocan->member_miniapp_id != $this->appid){
            $this->result('','400','不存在套餐','json');
        }
        if($taocan->store_id != $this->store_id){
            $this->result('','400','不存在套餐','json');
        }
        $list = $TaocanpackagepriceModel->backPrice($taocan_id, $this->appid, $date);
        $data['taocan_id'] = $taocan_id;
        $data['date'] = $date;
        $data['list'] = [];
        $data['bg_date'] = date("Y-m-d", time());
        if (empty($list)) {
            $this->result($data, '200', '数据初始化成功', 'json');
        }

        foreach ($list as $val) {
            $data['list'][] = [
                //当天价格
                'price_id' => $val['price_id'],
                'package_id' => $val['package_id'],
                'price' => sprintf("%.2f", $val['price']/100),
                //当天剩余房间数
                'surplus_num' => $val['surplus_num'],
                //该房源房间
                '_num_init' =>  $val['_num_init'],
                'package_num' => $val['package_num'],
                //当天是否上架
                'is_online' => $val['is_online'],
                'title'  => $val['title'],
                // 'area'  => $val->area,
                'photo' =>  IMG_URL . getImg($val['photo']),
                'is_cancel' => $val['surplus_num'],
                'is_changes' => $val['surplus_num'],
                'details' => $val['surplus_num'],
                'cancel' => $val['surplus_num'],
                'changes' => $val['surplus_num'],
                'especially' => $val['surplus_num'],
            ];
        }

        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, '200', '数据初始化成功', 'json');
    }




    public function setOnline() {
        $this->checkLogin();
        $package_id = (int) $this->request->param('package_id');
        $date = $this->request->param('date');
        $is_online = abs((int) $this->request->param('is_online'));
        $date = empty($date) ? date('Y-m-d', time()) : $date;
        $date = strtotime($date) ? date('Y-m-d', strtotime($date)) : date('Y-m-d', time());
        if ($date < date("Y-m-d", time())) {
            $this->result([], '400', '不可以设置过去的日期', 'json');
        }
        $TaocanpackagepriceModel = new TaocanpackagepriceModel();
        $PackageModel = new PackageModel();
        if (!$package= $PackageModel->find($package_id)) {
            $this->result([], '400', '不存在套餐', 'json');
        }
        if ($package->store_id != $this->store_id) {
            $this->result([], '400', '不存在该房间', 'json');
        }
        $where['day'] = $date;
        $where['package_id'] = $package_id;
        $where['store_id'] = $this->store_id;
        $where['member_miniapp_id'] = $this->appid;
        $price = $TaocanpackagepriceModel->where($where)->find();
        if ($price) {
            $updatedata['is_online'] = $is_online == 1 ? 1 : 0;
            $TaocanpackagepriceModel->save($updatedata, ['price_id' => $price->price_id]);
        } else {
            $savedata = [
                'is_online' => $is_online == 1 ? 1 : 0,
                'price' => $package->price,
                'day' => $date,
                'taocan_id' => $package->taocan_id,
                'package_id' => $package_id,
                'store_id'  => $this->store_id,
                'member_miniapp_id' => $this->appid,
            ];
            $TaocanpackagepriceModel->save($savedata);
        }
        $this->result([], '200', '设置成功', 'json');
    }

    public function addPackage(){
        $this->checkLogin();
        $data['member_miniapp_id'] = $this->appid;
        $data['taocan_id'] = (int) $this->request->param('taocan_id');
        if(empty($data['taocan_id'])){
           $this->result([],400,'不存在套餐刷新重试','json');
        }
        $PackageModel = new PackageModel();
        $TaocanModel = new TaocanModel();
        if(!$taocan = $TaocanModel->find($data['taocan_id'])){
           $this->result([],400,'不存在套餐','json');
        }
        if($taocan->member_miniapp_id != $this->appid){
           $this->result([],400,'不存在套餐','json');
        }
      if($taocan->store_id != $this->store_id){
         $this->result([],400,'不存在套餐','json');
      }
      $data['store_id'] = $this->store_id;
        $data['title'] = $this->request->param('title');
        if(empty($data['title'])){
           $this->result([],400,'标题不能为空','json');
        }
        $data['photo'] = $this->request->param('photo');
        if(empty($data['photo'])){
           $this->result([],400,'列表缩略图不能为空','json');
        }
        $data['price'] = ((int) $this->request->param('price'))*100;
        if(empty($data['price'])){
           $this->result([],400,'日常价格不能为空','json');
        }
        $taocanpruice = $TaocanModel->where(['member_miniapp_id'=>$this->appid,'taocan_id'=>$data['taocan_id']])->order("price asc")->find();
//            添加房屋是修改最民宿最低起始价如果新房源的价格是最小的则修改民宿其实价格；
        if($data['price'] <= $taocanpruice->price || empty($taocanpruice->price)){
            $package_data['price'] = $data['price'];
            $TaocanModel->save($package_data,['taocan_id'=>$data['taocan_id']]);
//                如果大于民宿最小价格则判断当前其实价格是不是最小价格如果不是则修改防止设最小价格后修改
        }else if($taocan->price  < $taocanpruice->price && $taocanpruice->price < $data['price'] ){
            $package_data['price'] = $taocanpruice->price;
            $TaocanModel->save($package_data,['taocan_id'=>$data['taocan_id']]);
        }
        $data['is_cancel'] = (int) $this->request->param('is_cancel');
        $data['is_changes'] = (int) $this->request->param('is_changes');
        $data['details'] = $this->request->param('details');
        $data['especially'] = $this->request->param('especially');
        if(empty($data['especially'])){
           $this->result([],400,'特别说明不能为空','json');
        }
        $data['cancel'] = $this->request->param('cancel');
        if(empty($data['cancel'])){
           $this->result([],400,'退订规则不能为空','json');
        }
        $data['changes'] = $this->request->param('changes');
        if(empty($data['changes'])){
           $this->result([],400,'改签政策不能为空','json');
        }
        $data['day_num'] = $this->request->param('day_num');
        if(empty($data['day_num'])){
           $this->result([],400,'单日最大预定数不能为空','json');
        }
        $PackageModel->save($data);
        $return['taocan_id'] = $data['taocan_id'];
        $this->result($return,'200','数据操作成功','json');
    }


    public function setPrice() {
        $this->checkLogin();
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);
        $data = $data['data'];
        $date = $this->request->param('date');
        $date = empty($date) ? date('Y-m-d', time()) : $date;
        $date = strtotime($date) ? date('Y-m-d', strtotime($date)) : date('Y-m-d', time());
        $savedata = $packageIds = $taocan = $pries = $updatedata = [];
        $taocan_id = 0;
        foreach ($data as $key => $val) {
            $packageIds[$key] = (int) $key;
        }
        $PackageModel  = new PackageModel();
        $packages = $PackageModel->itemsByIds($packageIds);
        foreach ($packages as $val) {
            if ($val->store_id != $this->store_id) {
                $this->result([], 400, '有不存在的套餐', 'json');
            }
            $pries[$val->package_id] = $val->price;
            $taocan[$val->taocan_id] = 1;
            $taocan_id = $val->taocan_id;
        }
        if (sizeof($taocan) > 1 || sizeof($packages) !== sizeof($pries)) {
            $this->result([], 400, '有不存在的套餐', 'json');
        }
        $TaocanpackagepriceModel= new TaocanpackagepriceModel();
        $price_where['day'] = $date;
        $price_where['package_id'] = ["IN", $packageIds];
        $list = $TaocanpackagepriceModel->where($price_where)->select();
        $lists = [];
        foreach ($list as $val) {
            $lists[$val->package_id] = $val;
        }
        foreach ($data as $key => $val) {
            if (empty($lists[$key])) {
                $savedata[] = [
                    'price' => empty($val) ? 0 : (int) $val * 100,
                    'taocan_id' => $taocan_id,
                    'package_id' => (int) $key,
                    'store_id' => $this->store_id,
                    'day' => $date,
                    'is_online' => 1,
                    'member_miniapp_id' => $this->appid,
                ];
            } else {
                $updatedata[] = [
                    'price_id' => $lists[$key]->price_id,
                    'price' => empty($val) ? 0 : (int) $val * 100,
                ];
            }
        }
        if (!empty($updatedata)) {
            $TaocanpackagepriceModel->saveAll($updatedata);
        }
        if (!empty($savedata)) {
            $TaocanpackagepriceModel->saveAll($savedata);
        }
        $this->result([], '200', '设置成功', 'json');
    }



    public function getComment() {
        $this->checkLogin();
        $type = (int) $this->request->param('type');
        $where['store_id'] = $this->store_id;
        switch ($type) {
            case 1:
                $where['score'] = [">=", 40];
                break;
            case 2:
                $where['score'] = [['>=', 25], ['<=', 35]];
                break;
            case 3:
                $where['score'] = ['<=', 20];
        }

        $CommentModel = new CommentModel();
        $data['totalNum'] = $CommentModel->where($where)->count();
        $list = $CommentModel->where($where)->order("comment_id desc")->limit($this->limit_bg, $this->limit_num)->select();
        if (empty($list)) {
            $data['list'] = [];
            $this->result($data, 200, '没有数据了', 'json');
        }
        $photoIds = $userIds = $roomIds = $hotelIds = [];
        foreach ($list as $val) {
            $photoIds[$val->comment_id] = $val->comment_id;
            $userIds[$val->user_id] = $val->user_id;
        }
        $CommentphotoModel = new CommentphotoModel();
        $UserModel = new UserModel();
        $users = $UserModel->itemsByIds($userIds);
        $photoIds = empty($photoIds) ? 0 : $photoIds;
        $photo_where['comment_id'] = ["IN", $photoIds];
        $photo = $CommentphotoModel->where($photo_where)->select();
        $photos = [];
        foreach ($photo as $val) {
            $photos[$val->comment_id][] = IMG_URL . getImg($val->photo);
        }
        $data['list'] = [];
        foreach ($list as $val) {
            $data['list'] [] = [
                'comment_id' => $val->comment_id,
                'user_id' => $val->user_id,
                'user_nick_name' => empty($users[$val->user_id]) ? '' : $users[$val->user_id]->nick_name,
                'user_face' => empty($users[$val->user_id]) ? '' : $users[$val->user_id]->face,
                'score' => round($val->score / 10, 1),
                'content' => $val->content,
                'content_time' => date("Y-m-d", $val->add_time),
                'reply' => $val->reply,
                'reply_time' => empty($val->reply_time) ? '' : date("Y-m-d", $val->reply_time),
                'photos' => empty($photos[$val->comment_id]) ? [] : $photos[$val->comment_id],
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, '200', '数据初始化成功', 'json');
    }

    /*
     * 回复评论
     * @param comment string 回复内容
     * */

    public function replay() {
        $this->checkLogin();
        $commenr_id = (int) $this->request->param('comment_id');
        $CommentModel = new CommentModel();
        if (!$comment = $CommentModel->find($commenr_id)) {
            $this->result([], '400', '不存在该评论', 'json');
        }
        if ($comment->store_id != $this->store_id) {
            $this->result([], '400', '不存在该评论', 'json');
        }
        $comment = (string) $this->request->param('comment');
        if (empty($comment)) {
            $this->result([], 400, '请回复内容', 'json');
        }
        $data['reply'] = $comment;
        $data['reply_time'] = $this->request->time();
        $data['reply_ip'] = $this->request->ip();
        $CommentModel->save($data, ['comment_id' => $commenr_id]);
        $this->result([], '200', '操作成功', 'json');
    }

    /*
     * 结算统计
     * */

    public function count() {
        $this->checkLogin();
        $date = $this->request->param('date');
        $getNum = $this->request->param('getNum');
        //为了保证日期的正取格式 需要先转换时间戳 在转回来 保证单日期 必须有0前缀
        $date = empty($date) ? date("Y-m-d", time()) : $date;
        if (!$datetime = strtotime($date)) {
            $this->result([], '400', '时间格式不对', 'json');
        }
        $date = date('Y-m', $datetime);
        $data['date'] = $date;
        $data['end_date'] = date("Y-m", time());

        $OrderModel= new OrderModel();
        $where['status'] = 8;
        $where['store_id'] = $this->store_id;
        $where["FROM_UNIXTIME(`add_time`, '%Y-%m')"] = $date;
        if (!empty($getNum)) {
            $sum = $OrderModel->field("sum(need_pay) as num")->where($where)->find();
            $data['sum'] = empty($sum->num) ? '0.00' : sprintf("%.2f", $sum->num / 100);
        }
        $PackageModel = new PackageModel();
        $packagesIds = $data['list'] = [];
        $OderList = $OrderModel->where($where)->order('order_id desc')->limit($this->limit_bg, $this->limit_num)->select();
        if (empty($OderList)) {
            $this->result($data, '200', '数据初始化成功', 'json');
        }
        foreach ($OderList as $val) {
            $packagesIds[$val->package_id] = $val->package_id;
        }
        $packages = $PackageModel->itemsByIds($packagesIds);
        foreach ($OderList as $val) {
            $data['list'] [] = [
                'order_id' => $val->order_id,
                'package_photo' => empty($packages[$val->package_id]) ? '' : IMG_URL . getImg($packages[$val->package_id]->photo),
                'room_title' => empty($packages[$val->package_id]) ? '' : $packages[$val->package_id]->title,
                'package_num' => $val->package_num,
                'need_price' => sprintf("%.2f", $val->need_pay / 100),
                'add_time' => date("Y-m-d", $val->add_time),
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    /*
     * 获取酒店信息；
     */


    public function getTaocan(){
        $this->checkLogin();
        $where['store_id'] = $this->store_id;
        $where['is_delete'] = 0;
        $TaocanModel  = new TaocanModel();
        $list = $TaocanModel->where($where)->order('orderby desc')->limit('0,20')->select();
        $data = [];
        foreach ($list as $val){
            $data['taocan_id'] = $val->taocan_id;
            $data['taocan_name'] = $val->taocan_name;
            $data['list'][] = [
                'taocan_id'  => $val->taocan_id,
                'taocan_name' => $val->taocan_name,
                'is_online'  => $val->is_online,
            ];
        }
        $this->result($data,'200','数据初始化成功','json');
    }

    /*
     *  获取商家套餐
     * */

    public function addTaocan(){
        $this->checkLogin();
        $data['member_miniapp_id'] = $this->appid;
        $data['city_id'] = (int) $this->request->param('city_id');
        if(empty($data['city_id'])){
            $this->result([],'400','所在城市不能为空','json');
        }
        $CityModel = new CityModel();
        if(!$city = $CityModel->find($data['city_id'])){
            $this->result([],'400','不存在城市','json');
        }
        if($city->member_miniapp_id != $this->appid){
            $this->result([],'400','不存在城市','json');
        }
       $data['store_id'] = $this->store_id;
        $data['type'] = (int) $this->request->param('type');
        if(empty($data['type'])){
            $this->result([],'400','请选择套餐类型','json');
        }
        $data['store_id'] = $this->store_id;
        $data['taocan_name'] = $this->request->param('taocan_name');
        if(empty($data['taocan_name'])){
            $this->result([],'400','套餐名称不能为空','json');
        }
        $data['taocan_tel'] = $this->request->param('taocan_tel');
        if(empty($data['taocan_tel'])){
            $this->result([],'400','负责人电话不能为空','json');
        }
        $data['photo'] = $this->request->param('photo');
        if(empty($data['photo'])){
            $this->result([],'400','图片不能为空','json');
        }
        $data['banner'] = $this->request->param('banner');
        if(empty($data['banner'])){
            $this->result([],'400','banner不能为空','json');
        }


        $data['address'] = $this->request->param('address');
        if(empty($data['address'])){
            $this->result([],'400','地址不能为空','json');
        }
        $data['is_hot'] = (int) $this->request->param('is_hot');
        $data2['restrict'] = $this->request->param('restrict');
        if(empty($data2['restrict'])){
            $this->result([],'400','预定限制不能为空','json');
        }
        $data2['usetime'] = $this->request->param('usetime');
        if(empty($data2['usetime'])){
            $this->result([],'400','使用时间不能为空','json');
        }
        $data2['service'] = $this->request->param('service');
        if(empty($data2['service'])){
            $this->result([],'400','服务提示不能为空','json');
        }
        $data2['method'] = $this->request->param('method');
        if(empty($data2['method'])){
            $this->result([],'400','使用方法不能为空','json');
        }
        $data2['other'] = $this->request->param('other');
        if(empty($data2['other'])){
            $this->result([],'400','其他提示不能为空','json');
        }
        $data2['plus'] = $this->request->param('plus');
        if(empty($data2['plus'])){
            $this->result([],'400','加购不能为空','json');
        }
        $TaocanModel = new TaocanModel();
        $TaocanModel->save($data);
        $TaocanDetailModel = new TaocanDetailModel();
        $data2['taocan_id'] = $TaocanModel->taocan_id;
        $TaocanDetailModel->save($data2);
        $photo = file_get_contents("php://input");
        $photo = json_decode($photo, true);
        $photos = $photo['photos'];
        $_photos = [];
        foreach ($photos as $key => $val) {
            $_photos[] = [
                'taocan_id' => $TaocanModel->taocan_id,
                'member_miniapp_id' => $this->appid,
                'photo' => getImg($val),
                'orderby' => abs($key - 100),
            ];
        }
        $TaocanphotoModel = new TaocanphotoModel();
        $TaocanphotoModel->saveAll($_photos);
        $data['taocan_id'] = $TaocanModel->taocan_id;
        $this->result($data,'200','发不成功','json');
    }



    public function taocanOnline(){
        $this->checkLogin();
        $taocan_id = (int) $this->request->param('taocan_id');
        $TaocanModel = new TaocanModel();
        if(!$taocan = $TaocanModel->find($taocan_id)){
            $this->result('','400','不存在套餐','json');
        }
        if($taocan->member_miniapp_id != $this->appid){
            $this->result('','400','不存在套餐','json');
        }
        if($taocan->store_id != $this->store_id){
            $this->result('','400','不存在套餐','json');
        }
        $data['is_online'] = 1;
        if($taocan->is_online == 1){
            $data['is_online'] = 0;
        }
        $TaocanModel->save($data,['taocan_id'=>$taocan_id]);
        $this->result([],'200','操作成功','json');
    }
    /*
  * 商户登录
  * @param string mobile 商户手机号；
  * @param string password 密码；
  * @return json  code:加密后的字符串
  */

    public function mangelogin() {
        $password = $this->request->param('password');
        $mobile = $this->request->param('mobile');
        $TaocanmanageModel = new TaocanmanageModel();
        if (!$manage = $TaocanmanageModel->where(['mobile' => $mobile])->find()) {
            $this->result([], 400, '不存在管理员', 'json');
        }
        if ($manage->is_lock == 1) {
            $this->result([], 400, '您的账号已锁定', 'json');
        }
        if ($manage->password != md5($password)) {
            $this->result([], 400, "账号或密码错误", 'json');
        }
        $TaocanmanageModel->save([
            'last_ip' => $this->request->ip(),
            'last_time' => $this->request->time(),
        ], ['manage_id' => $manage->manage_id]);
        $code = authcode($manage->manage_id . '|' . $manage->member_miniapp_id . '|miniapphotelmanage|' . $_SERVER['REQUEST_TIME']);
        $data['code'] = $code;
        $this->result($data, 200, '登录成功', 'json');
    }


    protected function checkLogin() {
        $code = $this->request->param('code');
        $codeInfo = authcode($code, 'DECODE');
        if (empty($codeInfo)) {
            $this->result([], 400, '请登录后再操作', 'json');
        }
        $codeArray = explode('|', $codeInfo);

        if ($codeArray[2] != 'miniapphotelmanage' || $codeArray[1] != $this->appid) {
            $this->result([], 400, '请登录后再操作', 'json');
        }
        $manager_id = (int) $codeArray[0];
        if (empty($manager_id)) {
            $this->result([], 400, '请登录后再操作', 'json');
        }
        $TaocanmanageModel = new TaocanmanageModel();
        if (!$user = $TaocanmanageModel->find($manager_id)) {
            $this->result([], 400, '不存在用户', 'json');
        }
        if ($user->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在用户', 'json');
        }

        $this->store_id = $user->store_id;
        $this->manage = $user;
    }

}
