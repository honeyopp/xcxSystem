<?php
namespace app\miniapp\controller\hotel;

use app\common\model\hotel\HotelModel;
use app\common\model\hotel\HotelorderModel;
use app\common\model\hotel\RoomModel;
use app\miniapp\controller\Common;

class Count extends Common
{

    /*
     * 结算统计；
     *
     */
    public function count(){
        $date   =   $this->request->param('date');
        $date   =   empty($date) ? date('Y-m',time()) : $date;
        $_date  =   strtotime($date) ?  date('Y-m',strtotime($date)) : date('Y-m',time());
        $where['status'] = 8;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['FROM_UNIXTIME(add_time,\'%Y-%m\')'] = $_date;
        $OrderModel = new HotelorderModel();

        $list = $OrderModel->field("FROM_UNIXTIME(add_time,'%Y-%m') as day , sum(need_pay) as num ,hotel_id ")->where($where)->order("day desc")->group('FROM_UNIXTIME(add_time,\'%Y-%m\'),hotel_id')->paginate(10);
        $hotelIds = [];
        foreach ($list as $val) {
                $hotelIds[$val->hotel_id] = $val->hotel_id;
        }
        $HotelModel = new HotelModel();
        $hotels = $HotelModel->itemsByIds($hotelIds);
        $page = $list->render();
        $this->assign('page',$page);
        $this->assign('hotels',$hotels);
        $this->assign('list', $list);
        $this->assign('date',$_date);
        return $this->fetch();
    }

    /*
     * 查询详情；
     */
    public function detail (){
        $date   =   $this->request->param('date');
        $date   =   empty($date) ? date('Y-m',time()) : $date;
        $_date  =   strtotime($date) ?  date('Y-m',strtotime($date)) : date('Y-m',time());
        $hotel_id = (int) $this->request->param('hotel_id');
        $HotelModel = new HotelModel();
        if(!$hotel = $HotelModel->find($hotel_id)){
            $this->error('不存在酒店',null,101);
        }
        if($hotel->member_miniapp_id != $this->miniapp_id ){
            $this->error('不存在酒店',null,101);
        }
        $HotelorderModel = new HotelorderModel();
        $where['status'] = 8;
        $where['hotel_id'] = $hotel_id;
        $where["FROM_UNIXTIME(`add_time`, '%Y-%m')"] = $_date;
        $RoomModel = new RoomModel();
        $RoomIds =  [];
        $list = $HotelorderModel->where($where)->order('order_id desc')->paginate(10);
        foreach ($list as $val){
            $RoomIds[$val->room_id] = $val->room_id;
        }
        $rooms = $RoomModel->itemsByIds($RoomIds);
        $this->assign('date',$_date);
        $this->assign('rooms',$rooms);
        $page = $list->render();
        $this->assign('page',$page);
        $this->assign('hotel_id' ,$hotel_id);
        $this->assign('list',$list);
        return $this->fetch();
    }


}