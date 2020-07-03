<?php
namespace app\miniapp\controller\ktv;
use app\common\model\ktv\RoomphotoModel;
use app\miniapp\controller\Common;
use app\common\model\ktv\RoomModel;
class Room extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = RoomModel::where($where)->count();
        $list = RoomModel::where($where)->order(['room_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    
    public function create() {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('列表图片不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['num'] = (int) $this->request->param('num');
            $data['enroll_time'] = $this->request->param('enroll_time');  
            if(empty($data['enroll_time'])){
                $this->error('可预约时间段不能为空',null,101);
            }
            $data['enroll_length'] = $this->request->param('enroll_length');  
            if(empty($data['enroll_length'])){
                $this->error('可预约时长不能为空',null,101);
            }
            $data['enroll_date'] = $this->request->param('enroll_date');  
            if(empty($data['enroll_date'])){
                $this->error('可预约日期不能为空',null,101);
            }
            $data['price'] = ((int) $this->request->param('price'))*100;
            if(empty($data['price'])){
                $this->error('价格不能为空',null,101);
            }
            $imgs = empty($_POST['imgs']) ?  [] :   $_POST['imgs'];
            if(empty($imgs)){
                $this->error('请上详情图片',null,101);
            }
            $data['photo'] = $imgs[0];

            $RoomModel = new RoomModel();
            $RoomModel->save($data);
            $data2 = [];
            $room_id = $RoomModel->room_id;
            foreach ($imgs as $val){
                $data2[] = [
                    'member_miniapp_id' => $this->miniapp_id,
                    'room_id' => $room_id,
                    'photo'  => $val,
                ];
            }
            $RoomphotoModel = new RoomphotoModel();
            $RoomphotoModel->saveAll($data2);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $room_id = (int)$this->request->param('room_id');
         $RoomModel = new RoomModel();
         if(!$detail = $RoomModel->get($room_id)){
             $this->error('请选择要编辑的包厢管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在包厢管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('列表图片不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['num'] = (int) $this->request->param('num');
            $data['enroll_time'] = $this->request->param('enroll_time');  
            if(empty($data['enroll_time'])){
                $this->error('可预约时间段不能为空',null,101);
            }
            $data['enroll_length'] = $this->request->param('enroll_length');  
            if(empty($data['enroll_length'])){
                $this->error('可预约时长不能为空',null,101);
            }
            $data['enroll_date'] = $this->request->param('enroll_date');  
            if(empty($data['enroll_date'])){
                $this->error('可预约日期不能为空',null,101);
            }
            $data['price'] = ((int) $this->request->param('price'))*100;
            if(empty($data['price'])){
                $this->error('价格不能为空',null,101);
            }

             $imgs = empty($_POST['imgs']) ? [] :  $_POST['imgs'];
             if(empty($imgs)){
                 $this->error('请上传案例图片',null,101);
             }
             $data['photo'] = $imgs[0];
             $data2 = [];
             foreach ($imgs as $val){
                 $data2[] = [
                     'member_miniapp_id' => $this->miniapp_id,
                     'room_id' => $room_id,
                     'photo'  => $val,
                 ];
             }
             $RoomphotoModel = new RoomphotoModel();
             $RoomphotoModel->where(['room_id'=>$room_id])->delete();
             $RoomphotoModel->saveAll($data2);
            $RoomModel = new RoomModel();
            $RoomModel->save($data,['room_id'=>$room_id]);
            $this->success('操作成功',null);
         }else{
             $RoomphotoModel = new RoomphotoModel();
             $where['member_miniapp_id'] = $this->miniapp_id;
             $where['room_id'] = $room_id;
             $photo = $RoomphotoModel->where($where)->limit(0,50)->select();
             $this->assign('photo',$photo);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $room_id = (int)$this->request->param('room_id');
         $RoomModel = new RoomModel();
       
        if(!$detail = $RoomModel->find($room_id)){
            $this->error("不存在该包厢管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该包厢管理', null, 101);
        }
        $RoomModel->where(['room_id'=>$room_id])->delete();
        $this->success('操作成功');
    }
   
}