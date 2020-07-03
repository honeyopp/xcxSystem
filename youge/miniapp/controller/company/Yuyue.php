<?php
namespace app\miniapp\controller\company;
use app\miniapp\controller\Common;
use app\common\model\company\YuyueModel;
class Yuyue extends Common {
    
    public function index() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        
                $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = array('LIKE', '%' . $search['mobile'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = YuyueModel::where($where)->count();
        $list = YuyueModel::where($where)->order(['yuyue_id'=>'desc'])->paginate(10, $count);
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
            $data['company_id'] = (int) $this->request->param('company_id');
            if(empty($data['company_id'])){
                $this->error('商家不能为空',null,101);
            }
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('用户ID不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('联系人不能为空',null,101);
            }
            $data['mobile'] = $this->request->param('mobile');  
            if(empty($data['mobile'])){
                $this->error('手机号码不能为空',null,101);
            }
            $data['content'] = $this->request->param('content');  
            
            
            $YuyueModel = new YuyueModel();
            $YuyueModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $yuyue_id = (int)$this->request->param('yuyue_id');
         $YuyueModel = new YuyueModel();
         if(!$detail = $YuyueModel->get($yuyue_id)){
             $this->error('请选择要编辑的预约商家',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在预约商家");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['company_id'] = (int) $this->request->param('company_id');
            if(empty($data['company_id'])){
                $this->error('商家不能为空',null,101);
            }
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('用户ID不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('联系人不能为空',null,101);
            }
            $data['mobile'] = $this->request->param('mobile');  
            if(empty($data['mobile'])){
                $this->error('手机号码不能为空',null,101);
            }
            $data['content'] = $this->request->param('content');  

            
            $YuyueModel = new YuyueModel();
            $YuyueModel->save($data,['yuyue_id'=>$yuyue_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $yuyue_id = (int)$this->request->param('yuyue_id');
         $YuyueModel = new YuyueModel();
       
        if(!$detail = $YuyueModel->find($yuyue_id)){
            $this->error("不存在该预约商家",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该预约商家', null, 101);
        }

        $YuyueModel->where(['yuyue_id'=>$yuyue_id])->delete();
        $this->success('操作成功');
    }
   
}