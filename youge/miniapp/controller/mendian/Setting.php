<?php
namespace app\miniapp\controller\mendian;
use app\miniapp\controller\Common;
use app\common\model\mendian\SettingModel;
class Setting extends Common {
    
    public function index() {
        
       
         $SettingModel = new SettingModel();
     
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['name'] = $this->request->param('name');  
            $data['banner'] = $this->request->param('banner');  
            $data['logo'] = $this->request->param('logo');  
            $data['addr'] = $this->request->param('addr');  
            $data['gps_addr'] = $this->request->param('gps_addr');  
            $data['lng'] = $this->request->param('lng');  
            $data['lat'] = $this->request->param('lat');  
            $data['tel'] = $this->request->param('tel');  
            $data['is_wifi'] = $this->request->param('is_wifi');  
            $data['is_p'] = $this->request->param('is_p');  
            $data['is_weixin'] = $this->request->param('is_weixin');  
            $data['is_alipay'] = $this->request->param('is_alipay');  
            $data['biz_t'] = $this->request->param('biz_t');  
            $data['info'] = $this->request->param('info');  
                
            if(!$detail = $SettingModel->get($this->miniapp_id)){
                  $SettingModel->save($data);
             }else{
                 $SettingModel->save($data,['member_miniapp_id'=>$this->miniapp_id]);
             }            
            $this->success('操作成功',null);
         }else{
            $detail = $SettingModel->get($this->miniapp_id);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    

   
}