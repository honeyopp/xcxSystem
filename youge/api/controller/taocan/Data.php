<?php
namespace app\api\controller\taocan;

use app\api\controller\Common;
use app\common\model\city\CityModel;
use app\common\model\taocan\BannerModel;
use app\common\model\taocan\DestinationModel;
use app\common\model\taocan\NavcityModel;
use app\common\model\taocan\NavModel;
use app\common\model\taocan\PackageModel;
use app\common\model\taocan\TaocanModel;
use app\miniapp\controller\taocan\Nav;

class  Data extends Common
{
    /*
     *  @param type int 0 获取全部数据库  1 获取banner  2 获取NAV 3 获取目的地推荐 4获取门票推荐
     *
     */
    public function getIndex(){
        $city_id = (int)$this->request->param('city_id');
        $type = (int) $this->request->param('type');
        $CityModel = new CityModel();
        if (!$city = $CityModel->find($city_id)) {
            $this->result([], '400', '不存在城市', 'json');
        }
        if ($city->member_miniapp_id != $this->appid) {
            $this->result([], '400', '不存在城市', 'json');
        }
        $where['city_id'] = $city_id;
        //获取banner；
        if($type == 0 || $type == 1){
            $BannerModel = new BannerModel();
            $banner_where['member_miniapp_id'] = $this->appid;
            $banner = $BannerModel->where($banner_where)->order("orderby desc")->limit(0,6)->select();
            $data['banner'] = [];
            foreach ($banner as $val){
                $data['banner'][] = [
                    'banner_id' => $val->banner_id,
                    'url'  => IMG_URL . getImg($val->photo),
                    'link'    => $val->url,
                ];
            }
        }

       //获取NAV
        if($type == 0 || $type == 2){
            $NavcityModel = new NavcityModel();
            if (!$city_navs = $NavcityModel->where($where)->find()) {
                $this->result([], '200', '数据初始化成功', 'json');
            }
            $navIds = explode(',', $city_navs->nav_ids);
            $NavModel = new NavModel();
            $navs = $NavModel->itemsByIds($navIds);
            $data['nav'] = [];
            foreach ($navs as $val) {
                $data['nav'][] = [
                    'nav_id' => $val->nav_id,
                    'nav_name' => $val->nav_name,
                    'nav_ico' => IMG_URL . getImg($val->nav_ico),
                    'orderby' => $val->orderby,
                ];
            }
            //降序排列数组;
            $data['nav'] =  array_sort($data['nav'],'orderby','desc');
        }

        if($type == 0 || $type == 3){
            //目的地推荐 首页 最多提取前15条;
            $DestinationModel = new DestinationModel();
            $destination = $DestinationModel
                             ->where(['member_miniapp_id'=>$this->appid,'city_id'=>$city->city_id,'is_delete'=>0])
                             ->order('orderby desc')
                             ->limit(0,14)
                             ->select();
            $destinations = [];
            foreach ($destination as $val){
                $destinations[]= [
                        'destination_id' => $val->destination_id,
                        'province_id'  => $val->province_id,
                        'title'   => $val->title,
                        'photo'  => IMG_URL . getImg($val->photo),
                    ];
            }
            $data['destination_num']  = $length =  floor(sizeof($destinations)/3) + (floor(sizeof($destinations)%3) > 0 ? 1:0 );
            for($i=0;$i<$length;$i++)
            {
                $data['destination'][] = array_slice($destinations, $i * 3 ,3);
            }
        }
        if($type == 0 || $type == 4){
            //门票推荐；
            $TaocanModel = new TaocanModel();
            //$p_where['province_id'] = $city->province_id;
            $p_where['member_miniapp_id'] = $this->appid;
            $p_where['is_delete'] = 0;
            $p_where['is_online'] = 1;
            $package = $TaocanModel->where($p_where)->order('orderby desc')->limit($this->limit_bg,$this->limit_num)->select();
            $data['package'] = [];
            if (empty($package)){
                $this->result($data,'200','没有数据了','json');
            }
            $cityIds = [];
            foreach ($package as $val){
                $cityIds[$val->city_id] = $val->city_id;
            }
            $citys = $CityModel->itemsByIds($cityIds);
            foreach ($package as $val){
                $data['package'][] = [
                    'taocan_id'  => $val->taocan_id,
                    'store_id'   => $val->store_id,
                    'photo'      => IMG_URL . getImg($val->photo),
                    'city'       => empty($citys[$val->city_id]) ? '' : $citys[$val->city_id]->city_name,
                    'order_num'  => $val->order_num,
                    'price'      => sprintf("%.2f",$val->price/100),
                    'taocan_name' => $val->taocan_name,
                ];
            }
        }
        $this->result($data,200,'数据初始化成功','json');
    }



}