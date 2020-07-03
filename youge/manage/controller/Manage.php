<?php
namespace app\manage\controller;
use app\common\model\miniapp\AuthorizerModel;
use app\common\model\miniapp\MiniappmanageModel;

class Manage extends Common {
    public function index() {
        $AuthorizerModel = new AuthorizerModel();
        $member_miniapp_id = (int) $this->request->param('miniapp_id');
        if(!$miniapp = $AuthorizerModel->find($member_miniapp_id)){
            $this->error('不存在小程序',null,101);
        }
        if($miniapp->member_id != $this->member_id){
            $this->error('不存在小程序',null,101);
        }
        $MangeModel = new MiniappmanageModel();
        $search = $where = [];
        $where['member_miniapp_id']  = $member_miniapp_id;
        $where['member_id'] = $this->member_id;
        $list = $MangeModel->where($where)->order("manage_id desc")->paginate(10);
        $this->assign('list',$list);
        $this->assign('miniapp_id',$member_miniapp_id);
        return $this->fetch();
    }
    public function create() {
        $AuthorizerModel = new AuthorizerModel();
        $member_miniapp_id = (int) $this->request->param('miniapp_id');
        if(!$miniapp = $AuthorizerModel->find($member_miniapp_id)){
            $this->error('不存在小程序',null,101);
        }
        if($miniapp->member_id != $this->member_id){
            $this->error('不存在小程序',null,101);
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $miniapp->member_miniapp_id;
            $data['app_key'] =  $miniapp->appkey;
            $data['authorizer_appid'] =$miniapp->authorizer_appid;
            $data['member_id'] =$this->member_id;
            $data['mobile'] = $this->request->param('mobile');
            if(empty($data['mobile'])){
                $this->error('手机号（登录账号）不能为空',null,101);
            }
            $MangeModel = new MiniappmanageModel();
            if($manger = $MangeModel->where(['mobile'=>$data['mobile']])->select()){
                $this->error('账号已存在请更还账号',null,101);
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
            $data['is_lock'] = (int) $this->request->param('is_lock');
            $MangeModel->save($data);
            $this->success('操作成功',null);
        } else {
            $this->assign('miniapp_id',$miniapp->member_miniapp_id);
            return $this->fetch();
        }
    }

    public function edit(){
        $mange_id = (int) $this->request->param('manage_id');
        $MangeModel = new MiniappmanageModel();
        if(!$detail = $MangeModel->get($mange_id)){
            $this->error('不存在管理员',null,101);
        }
        if($detail->member_id != $this->member_id){
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
            $MangeModel->save($data,['manage_id'=>$mange_id]);
            $this->success('操作成功',null);
        }else{
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }

    public function lock(){
        $manage_id = (int) $this->request->param('manage_id');
        $MiniappmanageModel = new MiniappmanageModel();
        if(!$member = $MiniappmanageModel->find($manage_id)){
            $this->error('不存在管理员',null,101);
        }
        if($member->member_id != $this->member_id){
            $this->error('不存在管理员',null,101);
        }
        $data['is_lock'] = 1;
        if($member->is_lock == 1){
            $data['is_lock'] = 0;
        }
        $MiniappmanageModel->save($data,['manage_id'=>$manage_id]);
        $this->success('操作成功');
    }

    public function delete() {
        $manage_id = (int) $this->request->param('manage_id');
        $MiniappmanageModel = new MiniappmanageModel();
        if(!$member = $MiniappmanageModel->find($manage_id)){
            $this->error('不存在管理员',null,101);
        }
        if($member->member_id != $this->member_id){
            $this->error('不存在管理员',null,101);
        }
        $MiniappmanageModel->where(['manage_id'=>$manage_id])->delete();
        $this->success('操作成功');
    }

}