<?php

namespace app\api\controller\hotel;

use app\api\controller\Common;
use app\common\model\hotel\CommentModel;
use app\common\model\hotel\CommentphotoModel;
use app\common\model\hotel\HotelModel;
use app\common\model\hotel\HoteldetailModel;
use app\common\model\hotel\HotelphotoModel;
use app\common\model\hotel\RoomModel;
use app\common\model\hotel\HotelpriceModel;
use app\common\model\user\UserModel;
class Index extends Common {
    /*
     * 获取酒店列表；
     */

    public function index() {
        //这里可能有关键字或者是分类的（如果分类有筛选了将不覆盖！如果分类没有筛选将覆盖）   
        $where = [];
        $city_id = (int) $this->request->param('city_id');
        if (empty($city_id)) {
            $this->result('', 400, '城市ID不能为空', 'json');
        }
        $lat = floatval($this->request->param('lat'));
        $lng = floatval($this->request->param('lng'));
        if (empty($lat) || empty($lng)) {
            //$this->result('', 400, '获取地理位置失败！', 'json');
        }
        $where['city_id'] = $city_id;
        $where['member_miniapp_id'] = $this->appid;

      
        
        $administration_id = (int) $this->request->param('administration_id');
        $area_id = (int) $this->request->param('area_id');
        $star_id = (int) $this->request->param('star_id');
        $station_id = (int) $this->request->param('station_id');
        $brand_id = (int) $this->request->param('brand_id');
        $colleges_id = (int) $this->request->param('colleges_id');
        $hospital_id = (int) $this->request->param('hospital_id');
        $scenic_spot_id = (int) $this->request->param('scenic_spot_id');
        $special_id = (int)  $this->request->param('special_id');
        $keyword = '';
        
        $search = $this->request->param('search');
        if(!empty($search) && $search!='[]'){
            $search = json_decode($search,true);
            if(!empty($search['type'])){
                switch ($search['type']){
                     case 1:
                         $scenic_spot_id = empty($scenic_spot_id) ? $search['id']:$scenic_spot_id;
                         break;
                     case 2:
                         $area_id = empty($area_id) ? $search['id']:$area_id;
                         break;
                    case 3:
                        $administration_id = empty($administration_id) ? $search['id']:$administration_id;
                         break;
                     case 4:
                         $station_id = empty($station_id) ? $search['id']:$station_id;
                         break;
                    case 5:
                        $colleges_id = empty($colleges_id) ? $search['id']:$colleges_id;
                         break;
                     case 6:
                         $hospital_id = empty($hospital_id) ? $search['id']:$hospital_id;
                         break;
                    case 7:
                         $brand_id = empty($brand_id) ? $search['id']:$brand_id;
                         break;
                     case 8:
                          $special_id = empty($special_id) ? $search['id']:$special_id;
                         break;
                    default:
                          $keyword = htmlspecialchars($search['name']);
                         break;
                }                
            }
        }
        
        if(!empty($scenic_spot_id)){
            $where['scenic_spot_id'] = $scenic_spot_id;
        }
        if(!empty($area_id)){
            $where['area_id'] = $area_id;
        }
        if(!empty($administration_id)){
            $where['administration_id'] = $administration_id;
        }
        if(!empty($station_id)){
            $where['station_id'] = $station_id;
        }
        if(!empty($scenic_spot_id)){
            $where['colleges_id'] = $colleges_id;
        }
        if(!empty($hospital_id)){
            $where['hospital_id'] = $hospital_id;
        }
        if(!empty($brand_id)){
            $where['brand_id'] = $brand_id;
        }
        if(!empty($star_id)){
            $where['hotel_level'] = $star_id;
        }
        if(!empty($keyword)){
            $where['hotel_name|address'] = ['LIKE','%'.$keyword.'%'];
        }
        
        $where['is_online'] = 1;
        $where['is_delete'] = 0;
        
                
        //价格筛选
        $bg_price = (int) ($this->request->param('bg_price') * 100);
        $end_price = (int) ($this->request->param('end_price') * 100);    
        
        if(!empty($bg_price) && !empty($end_price)){
            $where['price'] = ['BETWEEN',[$bg_price,$end_price]];
        }else{
            if(!empty($bg_price)){
                $where['price'] = ['EGT',$bg_price];
            }
            if(!empty($end_price)){
                $where['price'] = ['ELT',$end_price];
            }
        }
        $order = (int) $this->request->param('order');
        $juli = (int) $this->request->param('juli');
        if($juli){
            $order = 2; // 如果是距离那么将会按照距离排序，其他的排序规则不起作用
        }
        $orderby  = '';
        switch($order){
            case 1:
                $orderby = ' orderby  desc ';
                break;
            case 2:
                $orderby =  " ABS(lng-'{$lng}' + lat-'{$lat}')  ASC ";
                break;
            case 3:
                $orderby = ' price asc ';
                break;
            case 4:
                $orderby = ' price desc ';
                break;
            case 5:
                $orderby = ' score desc ';
                break;
        }
        
        $service = $this->request->param('service');
        
        $serviceCols = array(
            'is_wifi','is_water','is_hairdrier','is_airconditioner','is_elevator','is_fitnessroom','is_swimmingpool','is_sauna',
            'is_westernfood','is_chinesefood','is_disability','is_smokeless','is_stop','is_cereal','is_airportpickup','is_station',
            'is_cabs','is_luggage','is_carrental','is_disabled','is_conference','is_express','is_washclothes','is_merchant','is_awaken',
            'is_deposit','is_creditcard','is_reception','is_foreignguests',
        );
        $serviceWhere = [];
        //如果服务不为空要做服务的子查询
        if(!empty($service) && $service!='[]'){
            $service = json_decode($search,true);
            if(!empty($service)){
                foreach($service as $val){
                    if(in_array($val, $serviceCols)){
                        $serviceWhere[$val] = 1;
                    }
                }
            } 
        }
        //$special_id
        $HotelModel = new HotelModel();
        //$this->limit_bg
        $datas = $HotelModel->detailExists($serviceWhere)->specialExists($special_id)->where($where)->order($orderby)->limit($this->limit_bg,$this->limit_num)->select();
        $result  = [];
        if(empty($datas)){
            $this->result([],'200','没有数据了','json'); 
        }
        $config = config('dataattr.hotellevelnames');
        foreach($datas as $val){
           $m =  getDistanceNone($lat, $lng, $val->lat, $val->lng);
           if($juli==0 || $juli*10>$m){
               $result[] = [
                   'id' => $val->hotel_id,
                   'name' => $val->hotel_name,
                   'star' => isset($config[$val->hotel_level])?$config[$val->hotel_level]:'',
                   'photo' => IMG_URL.  getImg($val->photo),
                   'score' => round($val->score/10,1),
                   'advantage' => $val->advantage,
                   'price'     => round($val->price/100,2),
                   'praise_num' => $val->praise_num,
                   'bad_num'    => $val->bad_num,
                   'address'    => $val->address,
                   'juli'       => empty($lat) ? '（定位中）' : getDistance($lat, $lng, $val->lat, $val->lng),
                   'decoration_time' => $val->decoration_time,
                   'opening_time' => $val->opening_time,
               ]; 
           }          
        }
        $return = [];
        $return['datas'] = $result;
        $return['more']  = count($result) == $this->limit_num ? 1: 0;
        $return['num']   =  count($result);
        $this->result($return,'200','加载数据成功','json'); 
    }
    
