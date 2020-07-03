<?php
namespace app\miniapp\controller\setting;
use app\miniapp\controller\Common;
use app\common\model\setting\ActivityModel;
class Activity extends Common {
    
    public function index() {
        $where = $search = [];
      $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ActivityModel::where($where)->count();
        $list = ActivityModel::where($where)->order(['activity_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    
    public function create() {
        $ActivityModel = new ActivityModel();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] =  $this->request->param('title');
            if(empty($data['title'])){
                $this->error('请输入活动标题',null,101);
            }
            $data['money'] = ((float) $this->request->param('money')) * 100;
            if(empty($data['money'])){
                $this->error('优惠券面额不能为空',null,101);
            }
            $data['need_money'] = ((float) $this->request->param('need_money')) * 100;
            if(empty($data['need_money'])){
                $data['need_money'] = $data['money'] * 2;
            }
            $data['expire_day'] = (int) $this->request->param('expire_day');
            if(empty($data['expire_day'])){
                $data['expire_day']  = 7;
            }
            $data['use_day'] = (int) $this->request->param('use_day');
            if(empty($data['use_day'])){
                $data['use_day'] = 0;
            }
            if($data['use_day'] >= $data['expire_day'] ){
                $data['use_day'] = 0;
            }
            $data['is_newuser'] = (int) $this->request->param('is_newuser');
            if(empty($data['is_newuser'])){
                $data['is_newuser'] = 0;
            }elseif($data['is_newuser'] == 1 ){
                $data['is_newuser'] = 1;
            }
            $data['is_online'] = (int) $this->request->param('is_online');
            if(empty($data['is_online'])){
                $data['is_online'] = 0;
            }elseif($data['is_online'] == 1 ){
                $data['is_online'] = 1;
            }
            if($data['is_online'] == 1){
                $num = $ActivityModel->where(['member_miniapp_id'=>$this->miniapp_id,'is_online'=>1])->count();
                if($num >= 5){
                    $this->error('同时最多开启5个优惠券 请关闭一些红包',null,101);
                }
            }
            $data['num'] = (int) $this->request->param('num');
            if(empty($data['num'])){
                $this->error('请输入优惠券数量',null,101);
            }
            $data['bg_date'] = $this->request->param('bg_date');  
            if(empty($data['bg_date'])){
                $this->error('请输入开始时间',null,101);
            }
            $data['end_date'] = $this->request->param('end_date');  
            if(empty($data['end_date'])){
                $this->error('请输入结束时间',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $ActivityModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $activity_id = (int)$this->request->param('activity_id');
         $ActivityModel = new ActivityModel();
         if(!$detail = $ActivityModel->get($activity_id)){
             $this->error('不存在该活动',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
             $this->error('不存在该活动',null,101);
         }

         if ($this->request->method() == 'POST') {
            $data = [];
             $data['title'] =  $this->request->param('title');
             if(empty($data['title'])){
                 $this->error('请输入活动标题',null,101);
             }
             $data['money'] = ((float) $this->request->param('money')) * 100;
             if(empty($data['money'])){
                 $this->error('优惠券面额不能为空',null,101);
             }
             $data['need_money'] = ((float) $this->request->param('need_money')) * 100;
             if(empty($data['need_money'])){
                 $data['need_money'] = $data['money'] * 2;
             }
             $data['expire_day'] = (int) $this->request->param('expire_day');
             if(empty($data['expire_day'])){
                 $data['expire_day']  = 7;
             }
             $data['use_day'] = (int) $this->request->param('use_day');
             if(empty($data['use_day'])){
                 $data['use_day'] = 0;
             }
             if($data['use_day'] >= $data['expire_day'] ){
                 $data['use_day'] = 0;
             }
             $data['is_newuser'] = (int) $this->request->param('is_newuser');
             if(empty($data['is_newuser'])){
                 $data['is_newuser'] = 0;
             }elseif($data['is_newuser'] == 1 ){
                 $data['is_newuser'] = 1;
             }
             $data['is_online'] = (int) $this->request->param('is_online');
             if(empty($data['is_online'])){
                 $data['is_online'] = 0;
             }elseif($data['is_online'] == 1 ){
                 $data['is_online'] = 1;
             }
             if($data['is_online'] == 1){
                 $num = $ActivityModel->where(['member_miniapp_id'=>$this->miniapp_id,'is_online'=>1])->count();
                 if($num >= 5){
                     $this->error('同时最多开启5个优惠券 请关闭一些红包',null,101);
                 }
             }
             $data['num'] = (int) $this->request->param('num');
             if(empty($data['num'])){
                 $this->error('请输入优惠券数量',null,101);
             }
             $data['bg_date'] = $this->request->param('bg_date');
             if(empty($data['bg_date'])){
                 $this->error('请输入开始时间',null,101);
             }
             $data['end_date'] = $this->request->param('end_date');
             if(empty($data['end_date'])){
                 $this->error('请输入结束时间',null,101);
             }
             $data['orderby'] = (int) $this->request->param('orderby');
             if(empty($data['orderby'])){
                 $this->error('排序不能为空',null,101);
             }

            
            $ActivityModel = new ActivityModel();
            $ActivityModel->save($data,['activity_id'=>$activity_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        if($this->request->method() == 'POST'){
             $activity_id = $_POST['activity_id'];
        }else{
            $activity_id = $this->request->param('activity_id');
        }
        $data = [];
        if (is_array($activity_id)) {
            foreach ($activity_id as $k => $val) {
                $activity_id[$k] = (int) $val;
            }
            $data = $activity_id;
        } else {
            $data[] = $activity_id;
        }
        if (!empty($data)) {
            $ActivityModel = new ActivityModel();
            $ActivityModel->where(array('activity_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}