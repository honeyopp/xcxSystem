<?php
namespace app\home\controller;
use app\common\model\miniapp\MiniappModel;

class Mobile extends Common{
    public function index(){
        $this->view->engine->layout(false);
        $where = ['is_online'=>1];
        $list = MiniappModel::where($where)->order(['orderby'=>'desc'])->select();
        $this->assign('list', $list);
        return $this->fetch();
    }
}