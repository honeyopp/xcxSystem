<?php

namespace app\miniapp\controller;
use app\common\model\miniapp\AuthorizerModel;
use app\common\model\miniapp\MiniappModel;
use think\Controller;

class Common extends Controller {
    protected $app_id = 0;
    protected $app_key = '';
    protected $miniapp_id = '';
    protected $miniapp_info = [];
    protected $is_expir = true;
    protected function _initialize() {

        $cookieToken = cookie('miniapp');
        $cookieInfo = authcode($cookieToken, 'DECODE');
        if(empty($cookieInfo)){
            $this->redirect("miniapp/Passport/login");
        }
        $cookieArray = explode('|',$cookieInfo);
        if($cookieArray[2] != 'miniapp'){
            $this->redirect("miniapp/Passport/login");
        }
        $AuthorizerModel = new AuthorizerModel();
        $appkey = $cookieArray[1];
        $appid  =  $cookieArray[0];
        if(!$detail =$AuthorizerModel->find($appid) ){
            $this->redirect("miniapp/Passport/login");
        }
        if($detail->appkey != $appkey){
            $this->redirect("miniapp/Passport/errorinfo",['cord'=>101]);
        }
        if($this->is_expir == true){
            if($detail->expir_time < $this->request->time()){
                $this->redirect("miniapp/index/main");
                die();
            }
        }
        $MiniappModel  = new MiniappModel();
        if(!$miniapp = $MiniappModel->find($detail->miniapp_id)){
            $this->redirect("miniapp/Passport/errorinfo",['cord'=>101]);
        }
        $this->app_id = $detail->authorizer_appid;
        $this->app_key = $detail->appkey;
        $this->miniapp_id = $detail->member_miniapp_id;
        $this->miniapp_info = $detail;

        $this->assign('miniapp',$this->miniapp_info);
        $leftMenus = config($miniapp->miniapp_dir);
        if(empty($leftMenus)){
            $this->redirect("miniapp/Passport/errorinfo",['cord'=>102]);
        }
        if(!$this->getMennus($leftMenus)){
            $this->redirect("miniapp/Passport/errorinfo",['cord'=>102]);
        };
        $this->assign('leftMenus', $leftMenus);
    }


    

    //权限控制;
    public function getMennus($leftMenus){
//        $menus = $leftMenus;
//        $link  = strtolower('/miniapp/' . $this->request->controller()).'/'.strtolower($this->request->action());
//        $main = '/miniapp/index/main';
//        $index = '/miniapp/index/index';
//        if($main == $link || $index == $link){
//            return true;
//        }
//        foreach ($menus as $val){
//            foreach ($val['menu'] as $v){
//                  if($v['link'] == $link){
//                      return true;
//                  }
//                  foreach ($v['sub'] as $sub){
//                      if($sub['link'] == $link){
//                          return true;
//                      }
//                  }
//            }
//        }
        return true;
    }

}
