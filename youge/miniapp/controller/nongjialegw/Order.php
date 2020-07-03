<?php
namespace app\miniapp\controller\nongjialegw;
use app\common\model\nongjiale\OrderModel;
use app\common\model\nongjiale\PackageModel;
use app\common\model\nongjiale\RoomModel;
use app\common\model\taocan\TaocanModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
class Order extends Common {
    
    public function index()
    {
        $where = $search = [];
        $search['user_id'] = (int)$this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = $search['user_id'];
        }
        $search['date'] = $this->request->param('date');
        if (!empty($search['date'])) {
            $where['FROM_UNIXTIME(add_time, "%Y-%m-%d")'] = $search['date'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $HotelorderModel = new OrderModel();
        $count = $HotelorderModel->where($where)->count();
        $list = $HotelorderModel->where($where)->order(['order_id' => 'desc'])->paginate(10, $count);
        $userIds = $taocanIds = $packageIds = $roomIds = [];
        foreach ($list as $val) {
            if ($val->order_type == 1) {
                $taocanIds[$val->product_id] = $val->product_id;
            } elseif ($val->order_type == 2) {
                $roomIds[$val->product_id] = $val->product_id;
            }
            $packageIds[$val->package_id] = $val->package_id;

            $userIds[$val->user_id] = $val->user_id;
        }

        $UserModel = new UserModel();
        $RoomModel = new RoomModel();
        $TaocanModel = new \app\common\model\nongjiale\TaocanModel();
        $PackageModel = new PackageModel();
        $this->assign('user',$UserModel->itemsByIds($userIds));
        $this->assign('room',$RoomModel->itemsByIds($roomIds));
        $this->assign('taocan',$TaocanModel->itemsByIds($taocanIds));
        $this->assign("package",$PackageModel->itemsByIds($packageIds));
        $page = $list->render();
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
            $money = abs(((int) $this->request->param('money')) * 100);
            if($money > $order->pay_money){
                $this->error('不得超过该订单所支付的最大金额',null,101);
            }
            if(!empty($money)){
                if($order->status != 3 ){
                    $HotelorderModel->refund($order_id,$money);
                }
            }
            $data['status'] = 3;
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


    public function orderyes(){
        $order_id = (int) $this->request->param('order_id');
        $OrderModel = new OrderModel();
        if(!$order = $OrderModel->find($order_id)){
            $this->error('不存在订单',null,101);
        }
        if($order->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在订单',null,101);
        }
        if ($order->status != 1) {
            $this->result([], '400', '订单状态不正确', 'json');
        }
        $data['status'] = 2;
        $OrderModel->save($data,['order_id'=>$order_id]);
        $this->success('操作成功');
    }

    public function orderyok(){
        $order_id = (int) $this->request->param('order_id');
        $OrderModel = new OrderModel();
        if(!$order = $OrderModel->find($order_id)){
            $this->error('不存在订单',null,101);
        }
        if($order->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在订单',null,101);
        }
        if ($order->status != 2) {
            $this->result([], '400', '订单状态不正确', 'json');
        }
        $data['status'] = 8;
        $OrderModel->save($data,['order_id'=>$order_id]);
        $this->success('操作成功');
    }

   
}