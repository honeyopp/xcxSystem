<?php

namespace app\home\controller;

use app\common\model\member\MemberModel;
use think\Controller;
use think\Session;

class Common extends Controller
{
    protected $footer = 1;
   
    protected function _initialize() {
       $this->assign('footer',$this->footer);
    }
   
}
