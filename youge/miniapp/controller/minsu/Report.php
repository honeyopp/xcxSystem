<?php
 namespace app\miniapp\controller\minsu;
 use app\common\model\count\CountModel;
 use app\common\model\minsu\MinsuModel;
 use app\common\model\minsu\MinsuorderModel;
 use app\common\model\user\UserModel;
 use app\miniapp\controller\Common;

 class Report extends Common{


     public function store(){
         $search = [];
         $countModel = new CountModel();
         $date =  $countModel->getDate();
         $search['end_date'] = $date['EndDate'];
         $search['bg_date'] = $date['BingDate'];
         $where['member_miniapp_id'] = $this->miniapp_id;
         $where['add_time'] = array('between',array(strtotime($date['BingDate']),strtotime($date['EndDate']) + 86400));
         $fangdong = MinsuModel::field("FROM_UNIXTIME(add_time,'%Y-%m-%d') as day , count(*) as num  ")->where($where)->group('FROM_UNIXTIME(add_time,\'%Y-%m-%d\')')->select();
         $data = $countModel->checkDate($date['day'],$fangdong);
         $this->assign('data',$data);
         $this->assign('search',$search);
         return $this->fetch();
     }
     public function user(){
         $search = [];
         $countModel = new CountModel();
         $date =  $countModel->getDate();
         $search['end_date'] = $date['EndDate'];
         $search['bg_date'] = $date['BingDate'];
         $where['member_miniapp_id'] = $this->miniapp_id;
         $where['add_time'] = array('between',array(strtotime($date['BingDate']),strtotime($date['EndDate']) + 86400));
         $fangdong = UserModel::field("FROM_UNIXTIME(add_time,'%Y-%m-%d') as day , count(*) as num  ")->where($where)->group('FROM_UNIXTIME(add_time,\'%Y-%m-%d\')')->select();
         $data = $countModel->checkDate($date['day'],$fangdong);
         $this->assign('data',$data);
         $this->assign('search',$search);
         return $this->fetch();
     }

     public function order(){
         $search = [];
         $search['minsu_id'] = (int) $this->request->param('minsu_id');
         if(!empty($search['minsu_id'])){
            $where['minsu_id'] = $search['minsu_id'];
         }
         $countModel = new CountModel();
         $date =  $countModel->getDate();
         $search['end_date'] = $date['EndDate'];
         $search['bg_date'] = $date['BingDate'];
         $where['member_miniapp_id'] = $this->miniapp_id;
         $where['add_time'] = array('between',array(strtotime($date['BingDate']),strtotime($date['EndDate']) + 86400));
         $fangdong = MinsuorderModel::field("FROM_UNIXTIME(add_time,'%Y-%m-%d') as day , count(*) as num  ")->where($where)->group('FROM_UNIXTIME(add_time,\'%Y-%m-%d\')')->select();
         $data = $countModel->checkDate($date['day'],$fangdong);
         $this->assign('data',$data);
         $this->assign('search',$search);
         return $this->fetch();
     }
 }