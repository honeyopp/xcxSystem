<?php
namespace app\common\model\order;
use app\common\model\CommonModel;
class  OrderModel extends CommonModel{
    protected $pk       = 'order_id';
    protected $table    = 'order';
    
    
    public function getBuyIds($member_id,$member_miniapp_id){
        $return = [];
        $list = $this->where(['member_id'=>$member_id,'member_miniapp_id'=>$member_miniapp_id])->select();
        foreach($list as  $val){
           $return[$val->miniapp_id] = $val;
        }
        return $return;
    }
    
    
}