<?php
/**
 * @fileName    member.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/17 0017
 */
namespace app\manage\controller;
use app\common\model\member\MemberModel;
use app\common\model\member\MoneylogModel;
use app\common\model\member\SmslogModel;
use app\common\model\member\SmspayModel;
use app\manage\controller\Common;
use app\manage\controller\Pay;
class Member extends  Common{
   // 个人中心信息；

    public function index(){
         $MoneylogModel = new MoneylogModel();
          $SmspayModel = new SmspayModel();
         $where['member_id'] = $this->member_id;
         $money = $MoneylogModel->where($where)->order("add_time desc")->paginate(10);
         $sms = $SmspayModel->where($where)->order("add_time desc")->paginate(10);
         $this->assign('money',$money);
         $this->assign('sms',$sms);
         $this->assign('member',$this->member_info);
         return $this->fetch();
    }
    public function  edit(){
        if($this->request->method() == "POST"){
            $code = $this->request->param('code');
            if (empty($code)) {
                $this->result([], 0 ,'验证码和不能为空',null,101);
            }
            $CodeModel = new CodeModel();
            if (!$codeInfo = $CodeModel->get(['mobile' => $this->member_info->mobile])) {
                $this->error('验证码不正确',null,101);
            }
            if ($codeInfo['code_time'] < $_SERVER['REQUEST_TIME'] - 600) {
                $CodeModel->save(['err_num' => $codeInfo['err_num'] + 1], ['code_id' => $codeInfo['code_id']]);
                $this->error('短信验证码已经过期',null,101);
            }
            if ($codeInfo['code'] != $code) {
                $CodeModel->save(['err_num' => $codeInfo['err_num'] + 1], ['code_id' => $codeInfo['code_id']]);
                $this->error('验证码不正确',null,101);
            }
            if ($codeInfo['err_num'] > 5) {
                $this->error('请重新获取验证码',null,101);
            }
            $data['password'] = $this->request->param('password');
            $password2 = $this->request->param('password2');
            if(empty($data['password'])){
                $this->error('请输入密码');
            }else{
                $data['password'] = md5($data['password']);
            }
            if($data['password'] != md5($password2)){
                $this->error('密码不一致');
            }
            $MemberModel = new MemberModel();
            $MemberModel->save($data,['member_id'=>$this->member_info->member_id]);
            $code = authcode($this->member_info->member_id . '|yohnghu|' . $_SERVER['REQUEST_TIME']);
            cookie('memberID', $code);
            $this->success('密码修改成功',url("index/index"));
        }else{
            $this->assign('moblie',$this->member_info->mobile);
            return $this->fetch();
        }
    }

	public function  usertext(){
		$member_id =  (int) $this->member_info->member_id;
       if($this->request->method() == "POST"){
			 $data['nick_name'] = (string)$this->request->param('nick_name');
			 $data['real_name'] = (string)$this->request->param('real_name');
			 $data['email'] =    (string)$this->request->param('email');
			 $data['qq'] = (string)$this->request->param('qq');
			 $data['weixin'] =    (string)$this->request->param('weixin');
			 $data['nick_dllogo'] = (string)$this->request->param('nick_dllogo');
			 $data['nick_dltitle'] =    (string)$this->request->param('nick_dltitle');
			$MemberModel = new MemberModel();
            $MemberModel->save($data, ['member_id' => $member_id]);
            $this->success('修改成功',url("member/usertext"));
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
    /**
   	/*  * 短信充值；
     * @param sms_num int 短信条数
     */
    public function smspay(){
        $sms_num =  (int) $this->request->param('sms_num');
        if($sms_num <  config('setting.min_sms_num') ){
            $this->error('最少充值'.config('setting.min_sms_num') . '条',null,101 );
        }
        $sum_price = $sms_num * config("setting.sms_price");
        if($sum_price > $this->member_info->money ){
            $this->error('您的余额已不足',null,101);
        }
        $data['money'] = $this->member_info->money - $sum_price;
        $data['sms_num'] = $sms_num;
        if($this->member_info->sms_num > 0){
            $data['sms_num'] =    $this->member_info->sms_num + $sms_num;
        }
        $MemberModel = new MemberModel();
        $MemberModel->save($data,['member_id'=>$this->member_id]);
        $SmspayModel = new SmspayModel();
        $SmspayModel->save([
            'member_id' => $this->member_id,
            'sms_num'   => $sms_num,
            'this_sms_num' => $data['sms_num'],
            'way'       => 1,
            'member_miniapp_id' => 0,
            'is_consume'   => 2,
        ]);
        $MoneylogModel =  new MoneylogModel();
        $MoneylogModel->save([
            'member_id' => $this->member_id,
            'way'       => 3,
            'money'     => $sum_price ,
            'this_money' => $data['money'],
            'is_consume' => 1,
        ]);
        $this->success('充值成功',null,101);
    }
	
}
