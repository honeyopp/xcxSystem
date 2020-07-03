<?php
namespace app\miniapp\controller\zhuangxiu;
use app\miniapp\controller\Common;
use app\common\model\zhuangxiu\CaseModel;
class Case extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CaseModel::where($where)->count();
        $list = CaseModel::where($where)->order(['case_id'=>'desc'])->paginate(10, $count);
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
            $data['cat_id'] = (int) $this->request->param('cat_id');
            if(empty($data['cat_id'])){
                $this->error('风格分类不能为空',null,101);
            }
            $data['space_id'] = (int) $this->request->param('space_id');
            if(empty($data['space_id'])){
                $this->error('空间分类不能为空',null,101);
            }
            $data['color_id'] = (int) $this->request->param('color_id');
            if(empty($data['color_id'])){
                $this->error('色系主题分类不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('封面不能为空',null,101);
            }
            
            
            $CaseModel = new CaseModel();
            $CaseModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $case_id = (int)$this->request->param('case_id');
         $CaseModel = new CaseModel();
         if(!$detail = $CaseModel->get($case_id)){
             $this->error('请选择要编辑的效果图设置',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在效果图设置");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['cat_id'] = (int) $this->request->param('cat_id');
            if(empty($data['cat_id'])){
                $this->error('风格分类不能为空',null,101);
            }
            $data['space_id'] = (int) $this->request->param('space_id');
            if(empty($data['space_id'])){
                $this->error('空间分类不能为空',null,101);
            }
            $data['color_id'] = (int) $this->request->param('color_id');
            if(empty($data['color_id'])){
                $this->error('色系主题分类不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('封面不能为空',null,101);
            }

            
            $CaseModel = new CaseModel();
            $CaseModel->save($data,['case_id'=>$case_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $case_id = (int)$this->request->param('case_id');
         $CaseModel = new CaseModel();
       
        if(!$detail = $CaseModel->find($case_id)){
            $this->error("不存在该效果图设置",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该效果图设置', null, 101);
        }
        if($detail->is_delete == 1){
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $CaseModel->save($data,['case_id'=>$case_id]);
        $this->success('操作成功');
    }
   
}