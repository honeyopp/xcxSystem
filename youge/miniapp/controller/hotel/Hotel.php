<?php

namespace app\miniapp\controller\hotel;

use app\common\model\hotel\HotelbrandModel;
use app\common\model\hotel\HoteldetailModel;
use app\common\model\hotel\HotelphotoModel;
use app\common\model\hotel\HotelpriceModel;
use app\common\model\hotel\HotelspeciajoinModel;
use app\common\model\hotel\HotelspecialModel;
use app\common\model\hotel\RoomModel;
use app\common\model\setting\CityModel;
use app\common\model\setting\RegionModel;
use app\miniapp\controller\Common;
use app\common\model\hotel\HotelModel;

class Hotel extends Common
{

    public function index()
    {
        $where = $search = [];
        $search['city_id'] = $this->request->param('city_id');
        if (!empty($search['city_id'])) {
            $where['city_id'] = $search['city_id'];
        }
        $search['city_name']  = (string) $this->request->param('cityname');
        $search['hotel_name'] = $this->request->param('hotel_name');
        if (!empty($search['hotel_name'])) {
            $where['hotel_name'] = array('LIKE', '%' . $search['hotel_name'] . '%');
        }
        $search['hotel_level'] = $this->request->param('hotel_level');
        if (!empty($search['hotel_level'])) {
            $where['hotel_level'] = $search['hotel_level'];
        }
        $search['score'] = $this->request->param('score');
        if (!empty($search['score'])) {
            $where['score'] = $search['score'];
        }
        $where['is_delete'] = 0;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = HotelModel::where($where)->count();
        $list = HotelModel::where($where)->order(['hotel_id' => 'desc'])->paginate(10, $count);
        $cityIds = [];
        foreach ($list as $val){
            $cityIds[$val->city_id] = $val->city_id;
        }
        $CityModel = new  CityModel();
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('city',$CityModel->itemsByIds($cityIds));
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }

