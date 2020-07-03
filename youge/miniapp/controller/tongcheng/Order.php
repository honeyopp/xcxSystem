<?php
namespace app\miniapp\controller\tongcheng;
use app\common\model\tongcheng\InfoModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
use app\common\model\tongcheng\OrderModel;
class Order extends Common {

    public function index() {
        $where = $search = [];
        $search['user_id'] = (int)$this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = $search['user_id'];
        }
        $search['date'] = $this->request->param('date');
        if (!empty($search['date'])) {
            $where['FROM_UNIXTIME(add_time, "%Y-%m-%d")'] =  $search['date'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $HotelorderModel = new OrderModel();
        $count = $HotelorderModel->where($where)->count();
        $list = $HotelorderModel->where($where)->order(['order_id'=>'desc'])->paginate(10, $count);
        $userIds = $hotelIds = $infoIds = [];
        foreach ( $list as $val){
            $userIds[$val->user_id] = $val->user_id;
            $infoIds[$val->info_id] = $val->info_id;
        }
        $UserModel = new UserModel();
        $InfoModel = new InfoModel();
        $this->assign('user',$UserModel->itemsByIds($userIds));
        $this->assign('info',$InfoModel->itemsByIds($infoIds));
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
            $cancel_info = (string) $this->request->param('cancel_info');
            if(empty($cancel_info)) {
                $this->error('请输入备注信息', null, 101);
            }
            $money = abs(((float) $this->request->param('money')) * 100);
            if($money > $order->pay_money){
                $this->error('不得超过该订单所支付的最大金额',null,101);
            }
            if(!empty($money)){
                if($order->status != 2 ){
                    $HotelorderModel->refund($order_id,$money);
                }
            }
            $data['status'] = 2;
            $data['cancel_info'] = $cancel_info;
            $data['cancel_time'] = $this->request->time();
            $HotelorderModel->save($data,['order_id'=>$order_id]);
            $this->success('操作成功');
        }else{
            $this->assign('order',$order);
            $this->assign('order_id',$order_id);
            return $this->fetch();
        }
    }
}