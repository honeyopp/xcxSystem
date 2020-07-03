<?php
namespace app\admin\controller\publicmodel;
use app\admin\controller\Common;
use app\common\model\publicmodel\RegionModel;
class Region extends Common {
    
    public function index() {
        $where = $search = [];

        $count = RegionModel::where($where)->count();
        $list = RegionModel::where($where)->order(['region_id'=>'desc'])->paginate(10, $count);
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
            $data['type'] = (int) $this->request->param('type');
            if(empty($data['type'])){
                $this->error('类型不能为空',null,101);
            }
            $data['city_id'] = (int) $this->request->param('city_id');
            if(empty($data['city_id'])){
                $this->error('城市不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['reigon_name'] = $this->request->param('reigon_name');  
            if(empty($data['reigon_name'])){
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
            $RegionModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $region_id = (int)$this->request->param('region_id');
         $RegionModel = new RegionModel();
         if(!$detail = $RegionModel->get($region_id)){
             $this->error('请选择要编辑的区域设置',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['type'] = (int) $this->request->param('type');
            if(empty($data['type'])){
                $this->error('类型不能为空',null,101);
            }
            $data['city_id'] = (int) $this->request->param('city_id');
            if(empty($data['city_id'])){
                $this->error('城市不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['reigon_name'] = $this->request->param('reigon_name');  
            if(empty($data['reigon_name'])){
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
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        if($this->request->method() == 'POST'){
             $region_id = $_POST['region_id'];
        }else{
            $region_id = $this->request->param('region_id');
        }
        $data = [];
        if (is_array($region_id)) {
            foreach ($region_id as $k => $val) {
                $region_id[$k] = (int) $val;
            }
            $data = $region_id;
        } else {
            $data[] = $region_id;
        }
        if (!empty($data)) {
            $RegionModel = new RegionModel();
            $RegionModel->where(array('region_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}