<?php

/**
 * @fileName    Index.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/19 0019
 */

namespace app\miniapp\controller;

use app\common\model\count\CountModel;
use app\common\model\hotel\HotelModel;
use app\common\model\miniapp\MiniappModel;
use app\common\model\user\UserModel;
use app\common\model\miniapp\AuthorizerModel;
use app\common\library\MiniApp;
class Index extends Common {

    protected $is_expir = false;

    public function index() {
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    public function main() {

        $MiniappModel = new MiniappModel();
        $muabn = $MiniappModel->find($this->miniapp_info->miniapp_id);
        $is_soon_expir = $this->miniapp_info->expir_time + config('setting.miniapp_warning_day') * 86400 < $this->request->time() ? true : false;
        $is_expir = $this->miniapp_info->expir_time < $this->request->time() ? true : false;
        $this->assign('muabn', $muabn);
        $this->assign('is_soon_expir', $is_soon_expir);
        $this->assign('is_expir', $is_expir);
        $MiniApp = new MiniApp( $this->miniapp_id);
        $data2 = $MiniApp->getweanalysisappiddailysummarytrend();
        $this->assign('data2',$data2);
        return $this->fetch();
    }
    
    public function commit(){
         $MiniApp = new MiniApp( $this->miniapp_id);
         $page = $MiniApp->getPage();
         if($page['errcode'] !=0){
             $this->error('请联系平台客服，第三方配置有问题！');
         }
         $category = $MiniApp->getCategory();
         if($category['errcode'] !=0){
             $this->error('请联系平台客服，第三方配置有问题！');
         }
         if ($this->request->method() == 'POST') {
             $keyword = $this->request->param('keyword');
             if(empty($keyword)){
                 $this->error('关键字不能为空');
             }
             $keyword = explode(' ', $keyword);
             foreach($keyword as $val){
                 if(strlen($val)<=1 && strlen($val)>=20){
                      $this->error('关键字配置错误');
                 }
             }
             if(count($keyword)>10){
                 $this->error('最多10个关键字');
             }
             $tag  = join(" ",$keyword);
             
             $cat = (int)  $this->request->param('cat');
             
             $result =  $MiniApp->commit($tag,$page['page_list'][0],'首页',$category['category_list'][$cat]);
             if($result['errcode'] ==0){
                
                $this->success('提交审核成功',null,100);
             }
             $this->error('提交审核失败'.$result['errcode']);
         }else{
             $this->assign('page',$page);
             $this->assign('category',$category);
             return $this->fetch();
         }
        
    }
    
    public function look(){
        $MiniApp = new MiniApp( $this->miniapp_id);
        $result = $MiniApp->look();
        $this->assign('result',$result);
        return $this->fetch();
    }
    
    public function fabu(){
        $MiniApp = new MiniApp( $this->miniapp_id);
        $result = $MiniApp->fabu();
        if($result['errcode']==0){
            $this->success('发布成功！',null);
        }
        $this->error('发布失败');
    }
    
    public function upload(){
        $MiniApp = new MiniApp( $this->miniapp_id);
        $MiniApp->domain()->upload();
        $MiniApp->setTemplate(); 
        $this->success('上传成功！');
    }
    
    public function qrcode(){
        $this->view->engine->layout(false);
        $MiniApp = new MiniApp( $this->miniapp_id);
        echo  $MiniApp->getQrcode();
        die;
    }
   

    public function map() {
        $lat = (float) $this->request->param('lat');
        $lng = (float) $this->request->param('lng');
        if (empty($lat) || empty($lng)) {
            $lat = 0;
            $lng = 0;
        }
        $this->assign('lat', $lat);
        $this->assign('lng', $lng);
        $callback = $this->request->param('callback');
        $this->assign('callback', $callback);
        return $this->fetch();
    }


    /*
     * 操作问题报错页面；
     */
    public function errorinfo(){
        $code = (int) $this->request->param('code');
        $info = "您访问的页面出错";
        switch ($code){
            case 101:
                $info = "小程序配置错误";
                break;
            case 102;
               $info = "您没有权限操作此页面";
               break;
        }
        $this->assign('info',$info);
        return $this->fetch();
    }

}
