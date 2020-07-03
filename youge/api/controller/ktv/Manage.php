<?php
namespace app\api\controller\ktv;
use app\api\controller\Common;
use app\common\model\ktv\EnrollModel;
use app\common\model\ktv\RoomModel;

class Manage extends  Common{
    protected $checklogin = true;

    /*
     *预约酒店；
     */
    public function enroll(){
        $room_id = (int) $this->request->param('room_id');
        $RoomModel = new RoomModel();
        if(!$room = $RoomModel->find($room_id)){
            $this->result('',400,'参数错误','json');
        }
        if($room->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        $data['room_id'] = $room_id;
        $EnrollModel= new EnrollModel();
        $data['member_miniapp_id'] = $this->appid;
        $data['name'] = $this->request->param('name');
        if(empty($data['name'])){
            $this->result('',400,'请输入姓名','json');
        }
        $data['user_id'] = $this->user->user_id;
        $data['mobile'] = $this->request->param('mobile');
        if(empty($data['mobile'])){
            $this->result('',400,'请输入联系方式','json');
        }
        $data['enroll_date'] = $this->request->param('enroll_date');
        if(empty($data['enroll_date'])){
            $this->result('',400,'请选择预约日期','json');
        }
        $data['enroll_time'] = $this->request->param('enroll_time');
        if(empty($data['enroll_time'])){
            $this->result('',400,'请选择预约时长','json');
        }
        $data['status'] = 0;
        $EnrollModel->save($data);
        $this->result('',200,'操作成功','json');
    }
    /*
     * 我的预约列表
     */
    public function getEnroll(){
        $EnrollModel = new EnrollModel();
        $where['user_id'] = $this->user->user_id;
        $list = $EnrollModel->where($where)->order("enroll_id desc")->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        $roomIds = [];
        foreach ($list as $val){
            $roomIds[$val->room_id] = $val->room_id;
        }
        $RoomModel = new RoomModel();
        $rooms = $RoomModel->itemsByIds($roomIds);
        $status = ['等待消费','商家已接单','拒绝预约','已消费'];
        foreach ($list as $val){
            $data['list'][] = [
                'enroll_id' => $val->enroll_id,
                'photo' => empty($rooms[$val->room_id]) ? '' : IMG_URL . getImg($rooms[$val->room_id]->photo),
                'title' => empty($rooms[$val->room_id]) ? '' : $rooms[$val->room_id]->title,
                'price' => empty($rooms[$val->room_id]) ? '' : round($rooms[$val->room_id]->price/100,2),
                'status' => empty($status[$val->status]) ? '' : $status[$val->status],
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
       $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 预约详情
     */

    public function enrollDetail(){
        $enroll_id = (int) $this->request->param('enroll_id');
        $EnrollModel = new EnrollModel();
        if(!$detail = $EnrollModel->find($enroll_id)){
            $this->result('',400,'参数错误','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        $RoomModel = new RoomModel();
        $room = $RoomModel->find($detail->room_id);
        $status = ['等待消费','商家已接单','拒绝预约','已消费'];
        $data = [
            'title' => empty($room) ? '' : $room->title,
            'price' => empty($room) ? '' : round( $room->price/100,2),
            'photo' => empty($room) ? '' : IMG_URL . getImg( $room->photo),
            'enroll_id' => $detail->enroll_id,
            'name' => $detail->name,
            'mobile' => $detail->mobile,
            'enroll_date' => $detail->enroll_date,
            'enroll_time' => $detail->enroll_time,
            'status' => empty($status[$detail->status]) ? '' : $status[$detail->status],
        ];
        $this->result($data,200,'数据初始化成功','json');
    }
}