<?php

namespace app\api\controller;

use app\common\model\miniapp\AuthorizerModel;
use app\common\model\user\UserModel;
use think\Controller;

class Common extends Controller {

    protected $appid   = 0;//此APPID是我们的A自增ID
    protected $miniappid = 0;//小程序的APPID
    protected $user    = [];
    protected $checklogin  = false;
    protected $checkIsManage = false;
    protected $page = 1; //默认第一页
    protected $limit_bg = 0; //默认从第0条
    protected $limit_num = 15;
    protected function _initialize() {
        $this->page = (int) $this->request->param('page');
        $limit_num = (int) $this->request->param('limit_num');
        $this->limit_num = empty($limit_num) ? 15 : $limit_num;
        $this->page = $this->page <= 0 ? 1 : $this->page;
        $this->limit_bg = ($this->page - 1) * $this->limit_num;
        $this->appid = (int) $this->request->param('appid');
        $appkey = $this->request->param('appkey');
        if($this->appid == 40){
             file_put_contents('./aaa.text', $appkey);
        }
        if (empty($appkey) || empty($this->appid)) {
            $this->result('', 400, '程序配置错误', 'json');
        }
        $AuthorizerModel = new AuthorizerModel();
        if (!$detail = $AuthorizerModel->cacheData($this->appid)) {
            $this->result('', 401, '程序配置错误', 'json');
        }
       // echo $detail['appkey'];
        if ($detail['appkey'] !== $appkey) {
            $this->result('', 402, '程序配置错误', 'json');
        }
        if ($detail['expir_time'] < $this->request->time()) {
            $this->result('', 403, '程序已到期', 'json');
        }
        $this->miniappid = $detail['authorizer_appid'];
        //部分功能的两个前置判断
        if ($this->checklogin == true) {
            $openid = $this->request->param('openid');
            $UserModel = new UserModel();
            if(!$this->user = $UserModel->get(['open_id'=>$openid,'member_miniapp_id'=>  $this->appid])){
                $this->result('', 100, '未登录', 'json');
            }
            if($this->user->is_lock == 1){
                $this->result('', 400, '账户已被锁定', 'json');
            }
            if($this->checkIsManage == true){
                if($this->user->is_manage == 0){
                    $this->result('', 100, '没有权限', 'json');
                }    
            }
        }
    }
}
