<?php
/**
 * @fileName    City.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/25 0025
 */
namespace app\api\controller;
use app\common\model\city\CityModel;
use app\common\model\setting\RegionModel;
use app\common\library\Ip2Region;
class City extends Common{
    /**
     * 获取当前站点的城市列表；
     */
    public function getCityList(){
        $IpLocation  = new Ip2Region();
        $ipdata = $IpLocation->memorySearch('36.5.160.152');
        $region = !empty($ipdata['region']) ? $ipdata['region'] : '';
        $where['member_miniapp_id'] = $this->appid;
        $where['is_open'] = 1;
        $CityModel = new CityModel();
        $city = $CityModel->where($where)->order('orderby desc')->select();
        $province = config('province_id');
        $data = [];
        foreach ($city as $val){
            $default = 0;
            if(!empty($region)){
                if(strstr($region, $val->city_name)){
                    $default = 1;
                }
            }
            $data[] = [
                'city_id'   => $val->city_id,
                'province'  => empty($province[$val->province_id]) ? '' : $province[$val->province_id]['name'],
                'city_name' => $val->city_name,
                'lat'       => $val->lat,
                'lng'       => $val->lng,
                'pinyin'    => $val->pinyin,
                'initial'   => $val->initial,
                'is_open'   => $val->is_open,
                'is_hot'    => $val->is_hot,
                'orderby'   => $val->orderby,
                'default'   => $default,
                'scenic_spot_num' => $val->scenic_spot_num,
                'area_num'  => $val->area_num,
                'administration_num'  => $val->administration_num,
                'station_num'  => $val->station_num,
                'colleges_num'  => $val->colleges_num,
                'hospital_num'  => $val->hospital_num,
            ];
        }
        $this->result($data,'200','数据初始化成功','json');
    }
}