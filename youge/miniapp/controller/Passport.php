<?php
/**
 * @fileName    Passport.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/19 0019
 */
namespace app\miniapp\controller;
use app\common\model\member\ManagelogModel;
use app\common\model\miniapp\AuthorizerModel;
use app\common\model\miniapp\MiniappmanageModel;
use app\manage\controller\manage;
use think\Controller;
use think\Cookie;

class Passport extends Controller{
    /**
     * 登录小程序后台
     * @param $app_id string
     * @param app_key string
     */
    public function logout(){
        Cookie::delete('miniapp');
        $this->redirect(url('index/index'));
    }
    public function login(){
        $this->view->engine->layout(false);
        if($this->request->isPost()){
            $mobile = (string) $this->request->param('mobile');
            $password = (string) $this->request->param('password');
            $MiniappmanageModel = new MiniappmanageModel();
            $where['mobile'] = $mobile;
            if(!$manage = $MiniappmanageModel->where($where)->find()){
                $this->error('账号或密码错误',null,101);
            }
            if($manage->password != md5($password)){
                $this->error('账号或密码错误',null,101);
            }
            if($manage->is_lock == 1){
                $this->error('您的账户已锁定',null,101);
            }
            $data = [
                'last_time' => $this->request->time(),
                'last_ip'   => $this->request->ip(),
            ];
            $MiniappmanageModel->save($data,['manage_id'=>$manage->manage_id]);
            $code = authcode($manage->member_miniapp_id .'|' . $manage->app_key . '|miniapp|' . $_SERVER['REQUEST_TIME']);
            cookie('miniapp', $code);
            $this->success('登录成功',url('/miniapp/index/index'));
        }else{
            return $this->fetch();
        }

     }

}