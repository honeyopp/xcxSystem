<?php

namespace app\api\controller\ktv;

use app\api\controller\Common;
use app\common\model\ktv\KtvModel;
use app\common\model\ktv\RoomModel;
use app\common\model\ktv\RoomphotoModel;
use app\common\model\publicuse\BannerModel;
use app\common\model\setting\ActivityModel;

class Index extends Common
{
    /*
     * 获取首特数据
     **/
    public function getIndex()
    {
        //获取banner；
        $banner = BannerModel::where(['member_miniapp_id' => $this->appid])->order("orderby desc")->limit(0, 20)->select();
        $data['banner'] = [];
        foreach ($banner as $val) {
            $data['banner'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        //获取地址；
        $ktv = KtvModel::find($this->appid);
        $data['address'] = empty($ktv) ? '' : $ktv->address;
        $data['ktv_name'] = empty($ktv) ? '' : $ktv->ktv_name;
        //获取优惠券；
        $acitit_where['is_online'] = 1;
        $acitit_where['member_miniapp_id'] = $this->appid;
        $date = date("Y-m-d");
        $acitit_where['bg_date'] = ['<=', $date];
        $acitit_where['end_date'] = ['>=', $date];
        $ActivityModel = new ActivityModel();
        $data['activity'] = [];
        $activi = $ActivityModel->where($acitit_where)->order("orderby desc")->select();
        $data['activity'] = [];
        foreach ($activi as $val) {
            $data['activity'][] = [
                'activity_id' => $val->activity_id,
                'title' => $val->title,
                'money' => round( $val->money / 100,2),
                'need_money' => round( $val->need_money / 100,2),
                'expire_day' => $val->expire_day,
                'use_day' => $val->use_day,
                'is_newuser' => $val->is_newuser,
                'num' => $val->num,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
            ];
        }
        //最多获取6个排序最高的包间;
        $where['member_miniapp_id'] = $this->appid;
        $RoomModel = new RoomModel();
        $list = $RoomModel->where($where)->order('orderby desc')->limit(0,6)->select();
        $data['rooms'] = [];
        foreach ($list as $val){
            $data['rooms'][] = [
                'room_id' => $val->room_id,
                'title' => $val->title,
                'photo' => IMG_URL .getImg($val->photo),
                'price' => round($val->price/100,2),
                'num'  => $val->num,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 获取包间列表；
     *
     */
    public function getRooms(){
        $where['member_miniapp_id'] = $this->appid;
        $RoomModel = new RoomModel();
        $list = $RoomModel->where($where)->order("orderby desc")->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ( $list as $val){
            $data['list'][] = [
                'room_id' => $val->room_id,
                'title' => $val->title,
                'photo' => IMG_URL .getImg($val->photo),
                'price' => round($val->price/100,2),
                'num'  => $val->num,
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }

    /*
     * 包间详情
     */
    public function roomDetail(){
        $room_id = (int) $this->request->param('room_id');
        $RoomModel = new RoomModel();
        if(!$room = $RoomModel->find($room_id)){
            $this->result('',400,'参数错误','json');
        }
        if($room->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        $RoomphotoModel  =  new RoomphotoModel();
        $photo = $RoomphotoModel->where(['room_id'=>$room_id])->limit(0,20)->select();
        $photos = [];
        foreach ($photo as $val){
            $photos[] = IMG_URL . getImg($val->photo);
        }
        $data = [
            'room_id' => $room->room_id,
            'photo' => IMG_URL . getImg($room->photo),
            'title' => $room->title,
            'price' => round($room->price/100,2),
            'num' => $room->num,
            'enroll_time' => $room->enroll_time,
            'enroll_length' => $room->enroll_length,
            'enroll_date' => $room->enroll_date,
            'photos' => $photos,
            'date' => date("Y-m-d",time()),
        ];
        $this->result($data,200,'数据初始化成功','json');
    }

    /*
     * 门店详情
     */
    public function ktvDetail(){

        $KtvModel = new KtvModel();
        $ktv = $KtvModel->find($this->appid);
        if (empty($ktv)){
            $this->result('',200,'数据初始化成功','json');
        }
        $data = [
            'lat' => (float) $ktv->lat,
            'lng' => (float) $ktv->lng,
            'address' => $ktv->address,
            'trade' => $ktv->trade,
            'tel' => $ktv->tel,
            'introduce' => $ktv->introduce,
            'ktv_name' => $ktv->ktv_name,
        ];
        $banner = BannerModel::where(['member_miniapp_id' => $this->appid])->limit(0, 20)->select();
        $data['banner'] = [];
        foreach ($banner as $val) {
            $data['banner'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
}