<?php
namespace app\miniapp\controller\minsu;
use app\common\model\minsu\MinsumanageModel;
use app\common\model\minsu\MinsuModel;
use app\miniapp\controller\Common;

class Manage extends Common {
    public function index() {
        $MinsuModel = new MinsuModel();
        $minsu_id = (int) $this->request->param('minsu_id');
        if(!$minsu = $MinsuModel->find($minsu_id)){
            $this->error('不存在民宿',null,101);
        }
        if($minsu->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在民宿',null,101);
        }
        $MinsumanageModel= new MinsumanageModel();
        $search = $where = [];
        $where['minsu_id'] = $minsu_id;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $list = $MinsumanageModel->where($where)->order("manage_id desc")->paginate(10);
        $this->assign('list',$list);
        $this->assign('minsu_id',$minsu_id);
        return $this->fetch();
    }
    public function create() {

        $MinsuModel = new MinsuModel();
        $minsu_id = (int) $this->request->param('minsu_id');
        if(!$minsu = $MinsuModel->find($minsu_id)){
            $this->error('不存在民宿',null,101);
        }
        if($minsu->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在民宿',null,101);
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['minsu_id'] = $minsu_id;
            $data['mobile'] = $this->request->param('mobile');
            if(empty($data['mobile'])){
                $this->error('手机号（登录账号）不能为空',null,101);
            }
            $MinsumanageModel = new MinsumanageModel();
            if($manger = $MinsumanageModel->where(['mobile'=>$data['mobile']])->select()){
                $this->error('账号已存在',null,101);
            }
            $data['password'] = $this->request->param('password');
            if(empty($data['password'])){
                $this->error('密码不能为空',null,101);
            }else{
                $data['password'] = md5($data['password']);
            }
            $data['role_name'] = $this->request->param('role_name');
            if(empty($data['role_name'])){
                $this->error('角色名称（职位）不能为空',null,101);
            }
            $data['mange_name'] = $this->request->param('mange_name');
            if(empty($data['mange_name'])){
                $this->error('管理员姓名不能为空',null,101);
            }
            $data['is_lock'] = $this->request->param('is_lock');
            if(!empty($data['is_lock'])){
                $data['is_lock'] = 1;
            }
            $MinsumanageModel->save($data);
            $this->success('操作成功',null);
        } else {
            $this->assign('minsu_id',$minsu_id);
            return $this->fetch();
        }
    }
    public function edit(){ 
        $manage_id = (int) $this->request->param('manage_id');
        $MinsumanageModel = new MinsumanageModel();
        if(!$detail = $MinsumanageModel->get($manage_id)){
            $this->error('不存在管理员',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在管理员',null,101);
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $password  = $this->request->param('password');
            if(!empty($password)){
                $data['password'] = md5($password);
            }
            $data['role_name'] = $this->request->param('role_name');
            if(empty($data['role_name'])){
                $this->error('角色名称（职位）不能为空',null,101);
            }
            $data['mange_name'] = $this->request->param('mange_name');
            if(empty($data['mange_name'])){
                $this->error('管理员姓名不能为空',null,101);
            }
            $data['is_lock'] = (int) $this->request->param('is_lock');
            $MinsumanageModel->save($data,['manage_id'=>$manage_id]);
            $this->success('操作成功',null);
        }else{
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }
    public function lock(){
        $manage_id = (int) $this->request->param('manage_id');
        $MinsumanageModel = new MinsumanageModel();
        if(!$member = $MinsumanageModel->find($manage_id)){
            $this->error('不存在管理员',null,101);
        }
        if($member->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在管理员',null,101);
        }
        $data['is_lock'] = 1;
        if($member->is_lock == 1){
            $data['is_lock'] = 0;
        }
        $MinsumanageModel->save($data,['manage_id'=>$manage_id]);
        $this->success('操作成功');
    }



    public function delete() {
        $manage_id = (int) $this->request->param('manage_id');
        $MinsumanageModel = new MinsumanageModel();
        if(!$minsu = $MinsumanageModel->find($manage_id)){
            $this->error("不存在该管理员");
        }
        if($minsu->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在该管理员");
        }
        $MinsumanageModel->where(['manage_id'=>$manage_id])->delete();
        $this->success('操作成功');
    }

}