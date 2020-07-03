<?php
namespace app\api\controller\hotelgw;
use app\api\controller\Common;
use app\common\model\hotelgw\CommentModel;
use app\common\model\hotelgw\CommentphotoModel;
use app\common\model\hotelgw\HotelModel;
use app\common\model\hotelgw\RoomModel;
use app\common\model\hotelgw\RoompriceModel;
use app\common\model\user\UserModel;


class Data extends Common
{

    //获取酒店基本信息；
    public function getHotel(){
        $HotelModel = new HotelModel();
        if(!$hotel = $HotelModel->find($this->appid)){
            $this->result('',400,'请商家配置程序','json');
        }
        $tags = explode(',',$hotel->hotel_service);
        $data = [
            'banner' => IMG_URL .  getImg($hotel->banner),
            'lat'  => $hotel->lat,
            'lng'  => $hotel->lng,
            'address'  => $hotel->address,
            'hotel_name'  => $hotel->hotel_name,
            'describe'   => $hotel->describe,
            'tages'   => $tags,
        ];
        $this->result($data,'200','数据初始化成功','json');
    }

    //获取酒店房间 默认今天和明天的;
    public function getRooms()
    {
        $bg_date = date('Y-m-d',strtotime($this->request->param('bg_date')));
        $end_date = date('Y-m-d',strtotime($this->request->param('end_date')));
        $where['member_miniapp_id'] = $this->appid;
        $where['is_online'] = 1;
        $room = RoomModel::where($where)->select();
        $roomids = $room_num = [];
        foreach($room as $val){
            $roomids[$val->room_id] = $val->room_id;
            $room_num[$val->room_id] = $val->day_num;
        }
        //计算客满和日期价格
        $manroom = $roomprice = $unline = [];
        if(!empty($roomids)){
            $prices = RoompriceModel::where(['room_id'=>['IN',$roomids],'day'=>['BETWEEN',[$bg_date,$end_date]]])->limit($this->limit_bg,$this->limit_num)->select();
            if(!empty($prices)){
                foreach($prices as $val){
                    if($val->room_num >= $room_num[$val->room_id]){
                        $manroom[$val->room_id] = $val->room_id;
                    }
                    if($val->is_online==0){
                        $unline[$val->room_id]  = $val->room_id;
                    }
                    if($val->price>0 && $val->day == $bg_date){
                        $roomprice[$val->room_id] = $val->price;
                    }
                }
            }
        }
        //返回房屋列表
        $return = [];
        foreach($room as $val){
            if(!isset($unline[$val->room_id])){
                $return['list'][] =[
                    'id'    => $val->room_id,
                    'title' => $val->title,
                    'area'  => $val->area,
                    'photo' => IMG_URL.  getImg($val->photo),
                    'floor'=>$val->floor,
                    'is_window'=>$val->is_window,
                    'cancel'=>$val->cancel,
                    'is_cancel'=>$val->is_cancel,
                    'people_num'=>$val->people_num,
                    'is_wifi'       => $val->is_wifi,
                    'is_breakfast'       => $val->is_breakfast,
                    'price'         => isset($roomprice[$val->room_id]) ? round($roomprice[$val->room_id]/100,2) : round($val->price/100,2),
                    'is_show'   => 0,
                    'show_yvding' => 0,
                ];
            }
        }
        $return['more']  = count($return['list']) == $this->limit_num ? 1: 0;
        $return['bg_date'] = $bg_date;
        $return['end_date'] = $end_date;
        $this->result($return,'200','加载房型成功','json');
    }

    public function  getComment(){
        $type = (int) $this->request->param('type');
        switch ($type){
            case 1:
                $where['score'] = [">=",40];
                break;
            case 2:
                $where['score'] = [['>=',25],['<=',35]];
                break;
            case 3:
                $where['score'] = ['<=',20];

        }
        $CommentModel = new CommentModel();
        $where['member_miniapp_id'] = $this->appid;
        $data['totalNum'] = $CommentModel->where($where)->count();
        $list = $CommentModel->where($where)->order("comment_id desc")->limit($this->limit_bg,$this->limit_num)->select();
        if (empty($list)){
            $data['list'] = [];
            $this->result($data,200,'没有数据了','json');
        }
        $photoIds = $userIds = $roomIds = $hotelIds = [];
        foreach ($list as $val){
            $photoIds[$val->comment_id] = $val->comment_id;
            $userIds[$val->user_id] = $val->user_id;
        }
        $CommentphotoModel = new CommentphotoModel();
        $UserModel = new UserModel();
        $users = $UserModel->itemsByIds($userIds);
        $photoIds = empty($photoIds) ? 0 : $photoIds;
        $photo_where['comment_id'] = ["IN",$photoIds];
        $photo = $CommentphotoModel->where($photo_where)->select();
        $photos = [];
        foreach ($photo as $val){
            $photos[$val->comment_id][] = IMG_URL . getImg($val->photo);
        }
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'] [] = [
                'comment_id' => $val->comment_id,
                'user_id'    => $val->user_id,
                'user_nick_name' => empty($users[$val->user_id])  ? '' : $users[$val->user_id]->nick_name,
                'user_face'  => empty($users[$val->user_id]) ? '' : $users[$val->user_id]->face,
                'score'     => round($val->score/10,1),
                'content'    => $val->content,
                'content_time' => date("Y-m-d",$val->add_time),
                'reply'      => $val->reply,
                'reply_time'  => empty($val->reply_time) ? '' : date("Y-m-d",$val->reply_time),
                'photos'    => empty($photos[$val->comment_id]) ? [] : $photos[$val->comment_id],
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,'200','数据初始化成功','json');
    }
}