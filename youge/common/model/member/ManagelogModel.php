<?php
/**
 * @fileName    ManagelogModel.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/29 0029
 */
namespace app\common\model\member;
use app\common\model\CommonModel;

class ManagelogModel extends  CommonModel{
    protected $pk       = 'log_id';
    protected $table    = 'login_log';
}