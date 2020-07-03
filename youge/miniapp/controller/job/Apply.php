<?php
namespace app\miniapp\controller\job;
use app\miniapp\controller\Common;
use app\common\model\job\ApplyModel;
class Apply extends Common {
    
    public function index() {
        $where = $search = [];

        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ApplyModel::where($where)->count();
        $list = ApplyModel::where($where)->order(['apply_id'=>'desc'])->paginate(10, $count);
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
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('申请用户不能为空',null,101);
            }
            $data['job_id'] = (int) $this->request->param('job_id');
            if(empty($data['job_id'])){
                $this->error('职位招聘不能为空',null,101);
            }
            $data['company_id'] = (int) $this->request->param('company_id');
            if(empty($data['company_id'])){
                $this->error('公司不能为空',null,101);
            }
            $data['status'] = (int) $this->request->param('status');
            if(empty($data['status'])){
                $this->error('状态不能为空',null,101);
            }
            $data['look_num'] = (int) $this->request->param('look_num');
            if(empty($data['look_num'])){
                $this->error('企业查看不能为空',null,101);
            }
            $data['add_time'] = (int) strtotime($this->request->param('add_time'));
            if(empty($data['add_time'])){
                $this->error('申请时间不能为空',null,101);
            }
            
            
            $ApplyModel = new ApplyModel();
            $ApplyModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $apply_id = (int)$this->request->param('apply_id');
         $ApplyModel = new ApplyModel();
         if(!$detail = $ApplyModel->get($apply_id)){
             $this->error('请选择要编辑的用户申请记录',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在用户申请记录");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('申请用户不能为空',null,101);
            }
            $data['job_id'] = (int) $this->request->param('job_id');
            if(empty($data['job_id'])){
                $this->error('职位招聘不能为空',null,101);
            }
            $data['company_id'] = (int) $this->request->param('company_id');
            if(empty($data['company_id'])){
                $this->error('公司不能为空',null,101);
            }
            $data['status'] = (int) $this->request->param('status');
            if(empty($data['status'])){
                $this->error('状态不能为空',null,101);
            }
            $data['look_num'] = (int) $this->request->param('look_num');
            if(empty($data['look_num'])){
                $this->error('企业查看不能为空',null,101);
            }
            $data['add_time'] = (int) strtotime($this->request->param('add_time'));
            if(empty($data['add_time'])){
                $this->error('申请时间不能为空',null,101);
            }

            
            $ApplyModel = new ApplyModel();
            $ApplyModel->save($data,['apply_id'=>$apply_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $apply_id = (int)$this->request->param('apply_id');
         $ApplyModel = new ApplyModel();
       
        if(!$detail = $ApplyModel->find($apply_id)){
            $this->error("不存在该用户申请记录",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该用户申请记录', null, 101);
        }
        $ApplyModel->where(['apply_id'=>$apply_id])->delete();
        $this->success('操作成功');
    }
   
}