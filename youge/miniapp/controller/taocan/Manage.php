<?php
namespace app\miniapp\controller\taocan;
use app\common\model\taocan\TaocanmanageModel;
use app\common\model\taocan\StoreModel;
use app\miniapp\controller\Common;

class Manage extends Common {
    public function index() {
        $StoreModel = new StoreModel();
        $store_id= (int) $this->request->param('store_id');
        if(!$store = $StoreModel->find($store_id)){
            $this->error('不存在商家',null,101);
        }
        if($store->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在商家',null,101);
        }
        $TaocanmanageModel= new TaocanmanageModel();
        $search = $where = [];
        $where['store_id'] = $store_id;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $list = $TaocanmanageModel->where($where)->order("manage_id desc")->paginate(10);
        $this->assign('list',$list);
        $this->assign('store_id',$store_id);
        return $this->fetch();
    }
    public function create() {

        $StoreModel = new StoreModel();
        $store_id= (int) $this->request->param('store_id');
        if(!$store = $StoreModel->find($store_id)){
            $this->error('不存在商家',null,101);
        }
        if($store->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在商家',null,101);
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['store_id'] = $store_id;
            $data['mobile'] = $this->request->param('mobile');
            if(empty($data['mobile'])){
                $this->error('手机号（登录账号）不能为空',null,101);
            }
            $TaocanmanageModel = new TaocanmanageModel();
            if($manger = $TaocanmanageModel->where(['mobile'=>$data['mobile']])->select()){
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
            $data['is_lock'] = (int) $this->request->param('is_lock');
            $TaocanmanageModel->save($data);
            $this->success('操作成功',null);
        } else {
            $this->assign('store_id',$store_id);
            return $this->fetch();
        }
    }
    public function edit(){ 
        $manage_id = (int) $this->request->param('manage_id');
        $TaocanmanageModel = new TaocanmanageModel();
        if(!$detail = $TaocanmanageModel->get($manage_id)){
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
            $TaocanmanageModel->save($data,['manage_id'=>$manage_id]);
            $this->success('操作成功',null);
        }else{
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }
    public function lock(){
        $manage_id = (int) $this->request->param('manage_id');
        $TaocanmanageModel = new TaocanmanageModel();
        if(!$member = $TaocanmanageModel->find($manage_id)){
            $this->error('不存在管理员',null,101);
        }
        if($member->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在管理员',null,101);
        }
        $data['is_lock'] = 1;
        if($member->is_lock == 1){
            $data['is_lock'] = 0;
        }
        $TaocanmanageModel->save($data,['manage_id'=>$manage_id]);
        $this->success('操作成功');
    }



    public function delete() {
        $manage_id = (int) $this->request->param('manage_id');
        $TaocanmanageModel = new TaocanmanageModel();
        if(!$store = $TaocanmanageModel->find($manage_id)){
            $this->error("不存在该管理员");
        }
        if($store->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在该管理员");
        }
        $TaocanmanageModel->where(['manage_id'=>$manage_id])->delete();
        $this->success('操作成功');
    }

}