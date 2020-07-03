<?php
namespace app\miniapp\controller\hotelgw;
use app\common\model\hotelgw\RoompriceModel;
use app\miniapp\controller\Common;
use app\common\model\hotelgw\RoomModel;
class Room extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = RoomModel::where($where)->count();
        $list = RoomModel::where($where)->order(['orderby'=>'desc'])->paginate(10, $count);
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
            $data['photo'] = $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['price'] = ((int) $this->request->param('price')) * 100;
            if(empty($data['price'])){
                $this->error('日常价格不能为空',null,101);
            }
            $data['area'] = (int) $this->request->param('area');
            if(empty($data['area'])){
                $this->error('面积不能为空',null,101);
            }

            $data['cancel'] = $this->request->param('cancel');  
            if(empty($data['cancel'])){
                $this->error('取消规则不能为空',null,101);
            }

            $data['people_num'] = (int) $this->request->param('people_num');
            if(empty($data['people_num'])){
                $this->error('可入住人数不能为空',null,101);
            }
            $data['floor'] = $this->request->param('floor');  
            if(empty($data['floor'])){
                $this->error('所在楼层不能为空',null,101);
            }
            $data['day_num'] = (int) $this->request->param('day_num');
            if(empty($data['day_num'])){
                $this->error('单日最大预定数不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            $data['is_breakfast'] = (int) $this->request->param('is_breakfast');
            $data['is_wifi']   =   (int)  $this->request->param('is_wifi');
            $data['is_window'] = (int)    $this->request->param('is_window');
            $data['is_cancel'] = (int)    $this->request->param('is_cancel');
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
             $data['title'] = $this->request->param('title');
             if(empty($data['title'])){
                 $this->error('房间标题不能为空',null,101);
             }
             $data['photo'] = $this->request->param('photo');
             if(empty($data['photo'])){
                 $this->error('图片不能为空',null,101);
             }
             $data['price'] = ((int) $this->request->param('price')) * 100;
             if(empty($data['price'])){
                 $this->error('日常价格不能为空',null,101);
             }
             $data['area'] = (int) $this->request->param('area');
             if(empty($data['area'])){
                 $this->error('面积不能为空',null,101);
             }

             $data['cancel'] = $this->request->param('cancel');
             if(empty($data['cancel'])){
                 $this->error('取消规则不能为空',null,101);
             }

             $data['people_num'] = (int) $this->request->param('people_num');
             if(empty($data['people_num'])){
                 $this->error('可入住人数不能为空',null,101);
             }
             $data['floor'] = $this->request->param('floor');
             if(empty($data['floor'])){
                 $this->error('所在楼层不能为空',null,101);
             }
             $data['day_num'] = (int) $this->request->param('day_num');
             if(empty($data['day_num'])){
                 $this->error('单日最大预定数不能为空',null,101);
             }
             $data['orderby'] = (int) $this->request->param('orderby');
             $data['is_breakfast'] = (int) $this->request->param('is_breakfast');
             $data['is_wifi']   =   (int)  $this->request->param('is_wifi');
             $data['is_window'] = (int)    $this->request->param('is_window');
             $data['is_cancel'] = (int)    $this->request->param('is_cancel');
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
        $RoomModel->where(['room_id'=>$room_id])->delete();
        $this->success('操作成功');
    }




    public function price(){
        $HotelpriceModel = new RoompriceModel();
        $date   =   $this->request->param('date');
        $date   =   empty($date) ? date('Y-m-d',time()) : $date;
        $_date  =   strtotime($date) ?  date('Y-m-d',strtotime($date)) : date('Y-m-d',time());
        $rooms  =   $HotelpriceModel->backGwPrice($this->miniapp_id,$_date);
        $this->assign('date',$date);
        $this->assign('rooms',$rooms);

        return $this->fetch();
    }


    public function setprice(){
        $data = $_POST['data'];
        if (empty($data)){
            $this->error('请不要更改数据',null,101);
        }
        $date   =   $this->request->param('date');
        if($date < date("Y-m-d",time())){
            $this->error('不可以设置过去时间',null,101);
        }
        $savedata = $roomIds =$hotel = $updatedata =  [];
        foreach ($data as $key=>$val){
            $roomIds[$key] =  (int) $key;
        }
        $RoomModel = new RoomModel();
        $rooms = $RoomModel->itemsByIds($roomIds);
        foreach ($rooms as $val){
            if($val->member_miniapp_id != $this->miniapp_id){
                $this->error('有不存在的房间',null,101);
                die();
            }
        }
        if(sizeof($hotel) > 1 || sizeof($rooms) !== sizeof($data)){
            $this->error('有不存在的房间',null,101);
        }
        foreach ($data as $key=>$val){
            if(empty($val['price_id'])){
                $savedata[] = [
                    'price'     => ((float) $val['price']) * 100,
                    'room_id'   => (int)$key,
                    'day'       => $date,
                    'is_online' => empty($val['is_online']) ? 0 : 1 ,
                    'member_miniapp_id' => $this->miniapp_id,

                ];
            }else{
                $updatedata[] = [
                    'price_id'  => $val['price_id'],
                    'price'     => ((float) $val['price']) * 100,
                    'room_id'   => (int)$key,
                    'day'       => $date,
                    'is_online' => empty($val['is_online']) ? 0 : 1,
                ];
            }
        }
        $HotelpriceModel = new RoompriceModel();
        if(!empty($updatedata)){
            $HotelpriceModel->saveAll($updatedata);
        }
        if(!empty($savedata)){
            $HotelpriceModel->saveAll($savedata);
        }
        $this->success('操作成功');
    }


    public function  online (){
        $room_id  = (int) $this->request->param('room_id');
        $RoomModel = new RoomModel();
        if(!$room = $RoomModel->find($room_id)){
            $this->error('不存在房间',null,101);
        }
        if($room->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在房间',null,101);
        }
        $data['is_online'] =  $room->is_online == 1 ? 0 : 1;
        $RoomModel->save($data,['room_id'=>$room_id]);
        $this->success('操作成功');
    }
   
}