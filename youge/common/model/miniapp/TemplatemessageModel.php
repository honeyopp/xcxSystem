<?php
namespace app\common\model\miniapp;
use app\common\model\CommonModel;
class  TemplatemessageModel extends CommonModel{
    protected $pk       = 'id';
    protected $table    = 'member_miniapp_template_message';
    
    
    public function getTemplateId($member_miniapp_id){
        $data = $this->where(['member_miniapp_id'=>$member_miniapp_id])->select();
        $return  = [];
        foreach($data as $val){
            $return[$val->template] = $val->template_id;
        }
        return $return;
    }
    
    
}