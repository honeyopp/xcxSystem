<?php

namespace app\miniapp\controller\minsu;

use app\common\model\minsu\MinsubrandModel;
use app\common\model\minsu\MinsudetailModel;
use app\common\model\minsu\MinsuphotoModel;
use app\common\model\minsu\MinsupriceModel;
use app\common\model\minsu\MinsuspeciajoinModel;
use app\common\model\minsu\MinsuspecialModel;
use app\common\model\minsu\RoomModel;
use app\common\model\setting\CityModel;
use app\common\model\setting\RegionModel;
use app\miniapp\controller\Common;
use app\common\model\minsu\MinsuModel;
class minsu extends Common
{

    public function index()
    {
        $where = $search = [];
        $search['city_id'] = $this->request->param('city_id');
        if (!empty($search['city_id'])) {
            $where['city_id'] = $search['city_id'];
        }
        $search['city_name']  = (string) $this->request->param('cityname');
        $search['minsu_name'] = $this->request->param('minsu_name');
        if (!empty($search['minsu_name'])) {
            $where['minsu_name'] = array('LIKE', '%' . $search['minsu_name'] . '%');
        }
        $search['minsu_level'] = $this->request->param('minsu_level');
        if (!empty($search['minsu_level'])) {
            $where['minsu_level'] = $search['minsu_level'];
        }
        $search['score'] = $this->request->param('score');
        if (!empty($search['score'])) {
            $where['score'] = $search['score'];
        }
        $where['is_delete'] = 0;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = MinsuModel::where($where)->count();
        $list = MinsuModel::where($where)->order(['minsu_id' => 'desc'])->paginate(10, $count);
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
        $search['minsu_name'] = $this->request->param('minsu_name');
        if (!empty($search['minsu_name'])) {
            $where['minsu_name'] = array('LIKE', '%' . $search['minsu_name'] . '%');
        }
        $search['minsu_level'] = $this->request->param('minsu_level');
        if (!empty($search['minsu_level'])) {
            $where['minsu_level'] = $search['minsu_level'];
        }
        $search['score'] = $this->request->param('score');
        if (!empty($search['score'])) {
            $where['score'] = $search['score'];
        }
        $where['is_delete'] = 0;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = MinsuModel::where($where)->count();
        $list = MinsuModel::where($where)->order(['minsu_id' => 'desc'])->paginate(10, $count);
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
            $data['brand_id'] = (int) $this->request->param('brand_id');
            if(!empty($data['brand_id'])){
               $MinsubrandModel = new MinsubrandModel();
               if(!$brand = $MinsubrandModel->find($data['brand_id']) ){
                   $this->error('不存在品牌',null,101);
               }
               if($brand->member_miniapp_id != $this->miniapp_id){
                   $this->error('不存在品牌',null,101);
               }
            }
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['minsu_name'] = $this->request->param('minsu_name');
            if (empty($data['minsu_name'])) {
                $this->error('民宿名称不能为空', null, 101);
            }
            $data['minsu_tel'] = $this->request->param('minsu_tel');
            if (empty($data['minsu_tel'])) {
                $this->error('民宿电话不能为空', null, 101);
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
            $data['room_num'] = (int) $this->request->param('room_num');
            if (empty($data['room_num'])) {
                $this->error('房源数不能为空', null, 101);
            }
            $data['appropriate'] = $this->request->param('appropriate');
            if (empty($data['appropriate'])) {
                $this->error('每套可入住人数不能为空', null, 101);
            }
            $data['is_online'] = (int)$this->request->param('is_online');
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
            $MinsuModel = new MinsuModel();
            $MinsuModel->save($data);
            $data2['member_miniapp_id'] = $this->miniapp_id;
            $data2['minsu_id'] = $MinsuModel->minsu_id;
            $minsudetailModel = new MinsudetailModel();
            $minsudetailModel->save($data2);
            $this->success('操作成功', null);
        } else {
            return $this->fetch();
        }
    }
    public function edit()
    {
        $minsu_id = (int)$this->request->param('minsu_id');
        $MinsuModel = new MinsuModel();
        if (!$detail = $MinsuModel->get($minsu_id)) {
            $this->error('不存在民宿', null, 101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在民宿");
        }
        $minsudetailModel = new minsudetailModel();
        $where['minsu_id'] = $detail->minsu_id;
        $minsudetail = $minsudetailModel->where($where)->find();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['brand_id'] = (int) $this->request->param('brand_id');
            if(!empty($data['brand_id'])){
                $MinsubrandModel = new MinsubrandModel();
                if(!$brand = $MinsubrandModel->find($data['brand_id']) ){
                    $this->error('不存在品牌',null,101);
                }
                if($brand->member_miniapp_id != $this->miniapp_id){
                    $this->error('不存在品牌',null,101);
                }
            }
            $data['city_id'] = (int)$this->request->param('city_id');
            $CityModel = new CityModel();
            if(!$city = $CityModel->find($data['city_id'])){
                $this->error('不存在该城市',null,101);
            }
            if($city->member_miniapp_id != $this->miniapp_id){
                $this->error('不存在该城市',null,101);
            }
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['minsu_name'] = $this->request->param('minsu_name');
            if (empty($data['minsu_name'])) {
                $this->error('民宿名称不能为空', null, 101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            $data['minsu_tel'] = $this->request->param('minsu_tel');
            if (empty($data['minsu_tel'])) {
                $this->error('民宿电话不能为空', null, 101);
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
            $data['room_num'] = (int) $this->request->param('room_num');
            if (empty($data['room_num'])) {
                $this->error('房源数不能为空', null, 101);
            }
            $data['appropriate'] = $this->request->param('appropriate');
            if (empty($data['appropriate'])) {
                $this->error('每套可入住人数不能为空', null, 101);
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
            $MinsuModel->save($data, ['minsu_id' => $minsu_id]);
            $minsudetailModel->save($data2,['minsu_id'=>$minsu_id]);
            $this->success('操作成功', null);
        } else {
            $MinsubrandModel = new MinsubrandModel();
            $brand = $MinsubrandModel->find($detail->brand_id);
            $this->assign('brand',$brand);
            $CityModel = new CityModel();
            $city = $CityModel->find($detail->city_id);
            $this->assign('city',$city);
            $this->assign('minsudetail',$minsudetail);
            $this->assign('detail', $detail);
            return $this->fetch();
        }
    }
    /**
     * 民宿相册
     */
    public function photo(){
        $minsu_id = (int) $this->request->param('minsu_id');
        $MinsuModel = new MinsuModel();
        if(!$detail = $MinsuModel->find($minsu_id)){
            $this->error('请选择民宿',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择民宿',null,101);
        }
        $minsuphotoModel = new minsuphotoModel();
        $photos  = $minsuphotoModel->where(['minsu_id'=>$minsu_id,'member_miniapp_id'=>$this->miniapp_id])->order(['orderby'=>'desc'])->select();
        $this->assign('photos',$photos);
        $this->assign('minsu_id',$minsu_id);
        $this->assign('detail',$detail);

        return $this->fetch();
    }
    public function photoupdate(){
        $orderby = empty($_POST['orderby']) ? [] : $_POST['orderby'];
        $minsuphotoModel = new minsuphotoModel();
         $minsuIds = [];
         $data = [];
        foreach($orderby as $k=>$v){
          $data[] = ['photo_id'=>$k,'orderby'=>$v];
          $minsuIds[$k] = $k;
        }
        $minsu  = $minsuphotoModel->itemsByIds($minsuIds);
        foreach ($minsu as $val){
            if($val->member_miniapp_id != $this->miniapp_id){
                $this->error('有不存在的图片',null,101);
                break;
            }
        }
        $minsuphotoModel->saveAll($data);
        $this->success('操作成功！',null);
    }

    public function photodelete(){
        $photo_id = (int)$this->request->param('photo_id');
        if(empty($photo_id)){
            $this->error('参数错误',null,101);
        }
        //echo $photo_id;
        $minsuphotoModel = new minsuphotoModel();
        // var_dump($GoodsphotoModel->get($photo_id));
        if(!$photo = $minsuphotoModel->get($photo_id)){
            $this->error('参数错误',null,101);
        }
        if($photo->member_miniapp_id != $this->miniapp_id){
            $this->error('参数错误',null,101);
        }
        $minsuphotoModel->where(['photo_id'=>$photo_id])->delete();
        $this->success('删除成功！',null);
    }

    public function photosave(){
        $minsu_id = (int) $this->request->param('minsu_id');
        $MinsuModel = new MinsuModel();
        if(!$detail = $MinsuModel->find($minsu_id)){
            $this->error('请选择民宿',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择民宿',null,101);
        }
        //$mdl = $this->request->param('mdl');  //后期配缩略图
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $dir = ROOT_PATH . 'attachs' . DS . 'uploads';
        $info = $file->move($dir);
        if($info){
            $img = $info->getSaveName();
            $minsuphotoModel = new minsuphotoModel();
            $minsuphotoModel ->save([
                'minsu_id' => $minsu_id,
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
        $minsu_id = (int) $this->request->param('minsu_id');
        $region_id = (int) $this->request->param('region_id');
        $MinsuModel = new MinsuModel();
        if(!$minsu = $MinsuModel->find($minsu_id)){
            $this->error('不存在民宿',null,101);
        }
        if($minsu->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在民宿',null,101);
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
            if($val->member_miniapp_id != $this->miniapp_id || $val->city_id != $minsu->city_id){
                $this->error('有不存在的区域',null,101);
                break;
            }
        }

        $MinsuModel->save($data,['minsu_id'=>$minsu_id]);
        $this->success('操作成功');
    }


    public function regionselect(){
        $minsu_id = (int) $this->request->param('minsu_id');
        $MinsuModel = new MinsuModel();
        if(!$minsu = $MinsuModel->find($minsu_id)){
            $this->error('不存在民宿',null,101);
        }
        if($minsu->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在民宿',null,101);
        }
        $RegionModel = new RegionModel();
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['city_id'] = $minsu->city_id;
        $list = $RegionModel->where($where)->select();
        $region = [];
        foreach ($list  as $val){
                $region[$val->type][] = $val;
        }
        $this->assign('minsu',$minsu);
        $this->assign('region',$region);
        return $this->fetch();
    }


    /**
     * 民宿上下架；
     */
    public function online (){
        $minsu_id = (int) $this->request->param('minsu_id');
        $MinsuModel = new MinsuModel();
        if(!$minsu = $MinsuModel->find($minsu_id)){
            $this->error("不存在该民宿",null,101);
        }
        if($minsu->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该民宿', null, 101);
        }
        $data['is_online'] = 1;
        if($minsu->is_online == 1){
            $data['is_online'] = 0;
        }
        $MinsuModel->save($data,['minsu_id'=>$minsu_id]);
        $this->success('操作成功');
    }

    public function price(){
        $minsu_id = (int) $this->request->param('minsu_id');
        $MinsuModel = new MinsuModel();
        if(!$minsu = $MinsuModel->find($minsu_id)){
            $this->error('不存在民宿',null,101);
        }
        if($minsu->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在民宿',null,101);
        }
        $minsupriceModel = new minsupriceModel();
        $date   =   $this->request->param('date');
        $date   =   empty($date) ? date('Y-m-d',time()) : $date;
        $_date  =   strtotime($date) ?  date('Y-m-d',strtotime($date)) : date('Y-m-d',time());
        $rooms  =   $minsupriceModel->backPrice($minsu_id,$this->miniapp_id,$_date);
        $this->assign('minsu',$minsu);
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
        $savedata = $roomIds =$minsu = $updatedata =  [];
        $minsu_id = 0;
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
            $minsu[$val->minsu_id] = 1 ;
            $minsu_id = $val->minsu_id;
        }
        if(sizeof($minsu) > 1 || sizeof($rooms) !== sizeof($data)){
            $this->error('有不存在的房间',null,101);
        }

        foreach ($data as $key=>$val){
                if(empty($val['minsuprice_id'])){
                    $savedata[] = [
                        'price'     => ((float) $val['price']) * 100,
                        'minsu_id'  => $minsu_id,
                        'room_id'   => (int)$key,
                        'day'       => $date,
                        'is_online' => empty($val['is_online']) ? 0 : 1 ,
                        'member_miniapp_id' => $this->miniapp_id,

                    ];
                }else{
                    $updatedata[] = [
                        'price_id'  => $val['minsuprice_id'],
                        'price'     => ((float) $val['price']) * 100,
                        'minsu_id'  => $minsu_id,
                        'room_id'   => (int)$key,
                        'day'       => $date,
                        'is_online' => empty($val['is_online']) ? 0 : 1,
                    ];
                }

        }

        $minsupriceModel = new minsupriceModel();
        if(!empty($updatedata)){
            $minsupriceModel->saveAll($updatedata);
        }
        if(!empty($savedata)){
            $minsupriceModel->saveAll($savedata);
        }

        $this->success('操作成功');
    }
    /**
     * @param
     */
    public function delete()
    {
        $minsu_id = (int) $this->request->param('minsu_id');
        $MinsuModel = new MinsuModel();
        if(!$minsu = $MinsuModel->find($minsu_id)){
            $this->error("不存在该民宿",null,101);
        }
        if($minsu->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该民宿', null, 101);
        }
        if($minsu->is_delete == 1){
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $MinsuModel->save($data,['minsu_id'=>$minsu_id]);
        $this->success('操作成功');
    }

    public function special(){
        $minsu_id = (int) $this->request->param('minsu_id');
        $MinsuspeciajoinModel = new MinsuspeciajoinModel();
        $MinsuspecialModel = new MinsuspecialModel();
        $minsuModel = new minsuModel();
        if(!$minsu = $minsuModel->find($minsu_id)){
            $this->error('不存在酒店',null,101);
        }
        if($minsu->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在酒店',null,101);
        }
        if($this->request->method() == "POST"){
            $data = empty($_POST['data']) ? [] : $_POST['data'];
            $specialIds = [];
            foreach ($data as $key=>$val){
                $specialIds[$val] = $val;
            }
            $minsuspecials = $MinsuspecialModel->itemsByIds($specialIds);
            foreach ($minsuspecials as $val){
                if($val->member_miniapp_id != $this->miniapp_id){
                    $this->error('有不存在的专题',null,101);
                }
            }
            if(sizeof($specialIds) != sizeof($minsuspecials)){
                $this->error('有不存在的专题',null,101);
            }
            $data = [];
            foreach ($specialIds as $val){
                $data[] = [
                    'member_miniapp_id' => $this->miniapp_id,
                    'minsu_id'    => $minsu_id,
                    'special_id' => $val
                ];
            }
            $MinsuspeciajoinModel->where(['member_miniapp_id'=>$this->miniapp_id,'minsu_id'=>$minsu_id])->delete();
            $MinsuspeciajoinModel->saveAll($data);
            $this->success('操作成功');
        }else{
            $minsuspecia1 =  $MinsuspeciajoinModel->where(['member_miniapp_id'=>$this->miniapp_id,'minsu_id'=>$minsu_id])->select();
            $list = $MinsuspecialModel->where(['member_miniapp_id'=>$this->miniapp_id])->select();

            $minsuspecia = [];
            foreach ($minsuspecia1 as $val){
                $minsuspecia[$val->special_id] = $val->special_id;
            }
            $this->assign('list',$list);
            $this->assign('minsuspecia',$minsuspecia);
            $this->assign('minsu',$minsu);

            return $this->fetch();
        }
    }


}