<?php
namespace app\miniapp\controller\nongjialegw;

use app\common\model\hotel\HotelModel;
use app\common\model\hotel\HotelorderModel;
use app\common\model\nongjiale\OrderModel;
use app\common\model\nongjiale\PackageModel;
use app\common\model\nongjiale\RoomModel;
use app\common\model\nongjiale\TaocanModel;
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
        $OrderModel = new OrderModel();

        $list = $OrderModel->field("FROM_UNIXTIME(add_time,'%Y-%m') as day , sum(need_pay) as num ,product_id ,order_type")->where($where)->order("day desc")->group('FROM_UNIXTIME(add_time,\'%Y-%m\'),product_id,order_type')->paginate(10);
        $taocanIds = $roomIds = [];
        foreach ($list as $val) {
            if ($val->order_type == 1) {
                $taocanIds[$val->product_id] = $val->product_id;
            } elseif ($val->order_type == 2) {
                $roomIds[$val->product_id] = $val->product_id;
            }
        }
       $TaocanModel = new TaocanModel();
        $taocans = $TaocanModel->itemsByIds($taocanIds);
        $RoomModel = new RoomModel();
        $rooms = $RoomModel->itemsByIds($roomIds);
        $page = $list->render();
        $this->assign('page',$page);
        $this->assign('taocans',$taocans);
        $this->assign('rooms',$rooms);
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
        $taocan_id = (int) $this->request->param('product_id');
        $TaocanModel = new TaocanModel();
        if(!$hotel = $TaocanModel->find($taocan_id)){
            $this->error('不存在酒店',null,101);
        }
        if($hotel->member_miniapp_id != $this->miniapp_id ){
            $this->error('不存在酒店',null,101);
        }
        $HotelorderModel = new OrderModel();
        $where['status'] = 8;
        $where['order_type'] = 1;
        $where['product_id'] = $taocan_id;
        $where["FROM_UNIXTIME(`add_time`, '%Y-%m')"] = $_date;
        $RoomModel = new PackageModel();
        $RoomIds =  [];
        $list = $HotelorderModel->where($where)->order('order_id desc')->paginate(10);
        foreach ($list as $val){
            $RoomIds[$val->package_id] = $val->package_id;
        }
        $rooms = $RoomModel->itemsByIds($RoomIds);
        $this->assign('date',$_date);
        $this->assign('rooms',$rooms);
        $page = $list->render();
        $this->assign('page',$page);
        $this->assign('product_id' ,$taocan_id);
        $this->assign('list',$list);
        return $this->fetch();
    }


}