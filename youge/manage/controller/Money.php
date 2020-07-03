<?php
namespace app\manage\controller;
use app\manage\controller\Common;
use app\common\model\member\PaymentModel;
use app\common\model\member\MemberModel;
use app\common\model\member\MoneylogModel;
use app\common\model\member\SmslogModel;
use app\common\model\member\SmspayModel;
class Money extends  Common{
   
    public function recharge(){
		$member_id =  (int) $this->member_info->member_id;
		if($this->request->method() == "POST"){
        }else{
			$this->assign('member_id',$this->member_info->member_id);
            $this->assign('moblie',$this->member_info->mobile);
			$this->assign('nick_name',$this->member_info->nick_name);
			$this->assign('real_name',$this->member_info->real_name);
			$this->assign('email',$this->member_info->email);
			$this->assign('qq',$this->member_info->qq);
			$this->assign('weixin',$this->member_info->weixin);
			$this->assign('nick_dllogo',$this->member_info->nick_dllogo);
			$this->assign('nick_dltitle',$this->member_info->nick_dltitle);
            return $this->fetch();
        }
    }
    public function lists(){
		$member_id =  (int) $this->member_info->member_id;
		if($this->request->method() == "POST"){
        }else{
			$this->assign('member_id',$this->member_info->member_id);
            $this->assign('moblie',$this->member_info->mobile);
			$this->assign('nick_name',$this->member_info->nick_name);
			$this->assign('real_name',$this->member_info->real_name);
			$this->assign('email',$this->member_info->email);
			$this->assign('qq',$this->member_info->qq);
			$this->assign('weixin',$this->member_info->weixin);
			$this->assign('nick_dllogo',$this->member_info->nick_dllogo);
			$this->assign('nick_dltitle',$this->member_info->nick_dltitle);
            return $this->fetch();
        }
    }
    
}