    //
    public function detail(){
        $id = (int)$this->request->param('id');
        if(empty($id)){
            $this->result([],'400','参数错误#1','json');
        }
        $hotel = HotelModel::get($id);
        if(empty($hotel)){
            $this->result([],'400','参数错误#2','json');
        }
        if($hotel->member_miniapp_id != $this->appid){
            $this->result([],'400',$id.'-'.$hotel->member_miniapp_id.'-'.$this->appid,'json');
        }
        if($hotel['is_delete'] == 1 || $hotel['is_online'] == 0){
            $this->result([],'400','该房型还未上架#4','json'); 
        }
        
        $detail  = HoteldetailModel::get($id);
        if(empty($detail)){
            $this->result([],'400','参数错误#5','json');
        }
        
        $lat = floatval($this->request->param('lat'));
        $lng = floatval($this->request->param('lng'));
      
        
        $return = [
            'id'        => $hotel->hotel_id,
            'name'      => $hotel->hotel_name,
            'banner'    => IMG_URL . getImg($hotel->banner),
            'score'     => round($hotel->score/10,1),
            'juli'      => getDistance($lat, $lng, $hotel->lat, $hotel->lng),
            'praise_num' => $hotel->praise_num,
            'bad_num'   => $hotel->bad_num,
            'address'   => $hotel->address,
            'tel'       => $hotel->hotel_tel,
            'decoration_time' => $hotel->decoration_time,
            'opening_time'  =>$hotel->opening_time,
            'lat'           => $hotel->lat,
            'lng'           => $hotel->lng,
            'describe'      => $detail->describe,
            'unsubscribe'   => $detail->unsubscribe,
            'check_otice'   => $detail->check_otice,
            'is_wifi'       => $detail->is_wifi,
            'is_water'       => $detail->is_water,
            'is_hairdrier'       => $detail->is_hairdrier,
            'is_airconditioner'       => $detail->is_airconditioner,
            'is_elevator'       => $detail->is_elevator,
            'is_fitnessroom'       => $detail->is_fitnessroom,
            'is_swimmingpool'       => $detail->is_swimmingpool,
            'is_sauna'       => $detail->is_sauna,
            'is_westernfood'       => $detail->is_westernfood,
            'is_chinesefood'       => $detail->is_chinesefood,
            'is_disability'       => $detail->is_disability,
            'is_smokeless'       => $detail->is_smokeless,
            'is_stop'       => $detail->is_stop,
            'is_cereal'       => $detail->is_cereal,
            'is_airportpickup'       => $detail->is_airportpickup,
            'is_station'       => $detail->is_station,
            'is_cabs'       => $detail->is_cabs,
            'is_luggage'       => $detail->is_luggage,
            'is_carrental'       => $detail->is_carrental,
            'is_disabled'       => $detail->is_disabled,
            'is_conference'       => $detail->is_conference,
            'is_express'       => $detail->is_express,
            'is_washclothes'       => $detail->is_washclothes,
            'is_merchant'       => $detail->is_merchant,
            'is_awaken'       => $detail->is_awaken,
            'is_deposit'       => $detail->is_deposit,
            'is_creditcard'       => $detail->is_creditcard,
            'is_reception'       => $detail->is_reception,
            'is_foreignguests'       => $detail->is_foreignguests,
            'is_spa'       => $detail->is_spa,
            'is_chess'       => $detail->is_chess,
        ];
                
        $datas = [
            'detail' => $return,
        ];
        $photo = $this->request->param('photo');
        $photoArr = [];
        if($photo){
           $photos = HotelphotoModel::where(['hotel_id'=>$id])->select();
           foreach($photos as $val){
               $photoArr[]=IMG_URL.getImg($val->photo);
           } 
        }
        if(!empty($photoArr)){
            $datas['photos'] = $photoArr;
            $datas['num'] = count($photoArr);
        }
        $this->result($datas,'200','加载数据成功','json'); 
    }
    
