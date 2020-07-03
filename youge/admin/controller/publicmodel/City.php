<?php
namespace app\admin\controller\publicmodel;
use app\admin\controller\Common;
use app\common\model\setting\CityModel;
class City extends Common {
    
    public function index() {
        $where = $search = [];
        $search['member_miniapp_id'] = (int)$this->request->param('member_miniapp_id');
        if (!empty($search['member_miniapp_id'])) {
            $where['member_miniapp_id'] = $search['member_miniapp_id'];
        }
                $search['province_id'] = (int)$this->request->param('province_id');
        if (!empty($search['province_id'])) {
            $where['province_id'] = $search['province_id'];
        }
                $search['city_name'] = (int)$this->request->param('city_name');
        if (!empty($search['city_name'])) {
            $where['city_name'] = $search['city_name'];
        }
        
        $count = CityModel::where($where)->count();
        $list = CityModel::where($where)->order(['city_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    
    public function create() {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = (int) $this->request->param('member_miniapp_id');
            if(empty($data['member_miniapp_id'])){
                $this->error('用户小程序id不能为空',null,101);
            }
            $data['province_id'] = (int) $this->request->param('province_id');
            if(empty($data['province_id'])){
                $this->error('省份不能为空',null,101);
            }
            $data['city_name'] = (int) $this->request->param('city_name');
            if(empty($data['city_name'])){
                $this->error('城市名称不能为空',null,101);
            }
            $data['lat'] = (int) $this->request->param('lat');
            if(empty($data['lat'])){
                $this->error('纬度不能为空',null,101);
            }
            $data['lng'] = (int) $this->request->param('lng');
            if(empty($data['lng'])){
                $this->error('经度不能为空',null,101);
            }
            $data['pinyin'] = (int) $this->request->param('pinyin');
            if(empty($data['pinyin'])){
                $this->error('拼音（全拼）不能为空',null,101);
            }
            $data['initial'] = (int) $this->request->param('initial');
            if(empty($data['initial'])){
                $this->error('拼音（首字母）不能为空',null,101);
            }
            $data['scenic_spot_num'] = (int) $this->request->param('scenic_spot_num');
            if(empty($data['scenic_spot_num'])){
                $this->error('景点个数不能为空',null,101);
            }
            $data['area_num'] = (int) $this->request->param('area_num');
            if(empty($data['area_num'])){
                $this->error('商圈个数不能为空',null,101);
            }
            $data['administration_num'] = (int) $this->request->param('administration_num');
            if(empty($data['administration_num'])){
                $this->error('行政区域个数不能为空',null,101);
            }
            $data['station_num'] = (int) $this->request->param('station_num');
            if(empty($data['station_num'])){
                $this->error('车站个数不能为空',null,101);
            }
            $data['colleges_num'] = (int) $this->request->param('colleges_num');
            if(empty($data['colleges_num'])){
                $this->error('高校个数不能为空',null,101);
            }
            $data['Hospital_num'] = (int) $this->request->param('Hospital_num');
            if(empty($data['Hospital_num'])){
                $this->error('医院个数不能为空',null,101);
            }
            
            
            $CityModel = new CityModel();
            $CityModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $city_id = (int)$this->request->param('city_id');
         $CityModel = new CityModel();
         if(!$detail = $CityModel->get($city_id)){
             $this->error('请选择要编辑的城市设置',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = (int) $this->request->param('member_miniapp_id');
            if(empty($data['member_miniapp_id'])){
                $this->error('用户小程序id不能为空',null,101);
            }
            $data['province_id'] = (int) $this->request->param('province_id');
            if(empty($data['province_id'])){
                $this->error('省份不能为空',null,101);
            }
            $data['city_name'] = (int) $this->request->param('city_name');
            if(empty($data['city_name'])){
                $this->error('城市名称不能为空',null,101);
            }
            $data['lat'] = (int) $this->request->param('lat');
            if(empty($data['lat'])){
                $this->error('纬度不能为空',null,101);
            }
            $data['lng'] = (int) $this->request->param('lng');
            if(empty($data['lng'])){
                $this->error('经度不能为空',null,101);
            }
            $data['pinyin'] = (int) $this->request->param('pinyin');
            if(empty($data['pinyin'])){
                $this->error('拼音（全拼）不能为空',null,101);
            }
            $data['initial'] = (int) $this->request->param('initial');
            if(empty($data['initial'])){
                $this->error('拼音（首字母）不能为空',null,101);
            }
            $data['scenic_spot_num'] = (int) $this->request->param('scenic_spot_num');
            if(empty($data['scenic_spot_num'])){
                $this->error('景点个数不能为空',null,101);
            }
            $data['area_num'] = (int) $this->request->param('area_num');
            if(empty($data['area_num'])){
                $this->error('商圈个数不能为空',null,101);
            }
            $data['administration_num'] = (int) $this->request->param('administration_num');
            if(empty($data['administration_num'])){
                $this->error('行政区域个数不能为空',null,101);
            }
            $data['station_num'] = (int) $this->request->param('station_num');
            if(empty($data['station_num'])){
                $this->error('车站个数不能为空',null,101);
            }
            $data['colleges_num'] = (int) $this->request->param('colleges_num');
            if(empty($data['colleges_num'])){
                $this->error('高校个数不能为空',null,101);
            }
            $data['Hospital_num'] = (int) $this->request->param('Hospital_num');
            if(empty($data['Hospital_num'])){
                $this->error('医院个数不能为空',null,101);
            }

            
            $CityModel = new CityModel();
            $CityModel->save($data,['city_id'=>$city_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        if($this->request->method() == 'POST'){
             $city_id = $_POST['city_id'];
        }else{
            $city_id = $this->request->param('city_id');
        }
        $data = [];
        if (is_array($city_id)) {
            foreach ($city_id as $k => $val) {
                $city_id[$k] = (int) $val;
            }
            $data = $city_id;
        } else {
            $data[] = $city_id;
        }
        if (!empty($data)) {
            $CityModel = new CityModel();
            $CityModel->where(array('city_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}