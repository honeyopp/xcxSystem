<?php
namespace app\common\model\user;
use app\common\model\CommonModel;
class  ActivitylogModel extends CommonModel{
    protected $pk       = 'log_id';
    protected $table    = 'activity_user_log';

}