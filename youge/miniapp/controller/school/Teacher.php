<?php
namespace app\miniapp\controller\school;
use app\miniapp\controller\Common;
use app\common\model\school\TeacherModel;
class Teacher extends Common {
    
    public function index() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = TeacherModel::where($where)->count();
        $list = TeacherModel::where($where)->order(['teacher_id'=>'desc'])->paginate(10, $count);
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
                $this->error('漂亮的照片不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('教师称呼不能为空',null,101);
            }
            $data['zhiwu'] = $this->request->param('zhiwu');  
            if(empty($data['zhiwu'])){
                $this->error('教师职务不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('教师介绍不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            
            
            $TeacherModel = new TeacherModel();
            $TeacherModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $teacher_id = (int)$this->request->param('teacher_id');
         $TeacherModel = new TeacherModel();
         if(!$detail = $TeacherModel->get($teacher_id)){
             $this->error('请选择要编辑的师资力量',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在师资力量");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('漂亮的照片不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('教师称呼不能为空',null,101);
            }
            $data['zhiwu'] = $this->request->param('zhiwu');  
            if(empty($data['zhiwu'])){
                $this->error('教师职务不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('教师介绍不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }

            
            $TeacherModel = new TeacherModel();
            $TeacherModel->save($data,['teacher_id'=>$teacher_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $teacher_id = (int)$this->request->param('teacher_id');
         $TeacherModel = new TeacherModel();
       
        if(!$detail = $TeacherModel->find($teacher_id)){
            $this->error("不存在该师资力量",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该师资力量', null, 101);
        }
        $TeacherModel->where(['teacher_id'=>$teacher_id])->delete();
        $this->success('操作成功');
    }
   
}