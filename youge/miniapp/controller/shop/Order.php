<?php

namespace app\miniapp\controller\shop;

use app\common\model\shop\GoodsModel;
use app\common\model\shop\SkuModel;
use app\common\model\shop\TypeModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
use app\common\model\shop\OrderModel;

class Order extends Common
{

    public function index()
    {
        $where = $search = [];
        $search['user_id'] = $this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = array('LIKE', '%' . $search['user_id'] . '%');
        }
        $search['group_id'] = (int)$this->request->param('group_id');
        if (!empty($search['group_id'])) {
            $where['group_id'] = $search['group_id'];
        }
        $search['date'] = $this->request->param('date');
        if (!empty($search['date'])) {
          $where['FROM_UNIXTIME(add_time,"%Y-%m-%d")'] = $search['date'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = OrderModel::where($where)->count();
        $list = OrderModel::where($where)->order(['order_id' => 'desc'])->paginate(10, $count);
        $userIds = $goodsIds = [];
        foreach ($list as $val) {
            $userIds[$val->user_id] = $val->user_id;
        }
        $UserModel = new UserModel();
        $page = $list->render();
        $this->assign('user', $UserModel->itemsByIds($userIds));
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }


    /*
   * 取消订单；
   */
    public function cancel()
    {
        $order_id = (int)$this->request->param('order_id');
        $HotelorderModel = new OrderModel();
        if (!$order = $HotelorderModel->find($order_id)) {
            $this->error('不存在该订单1', null, 101);
        }
        if ($order->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该订单', null, 101);
        }
        if ($this->request->method() == "POST") {

            $cancel_type = (int)$this->request->param('cancel_type');
            if (empty($cancel_type)) {
                $this->error('请选择取消方', null, 101);
            }
            $cancel_info = (string)$this->request->param('cancel_info');
            if (empty($cancel_info)) {
                $this->error('请输入拒绝理由', null, 101);
            }
            $money = abs(((float) $this->request->param('money')) * 100);
            if ($money > $order->pay_money) {
                $this->error('不得超过该订单所支付的最大金额', null, 101);
            }
            if (!empty($money)) {
                //不等于4的时候才可以退款 保证只能退款一次
               if ($order->status != 4) {
                   $HotelorderModel->refund($order_id, $money);
               }
            }
            $data['status'] = 4;
            $data['cancel_info'] = $cancel_info;
            $data['cancel_type'] = $cancel_type;
            $data['cancel_time'] = $this->request->time();
            $HotelorderModel->save($data, ['order_id' => $order_id]);
            $this->success('操作成功');
        } else {
            $this->assign('order', $order);
            $this->assign('order_id', $order_id);
            return $this->fetch();
        }

    }

    public function fahuo()
    {
        $order_id = (int)$this->request->param('order_id');
        $HotelorderModel = new OrderModel();
        if (!$order = $HotelorderModel->find($order_id)) {
            $this->error('不存在该订单', null, 101);
        }
        if ($order->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该订单', null, 101);
        }
        if ($this->request->method() == "POST") {
            if($order->status <1 || $order->status >2){
                $this->error('不可发货订单',null,101);
            }
            $data['mail_number'] = (string) $this->request->param('mail_number');
            if(empty($data['mail_number'])){
                $this->error('请输入快递信息',null,101);
            }
            $data['status'] = 2;
            $HotelorderModel->save($data, ['order_id' => $order_id]);
            $this->success('操作成功');
        } else {
            $this->assign('order', $order);
            $this->assign('order_id', $order_id);
            return $this->fetch();
        }

    }


    /*
     * 查看商品列表；
     */
    public function goodslist()
    {
        $order_id = (int)$this->request->param('order_id');
        $OrderModel = new OrderModel();
        if (!$order = $OrderModel->find($order_id)) {
            $this->error('不存在订单', null, 101);
        }
        if ($order->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在订单', null, 101);
        }

        $sku = SkuModel::where(['order_id' => $order_id])->select();
        $goodsIds = $typeIds = [];
        foreach ($sku as $val) {
            $goodsIds[$val->goods_id] = $val->goods_id;
            $typeIds[$val->type_id] = $val->type_id;
        }
        $TypeModel = new TypeModel();
        $type = $TypeModel->itemsByIds($typeIds);
        $GoodsModel = new GoodsModel();
        $goods = $GoodsModel->itemsByIds($goodsIds);
        $this->assign('sku', $sku);
        $this->assign('type', $type);
        $this->assign('goods', $goods);
        return $this->fetch();
    }
}