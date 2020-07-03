<?php

namespace app\api\controller\minsu;

use app\api\controller\Common;
use app\common\model\minsu\CommentModel;
use app\common\model\minsu\CommentphotoModel;
use app\common\model\minsu\MinsudetailModel;
use app\common\model\minsu\MinsumanageModel;
use app\common\model\minsu\MinsuModel;
use app\common\model\minsu\MinsuorderModel;
use app\common\model\minsu\MinsuphotoModel;
use app\common\model\minsu\MinsupriceModel;
use app\common\model\minsu\RoomModel;
use app\common\model\member\ManagelogModel;
use app\common\model\user\CouponModel;
use app\common\model\user\UserModel;
use app\common\library\MiniApp;
use think\Validate;

//民宿商户的所有接口都放在这里
class Manage extends Common {

    protected $minsu_id = 0;
    //商户管理员信息
    protected $manage = [];

    public function cancel() {
        $this->checklogin();
        $id = (int) $this->request->param('id');
        if (empty($id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        $MinsuorderModel = new MinsuorderModel();
        if (!$order = $MinsuorderModel->find($id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->minsu_id != $this->minsu_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->status != 0 && $order->status != 1) {
            $this->result([], '400', '订单状态不可取消', 'json');
        }
        $cancel_info = $this->request->param('cancel_info');
        if (empty($cancel_info)) {
            $this->result([], '400', '取消理由不能为空', 'json');
        }
        if ($MinsuorderModel->save(['status' => 3, 'cancel_type' => 2, 'cancel_time' => $this->request->time(), 'cancel_info' => $cancel_info], ['order_id' => $id])) {
            if (!empty($order->pay_coupon_id)) {
                $CouponModel = new CouponModel();
                $CouponModel->save(['is_can' => 0], ['coupon_id' => $order->pay_coupon_id]);
            }
            $MinsupriceModel = new MinsupriceModel();
            $MinsupriceModel->removeBooked($order->room_id, $order->check_in_time, $order->leave_time, $order->room_num);

            if ($order->pay_money > 0) {
                $MinsuorderModel->refund($id, $order->pay_money);
            }

            $minsu = MinsuModel::get($this->minsu_id);
            //发送模版消息
            $formId = $this->request->param('formId');
            $Miniapp = new MiniApp($this->appid);
            $user = UserModel::get($order->user_id);
            $Miniapp->sendTemplateMessage('AT0012', $user->open_id, $formId, '/pages/minsu/order/detail?id=' . $id, [
                $minsu->minsu_name,
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
        $MinsuorderModel = new MinsuorderModel();
        if (!$order = $MinsuorderModel->find($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->minsu_id != $this->minsu_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->member_miniapp_id != $this->appid) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->status != 2) {
            $this->result([], '400', '订单状态不正确', 'json');
        }


        $minsu = MinsuModel::get($this->minsu_id);
        //发送模版消息
        $formId = $this->request->param('formId');
        $Miniapp = new MiniApp($this->appid);
        $user = UserModel::get($order->user_id);
        $Miniapp->sendTemplateMessage('AT0257', $user->open_id, $formId, '/pages/minsu/order/detail?id=' . $order_id, [
            $order_id,
            '完成入住',
            "您订购的民宿已经完成入住，评价". $minsu->minsu_name."有机会活动红包",
        ]);

        $MinsuorderModel->save(['status' =>8], ['order_id' => $order_id]);
        $this->result([], '200', '操作成功', 'json');

    }

    //审核订单
    public function orderAudit() {
        $this->checkLogin();
        $order_id = (int) $this->request->param('order_id');
        if (empty($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        $MinsuorderModel = new MinsuorderModel();
        if (!$order = $MinsuorderModel->find($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->minsu_id != $this->minsu_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->member_miniapp_id != $this->appid) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->status != 1) {
            $this->result([], '400', '订单状态不正确', 'json');
        }


        $minsu = MinsuModel::get($this->minsu_id);
        //发送模版消息
        $formId = $this->request->param('formId');
        $Miniapp = new MiniApp($this->appid);
        $user = UserModel::get($order->user_id);
        $result = $Miniapp->sendTemplateMessage('AT0011', $user->open_id, $formId, '/pages/minsu/order/detail?id=' . $order_id, [
            $minsu->minsu_name,
            date('Y-m-d H:i:s', $order->add_time),
            round($order->total_price/100,2),
            $order_id,
            $order->check_in_time,
            $order->leave_time,
            $minsu->minsu_tel
        ]);


        $MinsuorderModel->save(['status' => 2], ['order_id' => $order_id]);
        $this->result($result, '200', '操作成功', 'json');
    }

    /**
     * 订单详情；
     * */
    public function orderDetail() {
        $this->checkLogin();
        $order_id = (int) $this->request->param('order_id');
        if (empty($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        $MinsuorderModel = new MinsuorderModel();
        if (!$order = $MinsuorderModel->find($order_id)) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->minsu_id != $this->minsu_id) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        if ($order->member_miniapp_id != $this->appid) {
            $this->result([], '400', '不存在该订单', 'json');
        }
        $minsu = MinsuModel::find($order->minsu_id);
        $room = RoomModel::find($order->room_id);
        $config = config('dataattr.orderstatus');
        $bedtype = config('dataattr.minsubedtype');
        $data = [
            'order_id' => $order->order_id,
            'total_price' => sprintf("%.2f", $order->total_price / 100),
            'need_pay' => sprintf("%.2f", ($order->need_pay) / 100),
            'lijian' => sprintf("%.2f", ($order->lijian) / 100),
            'pay_coupon' => sprintf("%.2f", ($order->pay_coupon) / 100),
            'minsu_name' => empty($minsu) ? '' : $minsu->minsu_name,
            'address' => empty($minsu) ? '' : $minsu->address,
            'lat' => empty($minsu) ? '' : $minsu->lat,
            'lng' => empty($minsu) ? '' : $minsu->lng,
            'room_title' => empty($room) ? '' : $room->title,
            'room_num' => $order->room_num,
            'check_in_time' => $order->check_in_time,
            'leave_time' => $order->leave_time,
            'day' => (int) ((strtotime($order->leave_time) - strtotime($order->check_in_time)) / 86400),
            'is_wifi' => $room->is_wifi,
            'is_breakfast' => $room->is_breakfast,
            'bed_type' => empty($bedtype[$room->bed_type]) ? '' : $bedtype[$room->bed_type],
            'name' => $order->name,
            'mobile' => $order->mobile,
            'idcard' => $order->idcard,
            'minsu_id' => $minsu->minsu_id,
            'tel' => empty($minsu) ? '' : $minsu->minsu_tel,
            'add_time' => date("Y-m-d H:i:s", $order->add_time),
            'status' => $order->status,
            'is_comment' => $order->is_comment,
            'status_mean' => empty($config[$order->status]) ? '' : $config[$order->status],
            'cancel_type' => $order->cancel_type,
            'cancel_info' => $order->cancel_info,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    /**
     * 获取商户订单；
     * @papam  int type 0  全部 1 待确认 2 待入住 3 取消订单
     */
    public function order() {
        $this->checkLogin();
        $type = (int) $this->request->param('type');
        $mobile = $this->request->param('mobile');
        if (!empty($mobile)) {
            $where['mobile|idcard|name'] = ["LIKE", "%{$mobile}%"];
        }
        $where['minsu_id'] = $this->minsu_id;
        $where['member_miniapp_id'] = $this->appid;
        $MinsuorderModel = new MinsuorderModel();
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
        $data['totalNum'] = $MinsuorderModel->where($where)->count();
        $list = $MinsuorderModel->where($where)->order("order_id desc")->limit($this->limit_bg, $this->limit_num)->select();
        if (empty($list)) {
            $data['list'] = [];
            $this->result([], 200, '没有数据了', 'json');
        }
        $RoomIds = $rooms = [];
        foreach ($list as $val) {
            $RoomIds[$val->room_id] = $val->room_id;
        }
        $RoomModel = new RoomModel();
        $data['list'] = [];
        $rooms = $RoomModel->itemsByIds($RoomIds);
        foreach ($list as $val) {
            $data['list'][] = [
                'minsu_id' => $val->minsu_id,
                'room_id' => $val->room_id,
                'user_id' => $val->user_id,
                'order_id' => $val->order_id,
                'room_title' => empty($rooms[$val->room_id]) ? '' : $rooms[$val->room_id]->title,
                'address' => empty($minsus[$val->minsu_id]) ? '' : $minsus[$val->minsu_id]->address,
                'total_price' => sprintf("%.2f", $val->total_price / 100),
                'check_in_time' => $val->check_in_time,
                'leave_time' => $val->leave_time,
                'room_num' => $val->room_num,
                'person_num' => $val->person_num,
                'name' => $val->name,
                'mobile' => $val->mobile,
                'status' => $val->status,
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
        $where["minsu_id"] = $this->minsu_id;
        $where["member_miniapp_id"] = $this->appid;
        $MinsuorderModel = new MinsuorderModel();
        $where["status"] = 2;  //待入住
        $data["stayin"] = $MinsuorderModel->where($where)->count();
        $where["status"] = 1; //待确认；
        $data['stayok'] = $MinsuorderModel->where($where)->count();
        $where['status'] = 3;
        $data['statout'] = $MinsuorderModel->where($where)->count();
        $this->result($data, 200, '数据初始化成功', 'json');
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
        $MinsumanageModel = new MinsumanageModel();
        if (!$manage = $MinsumanageModel->where(['mobile' => $mobile])->find()) {
            $this->result([], 400, '不存在管理员', 'json');
        }
        if ($manage->is_lock == 1) {
            $this->result([], 400, '您的账号已锁定', 'json');
        }
        $ManagelogModel = new ManagelogModel();
        $log_where['manage_id'] = $manage->manage_id;
        $log_where['day'] = strtotime(date('Y-m-d', time()));
        if ($log = $ManagelogModel->where($log_where)->find()) {
            if ($log->login_num >= 5 && $log->add_time + 3600 > time()) {
                $times = $log->add_time + 3600 - time();
                $hour = floor($times / 3600);
                $minute = floor(($times - 3600 * $hour) / 60);
                $second = floor((($times - 3600 * $hour) - 60 * $minute) % 60);
                $result = $hour . ':' . $minute . ':' . $second;
                $this->result([], 400, "请您{$result}分后重试", 'json');
            }
        };
        if ($manage->password != md5($password)) {
            $error_data = [];
            $_login_num = empty($log) ? 0 : $log->login_num; //已登录次数；
            $login_num = $_login_num >= 5 ? 0 : $_login_num;
            $error_data['day'] = strtotime(date('Y-m-d', time()));
            $error_data['manage_id'] = $manage->manage_id;
            $error_data['login_num'] = 1;
            if ($_login_num == 0) {
                $ManagelogModel->save($error_data);
            } else {
                $error_data['login_num'] = $login_num + 1;
                $error_data['add_time'] = $this->request->time();
                $ManagelogModel->save($error_data, ['log_id' => $log->log_id]);
            }
            $frequency = 5 - $login_num;
            $this->result([], 400, "密码错误你还可以输入{$frequency}次机会 错误后将锁定您的账号60分钟", 'json');
        }
        if($manage->member_miniapp_id != $this->appid){
            $this->result('',400,'用户名或密码错误','json');
        }
        $MinsumanageModel->save([
            'last_ip' => $this->request->ip(),
            'last_time' => $this->request->time(),
        ], ['manage_id' => $manage->manage_id]);
        $code = authcode($manage->manage_id . '|' . $manage->member_miniapp_id . '|miniappminsumanage|' . $_SERVER['REQUEST_TIME']);
        $data['code'] = $code;
        $this->result($data, 200, '登录成功', 'json');
    }

    /**
     * 根据日期获取所有房源；价格 和  在线状态 使用统一接口；
     * @param date date 时间 default 当天
     */
    public function getRooms() {
        $this->checkLogin();
        $MinsupriceModel = new MinsupriceModel();
        $date = $this->request->param('date');
        $date = empty($date) ? date('Y-m-d', time()) : $date;
        $date = strtotime($date) ? date('Y-m-d', strtotime($date)) : date('Y-m-d', time());
        $list = $MinsupriceModel->backPrice($this->minsu_id, $this->appid, $date);
        if (empty($list)) {
            $this->result([], '200', '数据初始化成功', 'json');
        }
        $data['list'] = [];
        foreach ($list as $val) {
            $data['list'][] = [
                'minsuprice_id' => $val['minsuprice_id'],
                'room_id' => $val['room_id'],
                'price' => sprintf("%.2f", $val['price'] / 100),
                'room_num' => $val['room_num'],
                'surplus_num' => $val['surplus_num'],
                'room_num_init' => $val['room_num_init'],
                'is_online' => $val['is_online'],
                'title' => $val['title'],
                'area' => $val['area'],
                'photo' => IMG_URL . getImg($val['photo']),
                'bed_type' => $val['bed_type'],
                'bed_type_mean' => empty(config("dataattr.minsubedtype")[$val['bed_type']]) ? '' : config("dataattr.minsubedtype")[$val['bed_type']],
                'bed_width' => $val['bed_width'],
                'bed_logn' => $val['bed_logn'],
                'bed_num' => $val['bed_num'],
                'is_wifi' => $val['is_wifi'],
            ];
        }
        $data['date'] = $date;
        $data['bg_date'] = date("Y-m-d", time());
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, '200', '数据初始化成功', 'json');
    }

    /**
     *  根据日期 设置 房间上下架状态不可设置 只能设置10天以内
     * @param array[
     *       'key' => [  key为room_id
     *            'minsuprice_id'  => 0, 有则传 没有则不传 由上一个接口返回
     *            'is_online'       => 1, 是否上线
     *        ]
     * ]
     * @param date date 要设置的日期
     */
    public function setOnline() {
        $this->checkLogin();
        $room_id = (int) $this->request->param('room_id');
        $date = $this->request->param('date');
        $is_online = abs((int) $this->request->param('is_online'));
        $date = empty($date) ? date('Y-m-d', time()) : $date;
        $date = strtotime($date) ? date('Y-m-d', strtotime($date)) : date('Y-m-d', time());
        if ($date < date("Y-m-d", time())) {
            $this->result([], '400', '不可以设置过去的日期', 'json');
        }
        $MinsupriceModel = new MinsupriceModel();
        $RoomModel = new RoomModel();
        if (!$room = $RoomModel->find($room_id)) {
            $this->result([], '400', '不存在该房间', 'json');
        }
        if ($room->minsu_id != $this->minsu_id) {
            $this->result([], '400', '不存在该房间', 'json');
        }
        $where['day'] = $date;
        $where['room_id'] = $room_id;
        $where['member_miniapp_id'] = $this->appid;
        $price = $MinsupriceModel->where($where)->find();
        if ($price) {
            $updatedata['is_online'] = $is_online == 1 ? 1 : 0;
            $MinsupriceModel->save($updatedata, ['price_id' => $price->price_id]);
        } else {
            $savedata = [
                'is_online' => $is_online == 1 ? 1 : 0,
                'price' => $room->price,
                'day' => $date,
                'minsu_id' => $this->minsu_id,
                'room_id' => $room_id,
                'member_miniapp_id' => $this->appid,
            ];
            $MinsupriceModel->save($savedata);
        }
        $this->result([], '200', '设置成功', 'json');
    }

    /**
     * 根据日期设置 房间价格根据日期 设置 房间上下架状态不可设置 只能设置10天以内
     * /**
     *  根据日期 设置 房间上下架状态不可设置 只能设置10天以内
     * @param json[
     *       key => 房源id
     *       价格 =》  price;
     * ]
     *
     * @param date date 要设置的日期
     */
    public function setPrice() {
        $this->checkLogin();
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);
        $data = $data['data'];
        $date = $this->request->param('date');
        $date = empty($date) ? date('Y-m-d', time()) : $date;
        $date = strtotime($date) ? date('Y-m-d', strtotime($date)) : date('Y-m-d', time());
        $savedata = $roomIds = $minsu = $pries = $updatedata = [];
        $minsu_id = 0;
        foreach ($data as $key => $val) {
            $roomIds[$key] = (int) $key;
        }
        $RoomModel = new RoomModel();
        $rooms = $RoomModel->itemsByIds($roomIds);
        foreach ($rooms as $val) {
            if ($val->minsu_id != $this->minsu_id) {
                $this->result([], 400, '有不存在的房间1', 'json');
            }
            $pries[$val->room_id] = $val->price;
            $minsu[$val->minsu_id] = 1;
            $minsu_id = $val->minsu_id;
        }
        if (sizeof($minsu) > 1 || sizeof($rooms) !== sizeof($pries)) {
            $this->result([], 400, '有不存在的房间2', 'json');
        }
        $MinsupriceModel = new MinsupriceModel();
        $price_where['day'] = $date;
        $price_where['room_id'] = ["IN", $roomIds];
        $list = $MinsupriceModel->where($price_where)->select();
        $lists = [];
        foreach ($list as $val) {
            $lists[$val->room_id] = $val;
        }
        foreach ($data as $key => $val) {
            if (empty($lists[$key])) {
                $savedata[] = [
                    'price' => empty($val) ? 0 : (int) $val * 100,
                    'minsu_id' => $minsu_id,
                    'room_id' => (int) $key,
                    'day' => $date,
                    'is_online' => 1,
                    'member_miniapp_id' => $this->manage['member_miniapp_id'],
                ];
            } else {
                $updatedata[] = [
                    'price_id' => $lists[$key]->price_id,
                    'price' => empty($val) ? 0 : (int) $val * 100,
                ];
            }
        }

        $MinsupriceModel = new MinsupriceModel();
        if (!empty($updatedata)) {
            $MinsupriceModel->saveAll($updatedata);
        }
        if (!empty($savedata)) {
            $MinsupriceModel->saveAll($savedata);
        }
        $this->result([], '200', '设置成功', 'json');
    }

    /*
     * 发布房源；和修改房源
     */

    public function setRoom() {
        $this->checkLogin();
        $data = [];
        $MinsuModel = new MinsuModel();
        $minsu = $MinsuModel->find($this->minsu_id);
        $data['minsu_id'] = $this->minsu_id;
        $data['city_id'] = $minsu->city_id;
        $data['member_miniapp_id'] = $minsu->member_miniapp_id;
        $data['title'] = $this->request->param('title');
        if (empty($data['title'])) {
            $this->result([], 400, '标题不能为空', 'json');
        }
        $data['area'] = (int) $this->request->param('area');
        if (empty($data['area'])) {
            $this->result([], 400, '面积不能为空', 'json');
        }
        $data['photo'] = (string) $this->request->param('photo');
        if (empty($data['photo'])) {
            $this->result([], 400, '图片不能为空', 'json');
        }
        $data['price'] = ((int) $this->request->param('price')) * 100;
        if (empty($data['price'])) {
            $this->result([], 400, '日常价格不能为空', 'json');
        }
        $minsupruice = $MinsuModel->where(['member_miniapp_id' => $minsu->member_miniapp_id, 'minsu_id' => $data['minsu_id']])->order("price asc")->find();
//            添加房屋是修改最民宿最低起始价如果新房源的价格是最小的则修改民宿其实价格；
        if ($data['price'] <= $minsupruice->price || empty($minsupruice->price)) {
            $minsu_data['price'] = $data['price'];
            $MinsuModel->save($minsu_data, ['minsu_id' => $data['minsu_id']]);
//                如果大于民宿最小价格则判断当前其实价格是不是最小价格如果不是则修改防止设最小价格后修改
        } else if ($minsu->price < $minsupruice->price) {
            $minsu_data['price'] = $minsupruice->price;
            $MinsuModel->save($minsu_data, ['minsu_id' => $data['minsu_id']]);
        }
        $data['bed_type'] = (int) $this->request->param('bed_type');
        if (empty($data['bed_type'])) {
            $this->result([], 400, '床的类型不能为空', 'json');
        }
        $data['bed_width'] = $this->request->param('bed_width');
        if (empty($data['bed_width'])) {
            $this->result([], 400, '床宽不能为空', 'json');
        }
        $data['appropriate_num'] = $this->request->param('appropriate_num');
        if (empty($data['appropriate_num'])) {
            $this->result([], 400, '宜居人数不能为空', 'json');
        }
        $data['bed_logn'] = $this->request->param('bed_logn');
        if (empty($data['bed_logn'])) {
            $this->result([], 400, '床长不能为空', 'json');
        }
        $data['bed_num'] = (int) $this->request->param('bed_num');
        if (empty($data['bed_num'])) {
            $this->result([], 400, '床的数量不能为空', 'json');
        }
        $data['is_wifi'] = (int) $this->request->param('is_wifi');
        $data['is_breakfast'] = (int) $this->request->param('is_breakfast');

        $data['day_num'] = (int) $this->request->param('day_num');
        if (empty($data['day_num'])) {
            $this->result([], 400, '单日最大预定不能为空', 'json');
        }
        $RoomModel = new RoomModel();
        $RoomModel->save($data);
        $this->result([], 200, '发布成功', 'json');
    }

    /*
     * 设置民宿信息
     * */

    public function setminsu() {
        $this->checkLogin();
        $data = [];
        $data['minsu_name'] = $this->request->param('minsu_name');
        if (empty($data['minsu_name'])) {
            $this->result([], 400, '民宿名称不能为空', 'json');
        }
        $data['minsu_tel'] = $this->request->param('minsu_tel');
        if (empty($data['minsu_tel'])) {
            $this->result([], 400, '民宿电话不能为空', 'json');
        }
        $data['decoration_time'] = $this->request->param('decoration_time');
        if (empty($data['decoration_time'])) {
            $this->result([], 400, '最后装修时间不能为空', 'json');
        }
        $data['opening_time'] = $this->request->param('opening_time');
        if (empty($data['opening_time'])) {
            $this->result([], 400, '营业时间不能为空', 'json');
        }
        $data['photo'] = (string) $this->request->param('photo');
        if (empty($data['photo'])) {
            $this->result([], 400, '图片不能为空', 'json');
        }
        $data['banner'] = (string) $this->request->param('banner');
        if (empty($data['banner'])) {
            $this->result([], 400, 'banner图片不能为空', 'json');
        }
        $data['address'] = $this->request->param('address');
        if (empty($data['address'])) {
            $this->result([], 400, '地址不能为空', 'json');
        }
        $data2['describe'] = $this->request->param('describe');
        if (empty($data2['describe'])) {
            $this->result([], 400, '详情不能为空', 'json');
        }
        $data2['unsubscribe'] = $this->request->param('unsubscribe');
        if (empty($data2['unsubscribe'])) {
            $this->result([], 400, '退订规则不能为空', 'json');
        }
        $data2['check_otice'] = $this->request->param('check_otice');
        if (empty($data2['check_otice'])) {
            $this->result([], 400, '入住须知不能为空', 'json');
        }
        $MinsuModel = new MinsuModel();
        $MinsuModel->save($data, ['minsu_id' => $this->minsu_id]);
        $MinsudetailModel = new MinsudetailModel();
        if (!$detail = $MinsudetailModel->find($this->minsu_id)) {
            $data2['member_miniapp_id'] = $this->appid;
            $data2['minsu_id'] = $this->minsu_id;
            $MinsudetailModel->save($data2);
        } else {
            $MinsudetailModel->save($data2, ['minsu_id' => $this->minsu_id]);
        }
        $this->result([], 200, '操作成功', 'json');
    }

    /*
     * 设置民宿设施信息
     * */

    public function setminsuDetail() {
        $this->checkLogin();
        $data = [];
        $data2['is_wifi'] = (int) $this->request->param('is_wifi');
        $data2['is_water'] = $this->request->param('is_water');
        $data2['is_hairdrier'] = $this->request->param('is_hairdrier');
        $data2['is_airconditioner'] = $this->request->param('is_airconditioner');
        $data2['is_elevator'] = $this->request->param('is_elevator');
        $data2['is_fitnessroom'] = $this->request->param('is_fitnessroom');
        $data2['is_swimmingpool'] = $this->request->param('is_swimmingpool');
        $data2['is_sauna'] = $this->request->param('is_sauna');
        $data2['is_westernfood'] = $this->request->param('is_westernfood');
        $data2['is_chinesefood'] = $this->request->param('is_chinesefood');
        $data2['is_disability'] = $this->request->param('is_disability');
        $data2['is_smokeless'] = $this->request->param('is_smokeless');
        $data2['is_stop'] = $this->request->param('is_stop');
        $data2['is_cereal'] = $this->request->param('is_cereal');
        $data2['is_airportpickup'] = $this->request->param('is_airportpickup');
        $data2['is_station'] = $this->request->param('is_station');
        $data2['is_cabs'] = $this->request->param('is_cabs');
        $data2['is_luggage'] = $this->request->param('is_luggage');
        $data2['is_carrental'] = $this->request->param('is_carrental');
        $data2['is_disabled'] = $this->request->param('is_disabled');
        $data2['is_conference'] = $this->request->param('is_conference');
        $data2['is_express'] = $this->request->param('is_express');
        $data2['is_washclothes'] = $this->request->param('is_washclothes');
        $data2['is_merchant'] = $this->request->param('is_merchant');
        $data2['is_awaken'] = $this->request->param('is_awaken');
        $data2['is_deposit'] = $this->request->param('is_deposit');
        $data2['is_creditcard'] = $this->request->param('is_creditcard');
        $data2['is_reception'] = $this->request->param('is_reception');
        $data2['is_foreignguests'] = $this->request->param('is_foreignguests');
        $data2['is_spa'] = $this->request->param('is_spa');
        $data2['is_chess'] = $this->request->param('is_chess');
        $MinsudetailModel = new MinsudetailModel();
        if (!$detail = $MinsudetailModel->find($this->minsu_id)) {
            $data2['member_miniapp_id'] = $this->appid;
            $data2['minsu_id'] = $this->minsu_id;
            $MinsudetailModel->save($data2);
        } else {
            $MinsudetailModel->save($data2, ['minsu_id' => $this->minsu_id]);
        }
        $this->result([], '200', '数据初始化成功', 'json');
    }

    /*
     * 设置民宿民宿相册
     * */

    public function setminsuPhoto() {
        $this->checkLogin();
        $MinsuphotoModel = new MinsuphotoModel();
        $where['minsu_id'] = $this->minsu_id;
        $photo = file_get_contents("php://input");
        $photo = json_decode($photo, true);
        $photos = $photo['photos'];
        $MinsuphotoModel->where($where)->delete();
        $data = [];
        foreach ($photos as $key => $val) {
            $data[] = [
                'minsu_id' => $this->minsu_id,
                'member_miniapp_id' => $this->appid,
                'photo' => getImg($val),
                'orderby' => abs($key - 100),
            ];
        }
        $MinsuphotoModel->saveAll($data);
        $this->result([], '200', '操作成功', 'json');
    }

    /*
     * 获得民宿相册；
     */

    public function getminsuPhoto() {
        $this->checkLogin();
        $MinsuphotoModel = new MinsuphotoModel();
        $where['minsu_id'] = $this->minsu_id;
        $data['totalNum'] = $MinsuphotoModel->where($where)->count();
        $list = $MinsuphotoModel->where($where)->order("orderby desc")->select();
        $data['list'] = [];
        foreach ($list as $val) {
            $data['list'][] = [
                'photo_url' => IMG_URL . getImg($val->photo),
                'photo' => getImg($val->photo),
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    /*
     * 获取商户评论；
     */

    public function getComment() {
        $this->checkLogin();
        $minsu_id = $this->minsu_id;
        $type = (int) $this->request->param('type');
        $MinsuModel = new MinsuModel();
        if (!$minsu = $MinsuModel->find($minsu_id)) {
            $this->result([], 400, '不存在民宿', 'json');
        }
        if ($minsu->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在民宿', 'json');
        }
        $where['minsu_id'] = $minsu_id;
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
        $photoIds = $userIds = $roomIds = $minsuIds = [];
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
        if ($comment->minsu_id != $this->minsu_id) {
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

        $MinsuorderModel = new MinsuorderModel();
        $where['status'] = 8;
        $where['minsu_id'] = $this->minsu_id;
        $where["FROM_UNIXTIME(`add_time`, '%Y-%m')"] = $date;
        if (!empty($getNum)) {
            $sum = $MinsuorderModel->field("sum(need_pay) as num")->where($where)->find();
            $data['sum'] = empty($sum->num) ? '0.00' : sprintf("%.2f", $sum->num / 100);
        }
        $RoomModel = new RoomModel();
        $RoomIds = $data['list'] = [];
        $OderList = $MinsuorderModel->where($where)->order('order_id desc')->limit($this->limit_bg, $this->limit_num)->select();
        if (empty($OderList)) {
            $this->result($data, '200', '数据初始化成功', 'json');
        }
        foreach ($OderList as $val) {
            $RoomIds[$val->room_id] = $val->room_id;
        }
        $rooms = $RoomModel->itemsByIds($RoomIds);
        foreach ($OderList as $val) {
            $data['list'] [] = [
                'order_id' => $val->order_id,
                'room_photo' => empty($rooms[$val->room_id]) ? '' : IMG_URL . getImg($rooms[$val->room_id]->photo),
                'room_title' => empty($rooms[$val->room_id]) ? '' : $rooms[$val->room_id]->title,
                'room_num' => $val->room_num,
                'need_price' => sprintf("%.2f", $val->need_pay / 100),
                'add_time' => date("Y-m-d", $val->add_time),
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, 200, '数据初始化成功', 'json');
    }
    /*
     * 获取民宿信息；
     */
    public function getminsuDetail() {
        $this->checkLogin();
        $MinsuModel = new MinsuModel();
        if (!$minsu = $MinsuModel->find($this->minsu_id)) {
            $this->result([], '200', '数据初始化成功1', 'json');
        };
        $MinsudetailModel = new MinsudetailModel();
        $detail = $MinsudetailModel->find($this->minsu_id);
        $data = [
            'minsu_id' => $minsu->minsu_id,
            'city_id' => $minsu->city_id,
            'brand_id' => $minsu->brand_id,
            'minsu_name' => $minsu->minsu_name,
            'minsu_tel' => $minsu->minsu_tel,
            'decoration_time' => $minsu->decoration_time,
            'opening_time' => $minsu->opening_time,
            'photo_url' => IMG_URL . getImg($minsu->photo),
            'banner_url' => IMG_URL . getImg($minsu->banner),
            'photo' => getImg($minsu->photo),
            'banner' => getImg($minsu->banner),
            'address' => $minsu->address,
            'describe' => $detail->describe,
            'unsubscribe' => $detail->unsubscribe,
            'check_otice' => $detail->check_otice,
            'is_wifi' => $detail->is_wifi,
            'is_water' => $detail->is_water,
            'is_hairdrier' => $detail->is_hairdrier,
            'is_airconditioner' => $detail->is_airconditioner,
            'is_elevator' => $detail->is_elevator,
            'is_fitnessroom' => $detail->is_fitnessroom,
            'is_swimmingpool' => $detail->is_swimmingpool,
            'is_sauna' => $detail->is_sauna,
            'is_westernfood' => $detail->is_westernfood,
            'is_chinesefood' => $detail->is_chinesefood,
            'is_disability' => $detail->is_disability,
            'is_smokeless' => $detail->is_smokeless,
            'is_stop' => $detail->is_stop,
            'is_cereal' => $detail->is_cereal,
            'is_airportpickup' => $detail->is_airportpickup,
            'is_station' => $detail->is_station,
            'is_cabs' => $detail->is_cabs,
            'is_luggage' => $detail->is_luggage,
            'is_carrental' => $detail->is_carrental,
            'is_disabled' => $detail->is_disabled,
            'is_conference' => $detail->is_conference,
            'is_express' => $detail->is_express,
            'is_washclothes' => $detail->is_washclothes,
            'is_merchant' => $detail->is_merchant,
            'is_awaken' => $detail->is_awaken,
            'is_deposit' => $detail->is_deposit,
            'is_creditcard' => $detail->is_creditcard,
            'is_reception' => $detail->is_reception,
            'is_foreignguests' => $detail->is_foreignguests,
            'is_spa' => $detail->is_spa,
            'is_chess' => $detail->is_chess,
        ];
        $this->result($data, '200', '数据初始化成功', 'json');
    }

    protected function checkLogin() {
        $code = $this->request->param('code');
        $codeInfo = authcode($code, 'DECODE');
        if (empty($codeInfo)) {
            $this->result([], 400, '请登录后再操作', 'json');
        }
        $codeArray = explode('|', $codeInfo);
        if ($codeArray[2] != 'miniappminsumanage' || $codeArray[1] != $this->appid) {
            $this->result([], 400, '请登录后再操作', 'json');
        }
        $minsu_id = (int) $codeArray[0];
        if (empty($minsu_id)) {
            $this->result([], 400, '请登录后再操作', 'json');
        }
        $MinsumanageModel = new MinsumanageModel();
        if (!$user = $MinsumanageModel->find($minsu_id)) {
            $this->result([], 400, '不存在用户', 'json');
        }
        if ($user->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在用户', 'json');
        }
        $this->minsu_id = $user->minsu_id;
        $this->manage = $user;
    }

}
