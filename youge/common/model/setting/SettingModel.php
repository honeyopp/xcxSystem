<?php
namespace app\common\model\setting;
use app\common\model\CommonModel;
use think\Cache;
class  SettingModel extends CommonModel{
    protected $pk       = 'k';
    protected $table    = 'setting';
    protected $fetchAllCache = 'site_setting';
    
    
    public function fetchAll($type=false){
        $return  = [];
        $return = Cache::get($this->fetchAllCache);
        if(empty($return) || $type === true){
            $data = $this->order($this->fetchAllOrder)->select();
            foreach($data as $val){
                $return[$val[$this->pk]] = !empty($val['v']) ? unserialize($val['v']) : [];
            }
           Cache::set($this->fetchAllCache, $return);
        }
        return $return;
    }
 
    
    
    
    
}