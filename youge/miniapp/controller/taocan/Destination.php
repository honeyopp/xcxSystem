<?php
namespace app\miniapp\controller\taocan;
use app\common\model\setting\CityModel;
use app\miniapp\controller\Common;
use app\common\model\taocan\DestinationModel;
class Destination extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['is_delete'] = 0;
        $count = DestinationModel::where($where)->count();
        $list = DestinationModel::where($where)->order(['destination_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $cityIds = [];
        foreach ($list as $val){
            $cityIds[$val->city_id] = $val->city_id;
        }
        $CityModel = new CityModel();
        $this->assign('city',$CityModel->itemsByIds($cityIds));
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function select() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['is_delete'] = 0;
        $count = DestinationModel::where($where)->count();
        $list = DestinationModel::where($where)->order(['destination_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $cityIds = [];
        foreach ($list as $val){
            $cityIds[$val->city_id] = $val->city_id;
        }
        $CityModel = new CityModel();
        $this->assign('city',$CityModel->itemsByIds($cityIds));
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
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['city_id'] = (int) $this->request->param('city_id');
            if(empty($data['city_id'])){
                $this->error('请选择城市',null,101);
            }
            $data['title2'] = $this->request->param('title2');  
            if(empty($data['title2'])){
                $this->error('副标题不能为空',null,101);
            }
            $data['is_hot'] = (int) $this->request->param('is_hot');
            $DestinationModel = new DestinationModel();
            $DestinationModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $destination_id = (int)$this->request->param('destination_id');
         $DestinationModel = new DestinationModel();
         if(!$detail = $DestinationModel->get($destination_id)){
             $this->error('请选择要编辑的目的地推荐',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在目的地推荐");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
             $data['city_id'] = (int) $this->request->param('city_id');
             if(empty($data['city_id'])){
                 $this->error('请选择城市',null,101);
             }
            $data['title2'] = $this->request->param('title2');  
            if(empty($data['title2'])){
                $this->error('副标题不能为空',null,101);
            }
            $DestinationModel = new DestinationModel();
            $DestinationModel->save($data,['destination_id'=>$destination_id]);
            $this->success('操作成功',null);
         }else{
             $city = CityModel::find($detail->city_id);
             $this->assign('city',$city);
             $this->assign('detail',$detail);
             return $this->fetch();
         }
    }
    
    public function delete() {
   
        $destination_id = (int)$this->request->param('destination_id');
         $DestinationModel = new DestinationModel();
       
        if(!$detail = $DestinationModel->find($destination_id)){
            $this->error("不存在该目的地推荐",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该目的地推荐', null, 101);
        }
        if($detail->is_delete == 1){
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $DestinationModel->save($data,['destination_id'=>$destination_id]);
        $this->success('操作成功');
    }
   
}