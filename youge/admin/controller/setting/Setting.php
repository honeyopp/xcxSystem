<?php
namespace app\admin\controller\setting;
use app\admin\controller\Common;
use app\common\model\setting\SettingModel;

class Setting extends Common {
    
     public function index() {
         $SettingModel  = new SettingModel();
         if ($this->request->method() == 'POST') {
             $data = serialize($_POST['data']);
             $SettingModel->save(['v'=>$data],['k'=>'site']);
             $this->success('操作成功');
         }else{
      
            $this->assign('setting',$SettingModel->fetchAll(true));
            return $this->fetch();
         }
     }
      public function sms() {
         $SettingModel  = new SettingModel();
         if ($this->request->method() == 'POST') {
             $data = serialize($_POST['data']);
             $SettingModel->save(['v'=>$data],['k'=>'sms']);
             $this->success('操作成功');
         }else{
      
            $this->assign('setting',$SettingModel->fetchAll(true));
            return $this->fetch();
         }
     }
      public function payment() {
         $SettingModel  = new SettingModel();
         if ($this->request->method() == 'POST') {
             $data = serialize($_POST['data']);
             $SettingModel->save(['v'=>$data],['k'=>'payment']);
             $this->success('操作成功');
         }else{
      
            $this->assign('setting',$SettingModel->fetchAll(true));
            return $this->fetch();
         }
     }
     
     public function attachs() {
         $SettingModel  = new SettingModel();
         if ($this->request->method() == 'POST') {
             $data = serialize($_POST['data']);
             $SettingModel->save(['v'=>$data],['k'=>'attachs']);
             $this->success('操作成功');
         }else{
      
            $this->assign('setting',$SettingModel->fetchAll(true));
            return $this->fetch();
         }
     }
     public function jiguang() {
         $SettingModel  = new SettingModel();
         if ($this->request->method() == 'POST') {
             $data = serialize($_POST['data']);
             $SettingModel->save(['v'=>$data],['k'=>'jiguang']);
             $this->success('操作成功');
         }else{
      
            $this->assign('setting',$SettingModel->fetchAll(true));
            return $this->fetch();
         }
     }
     public function integral() {
         $SettingModel  = new SettingModel();
         if ($this->request->method() == 'POST') {
             $data = serialize($_POST['data']);
             $SettingModel->save(['v'=>$data],['k'=>'integral']);
             $this->success('操作成功');
         }else{
      
            $this->assign('setting',$SettingModel->fetchAll(true));
            return $this->fetch();
         }
     }
}
