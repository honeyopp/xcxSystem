<?php
 namespace app\miniapp\controller\nongjialegw;
 use app\common\model\count\CountModel;
 use app\common\model\nongjiale\OrderModel;
 use app\common\model\user\UserModel;
 use app\miniapp\controller\Common;

 class Report extends Common{


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
         $search['hotel_id'] = (int) $this->request->param('hotel_id');
         if(!empty($search['hotel_id'])){
            $where['hotel_id'] = $search['hotel_id'];
         }
         $countModel = new CountModel();
         $date =  $countModel->getDate();
         $search['end_date'] = $date['EndDate'];
         $search['bg_date'] = $date['BingDate'];
         $where['member_miniapp_id'] = $this->miniapp_id;
         $where['add_time'] = array('between',array(strtotime($date['BingDate']),strtotime($date['EndDate']) + 86400));
         $fangdong = OrderModel::field("FROM_UNIXTIME(add_time,'%Y-%m-%d') as day , count(*) as num  ")->where($where)->group('FROM_UNIXTIME(add_time,\'%Y-%m-%d\')')->select();
         $data = $countModel->checkDate($date['day'],$fangdong);
         $this->assign('data',$data);
         $this->assign('search',$search);
         return $this->fetch();
     }
 }