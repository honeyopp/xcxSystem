<?php
namespace app\common\model\hotel;
use app\common\model\CommonModel;
class  HotelModel extends CommonModel{
    protected $pk       = 'hotel_id';
    protected $table    = 'hotel';
    
    
     public function detailExists($serviceWhere){
        if(empty($serviceWhere)) return $this;
        
        $where = [];
        foreach($serviceWhere as $k=>$v){
            $where[] = "  `{$k}` = '1' ";
        }
        $where = join(" AND  ",$where);
        
        $this->whereExists(" select * from  `".config('database.prefix')."hotel_detail` where  {$where} AND  `".config('database.prefix')."hotel_detail`.hotel_id = `".config('database.prefix')."hotel`.hotel_id ");
       
        return $this;
    }
    
    public function specialExists($special_id){
        if(empty($special_id)) return $this;
        $special_id = (int)$special_id;
        $this->whereExists(" select * from  `".config('database.prefix')."hotel_special_join` where  `special_id` = '{$special_id}'  AND  `".config('database.prefix')."hotel_special_join`.hotel_id = `".config('database.prefix')."hotel`.hotel_id ");
        return $this;
    }
    
    
}