    public function select (){
        $where = $search = [];
        $search['city_id'] = $this->request->param('city_id');
        if (!empty($search['city_id'])) {
            $where['city_id'] = $search['city_id'];
        }
        $search['city_name']  = (string) $this->request->param('cityname');
        $search['hotel_name'] = $this->request->param('hotel_name');
        if (!empty($search['hotel_name'])) {
            $where['hotel_name'] = array('LIKE', '%' . $search['hotel_name'] . '%');
        }
        $search['hotel_level'] = $this->request->param('hotel_level');
        if (!empty($search['hotel_level'])) {
            $where['hotel_level'] = $search['hotel_level'];
        }
        $search['score'] = $this->request->param('score');
        if (!empty($search['score'])) {
            $where['score'] = $search['score'];
        }
        $where['is_delete'] = 0;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = HotelModel::where($where)->count();
        $list = HotelModel::where($where)->order(['hotel_id' => 'desc'])->paginate(10, $count);
        $cityIds = [];
        foreach ($list as $val){
            $cityIds[$val->city_id] = $val->city_id;
        }
        $CityModel = new  CityModel();
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('city',$CityModel->itemsByIds($cityIds));
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }
    public function create()
    {
        if ($this->request->method() == 'POST') {
            $data = [];

            $data['city_id'] = (int)$this->request->param('city_id');
            $CityModel = new CityModel();
            if(!$city = $CityModel->find($data['city_id'])){
                $this->error('不存在该城市',null,101);
            }
            if($city->member_miniapp_id != $this->miniapp_id){
                $this->error('不存在该城市',null,101);
            }
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['hotel_name'] = $this->request->param('hotel_name');
            if (empty($data['hotel_name'])) {
                $this->error('酒店名称不能为空', null, 101);
            }
            $data['hotel_level'] = (int)$this->request->param('hotel_level');
            if (empty($data['hotel_level'])) {
                $this->error('酒店等级不能为空', null, 101);
            }
            $data['brand_id'] = (int) $this->request->param('brand_id');
            $HotelbrandModel = new HotelbrandModel();
            if(!$brand = $HotelbrandModel->find($data['brand_id']) ){
                $this->error('不存在品牌',null,101);
            }
            if($brand->member_miniapp_id != $this->miniapp_id){
                $this->error('不存在品牌',null,101);
            }
            $data['hotel_tel'] = $this->request->param('hotel_tel');
            if (empty($data['hotel_tel'])) {
                $this->error('酒店电话不能为空', null, 101);
            }
            $data['decoration_time'] =  $this->request->param('decoration_time');
            if (empty($data['decoration_time'])) {
                $this->error('最后装修时间不能为空', null, 101);
            }
            $data['opening_time'] = $this->request->param('opening_time');
            if (empty($data['opening_time'])) {
                $this->error('营业时间不能为空', null, 101);
            }

            $data['photo'] = (string) $this->request->param('photo');
            if (empty($data['photo'])) {
                $this->error('图片不能为空', null, 101);
            }
            $data['banner'] = (string) $this->request->param('banner');
            if (empty($data['banner'])) {
                $this->error('banner图片不能为空', null, 101);
            }
            $data['score'] = ((float) $this->request->param('score') * 10);
            if (empty($data['score'])) {
                $this->error('评分不能为空', null, 101);
            }
            if($data['score'] < 0 || $data['score'] > 50){
                $this->error('评分错误',null,101);
            }
            $data['advantage'] = $this->request->param('advantage');
            if (empty($data['advantage'])) {
                $this->error('附近不能为空', null, 101);
            }
            $data['lat'] = $this->request->param('lat');
            if (empty($data['lat'])) {
                $this->error('经度不能为空', null, 101);
            }
            $data['lng'] = $this->request->param('lng');
            if (empty($data['lng'])) {
                $this->error('纬度不能为空', null, 101);
            }
            $data['address'] = $this->request->param('address');
            if (empty($data['address'])) {
                $this->error('地址不能为空', null, 101);
            }
            $data['is_online'] = (int)$this->request->param('is_online');
            if (empty($data['is_online'])) {
                $this->error('是否营业不能为空', null, 101);
            }
            $data2['describe'] = $this->request->param('describe');
            if(empty($data2['describe'])){
                $this->error('详情不能为空',null,101);
            }
            $data2['unsubscribe'] = $this->request->param('unsubscribe');
            if(empty($data2['unsubscribe'])){
                $this->error('退订规则不能为空',null,101);
            }
            $data2['check_otice'] = $this->request->param('check_otice');
            if(empty($data2['check_otice'])){
                $this->error('入住须知不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            $data2['is_wifi'] = (int) $this->request->param('is_wifi');
            $data2['is_water'] = (int) $this->request->param('is_water');
            $data2['is_hairdrier'] = (int) $this->request->param('is_hairdrier');
            $data2['is_airconditioner'] = (int) $this->request->param('is_airconditioner');
            $data2['is_elevator'] = (int) $this->request->param('is_elevator');
            $data2['is_fitnessroom'] = (int) $this->request->param('is_fitnessroom');
            $data2['is_swimmingpool'] = (int) $this->request->param('is_swimmingpool');
            $data2['is_sauna'] = (int) $this->request->param('is_sauna');
            $data2['is_westernfood'] = (int) $this->request->param('is_westernfood');
            $data2['is_chinesefood'] = (int) $this->request->param('is_chinesefood');
            $data2['is_disability'] = (int) $this->request->param('is_disability');
            $data2['is_smokeless'] = (int) $this->request->param('is_smokeless');
            $data2['is_stop'] = (int) $this->request->param('is_stop');
            $data2['is_cereal'] = (int) $this->request->param('is_cereal');
            $data2['is_airportpickup'] = (int) $this->request->param('is_airportpickup');
            $data2['is_station'] = (int) $this->request->param('is_station');
            $data2['is_cabs'] =  (int) $this->request->param('is_cabs');
            $data2['is_luggage'] = (int) $this->request->param('is_luggage');
            $data2['is_carrental'] = (int) $this->request->param('is_carrental');
            $data2['is_disabled'] = (int) $this->request->param('is_disabled');
            $data2['is_conference'] = (int) $this->request->param('is_conference');
            $data2['is_express'] = (int) $this->request->param('is_express');
            $data2['is_washclothes'] = (int) $this->request->param('is_washclothes');
            $data2['is_merchant'] = (int) $this->request->param('is_merchant');
            $data2['is_awaken'] = (int) $this->request->param('is_awaken');
            $data2['is_deposit'] = (int) $this->request->param('is_deposit');
            $data2['is_creditcard'] = (int) $this->request->param('is_creditcard');
            $data2['is_reception'] = (int) $this->request->param('is_reception');
            $data2['is_foreignguests'] = (int) $this->request->param('is_foreignguests');
            $HotelModel = new HotelModel();
            $HotelModel->save($data);
            $data2['member_miniapp_id'] = $this->miniapp_id;
            $data2['hotel_id'] = $HotelModel->hotel_id;
            $HoteldetailModel = new HoteldetailModel();
            $HoteldetailModel->save($data2);
            $this->success('操作成功', null);
        } else {
            $HotelspecialModel = new HotelspecialModel();
            $special = $HotelspecialModel->where(['member_miniapp_id'=>$this->miniapp_id])->select();
            $this->assign('special',$special);
            return $this->fetch();
        }
    }
    public function edit()
    {
        $hotel_id = (int)$this->request->param('hotel_id');
        $HotelModel = new HotelModel();
        if (!$detail = $HotelModel->get($hotel_id)) {
            $this->error('不存在酒店', null, 101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在酒店");
        }
        $HoteldetailModel = new HoteldetailModel();
        $where['hotel_id'] = $detail->hotel_id;
        $hoteldetail = $HoteldetailModel->where($where)->find();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['city_id'] = (int)$this->request->param('city_id');
            $CityModel = new CityModel();
            if(!$city = $CityModel->find($data['city_id'])){
                $this->error('不存在该城市',null,101);
            }
            if($city->member_miniapp_id != $this->miniapp_id){
                $this->error('不存在该城市',null,101);
            }
            $data['brand_id'] = (int) $this->request->param('brand_id');
            $HotelbrandModel = new HotelbrandModel();
            if(!$brand = $HotelbrandModel->find($data['brand_id']) ){
                $this->error('不存在品牌',null,101);
            }
            if($brand->member_miniapp_id != $this->miniapp_id){
                $this->error('不存在品牌',null,101);
            }
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['hotel_name'] = $this->request->param('hotel_name');
            if (empty($data['hotel_name'])) {
                $this->error('酒店名称不能为空', null, 101);
            }
            $data['hotel_level'] = (int)$this->request->param('hotel_level');
            if (empty($data['hotel_level'])) {
                $this->error('酒店等级不能为空', null, 101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            $data['hotel_tel'] = $this->request->param('hotel_tel');
            if (empty($data['hotel_tel'])) {
                $this->error('酒店电话不能为空', null, 101);
            }
            $data['decoration_time'] = $this->request->param('decoration_time');
            if (empty($data['decoration_time'])) {
                $this->error('最后装修时间不能为空', null, 101);
            }
            $data['opening_time'] = $this->request->param('opening_time');
            if (empty($data['opening_time'])) {
                $this->error('营业时间不能为空', null, 101);
            }
            $data['photo'] = (string) $this->request->param('photo');
            if (empty($data['photo'])) {
                $this->error('图片不能为空', null, 101);
            }
            $data['banner'] = (string) $this->request->param('banner');
            if (empty($data['banner'])) {
                $this->error('图片不能为空', null, 101);
            }
            $data['score'] = ((float) $this->request->param('score') * 10);
            if (empty($data['score'])) {
                $this->error('评分不能为空', null, 101);
            }
            if($data['score'] < 0 || $data['score'] > 50){
                $this->error('评分错误',null,101);
            }
            $data['advantage'] = $this->request->param('advantage');
            if (empty($data['advantage'])) {
                $this->error('附近不能为空', null, 101);
            }
            $data['lat'] = $this->request->param('lat');
            if (empty($data['lat'])) {
                $this->error('经度不能为空', null, 101);
            }
            $data['lng'] = $this->request->param('lng');
            if (empty($data['lng'])) {
                $this->error('纬度不能为空', null, 101);
            }
            $data['address'] = $this->request->param('address');
            if (empty($data['address'])) {
                $this->error('地址不能为空', null, 101);
            }
            $data['is_online'] = (int)$this->request->param('is_online');
            if (empty($data['is_online'])) {
                $this->error('是否营业不能为空', null, 101);
            }
            $data2['describe'] = $this->request->param('describe');
            if(empty($data2['describe'])){
                $this->error('详情不能为空',null,101);
            }
            $data2['unsubscribe'] = $this->request->param('unsubscribe');
            if(empty($data2['unsubscribe'])){
                $this->error('退订规则不能为空',null,101);
            }
            $data2['check_otice'] = $this->request->param('check_otice');
            if(empty($data2['check_otice'])){
                $this->error('入住须知不能为空',null,101);
            }
            $data2['is_wifi'] = (int) $this->request->param('is_wifi');
            $data2['is_water'] = (int) $this->request->param('is_water');
            $data2['is_hairdrier'] = (int) $this->request->param('is_hairdrier');
            $data2['is_airconditioner'] = (int) $this->request->param('is_airconditioner');
            $data2['is_elevator'] = (int) $this->request->param('is_elevator');
            $data2['is_fitnessroom'] = (int) $this->request->param('is_fitnessroom');
            $data2['is_swimmingpool'] = (int) $this->request->param('is_swimmingpool');
            $data2['is_sauna'] = (int) $this->request->param('is_sauna');
            $data2['is_westernfood'] = (int) $this->request->param('is_westernfood');
            $data2['is_chinesefood'] = (int) $this->request->param('is_chinesefood');
            $data2['is_disability'] = (int) $this->request->param('is_disability');
            $data2['is_smokeless'] = (int) $this->request->param('is_smokeless');
            $data2['is_stop'] = (int) $this->request->param('is_stop');
            $data2['is_cereal'] = (int) $this->request->param('is_cereal');
            $data2['is_airportpickup'] = (int) $this->request->param('is_airportpickup');
            $data2['is_station'] = (int) $this->request->param('is_station');
            $data2['is_cabs'] =  (int) $this->request->param('is_cabs');
            $data2['is_luggage'] = (int) $this->request->param('is_luggage');
            $data2['is_carrental'] = (int) $this->request->param('is_carrental');
            $data2['is_disabled'] = (int) $this->request->param('is_disabled');
            $data2['is_conference'] = (int) $this->request->param('is_conference');
            $data2['is_express'] = (int) $this->request->param('is_express');
            $data2['is_washclothes'] = (int) $this->request->param('is_washclothes');
            $data2['is_merchant'] = (int) $this->request->param('is_merchant');
            $data2['is_awaken'] = (int) $this->request->param('is_awaken');
            $data2['is_deposit'] = (int) $this->request->param('is_deposit');
            $data2['is_creditcard'] = (int) $this->request->param('is_creditcard');
            $data2['is_reception'] = (int) $this->request->param('is_reception');
            $data2['is_foreignguests'] = (int) $this->request->param('is_foreignguests');
            $HotelModel->save($data, ['hotel_id' => $hotel_id]);
            $HoteldetailModel->save($data2,['hotel_id'=>$hotel_id]);
            $this->success('操作成功', null);
        } else {
            $HotelbrandModel = new HotelbrandModel();
            $brand = $HotelbrandModel->find($detail->brand_id);
            $this->assign('brand',$brand);
            $CityModel = new CityModel();
            $city = $CityModel->find($detail->city_id);
            $this->assign('city',$city);
            $this->assign('hoteldetail',$hoteldetail);
            $this->assign('detail', $detail);
            return $this->fetch();
        }
    }

    /**
     * 酒店相册
     */
    public function photo(){
        $hotel_id = (int) $this->request->param('hotel_id');
        $HotelModel = new HotelModel();
        if(!$detail = $HotelModel->find($hotel_id)){
            $this->error('请选择酒店',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择酒店',null,101);
        }
        $HotelphotoModel = new HotelphotoModel();
        $photos  = $HotelphotoModel->where(['hotel_id'=>$hotel_id,'member_miniapp_id'=>$this->miniapp_id])->order(['orderby'=>'desc'])->select();
        $this->assign('photos',$photos);
        $this->assign('hotel_id',$hotel_id);
        $this->assign('detail',$detail);

        return $this->fetch();
    }
    public function photoupdate(){
        $orderby = empty($_POST['orderby']) ? [] : $_POST['orderby'];
        $HotelphotoModel = new HotelphotoModel();
         $HotelIds = [];
         $data = [];
        foreach($orderby as $k=>$v){
          $data[] = ['photo_id'=>$k,'orderby'=>$v];
          $HotelIds[$k] = $k;
        }
        $hotel  = $HotelphotoModel->itemsByIds($HotelIds);
        foreach ($hotel as $val){
            if($val->member_miniapp_id != $this->miniapp_id){
                $this->error('有不存在的图片',null,101);
                break;
            }
        }
        $HotelphotoModel->saveAll($data);
        $this->success('操作成功！',null);
    }

    public function photodelete(){
        $photo_id = (int)$this->request->param('photo_id');
        if(empty($photo_id)){
            $this->error('参数错误',null,101);
        }
        //echo $photo_id;
        $HotelphotoModel = new HotelphotoModel();
        // var_dump($GoodsphotoModel->get($photo_id));
        if(!$photo = $HotelphotoModel->get($photo_id)){
            $this->error('参数错误',null,101);
        }
        if($photo->member_miniapp_id != $this->miniapp_id){
            $this->error('参数错误',null,101);
        }
        $HotelphotoModel->where(['photo_id'=>$photo_id])->delete();
        $this->success('删除成功！',null);
    }

    public function photosave(){
        $hotel_id = (int) $this->request->param('hotel_id');
        $HotelModel = new HotelModel();
        if(!$detail = $HotelModel->find($hotel_id)){
            $this->error('请选择酒店',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择酒店',null,101);
        }
        //$mdl = $this->request->param('mdl');  //后期配缩略图
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $dir = ROOT_PATH . 'attachs' . DS . 'uploads';
        $info = $file->move($dir);
        if($info){
            $img = $info->getSaveName();
            $HotelphotoModel = new HotelphotoModel();
            $HotelphotoModel ->save([
                'hotel_id' => $hotel_id,
                'photo'    => $img,
                'member_miniapp_id' => $this->miniapp_id,
                'add_time'  => $this->request->time(),
            ]);
            echo $img;
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }


    public function region(){
        $hotel_id = (int) $this->request->param('hotel_id');
        $region_id = (int) $this->request->param('region_id');
        $HotelModel = new HotelModel();
        if(!$hotel = $HotelModel->find($hotel_id)){
            $this->error('不存在酒店',null,101);
        }
        if($hotel->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在酒店',null,101);
        }
        $data['scenic_spot_id'] = (int) $this->request->param('scenic_spot_id');
        $data['area_id'] = (int) $this->request->param('area_id');
        $data['administration_id'] = (int) $this->request->param('administration_id');
        $data['station_id'] = (int) $this->request->param('station_id');
        $data['colleges_id'] = (int) $this->request->param('colleges_id');
        $data['hospital_id'] = (int) $this->request->param('hospital_id');
        $regionIds = [];
        if(!empty( $data['scenic_spot_id'] )){
            $regionIds[] = $data['scenic_spot_id'];
        }
        if(!empty($data['area_id'])){
            $regionIds[] = $data['area_id'];
        }
        if(!empty($data['administration_id'])){
            $regionIds[] = $data['administration_id'];
        }
        if(!empty($data['station_id'])){
            $regionIds[] = $data['station_id'];
        }
        if(!empty($data['colleges_id'])){
            $regionIds[] = $data['colleges_id'];
        }
        if(!empty($data['hospital_id'])){
            $regionIds[] = $data['hospital_id'];
        }
        $RegionModel= new RegionModel();
        $region = $RegionModel->itemsByIds($regionIds);
        if(sizeof($regionIds) != sizeof($region)){
            $this->error('有不存在的区域',null,101);
        }

        foreach ($region as $val){
            if($val->member_miniapp_id != $this->miniapp_id || $val->city_id != $hotel->city_id){
                $this->error('有不存在的区域',null,101);
                break;
            }
        }

        $HotelModel->save($data,['hotel_id'=>$hotel_id]);
        $this->success('操作成功');
    }


    public function regionselect(){
        $hotel_id = (int) $this->request->param('hotel_id');
        $HotelModel = new HotelModel();
        if(!$hotel = $HotelModel->find($hotel_id)){
            $this->error('不存在酒店',null,101);
        }
        if($hotel->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在酒店',null,101);
        }
        $RegionModel = new RegionModel();
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['city_id'] = $hotel->city_id;
        $list = $RegionModel->where($where)->select();
        $region = [];
        foreach ($list  as $val){
                $region[$val->type][] = $val;
        }
        $this->assign('hotel',$hotel);
        $this->assign('region',$region);
        return $this->fetch();
    }


    /**
     * 酒店上下架；
     */
    public function online (){
        $hotel_id = (int) $this->request->param('hotel_id');
        $HotelModel = new HotelModel();
        if(!$hotel = $HotelModel->find($hotel_id)){
            $this->error("不存在该酒店",null,101);
        }
        if($hotel->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该酒店', null, 101);
        }
        $data['is_online'] = 1;
        if($hotel->is_online == 1){
            $data['is_online'] = 0;
        }
        $HotelModel->save($data,['hotel_id'=>$hotel_id]);
        $this->success('操作成功');
    }

    public function price(){
        $hotel_id = (int) $this->request->param('hotel_id');
        $HoTelModel = new HotelModel();
        if(!$hotel = $HoTelModel->find($hotel_id)){
            $this->error('不存在酒店',null,101);
        }
        if($hotel->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在酒店',null,101);
        }
        $HotelpriceModel = new HotelpriceModel();
        $date   =   $this->request->param('date');
        $date   =   empty($date) ? date('Y-m-d',time()) : $date;
        $_date  =   strtotime($date) ?  date('Y-m-d',strtotime($date)) : date('Y-m-d',time());
        $rooms  =   $HotelpriceModel->backPrice($hotel_id,$this->miniapp_id,$_date);
        $this->assign('hotel',$hotel);
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
        $savedata = $roomIds =$hotel = $updatedata =  [];
        $hotel_id = 0;
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
            $hotel[$val->hotel_id] = 1 ;
            $hotel_id = $val->hotel_id;
        }
        if(sizeof($hotel) > 1 || sizeof($rooms) !== sizeof($data)){
            $this->error('有不存在的房间',null,101);
        }

        foreach ($data as $key=>$val){
                if(empty($val['hotelprice_id'])){
                    $savedata[] = [
                        'price'     => ((float) $val['price']) * 100,
                        'hotel_id'  => $hotel_id,
                        'room_id'   => (int)$key,
                        'day'       => $date,
                        'is_online' => empty($val['is_online']) ? 0 : 1 ,
                        'member_miniapp_id' => $this->miniapp_id,

                    ];
                }else{
                    $updatedata[] = [
                        'price_id'  => $val['hotelprice_id'],
                        'price'     => ((float) $val['price']) * 100,
                        'hotel_id'  => $hotel_id,
                        'room_id'   => (int)$key,
                        'day'       => $date,
                        'is_online' => empty($val['is_online']) ? 0 : 1,
                    ];
                }

        }

        $HotelpriceModel = new HotelpriceModel();
        if(!empty($updatedata)){
            $HotelpriceModel->saveAll($updatedata);
        }
        if(!empty($savedata)){
            $HotelpriceModel->saveAll($savedata);
        }

        $this->success('操作成功');
    }
    /**
     * @param
     */
    public function delete()
    {
        $hotel_id = (int) $this->request->param('hotel_id');
        $HotelModel = new HotelModel();
        if(!$hotel = $HotelModel->find($hotel_id)){
            $this->error("不存在该酒店",null,101);
        }
        if($hotel->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该酒店', null, 101);
        }
        if($hotel->is_delete == 1){
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $HotelModel->save($data,['hotel_id'=>$hotel_id]);
        $this->success('操作成功');
    }


    /*
     *出行特色设置
     * */

    public function special(){
        $hotel_id = (int) $this->request->param('hotel_id');
        $HotelspeciajoinModel = new HotelspeciajoinModel();
        $HotelspecialModel = new HotelspecialModel();
        $HotelModel = new HotelModel();
        if(!$hotel = $HotelModel->find($hotel_id)){
            $this->error('不存在酒店',null,101);
        }
        if($hotel->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在酒店',null,101);
        }
        if($this->request->method() == "POST"){
            $data = empty($_POST['data']) ? [] : $_POST['data'];
            $specialIds = [];
            foreach ($data as $key=>$val){
                $specialIds[$val] = $val;
            }
            $hotelspecials = $HotelspecialModel->itemsByIds($specialIds);
            foreach ($hotelspecials as $val){
                if($val->member_miniapp_id != $this->miniapp_id){
                    $this->error('有不存在的专题',null,101);
                }
            }
            if(sizeof($specialIds) != sizeof($hotelspecials)){
                $this->error('有不存在的专题',null,101);
            }
            $data = [];
            foreach ($specialIds as $val){
                $data[] = [
                    'member_miniapp_id' => $this->miniapp_id,
                    'hotel_id'    => $hotel_id,
                    'special_id' => $val
                ];
            }
            $HotelspeciajoinModel->where(['member_miniapp_id'=>$this->miniapp_id,'hotel_id'=>$hotel_id])->delete();
            $HotelspeciajoinModel->saveAll($data);
            $this->success('操作成功');
        }else{
             $hotelspecia1 =  $HotelspeciajoinModel->where(['member_miniapp_id'=>$this->miniapp_id,'hotel_id'=>$hotel_id])->select();
             $list = $HotelspecialModel->where(['member_miniapp_id'=>$this->miniapp_id])->select();

            $hotelspecia = [];
             foreach ($hotelspecia1 as $val){
                 $hotelspecia[$val->special_id] = $val->special_id;
             }
            $this->assign('list',$list);
             $this->assign('hotelspecia',$hotelspecia);
             $this->assign('hotel',$hotel);

             return $this->fetch();
        }
    }

}