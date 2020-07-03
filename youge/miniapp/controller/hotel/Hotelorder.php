<?php
namespace app\miniapp\controller\hotel;
use app\common\model\hotel\HotelModel;
use app\common\model\hotel\RoomModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
use app\common\model\hotel\HotelorderModel;
class Hotelorder extends Common {
    
    public function index() {
        $where = $search = [];
        $search['user_id'] = (int)$this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = $search['user_id'];
        }
        $search['hotel_id'] = (int)$this->request->param('hotel_id');
        if (!empty($search['hotel_id'])) {
            $where['hotel_id'] = $search['hotel_id'];
        }
        $search['date'] = $this->request->param('date');
        if (!empty($search['date'])) {
            $where['FROM_UNIXTIME(add_time, "%Y-%m-%d")'] =  $search['date'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $HotelorderModel = new HotelorderModel();
        $count = $HotelorderModel->where($where)->count();
        $list = $HotelorderModel->where($where)->order(['order_id'=>'desc'])->paginate(10, $count);
        $userIds = $hotelIds = $roomIds = [];
        foreach ( $list as $val){
            $userIds[$val->user_id] = $val->user_id;
            $hotelIds[$val->hotel_id] = $val->hotel_id;
            $roomIds[$val->room_id] = $val->room_id;
        }
        $UserModel = new UserModel();
        $RoomModel = new RoomModel();
        $HotelModel = new HotelModel();
        $this->assign('user',$UserModel->itemsByIds($userIds));
        $this->assign('room',$RoomModel->itemsByIds($roomIds));
        $this->assign('hotel',$HotelModel->itemsByIds($hotelIds));
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
        $HotelorderModel = new HotelorderModel();
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

   
}