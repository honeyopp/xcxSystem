<?php
namespace app\common\model\hotel;
use app\common\model\CommonModel;
class  CouponModel extends CommonModel{
    protected $pk       = 'hotel_id';
    protected $table    = 'hotel_coupon';
}