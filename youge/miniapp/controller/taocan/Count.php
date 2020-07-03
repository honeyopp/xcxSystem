<?php
namespace app\miniapp\controller\taocan;

use app\common\model\taocan\TaocanModel;
use app\common\model\taocan\OrderModel;
use app\common\model\taocan\packageModel;
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

        $list = $OrderModel->field("FROM_UNIXTIME(add_time,'%Y-%m') as day , sum(need_pay) as num ,taocan_id ")->where($where)->order("day desc")->group('FROM_UNIXTIME(add_time,\'%Y-%m\'),taocan_id')->paginate(10);
        $taocanIds = [];
        foreach ($list as $val) {
                $taocanIds[$val->taocan_id] = $val->taocan_id;
        }
        $TaocanModel = new TaocanModel();
        $taocans = $TaocanModel->itemsByIds($taocanIds);
        $page = $list->render();
        $this->assign('page',$page);
        $this->assign('taocans',$taocans);
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
        $taocan_id = (int) $this->request->param('taocan_id');
        $TaocanModel = new TaocanModel();
        if(!$taocan = $TaocanModel->find($taocan_id)){
            $this->error('不存在酒店',null,101);
        }
        if($taocan->member_miniapp_id != $this->miniapp_id ){
            $this->error('不存在酒店',null,101);
        }
        $OrderModel = new OrderModel();
        $where['status'] = 8;
        $where['taocan_id'] = $taocan_id;
        $where["FROM_UNIXTIME(`add_time`, '%Y-%m')"] = $_date;
        $packageModel = new packageModel();
        $packageIds =  [];
        $list = $OrderModel->where($where)->order('order_id desc')->paginate(10);
        foreach ($list as $val){
            $packageIds[$val->package_id] = $val->package_id;
        }
        $packages = $packageModel->itemsByIds($packageIds);
        $this->assign('date',$_date);
        $this->assign('packages',$packages);
        $page = $list->render();
        $this->assign('page',$page);
        $this->assign('taocan_id' ,$taocan_id);
        $this->assign('list',$list);
        return $this->fetch();
    }


}