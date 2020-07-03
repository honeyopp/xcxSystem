<?php
/**
 * @fileName    Faq.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/24 0024
 */
namespace app\manage\controller\help;
use app\manage\controller\Common;

class Faq extends  Common{
    public function index(){
        return $this->fetch();
    }
}