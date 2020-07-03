<?php

namespace app\home\controller;

use app\common\model\miniapp\MiniappModel;

class Index extends Common {
    protected $footer = 1;

    public function index(){
        if($this->request->isMobile()){
            header("Location:".url('mobile/index'));
            die;
        }
		//$count = 0;	
        $where = ['is_online'=>1];
        $list = MiniappModel::where($where)->order(['orderby'=>'desc'])->limit(0,12)->select();
        $this->assign('list', $list); 
			
        return $this->fetch();
    }
    public function main(){
        //获取今日访客数；
        return $this->fetch();
    }
    
    public function  test(){
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';  
        echo $http_type;
    }
}