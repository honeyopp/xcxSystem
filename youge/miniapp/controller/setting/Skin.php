<?php
namespace app\miniapp\controller\setting;
use app\miniapp\controller\Common;
use app\common\model\setting\SkinModel;
class Skin extends Common {
    

    public function create() {
        $SkinModel = new  SkinModel();
        $detail = $SkinModel->find($this->miniapp_id);
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['service_tel'] = $this->request->param('service_tel');  
            if(empty($data['service_tel'])){
                $this->error('客服电话不能为空',null,101);
            }
            $data['complaint_tel'] = $this->request->param('complaint_tel');  
            if(empty($data['complaint_tel'])){
                $this->error('投诉电话不能为空',null,101);
            }
            $data['about'] = $this->request->param('about');  
            if(empty($data['about'])){
                $this->error('关于本站不能为空',null,101);
            }
            $data['app_name'] = $this->request->param('app_name');  
            if(empty($data['app_name'])){
                $this->error('站点名称不能为空',null,101);
            }
            $data['logo'] = (string) $this->request->param('logo');
            $data['skin'] = $this->request->param('skin');  
            if(empty($data['skin'])){
                $this->error('系统皮肤（颜色设置）不能为空',null,101);
            }
            if($detail){
                $SkinModel->save($data,['member_miniapp_id',$this->miniapp_id]);
            }else{
                $SkinModel->save($data);
            }
            $this->success('操作成功',null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }


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
            $data['is_pay'] = (int) $this->request->param('is_pay');
            if(!empty($data['is_pay'])){
                $data['pay_money'] = ((int) $this->request->param('pay_money')) * 100;
                if($data['pay_money'] <= 0){
                    $this->error('开启在线减 请填写立减额度 不得大于100元',null,101);
                }
                if($data['pay_money'] >= 10000){
                    $this->error('立减额度 不得大于100元',null,101);
                }
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