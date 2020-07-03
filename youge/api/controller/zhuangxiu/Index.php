<?php
namespace app\api\controller\zhuangxiu;
use app\api\controller\Common;
use app\common\model\zhuangxiu\TenderModel;

class  Index extends Common {
        protected $checklogin = true;
    public function tender(){
        $data['bedroom'] = (int) $this->request->param('bedroom');
        $data['livingroom'] = (int) $this->request->param('livingroom');
        $data['toilet'] = (int) $this->request->param('toilet');
        $data['balcony'] = (int) $this->request->param('balcony');
        $data['kitchen'] = (int) $this->request->param('kitchen');
        $data['area'] = (string) $this->request->param('area');
        $data['member_miniapp_id'] = $this->appid;
        $data['name'] = (string) $this->request->param('name');
        if (empty($data['name'])){
            $this->result([],'400','请输入姓名','json');
        }
        $data['mobile'] = (string) $this->request->param('mobile');
        if(empty($data['mobile'])){
            $this->result([],'400','请输入手机号','json');
        }
        $data['content'] = (string) $this->request->param('content');
        if(empty($data['content'])){
            $this->result([],'400','请输入备注','json');
        }
        $data['user_id']  = $this->user->user_id;
        $TenderModel = new TenderModel();
        $TenderModel->save($data);
        $this->result([],'200','操作成功','json');
    }
}