<?php
namespace app\miniapp\controller\nongjialegw;
use app\miniapp\controller\Common;
use app\common\model\nongjiale\ProjectModel;
class Project extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ProjectModel::where($where)->count();
        $list = ProjectModel::where($where)->order(['project_id'=>'desc'])->paginate(10, $count);
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
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['content'] = $this->request->param('content');  
            if(empty($data['content'])){
                $this->error('介绍不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            
            
            $ProjectModel = new ProjectModel();
            $ProjectModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $project_id = (int)$this->request->param('project_id');
         $ProjectModel = new ProjectModel();
         if(!$detail = $ProjectModel->get($project_id)){
             $this->error('请选择要编辑的项目管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在项目管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['content'] = $this->request->param('content');  
            if(empty($data['content'])){
                $this->error('介绍不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }

            
            $ProjectModel = new ProjectModel();
            $ProjectModel->save($data,['project_id'=>$project_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        $project_id = (int)$this->request->param('project_id');
         $ProjectModel = new ProjectModel();
        if(!$detail = $ProjectModel->find($project_id)){
            $this->error("不存在该项目管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该项目管理', null, 101);
        }
        $ProjectModel->where(['project_id'=>$project_id])->delete();
        $this->success('操作成功');
    }
   
}