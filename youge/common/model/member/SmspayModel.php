<?php
namespace app\common\model\member;
use app\common\model\CommonModel;
class  SmspayModel extends CommonModel{
    protected $pk       = 'pay_id';
    protected $table    = 'member_pay_sms';

}