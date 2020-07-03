<?php
/**
 * @fileName    setting.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/20 0020
 */
namespace app\miniapp\controller;
use app\common\model\miniapp\MiniappsettingModel;
use app\common\model\setting\CityModel;

class Setting extends  Common{
    /**
     * 设置小程序基本信息;
     * @param $data 一大堆数据;
     */
    public function setting(){
        $MiniappsettingModel = new MiniappsettingModel();
        $setting =  $MiniappsettingModel->where(['member_miniapp_id'=>$this->miniapp_id])->find();
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
                $this->error('关于我们不能为空',null,101);
            }
            $data['copyright'] = $this->request->param('copyright');
            $data['apiclient_cert'] = $this->request->param('apiclient_cert');
            if(empty($data['apiclient_cert'])){
                $this->error('证书pem格式不能为空',null,101);
            }
            $data['apiclient_cert_key'] = $this->request->param('apiclient_cert_key');
            if(empty($data['apiclient_cert_key'])){
                $this->error('证书秘钥pem格式不能为空',null,101);
            }
            $data['rootca'] = $this->request->param('rootca');
            if(empty($data['rootca'])){
                $this->error('ci证书文件不能为空',null,101);
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