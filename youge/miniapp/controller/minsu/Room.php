<?php
namespace app\miniapp\controller\minsu;
use app\common\model\minsu\MinsuModel;
use app\common\model\setting\RegionModel;
use app\miniapp\controller\Common;
use app\common\model\minsu\RoomModel;
class Room extends Common {

    public function index() {
        $where = $search = [];
        $search['minsu_id'] = (int)$this->request->param('minsu_id');
        if (!empty($search['minsu_id'])) {
            $where['minsu_id'] = $search['minsu_id'];
        }
       $search['city_id'] = (int)$this->request->param('city_id');
        if (!empty($search['city_id'])) {
            $where['city_id'] = $search['city_id'];
        }
                $search['score'] = (int)$this->request->param('score');
        if (!empty($search['score'])) {
            $where['score'] = $search['score'];
        }
                $search['room_type'] = (int)$this->request->param('room_type');
        if (!empty($search['room_type'])) {
            $where['room_type'] = $search['room_type'];
        }
                $search['price'] = (int)$this->request->param('price');
        if (!empty($search['price'])) {
            $where['price'] = $search['price'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['is_delete'] = 0;
        $count = RoomModel::where($where)->count();
        $list = RoomModel::where($where)->order(['room_id'=>'desc'])->paginate(10, $count);
        $minsuIds = [];
        foreach ($list as $val){
            $minsuIds[$val->minsu_id] = $val->minsu_id;
        }
        $minsuModel = new minsuModel();
        $minsu =  $minsuModel->itemsByIds($minsuIds);
        if(empty($minsuIds)){
            $minsu[$search['minsu_id']] = $minsuModel->find($search['minsu_id']);
        }
        $this->assign('minsu',$minsu);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function create() {
        $minsuModel = new MinsuModel();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['minsu_id'] = (int) $this->request->param('minsu_id');
            if(!$minsu = $minsuModel->find($data['minsu_id'])){
                $this->error('不存在该民宿',null,101);
            }

            if($minsu->member_miniapp_id != $this->miniapp_id){
                $this->error('不存在该民宿',null,101);
            }

            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['city_id'] = $minsu->city_id;
            $data['title'] = $this->request->param('title');
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['area'] = (int) $this->request->param('area');
            if(empty($data['area'])){
                $this->error('面积不能为空',null,101);
            }
            $data['photo'] = (string) $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['price'] = ((int) $this->request->param('price')) * 100;
            if(empty($data['price'])){
                $this->error('日常价格不能为空',null,101);
            }
            $minsupruice = $minsuModel->where(['member_miniapp_id'=>$this->miniapp_id,'minsu_id'=>$data['minsu_id']])->order("price asc")->find();
//            添加房屋是修改最民宿最低起始价如果新房源的价格是最小的则修改民宿其实价格；
            if($data['price'] <= $minsupruice->price || empty($minsupruice->price)){
                 $minsu_data['price'] = $data['price'];
                 $minsuModel->save($minsu_data,['minsu_id'=>$data['minsu_id']]);
//                如果大于民宿最小价格则判断当前其实价格是不是最小价格如果不是则修改防止设最小价格后修改
             }else if($minsu->price  < $minsupruice->price){
                $minsu_data['price'] = $minsupruice->price;
                $minsuModel->save($minsu_data,['minsu_id'=>$data['minsu_id']]);
            }
            $data['bed_type'] = (int) $this->request->param('bed_type');
            if(empty($data['bed_type'])){
                $this->error('床的类型不能为空',null,101);
            }
            $data['bed_width'] = (int) $this->request->param('bed_width');
            if(empty($data['bed_width'])){
                $this->error('床宽不能为空',null,101);
            }
            $data['bed_logn'] = (int) $this->request->param('bed_logn');
            if(empty($data['bed_logn'])){
                $this->error('床长不能为空',null,101);
            }
            $data['bed_num'] = (int) $this->request->param('bed_num');
            if(empty($data['bed_num'])){
                $this->error('床的数量不能为空',null,101);
            }
            $data['is_wifi'] = $this->request->param('is_wifi');
            if(empty($data['is_wifi'])){
                $this->error('是否有WIFI不能为空',null,101);
            }
            $data['day_num'] = (int) $this->request->param('day_num');
            if(empty($data['day_num'])){
                $this->error('单日最大预定不能为空',null,101);
            }
            $data['is_online'] = $this->request->param('is_online');
            if(empty($data['is_online'])){
                $this->error('是否上架不能为空',null,101);
            }
            $RoomModel = new RoomModel();
            $RoomModel->save($data);
            $this->success('操作成功',null);
        } else {
            $minsu_id = (int) $this->request->param('minsu_id');
            $minsu = $minsuModel->find($minsu_id);
            if($minsu && $minsu->member_miniapp_id == $this->miniapp_id){
                $this->assign('minsu',$minsu);
            }
            return $this->fetch();
        }
    }
    public function edit(){
         $room_id = (int)$this->request->param('room_id');
         $RoomModel = new RoomModel();
         $minsuModel = new minsuModel();
         if(!$detail = $RoomModel->get($room_id)){
             $this->error('不存在房间',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
             $this->error('不存在房间',null,101);
         }
         if ($this->request->method() == 'POST') {
             $data = [];

             if(!$minsu = $minsuModel->find($detail->minsu_id)){
                 $this->error('不存在该民宿',null,101);
             }
             if($minsu->member_miniapp_id != $this->miniapp_id){
                 $this->error('不存在该民宿',null,101);
             }
             $data['member_miniapp_id'] = $this->miniapp_id;
             $data['title'] = $this->request->param('title');
             if(empty($data['title'])){
                 $this->error('标题不能为空',null,101);
             }
             $data['area'] = (int) $this->request->param('area');
             if(empty($data['area'])){
                 $this->error('面积不能为空',null,101);
             }
             $data['photo'] = (string) $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
             $data['price'] = ((int) $this->request->param('price')) * 100;
             if(empty($data['price'])){
                 $this->error('日常价格不能为空',null,101);
             }
             $minsupruice = $minsuModel->where(['member_miniapp_id'=>$this->miniapp_id,'minsu_id'=>$detail->minsu_id])->order("price asc")->find();
//            添加房屋是修改最民宿最低起始价如果新房源的价格是最小的则修改民宿其实价格；
             if($data['price'] <= $minsupruice->price || empty($minsupruice->price)){
                 $minsu_data['price'] = $data['price'];
                 $minsuModel->save($minsu_data,['minsu_id'=>$detail->minsu_id]);
//                如果大于民宿最小价格则判断当前其实价格是不是最小价格如果不是则修改防止设最小价格后修改
             }else if($minsu->price  < $minsupruice->price){
                 $minsu_data['price'] = $minsupruice->price;
                 $minsuModel->save($minsu_data,['minsu_id'=>$detail->minsu_id]);
             }
             $data['bed_type'] = (int) $this->request->param('bed_type');
             if(empty($data['bed_type'])){
                 $this->error('床的类型不能为空',null,101);
             }
             $data['bed_width'] = (int) $this->request->param('bed_width');
             if(empty($data['bed_width'])){
                 $this->error('床宽不能为空',null,101);
             }
             $data['bed_logn'] = (int) $this->request->param('bed_logn');
             if(empty($data['bed_logn'])){
                 $this->error('床长不能为空',null,101);
             }
             $data['bed_num'] = (int) $this->request->param('bed_num');
             if(empty($data['bed_num'])){
                 $this->error('床的数量不能为空',null,101);
             }
             $data['is_wifi'] = $this->request->param('is_wifi');
             if(empty($data['is_wifi'])){
                 $this->error('是否有WIFI不能为空',null,101);
             }
             $data['day_num'] = (int) $this->request->param('day_num');
             if(empty($data['day_num'])){
                 $this->error('单日最大预定不能为空',null,101);
             }
             $data['is_online'] = $this->request->param('is_online');
             if(empty($data['is_online'])){
                 $this->error('是否上架不能为空',null,101);
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
         $room_id = (int) $this->request->param('room_id');
         $RoomModel = new RegionModel();
         if(!$room = $RoomModel->find($room_id)){
             $this->error("不存在房间",null,101);
         }
         if($room->member_miniapp_id != $this->miniapp_id){
             $this->error("不存在房间",null,101);
         }
         $data['is_delete'] = 1;
         $RoomModel->save($data,['room_id'=>$room_id]);
        $this->success('操作成功');
    }

    public function online(){
        $room_id = (int) $this->request->param('room_id');
        $RoomModel = new RoomModel();
        if(!$room = $RoomModel->find($room_id)){
            $this->error("不存在房间1",null,101);
        }
        if($room->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在房间2",null,101);
        }
        $data['is_online'] = 1;
        if($room->is_online == 1){
            $data['is_online'] = 2;
        }
        $RoomModel->save($data,['room_id'=>$room_id]);
        $this->success('操作成功');
    }

}