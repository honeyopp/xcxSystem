<?php
namespace app\miniapp\controller\nongjiale;
use app\miniapp\controller\Common;
use app\common\model\nongjiale\RoomModel;
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
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('房间标题不能为空',null,101);
            }
            $data['area'] = (int) $this->request->param('area');
            if(empty($data['area'])){
                $this->error('面积不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            if(empty($data['price'])){
                $this->error('价格不能为空',null,101);
            }
            $data['is_wifi'] = $this->request->param('is_wifi');  
            if(empty($data['is_wifi'])){
                $this->error('是否有WIFI不能为空',null,101);
            }
            $data['day_num'] = (int) $this->request->param('day_num');
            if(empty($data['day_num'])){
                $this->error('单日最大预定数不能为空',null,101);
            }
            $data['is_online'] = $this->request->param('is_online');  
            if(empty($data['is_online'])){
                $this->error('上线不能为空',null,101);
            }
            
            
            $RoomModel = new RoomModel();
            $RoomModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $room_id = (int)$this->request->param('room_id');
         $RoomModel = new RoomModel();
         if(!$detail = $RoomModel->get($room_id)){
             $this->error('请选择要编辑的房间管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在房间管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('房间标题不能为空',null,101);
            }
            $data['area'] = (int) $this->request->param('area');
            if(empty($data['area'])){
                $this->error('面积不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            if(empty($data['price'])){
                $this->error('价格不能为空',null,101);
            }
            $data['is_wifi'] = $this->request->param('is_wifi');  
            if(empty($data['is_wifi'])){
                $this->error('是否有WIFI不能为空',null,101);
            }
            $data['day_num'] = (int) $this->request->param('day_num');
            if(empty($data['day_num'])){
                $this->error('单日最大预定数不能为空',null,101);
            }
            $data['is_online'] = $this->request->param('is_online');  
            if(empty($data['is_online'])){
                $this->error('上线不能为空',null,101);
            }

            
            $RoomModel = new RoomModel();
            $RoomModel->save($data,['room_id'=>$room_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $room_id = (int)$this->request->param('room_id');
         $RoomModel = new RoomModel();
       
        if(!$detail = $RoomModel->find($room_id)){
            $this->error("不存在该房间管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该房间管理', null, 101);
        }
        if($detail->is_delete == 1){
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $RoomModel->save($data,['room_id'=>$room_id]);
        $this->success('操作成功');
    }
   
}