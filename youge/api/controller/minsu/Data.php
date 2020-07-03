<?php

/**
 * Created by PhpStorm.
 * 获取所有酒店的配置信息
 * User: Administrator
 * Date: 2017/7/31 0031
 * Time: 10:28
 */

namespace app\api\controller\minsu;

use app\api\controller\Common;
use app\common\model\minsu\BannerModel;
use app\common\model\minsu\MinsubrandModel;
use app\common\model\setting\RegionModel;
use app\common\model\minsu\MinsuspecialModel;

class Data extends Common {

    /**
     * 获取搜索 页面的配置项
     * @param city_id int 城市id;
     * @paran is_host int 1获取 热门 2 获取所有；
     */
    public function getSearch() {
        $city_id = (int) $this->request->param('city_id');
        $datas = []; // 返回的数据
        //查询所有的区域商圈设置
        $Regions = RegionModel::where(['city_id' => $city_id, 'member_miniapp_id' => $this->appid])->order(['orderby' => 'desc'])->select();

        //返回 数据 长度 及 数据
        $scenic = $area = $administration = $colleges = $hospital = $station = [];

        foreach ($Regions as $val) {
            switch ($val->type) {
                case 1:
                    $scenic[] = [
                        'id' => $val->region_id,
                        'name' => $val->region_name,
                        'is_hot' => $val->is_hot,
                    ];
                    break;
                case 2:
                    $area[] = [
                        'id' => $val->region_id,
                        'name' => $val->region_name,
                        'is_hot' => $val->is_hot,
                    ];
                    break;
                case 3:
                    $administration[] = [
                        'id' => $val->region_id,
                        'name' => $val->region_name,
                        'is_hot' => $val->is_hot,
                    ];
                    break;
                case 4:
                   $station[] = [
                        'id' => $val->region_id,
                        'name' => $val->region_name,
                        'is_hot' => $val->is_hot,
                    ];
                    break;
                case 5:
                    $colleges[] = [
                        'id' => $val->region_id,
                        'name' => $val->region_name,
                        'is_hot' => $val->is_hot,
                    ];
                    break;
                case 6: 
                    $hospital[] = [
                        'id' => $val->region_id,
                        'name' => $val->region_name,
                        'is_hot' => $val->is_hot,
                    ];
                    break;
            }
        }

        $Brand = MinsubrandModel::where(['member_miniapp_id' => $this->appid])->order(['orderby' => 'desc'])->select();
        $Special = MinsuspecialModel::where(['member_miniapp_id' => $this->appid])->order(['orderby' => 'desc'])->select();
        $brandList = $specialList = [];
        foreach ($Brand as $val) {
            $brandList[] = [
                'id' => $val->brand_id,
                'name' => $val->brand_name,
                'photo' => IMG_URL . getImg($val->photo),
                'bloc' => $val->bloc,
            ];
        }
        foreach ($Special as $val) {
            $specialList[] = [
                'id' => $val->special_id,
                'name' => $val->special_title1,
                'photo' => empty($val->photo) ? '' : IMG_URL . getImg($val->photo),
            ];
        }

        $datas = [
            'scenic' => $scenic,
            'scenicNum' => count($scenic),
            'area' => $area,
            'areaNum' => count($area),
            'administration' => $administration,
            'administrationNum' => count($administration),
            'colleges' => $colleges,
            'collegesNum' => count($colleges),
            'hospital' => $hospital,
            'hospitalNum' => count($hospital),
            'station' => $station,
            'stationNum' => count($station),
            'brand' => $brandList,
            'brandListNum' => count($brandList),
            'specialNum' => count($specialList),
            'special' => $specialList,
        ];
        $this->result($datas,200,'获取数据成功','json');
    }

    /*
     * 获取床铺类型配置项；
     * */
    public function getBedType(){
        $datas = config("dataattr.minsubedtype");
        $data = [];
        foreach ($datas as  $key=>$val){
                $data[] = [
                    'type_name'=> $val,
                    'id' => $key,
                ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }


    public function getBanner(){
        $where['member_miniapp_id'] = $this->appid;
        $BannerModel  = new BannerModel();
        $banner = $BannerModel->where($where)->order("orderby desc")->limit(0,10)->select();
        if(empty($banner)){
            $this->result([],'200','数据初始化成功','json');
        }
        $data = [];
        foreach ($banner as $val){
            $data[] = [
                'photo' => IMG_URL .getImg($val->photo),
            ];
        }
        $this->result($data,'200','数据初始化成功','json');
    }

}
