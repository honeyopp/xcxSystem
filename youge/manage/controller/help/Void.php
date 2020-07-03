<?php
/**
 * @fileName    Void.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/25 0025
 */
namespace app\manage\controller\help;
use app\manage\controller\Common;

class Void extends Common{
    public function index (){
        return $this->fetch();
    }
}