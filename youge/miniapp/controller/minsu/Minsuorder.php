<?php
namespace app\miniapp\controller\minsu;
use app\common\model\minsu\MinsuModel;
use app\common\model\minsu\RoomModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
use app\common\model\minsu\MinsuorderModel;
class Minsuorder extends Common {
    
    public function index() {
        $where = $search = [];
        $search['user_id'] = (int)$this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = $search['user_id'];
        }
        $search['minsu_id'] = (int)$this->request->param('minsu_id');
        if (!empty($search['minsu_id'])) {
            $where['minsu_id'] = $search['minsu_id'];
        }
        $search['date'] = $this->request->param('date');
        if (!empty($search['date'])) {
            $where['FROM_UNIXTIME(add_time, "%Y-%m-%d")'] =  $search['date'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $MinsuorderModel = new MinsuorderModel();
        $count = $MinsuorderModel->where($where)->count();
        $list = $MinsuorderModel->where($where)->order(['order_id'=>'desc'])->paginate(10, $count);
        $userIds = $minsuIds = $roomIds = [];
        foreach ( $list as $val){
            $userIds[$val->user_id] = $val->user_id;
            $minsuIds[$val->minsu_id] = $val->minsu_id;
            $roomIds[$val->room_id] = $val->room_id;
        }
        $UserModel = new UserModel();
        $RoomModel = new RoomModel();
        $minsuModel = new minsuModel();
        $this->assign('user',$UserModel->itemsByIds($userIds));
        $this->assign('room',$RoomModel->itemsByIds($roomIds));
        $this->assign('minsu',$minsuModel->itemsByIds($minsuIds));
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
        $MinsuorderModel = new MinsuorderModel();
        if(!$order = $MinsuorderModel->find($order_id)){
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
                    $MinsuorderModel->refund($order_id,$money);
                }
            }
            $data['status'] = 3;
            $data['cancel_info'] = $cancel_info;
            $data['cancel_type'] = $cancel_type;
            $data['cancel_time'] = $this->request->time();
            $MinsuorderModel->save($data,['order_id'=>$order_id]);
            $this->success('操作成功');
        }else{
            $this->assign('order',$order);
            $this->assign('order_id',$order_id);
            return $this->fetch();
        }

    }

   
}