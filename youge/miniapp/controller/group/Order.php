<?php
namespace app\miniapp\controller\group;
use app\common\model\group\GoodsModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
use app\common\model\group\OrderModel;
class Order extends Common {
    
    public function index() {
        $where = $search = [];
        $search['user_id'] = $this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = array('LIKE', '%' . $search['user_id'] . '%');
        }
        $search['group_id'] = (int)$this->request->param('group_id');
        if (!empty($search['group_id'])) {
            $where['group_id'] = $search['group_id'];
        }
        $search['status'] = (int)$this->request->param('status');
        if (!empty($search['status'])) {
            $where['status'] = $search['status'];
        }
        $search['expire_time'] = $this->request->param('expire_time');
        if (!empty($search['expire_time'])) {
            $where['expire_time'] = array('LIKE', '%' . $search['expire_time'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = OrderModel::where($where)->count();
        $list = OrderModel::where($where)->order(['order_id'=>'desc'])->paginate(10, $count);
        $userIds = $goodsIds = [];
        foreach ($list as $val){
            $userIds[$val->user_id] = $val->user_id;
            $goodsIds[$val->goods_id] = $val->goods_id;
        }
        $UserModel = new UserModel();
        $GoodsModel = new GoodsModel();
        $page = $list->render();
        $this->assign('user',$UserModel->itemsByIds($userIds));
        $this->assign('goods',$GoodsModel->itemsByIds($goodsIds));
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }


    /*
   * 取消订单；
   */
    public function cancel(){
        $order_id = (int) $this->request->param('order_id');
        $HotelorderModel = new OrderModel();
        if(!$order = $HotelorderModel->find($order_id)){
            $this->error('不存在该订单',null,101);
        }
        if($order->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在该订单',null,101);
        }
        if($this->request->method() == "POST"){

            $cancel_type = (int) $this->request->param('cancel_type');
            if(empty($cancel_type)){
                $this->error('请选择取消方',null,101);
            }
            $cancel_info = (string) $this->request->param('cancel_info');
            if(empty($cancel_info)) {
                $this->error('请输入拒绝理由', null, 101);
            }
            $money = abs(((float) $this->request->param('money')) * 100);
            if($money > $order->pay_money){
                $this->error('不得超过该订单所支付的最大金额',null,101);
            }
            if(!empty($money)){
                if($order->status != 5 ){
                    $HotelorderModel->refund($order_id,$money);
                }
            }
            $data['status'] = 5;
            $data['cancel_info'] = $cancel_info;
            $data['cancel_type'] = $cancel_type;
            $data['cancel_time'] = $this->request->time();
            $HotelorderModel->save($data,['order_id'=>$order_id]);
            $this->success('操作成功');
        }else{
            $this->assign('order',$order);
            $this->assign('order_id',$order_id);
            return $this->fetch();
        }

    }


    /*
     * 发货
     */
    public function fahuo (){
        $order_id = (int) $this->request->param('order_id');
        $HotelorderModel = new OrderModel();
        if(!$order = $HotelorderModel->find($order_id)){
            $this->error('不存在该订单',null,101);
        }
        if($order->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在该订单',null,101);
        }
        if($this->request->method() == "POST"){
            $mail_number = (string) $this->request->param('mail_number');
            if(empty($mail_number)) {
                $this->error('请输入快递信息', null, 101);
            }
            $data['status'] = 3;
            $data['mail_number'] = $mail_number;
            $HotelorderModel->save($data,['order_id'=>$order_id]);
            $this->success('操作成功');
        }else{
            $this->assign('order',$order);
            $this->assign('order_id',$order_id);
            return $this->fetch();
        }

    }
   
}