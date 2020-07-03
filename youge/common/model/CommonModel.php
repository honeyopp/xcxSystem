<?php
namespace app\common\model;
use think\Model;
use think\Request;
use think\Cache;
class   CommonModel extends Model{
    protected $auto   = [];
    protected $insert = ['add_time','add_ip'];  
    protected $update = [];  
    protected $fetchAllOrder = [];
    protected $fetchAllCache = '';
    public function setAddTimeAttr(){
        return   Request::instance()->time();
    }

    public function setAddIpAttr(){
        return Request::instance()->ip();
    }
    
    //字段自增或减少数量
    public function IncDecCol($pk,$col,$num=1){
        $num = (int)$num;
        return  $this->db()->execute("update ".config('database.prefix').$this->table." set `{$col}` = `{$col}` + {$num}  where `{$this->pk}` = '{$pk}' ");
    }
    
    public function getChildIds($parent_id){
        
        $datas = $this->field($this->pk)->where(['parent_id'=>$parent_id])->select();
        $return = [];
        foreach($datas as $val){
            $return[$val[$this->pk]] = $val[$this->pk];
        }
        return $return;
    }
    
    
    //根据一组ID查询
    public function  itemsByIds($ids){
        //var_dump($ids);
        $return  = [];
        if(empty($ids)) return $return;
        $data = $this->where([$this->pk=>['IN',$ids]])->select();     
        foreach($data as $val){
            $return[$val[$this->pk]] = $val;
        }
        return $return;
    }
    
    //查询某个条件下的所有数据 然后将ID 作为KEY 返回
    public function fetchItems($miniapp_id,$order=[]){
        $order = empty($order) ? ["{$this->pk}" => 'desc'] : $order;
        $miniapp_id = (int) $miniapp_id;
        $data = $this->where(['member_miniapp_id'=>$miniapp_id])->select();
        $return = [];
        foreach($data as $val){
            $return[$val[$this->pk]] = $val;
        }
        return $return;
    }
    
    //查询所有
    public function fetchAll($type=false){
        $return  = [];
        $return = Cache::get($this->fetchAllCache);

        if(empty($return) || $type === true){
            $data = $this->order($this->fetchAllOrder)->select();
            foreach($data as $val){
                $return[$val[$this->pk]] = $val;
            }
           Cache::set($this->fetchAllCache, $return);
        }

        return $return;
    }
    
    
}