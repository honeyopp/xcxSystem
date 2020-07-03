<?php
namespace app\miniapp\controller\taocan;
use app\miniapp\controller\Common;
use app\common\model\taocan\NavModel;
class Nav extends Common {
    
    public function index() {
        $where = $search = [];
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = NavModel::where($where)->count();
        $list = NavModel::where($where)->order(['orderby'=>'desc','nav_id'=>'desc'])->paginate(10, $count);
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
            $where['member_miniapp_id'] = $this->miniapp_id;
            $count = NavModel::where($where)->count();
            if($count >= 20){
                $this->error('您最多添加20个图标',null,101);
            }
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['nav_name'] = $this->request->param('nav_name');  
            if(empty($data['nav_name'])){
                $this->error('导航名称不能为空',null,101);
            }
            $data['nav_ico'] = $this->request->param('nav_ico');  
            if(empty($data['nav_ico'])){
                $this->error('导航图标不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
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
             $this->error('请选择要编辑的导航（分类）',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在导航（分类）");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['nav_name'] = $this->request->param('nav_name');  
            if(empty($data['nav_name'])){
                $this->error('导航名称不能为空',null,101);
            }
            $data['nav_ico'] = $this->request->param('nav_ico');  
            if(empty($data['nav_ico'])){
                $this->error('导航图标不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }

            
            $NavModel = new NavModel();
            $NavModel->save($data,['nav_id'=>$nav_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }


    public function  baototo(){
            return $this->fetch();
    }

    public function show(){
        $nav_id = (int)$this->request->param('nav_id');
        $NavModel = new NavModel();
        if(!$detail = $NavModel->find($nav_id)){
            $this->error("不存在该导航（分类）",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该导航（分类）', null, 101);
        }
        $data['is_show'] = 1;
        if($detail->is_show == 1){
            $data['is_show'] = 0;
        }
        $NavModel->save($data,['nav_id'=>$nav_id]);
        $this->success('操作成功',null,101);
    }
    public function delete() {
        $nav_id = (int)$this->request->param('nav_id');
         $NavModel = new NavModel();
        if(!$detail = $NavModel->find($nav_id)){
            $this->error("不存在该导航（分类）",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该导航（分类）', null, 101);
        }
        $NavModel->where(['nav_id'=>$nav_id])->delete();
        $this->success('操作成功');
    }
   
}