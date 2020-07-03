<?php

namespace app\api\controller;
use app\common\model\member\SmslogModel;
use app\common\model\miniapp\AuthorizerModel;
use app\common\model\mobile\CodeModel;
use app\common\model\setting\ComponentVerifyTicketModel;
use app\common\model\user\UserModel;
class Login extends Common{
    //获得OPENID    
    public function index(){
        $code = $this->request->param('code');
        $ComponentVerifyTicketModel = new ComponentVerifyTicketModel();
        $token = $ComponentVerifyTicketModel->getToken();
        $api = 'https://api.weixin.qq.com/sns/component/jscode2session?appid='.$this->miniappid.
                '&js_code='.$code.'&grant_type=authorization_code&component_appid='. 
                config('weixin.appid').'&component_access_token='.$token;
        $curl = new \app\common\library\Curl();
        $result = $curl->get($api);
        $result = json_decode($result,true);
        if(empty($result['openid'])){
            $this->result([], 400, '获取用户信息失败', 'json');
        }
        $UserModel = new UserModel();
        if(!$user = $UserModel->get(['open_id'=>$result['openid'],'member_miniapp_id'=>  $this->appid])){
           // $UserModel = new UserModel();
            $UserModel->save([
                'open_id'          =>  $result['openid'],
                'member_miniapp_id'=>  $this->appid,
                'last_time'        =>  $this->request->time(),
                'last_ip'          =>  $this->request->ip(),
                'day'              =>  1,    
            ]);
            $user = $UserModel->get($UserModel->user_id);
        }else{
            $time = $this->request->time();
            if($time - $user['last_time'] > 86400){ //做一次更新操作
                $UserModel->save([
                    'last_time' =>  $time,
                    'last_ip'   =>  $this->request->ip(),
                    'day'       =>  $user->day+1,    
                ],[
                 'user_id'=>$user->user_id,
                ]);
                $user['last_time'] = $this->request->time();
                $user['last_ip']   = $this->request->ip();
            }
        }
        $return = [
             'user_id'   => $user->user_id,
             'open_id'   => $user->open_id,
             'face'      => $user->face,
             'nick_name' => $user->nick_name,
             'real_name' => $user->real_name,
             'mobile'    => $user->mobile,
             'sex'       => $user->sex,
             'birthday'  => $user->birthday,
            'is_lock'    => $user->is_lock,
            'day'        => $user->day,
            'is_manage'  => $user->is_manage,
            'last_time'  => $user['last_time'],
            'last_ip'    => $user['last_ip'],
        ];  
        $this->result($return, 200, '获取用户OPENID成功', 'json');
    }
    //将OPENID和数据库里面的关联
    public function bind(){
        $openid = $this->request->param('openid');
        $face   = $this->request->param('face');
        $nick_name = $this->request->param('nick_name');
        $sex    = $this->request->param('sex');
         $UserModel = new UserModel();
        if(!$user = $UserModel->get(['open_id'=>$openid,'member_miniapp_id'=>  $this->appid])){
            $this->result([], 400, '用户信息不存在', 'json');
        }
        $user['face'] = $face;
        $user['nick_name'] = $nick_name;
        $user['sex'] = (int)$sex;
        $UserModel->save([
            'face' =>  $face,
            'nick_name'   =>  $nick_name,
            'sex'       => $sex,    
        ],[
         'user_id'=>$user->user_id,
        ]);
        
        $return = [
             'user_id'   => $user->user_id,
             'open_id'   => $user->open_id,
             'face'      => $user['face'],
             'nick_name' => $user['nick_name'],
             'real_name' => $user->real_name,
             'mobile'    => $user->mobile,
             'sex'       => $user['sex'],
             'birthday'  => $user->birthday,
            'is_lock'    => $user->is_lock,
            'day'        => $user->day,
            'last_time'  => $user->last_time,
            'last_ip'    => $user->last_ip,
        ];  
        $this->result($return, 200, '获取用户OPENID成功', 'json');
    }
    //获取短信验证码
    public function sendSms(){
        $AuthorizerModel = new AuthorizerModel();
        $miniapp = $AuthorizerModel->find($this->appid);
        if($miniapp->sms_num <= 0 ){
            $this->result([],400,'您好该商家短信服务已到期','json');
        }
        $mobile = $this->request->param('mobile');
        if(empty($mobile)){
            $this->result([], 400,'请填写正确的手机号码','json');
        }
        $CodeModel = new CodeModel();
        $CodeModel->sendSms($mobile);
        $AuthorizerModel->where(['member_miniapp_id'=>$this->appid])->setDec('sms_num');
        $SmslogModel = new SmslogModel();
        $SmslogModel->save([
            'member_miniapp_id'  => $this->appid,
            'member_id'          => $miniapp->member_id,
            'user_mobile'        => $mobile,
            'sms_content'        => '发送验证码 **** 10分钟内有效 ',
        ]);
        $this->result([], 200 ,'短信发送成功，10分钟后失效！','json');
    }



}