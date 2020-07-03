<?php
namespace app\common\model\minsu;
use app\common\model\CommonModel;
class  MinsuModel extends CommonModel{
    protected $pk       = 'minsu_id';
    protected $table    = 'minsu';
    
    
     public function detailExists($serviceWhere){
        if(empty($serviceWhere)) return $this;
        
        $where = [];
        foreach($serviceWhere as $k=>$v){
            $where[] = "  `{$k}` = '1' ";
        }
        $where = join(" AND  ",$where);
        
        $this->whereExists(" select * from  `".config('database.prefix')."minsu_detail` where  {$where} AND  `".config('database.prefix')."minsu_detail`.minsu_id = `".config('database.prefix')."minsu`.minsu_id ");
       
        return $this;
    }
    
    public function specialExists($special_id){
        if(empty($special_id)) return $this;
        $special_id = (int)$special_id;
        $this->whereExists(" select * from  `".config('database.prefix')."minsu_special_join` where  `special_id` = '{$special_id}'  AND  `".config('database.prefix')."minsu_special_join`.minsu_id = `".config('database.prefix')."minsu`.minsu_id ");
        return $this;
    }
    
    
}