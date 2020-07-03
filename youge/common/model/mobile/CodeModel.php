<?php
namespace app\common\model\mobile;
use app\common\model\CommonModel;
use app\common\library\sms\SmsSingleSender;
use think\Request;
class  CodeModel extends CommonModel{
    protected $pk       = 'code_id';
    protected $table    = 'mobile_code';
    protected $insert   = [];
    
    public function sendSms($mobile,$num = '86'){
        $data = $this->get(['mobile'=>$mobile]);
        if(empty($data)){
            $code = IS_ONLINE ?   rand(100000,999999) : '123456';
            $this->save([
                'mobile'=>$mobile,
                'code'  =>$code,
                'code_time' => Request::instance()->time(),
                'err_num' => 0,
            ]);
        }else{
            if($data['code_time'] < Request::instance()->time() - 600){ //10分钟
                $code = IS_ONLINE ?   rand(100000,999999) : '123456';
            }else{
                $code = $data['code'];
            }
            $this->save([
                'code_time' => Request::instance()->time(),
                'code' => $code,
                'err_num' => 0,
            ],['mobile'=>$mobile]);
        }
        //下面发短信的逻辑后面补充
        if(IS_ONLINE){
            $SmsSingleSender = new SmsSingleSender();
            $result = $SmsSingleSender->send(0, $num, $mobile, "尊敬的用户,您的短信验证码为:".$code."；10分钟内有效！", "", "");
            $rsp = json_decode($result);
        
            // var_dump($rsp);
        return $rsp;
        }
        return true;
    }

}