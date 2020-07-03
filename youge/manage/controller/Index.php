<?php

namespace app\manage\controller;
use app\common\model\miniapp\AuthorizerModel;
use app\common\model\miniapp\MiniappModel;
class Index extends Common {

    public function index(){
        $this->view->engine->layout(false);
        return $this->fetch();
    }
    public function main(){
        header("Location:".url('miniappshop/index'));
        die;
    }

}