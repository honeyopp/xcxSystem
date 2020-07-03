<?php

namespace app\home\controller;
use app\common\model\member\MemberModel;
use app\common\model\mobile\CodeModel;
use app\common\model\setting\ComponentVerifyTicketModel;
use app\common\library\Curl;
use app\common\model\miniapp\AuthorizerModel;
use think\Controller;

class Passport extends Controller {
       
  

   /**
    *用户登录；
    * @param  $mobile number 手机号码；
    * @param  $password string 密码；
    */
   public function login(){
       if($this->request->method() == "POST") {
           $mobile = $this->request->param('monile');
           $password = $this->request->param('password');
           $MemberModel = new MemberModel();
           if (!$detail = $MemberModel->where(['mobile' => $mobile])->find()) {
               $this->error('账号不存在',null,101);
           }
           if ($detail->is_lock == 1) {
               $this->error('账户已锁定',null,101);
           }
           if ($detail->password == md5($password)) {
               //登录成功
               $data['last_time'] = $this->request->time();
               $data['last_ip'] = $this->request->ip(0, true);
               $MemberModel->save($data, ['member_id' => $detail->member_id]);
               $code = authcode($detail->member_id . '|yohnghu|' . $_SERVER['REQUEST_TIME']);
               cookie('memberID', $code);
               $this->success('登录成功',url("index/index"));
           } else {
               $this->error('密码不正确',null,101);
           }
       }else{
           return $this->fetch();
       }
   }
   /**
    *注册；
    * @param $mobile number 手机号；
    * @param $password string 密码；
    * @param $password2 string 确认密码；
    * @param $code  int  短信验证码；
    * @param $nick_name string 昵称；
    * @param $real_name string 真实姓名；
    * @param $email string 邮箱；
    * @param $qq string qq
    * @param $weixin string 微信号
    */
   public function register(){
       if($this->request->method() == "POST"){
           $code = $this->request->param('code');
           $data['mobile'] =  $this->request->param('mobile');
           $MemberModel = new MemberModel();
           if($MemberModel->where(['mobile'=>$data['mobile']])->select()){
               $this->error('已存在该用户');
           }
           if (empty($code) || empty($data['mobile'])) {
               $this->error('验证码和手机号不能为空',null,101);
           }
           $CodeModel = new CodeModel();
           if (!$codeInfo = $CodeModel->get(['mobile' => $data['mobile']])) {
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
               $this->error('两次密码不一致');
           }
           $data['nick_name'] = (string) $this->request->param('nick_name');
           if(empty($data['nick_name'])){
               $data['nick_name'] = '用户' . substr($data['mobile'],-4);
           }
           $data['qq'] = (string) $this->request->param('qq');
           $data['weixin'] = (string) $this->request->param('weixin');
           $data['real_name'] = (string) $this->request->param('real_name');
           $data['email'] = (string) $this->request->param('email');
           $MemberModel->save($data);
           $MemberModel->save($data,['member_id'=>$MemberModel->member_id]);
           $code = authcode($MemberModel->member_id . '|yohnghu|' . $_SERVER['REQUEST_TIME']);
           cookie('memberID', $code);
           $this->success('注册成功',url("index/index"));
//       注册成功的操作；
       }else{
           return $this->fetch();
       }

   }
    /**
     * 找回密码 AND 修改密码
     * @param  $mobile number 手机号；
     * @param  $code int  验证码；
     * @param  $password string 新密码；
     * @param  $password2 string 确认密码；
     */
    public function  findpwd (){
        $code = $this->request->param('code');
        $data['mobile'] =  $this->request->param('mobile');
        $MemberModel = new MemberModel();
        if(!$detail  = $MemberModel->where(['mobile'=>$data['mobile']])->select()){
            $this->error('不存在该用户');
        }
        if (empty($code) || empty($data['mobile'])) {
            $this->result([], 0 ,'验证码和手机号不能为空',null,101);
        }
        $CodeModel = new CodeModel();
        if (!$codeInfo = $CodeModel->get(['mobile' => $data['mobile']])) {
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
        $password2 = $this->request->path('password2');
        if(empty($data['password'])){
            $this->error('请输入密码');
        }else{
            $data['password'] = md5($data['password']);
        }
        if($data['password'] != md5($password2)){
            $this->error('密码不一致');
        }
        $MemberModel->save($data,['member_id'=>$detail->member_id]);
        $code = authcode($detail->member_id . '|yohnghu|' . $_SERVER['REQUEST_TIME']);
        cookie('memberID', $code);
        $this->success('登录成功',url("index/index"));
    }
    /**
     * 获取短信验证码；
     * @param mobile number 手机号
     * @return int|false 6位数字验证码；
     */
    //获取短信验证码
    public function sendSms(){
        $mobile = $this->request->param('mobile');
         if(!$this->checkMobile($mobile)){
             $this->error('请填写正确的是手机号',null,101);
         }
        $CodeModel = new CodeModel();
        $CodeModel->sendSms($mobile);
        $this->error('短信发送成功10分钟内有效',null,200);
    }
    public function checkMobile($tel){
        $search ='/^1[0-9]{1}\d{9}$/';
        if(preg_match($search,$tel)) {
            return true;
        }else {
            return false;
        }
    }
}