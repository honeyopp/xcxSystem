<?php

namespace app\admin\controller;
class Index extends Common
{

    public function index(){
        $this->view->engine->layout(false);
        return $this->fetch();
    }
    
    public function main(){
          //获取今日访客数；
        
        return $this->fetch();
    }


    //高德地图 选取经纬度；
    public function map(){
        $lat = (float) $this->request->param('lat');
        $lng = (float) $this->request->param('lng');
        if(empty($lat) || empty($lng)){
                $lat = 0;
                $lng = 0;
        }
        $this->assign('lat',$lat);
        $this->assign('lng',$lng);
        $callback = $this->request->param('callback');
        $this->assign('callback',$callback);
        return $this->fetch();
    }
}