<?php
namespace app\miniapp\controller\company;
use app\miniapp\controller\Common;
use app\common\model\company\AreaModel;
class Area extends Common {
    
    public function index() {
        $where = $search = [];
        $search['area_name'] = $this->request->param('area_name');
        if (!empty($search['area_name'])) {
            $where['area_name'] = array('LIKE', '%' . $search['area_name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = AreaModel::where($where)->count();
        $list = AreaModel::where($where)->order(['area_id'=>'desc'])->paginate(10, $count);
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
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['area_name'] = $this->request->param('area_name');  
            if(empty($data['area_name'])){
                $this->error('区域名称不能为空',null,101);
            }
            
            
            $AreaModel = new AreaModel();
            $AreaModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $area_id = (int)$this->request->param('area_id');
         $AreaModel = new AreaModel();
         if(!$detail = $AreaModel->get($area_id)){
             $this->error('请选择要编辑的商家区域',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在商家区域");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['area_name'] = $this->request->param('area_name');  
            if(empty($data['area_name'])){
                $this->error('区域名称不能为空',null,101);
            }

            
            $AreaModel = new AreaModel();
            $AreaModel->save($data,['area_id'=>$area_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        $area_id = (int)$this->request->param('area_id');
        $AreaModel = new AreaModel();
        if(!$detail = $AreaModel->find($area_id)){
            $this->error("不存在该商家区域",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该商家区域', null, 101);
        }
        $AreaModel->where(['area_id'=>$area_id])->delete();
        $this->success('操作成功');
    }
   
}