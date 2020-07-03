<?php
namespace app\miniapp\controller\minsu;

use app\common\model\minsu\MinsuModel;
use app\common\model\minsu\MinsuorderModel;
use app\common\model\minsu\RoomModel;
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
        $OrderModel = new MinsuorderModel();

        $list = $OrderModel->field("FROM_UNIXTIME(add_time,'%Y-%m') as day , sum(need_pay) as num ,minsu_id ")->where($where)->order("day desc")->group('FROM_UNIXTIME(add_time,\'%Y-%m\'),minsu_id')->paginate(10);
        $minsuIds = [];
        foreach ($list as $val) {
                $minsuIds[$val->minsu_id] = $val->minsu_id;
        }
        $MinsuModel = new MinsuModel();
        $minsus = $MinsuModel->itemsByIds($minsuIds);
        $page = $list->render();
        $this->assign('page',$page);
        $this->assign('minsus',$minsus);
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
        $minsu_id = (int) $this->request->param('minsu_id');
        $MinsuModel = new MinsuModel();
        if(!$minsu = $MinsuModel->find($minsu_id)){
            $this->error('不存在酒店',null,101);
        }
        if($minsu->member_miniapp_id != $this->miniapp_id ){
            $this->error('不存在酒店',null,101);
        }
        $MinsuorderModel = new MinsuorderModel();
        $where['status'] = 8;
        $where['minsu_id'] = $minsu_id;
        $where["FROM_UNIXTIME(`add_time`, '%Y-%m')"] = $_date;
        $RoomModel = new RoomModel();
        $RoomIds =  [];
        $list = $MinsuorderModel->where($where)->order('order_id desc')->paginate(10);
        foreach ($list as $val){
            $RoomIds[$val->room_id] = $val->room_id;
        }
        $rooms = $RoomModel->itemsByIds($RoomIds);
        $this->assign('date',$_date);
        $this->assign('rooms',$rooms);
        $page = $list->render();
        $this->assign('page',$page);
        $this->assign('minsu_id' ,$minsu_id);
        $this->assign('list',$list);
        return $this->fetch();
    }


}