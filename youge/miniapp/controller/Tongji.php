<?php
/**
 * @fileName    setting.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/20 0020
 */
namespace app\miniapp\controller;
use app\common\library\MiniApp;

class Tongji extends  Common{
    
    public function index(){
        
        $MiniApp = new MiniApp( $this->miniapp_id);
        $data2 = $MiniApp->getweanalysisappiddailysummarytrend();
        $this->assign('data2',$data2);
        return $this->fetch();
    }
    
    public function fenbu(){
        $MiniApp = new MiniApp( $this->miniapp_id);
        $data = $MiniApp->getweanalysisappidvisitdistribution();
        $this->assign('data',$data);
        return $this->fetch();
    }
    
    public function liucun(){
        $MiniApp = new MiniApp( $this->miniapp_id);
        $data2 = $MiniApp->getweanalysisappiddailyretaininfo();
        $this->assign('data2',$data2);
        return $this->fetch();
    }
    
 
    
    
    
}