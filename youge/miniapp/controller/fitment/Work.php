<?php
namespace app\miniapp\controller\fitment;
use app\common\model\fitment\DesignerModel;
use app\miniapp\controller\Common;
use app\common\model\fitment\WorkModel;
class Work extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = WorkModel::where($where)->count();
        $list = WorkModel::where($where)->order(['work_id'=>'desc'])->paginate(10, $count);
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
            if(empty($_POST['ids'])){
                $this->error('至少选择一个设计师',null,101);
            }
            $designerIds = $_POST['ids'];
            $data['designer_ids'] = implode(',',$designerIds);
            $data['photo'] = $this->request->param('photo');
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['area'] = (int) $this->request->param('area');
            if(empty($data['area'])){
                $this->error('面积不能为空',null,101);
            }
            $data['village'] = $this->request->param('village');  
            if(empty($data['village'])){
                $this->error('所在小区不能为空',null,101);
            }
            $data['company'] = $this->request->param('company');  
            if(empty($data['company'])){
                $this->error('施工单位不能为空',null,101);
            }
            $WorkModel = new WorkModel();
            $WorkModel->save($data);
            $this->success('操作成功',null);
        } else {
            $DesignerModel = new DesignerModel();
            $where['member_miniapp_id'] = $this->miniapp_id;
            $list = $DesignerModel->where($where)->limit(0,50)->select();
            $this->assign('list',$list);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $work_id = (int)$this->request->param('work_id');
         $WorkModel = new WorkModel();
         if(!$detail = $WorkModel->get($work_id)){
             $this->error('请选择要编辑的看工地',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在看工地");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
             $data['member_miniapp_id'] = $this->miniapp_id;
             if(empty($_POST['ids'])){
                 $this->error('至少选择一个设计师',null,101);
             }
             $designerIds = $_POST['ids'];
             $data['designer_ids'] = implode(',',$designerIds);
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['area'] = (int) $this->request->param('area');
            if(empty($data['area'])){
                $this->error('面积不能为空',null,101);
            }
            $data['village'] = $this->request->param('village');  
            if(empty($data['village'])){
                $this->error('所在小区不能为空',null,101);
            }
            $data['company'] = $this->request->param('company');  
            if(empty($data['company'])){
                $this->error('施工单位不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');
            $WorkModel = new WorkModel();
            $WorkModel->save($data,['work_id'=>$work_id]);
            $this->success('操作成功',null);
         }else{
             $DesignerModel = new DesignerModel();
             $where['member_miniapp_id'] = $this->miniapp_id;
             $list = $DesignerModel->where($where)->limit(0,50)->select();
             $this->assign('list',$list);
             $_ids = explode(',',$detail->designer_ids);
             $ids = [];
             foreach ($_ids as $val){
                 $ids[$val] = $val;
             }
             $this->assign('ids',$ids);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    public function delete() {
        $work_id = (int)$this->request->param('work_id');
         $WorkModel = new WorkModel();
        if(!$detail = $WorkModel->find($work_id)){
            $this->error("不存在该看工地",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该看工地', null, 101);
        }
        $WorkModel->where(['work_id'=>$work_id])->delete();
        $this->success('操作成功');
    }
   
}