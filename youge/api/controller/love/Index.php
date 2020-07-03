<?php
namespace app\api\controller\love;
use app\api\controller\Common;
use app\common\model\love\UserModel;
use think\console\command\make\Model;

class Index extends Common{
    //获取用户列表；未登录
    public function getIndex(){
        $UserModel = new UserModel();
        $where['member_miniapp_id'] = $this->appid;
        $list =   $UserModel->where($where)->order('orderby desc')->limit(0,10)->select();
        $data = [];
        foreach ($list as $val){
            $data[] = [
                'photo' => IMG_URL .getImg($val->photo),
                'nickname' => $val->nickname,
                'city'   =>  $val->city,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }


    public function getDatas(){
        $data = config('jobsetting');
        $this->result($data,'200','数据初始成功','json');
    }
}