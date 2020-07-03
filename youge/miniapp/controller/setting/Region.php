<?php
/**
 * @fileName    Region.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/20 0020
 */
namespace app\miniapp\controller\setting;
use app\common\model\city\CityModel;
use app\common\model\setting\RegionModel;
use app\miniapp\controller\Common;

class  Region extends Common{
    public function index() {
        $where = $search = [];
        $where['member_miniapp_id'] = $this->miniapp_id;
        $search['type']  = (int) $this->request->param('type');
        if($search['type'] > 0 && $search['type'] < 7){
            $where['type'] = $search['type'];
        }
        $search['city_id'] = (int) $this->request->param('city_id');
        if(!empty($search['city_id'])){
            $where['city_id'] = $search['city_id'];
        }
        $ReionModel = new RegionModel();
        $count = $ReionModel->where($where)->count();
        $list = $ReionModel->where($where)->order(['region_id'=>'desc'])->paginate(10, $count);
        $cityIds = [];
        foreach ($list as $val) {
           $cityIds[$val->city_id] = $val->city_id;
        }
        $CityModel = new CityModel();
        $citys = $CityModel->itemsByIds($cityIds);
        $page = $list->render();
        $this->assign('citys',$citys);
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
            $data['type'] = (int) $this->request->param('type');
            if(empty($data['type'])){
                $this->error('类型不能为空',null,101);
            }
            $data['city_id'] = (int) $this->request->param('city_id');
            if(empty($data['city_id'])){
                $this->error('城市不能为空',null,101);
            }
            $CityModel = new CityModel();
            if(!$city = $CityModel->get($data['city_id'])){
                $this->error('请选择要编辑的城市',null,101);
            }
            if($city->member_miniapp_id != $this->miniapp_id){
                $this->error("请选择要编辑的城市",null,101);
            }
            $data['photo'] = $this->request->param('photo');

            $data['region_name'] = $this->request->param('region_name');
            if(empty($data['region_name'])){
                $this->error('区域名称不能为空',null,101);
            }
            $data['lat'] = $this->request->param('lat');
            if(empty($data['lat'])){
                $this->error('纬度不能为空',null,101);
            }
            $data['lng'] = $this->request->param('lng');
            if(empty($data['lng'])){
                $this->error('经度不能为空',null,101);
            }
            $data['gps_name'] = $this->request->param('gps_name');
            if(empty($data['gps_name'])){
                $this->error('定位地址不能为空',null,101);
            }
            $field = config('dataattr.regionfield')[$data['type']] . '_num';
            $city_data[$field] = $city->$field + 1;
            $CityModel->save($city_data,['city_id'=>$data['city_id']]);
            $RegionModel = new RegionModel();
            $RegionModel->save($data);
            $this->success('操作成功',null,100);
        } else {
            $city_id = (int) $this->request->param('city_id');
            $CityModel = new CityModel();
             if($city = $CityModel->find($city_id)){
                 if($city->member_miniapp_id == $this->miniapp_id){
                     $this->assign('city',$city);
                 }
             }
            $type = (int) $this->request->param('type');
            $this->assign('type',$type);
            return $this->fetch();
        }
    }
    public function edit(){
        $region_id = (int)$this->request->param('region_id');
        $RegionModel = new RegionModel();
        if(!$detail = $RegionModel->get($region_id)){
            $this->error('请选择要编辑的区域',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择要编辑的区域',null,101);
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['photo'] = $this->request->param('photo');
            $data['region_name'] = $this->request->param('region_name');
            if(empty($data['region_name'])){
                $this->error('区域名称不能为空',null,101);
            }
            $data['lat'] = $this->request->param('lat');
            if(empty($data['lat'])){
                $this->error('纬度不能为空',null,101);
            }
            $data['lng'] = $this->request->param('lng');
            if(empty($data['lng'])){
                $this->error('经度不能为空',null,101);
            }
            $data['gps_name'] = $this->request->param('gps_name');
            if(empty($data['gps_name'])){
                $this->error('定位地址不能为空',null,101);
            }
            $RegionModel = new RegionModel();
            $RegionModel->save($data,['region_id'=>$region_id]);
            $this->success('操作成功',null);
        }else{
            $CityModel = new CityModel();
            $city  = $CityModel->find($detail->city_id);
            $this->assign('detail',$detail);
            $this->assign('city',$city);
            return $this->fetch();
        }
    }

    public function delete() {
        $region_id = (int)$this->request->param('region_id');
        $RegionModel = new RegionModel();
        if(!$detail = $RegionModel->get($region_id)){
            $this->error('请选择要编辑的区域',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择要编辑的区域',null,101);
        }
        $CityModel = new CityModel();
        $RegionModel->where(['region_id'=>$region_id])->delete();
        $this->success("操作成功");
    }
}