    //获取酒店的套餐和价格
    public function price(){
        $id = (int)$this->request->param('id');
        $bg_date = date('Y-m-d',strtotime($this->request->param('bg_date')));
        $end_date = date('Y-m-d',strtotime($this->request->param('end_date')));
        $id = (int)$this->request->param('id');
        if(empty($id)){
            $this->result([],'400','参数错误','json'); 
        }
        $hotel = HotelModel::get($id);
        if(empty($hotel)){
            $this->result([],'400','参数错误','json'); 
        }
        if($hotel['member_miniapp_id']!= $this->appid){
            $this->result([],'400','参数错误','json'); 
        }
        if($hotel['is_delete'] == 1 || $hotel['is_online'] == 0){
            $this->result([],'400','该房型还未上架','json'); 
        }
        $room = RoomModel::where(['hotel_id'=>$id,'is_online'=>1,'is_delete'=>0])->select();
        $roomids = $room_num = [];
        foreach($room as $val){
           $roomids[$val->room_id] = $val->room_id;
           $room_num[$val->room_id] = $val->day_num;
        }
        //计算客满和日期价格
        $manroom = $roomprice = $unline = [];
        if(!empty($roomids)){
           $prices = HotelpriceModel::where(['room_id'=>['IN',$roomids],'day'=>['BETWEEN',[$bg_date,$end_date]]])->select();;
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
        $config =config('dataattr.hotelbedtype');
        $roomtype = config('dataattr.hotelroomtypenames');
        foreach($room as $val){
            if(!isset($unline[$val->room_id])){
                $return[]=[
                    'id'    => $val->room_id,
                    'title' => $val->title,
                    'area'  => $val->area,
                    'photo' => IMG_URL.  getImg($val->photo),
                    'bed_type' => isset($config[$val->bed_type]) ? $config[$val->bed_type]: '',
                    'bed_width' => $val->bed_width,
                    'bed_logn'  => $val->bed_logn,
                    'bed_num'   => $val->bed_num,
                    'room_type' => isset($config[$val->room_type]) ? $config[$val->room_type]:'',
                    'appropriate_num'=>$val->appropriate_num,
                    'is_wifi'       => $val->is_wifi,
                    'price'         => isset($roomprice[$val->room_id]) ? round($roomprice[$val->room_id]/100,2) : round($val->price/100,2),
                ];
            }            
        }
        $this->result($return,'200','加载房型成功','json'); 
        
    }
    
    
    

    /*
     * 获取酒店评论；1好评 2中频 2差评
     */
    public function  getComment(){
      $hotel_id = (int) $this->request->param('hotel_id');
      $type = (int) $this->request->param('type');
      $HotelModel = new HotelModel();
      if(!$hotel = $HotelModel->find($hotel_id)){
          $this->result([],400,'不存在酒店','json');
      }
      if($hotel->member_miniapp_id != $this->appid){
          $this->result([],400,'不存在酒店','json');
      }
      $where['hotel_id'] = $hotel_id;
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
