<?php
namespace app\admin\controller;
use app\common\model\admin\AdminModel;
use think\Session;
use think\Config;
class Login extends Common
{
    public function index()
    {
        //$this->view->engine->layout(false);
        return  $this->fetch();
    }
    
    public function loging(){
        $username = $this->request->param('username'); 
        $password  = $this->request->param('password'); 
        if(empty($username) || empty($password)){
            $this->error('账号或密码不能为空',null,101); // 101 代表普通单条错误  102 代表多条错误  201代表异步登录（网站前台状态） 
        }
        $admin = AdminModel::get(['username'=>$username]);
        if(empty($admin)){
            $this->error('账号或密码不正确',null,101);
        }
        if($admin->password != md5($password)){
            $this->error('账号或密码不正确',null,101);
        }
        if($admin->is_delete){
            $this->error('帐号已经锁定',null,101);
        }
        Session::set('adminId',$admin->admin_id);
        $admin->save();
        $this->success('恭喜您登录成功',  url('index/index'));
    }
    
    public function logout(){
        Session::delete('adminId');
        $this->success('退出成功',  url('index/index'));
    }
}
