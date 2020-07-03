<?php
namespace app\miniapp\controller\toutiao;
use app\miniapp\controller\Common;
use app\common\model\toutiao\NavModel;
class Nav extends Common {
    
    public function index() {
        $where = $search = [];

        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = NavModel::where($where)->count();
        $list = NavModel::where($where)->order(['nav_id'=>'desc'])->paginate(10, $count);
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
            $data['nav_name'] = $this->request->param('nav_name');  
            if(empty($data['nav_name'])){
                $this->error('导航名称不能为空',null,101);
            }
            $data['orderby'] = (int)$this->request->param('orderby');  
            $data['is_show'] = $this->request->param('is_show');  
            if(empty($data['is_show'])){
                $this->error('是否展示不能为空',null,101);
            }
            
            
            $NavModel = new NavModel();
            $NavModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $nav_id = (int)$this->request->param('nav_id');
         $NavModel = new NavModel();
         if(!$detail = $NavModel->get($nav_id)){
             $this->error('请选择要编辑的导航设置',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在导航设置");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['nav_name'] = $this->request->param('nav_name');  
            if(empty($data['nav_name'])){
                $this->error('导航名称不能为空',null,101);
            }
            $data['orderby'] = (int)$this->request->param('orderby');  
            $data['is_show'] = $this->request->param('is_show');  
            if(empty($data['is_show'])){
                $this->error('是否展示不能为空',null,101);
            }

            
            $NavModel = new NavModel();
            $NavModel->save($data,['nav_id'=>$nav_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $nav_id = (int)$this->request->param('nav_id');
         $NavModel = new NavModel();
       
        if(!$detail = $NavModel->find($nav_id)){
            $this->error("不存在该导航设置",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该导航设置', null, 101);
        }
        $NavModel->where(['nav_id'=>$nav_id])->delete();
        $this->success('操作成功');
    }
   
}