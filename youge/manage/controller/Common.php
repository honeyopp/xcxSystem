<?php

namespace app\manage\controller;

use app\common\model\member\MemberModel;
use think\Controller;

class Common extends Controller {

    protected $member_id = 0;
    protected $member_info = [];

    protected function _initialize() {
        $cookieToken = cookie('memberID');
        $cookieInfo = authcode($cookieToken, 'DECODE');
        $this->member_id = substr($cookieInfo, 0, strpos($cookieInfo, '|'));
        $this->member_info = MemberModel::get($this->member_id);
        if ($this->request->controller() != 'passport') {
            if (empty($this->member_id)) {
                header("Location:" . url('manage/passport/login'));
                die();
            }
            if (empty($this->member_info)) {
                header("Location:" . url('manage/login/index'));
                die();
            }
        }
        $this->assign('member',$this->member_info);
        $leftMenus = config('manage');
        $this->assign('leftMenus', $leftMenus);
    }

    //验证手机号；
    public function checkMobile($tel) {
        $search = '/^1[0-9]{1}\d{9}$/';
        if (preg_match($search, $tel)) {
            return true;
        } else {
            return false;
        }
    }

}
