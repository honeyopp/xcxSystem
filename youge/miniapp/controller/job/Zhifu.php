<?php
namespace app\miniapp\controller\job;
use app\miniapp\controller\Common;
use app\common\model\setting\SkinModel;
class Zhifu extends Common {

    public function pay(){
        $MiniappsettingModel = new SkinModel();
        $setting =  $MiniappsettingModel->where(['member_miniapp_id'=>$this->miniapp_id])->find();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['mini_appid'] = $this->request->param('mini_appid');
            if(empty($data['mini_appid'])){
                $this->error('您的appid不能为空',null,101);
            }
            $data['mini_appsecrept'] = $this->request->param('mini_appsecrept');
            if(empty($data['mini_appid'])){
                $this->error('您的appsecrept不能为空',null,101);
            }
            $data['mini_mid'] = $this->request->param('mini_mid');
            if(empty($data['mini_appid'])){
                $this->error('您的商户id不能为空',null,101);
            }
            $data['mini_apicode'] = $this->request->param('mini_apicode');
            if(empty($data['mini_appid'])){
                $this->error('您的商户秘钥不能为空',null,101);
            }
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['apiclient_cert'] = $this->request->param('apiclient_cert');
            if(empty($data['apiclient_cert'])){
                $this->error("证书不能为空",null,101);
            }
            $data['apiclient_cert_key'] = $this->request->param('apiclient_cert_key');
            if(empty($data['apiclient_cert_key'])){
                $this->error("证书秘钥不能为空",null,101);
            }
            if(!$setting){
                $MiniappsettingModel->save($data);
                $this->success('操作成功',null);
            }else{
                $data['member_miniapp_id']  = $this->miniapp_id;
                $MiniappsettingModel->save($data,['member_miniapp_id'=>$this->miniapp_id]);
                $this->success('操作成功',null);
            }
        } else {
            $this->assign('detail',$setting);
            return $this->fetch();
        }
    }
}