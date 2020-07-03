<?php
namespace app\miniapp\controller\zhuangxiu;
use app\miniapp\controller\Common;
use app\common\model\zhuangxiu\SpaceModel;
class Space extends Common {
    
    public function index() {
        $where = $search = [];
        $search['space_name'] = $this->request->param('space_name');
        if (!empty($search['space_name'])) {
            $where['space_name'] = array('LIKE', '%' . $search['space_name'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = SpaceModel::where($where)->count();
        $list = SpaceModel::where($where)->order(['space_id'=>'desc'])->paginate(10, $count);
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
            $data['space_name'] = $this->request->param('space_name');  
            if(empty($data['space_name'])){
                $this->error('分类名称不能为空',null,101);
            }
            
            
            $SpaceModel = new SpaceModel();
            $SpaceModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $space_id = (int)$this->request->param('space_id');
         $SpaceModel = new SpaceModel();
         if(!$detail = $SpaceModel->get($space_id)){
             $this->error('请选择要编辑的效果图空间分类',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在效果图空间分类");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['space_name'] = $this->request->param('space_name');  
            if(empty($data['space_name'])){
                $this->error('分类名称不能为空',null,101);
            }

            
            $SpaceModel = new SpaceModel();
            $SpaceModel->save($data,['space_id'=>$space_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $space_id = (int)$this->request->param('space_id');
         $SpaceModel = new SpaceModel();
       
        if(!$detail = $SpaceModel->find($space_id)){
            $this->error("不存在该效果图空间分类",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该效果图空间分类', null, 101);
        }
        $SpaceModel->where(['space_id'=>$space_id])->delete();
        $this->success('操作成功');
    }
   
}