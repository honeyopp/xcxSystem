<?php
namespace app\common\model\setting;
use app\common\model\CommonModel;
class  CityModel extends CommonModel{
    protected $pk       = 'city_id';
    protected $table    = 'city';
    protected $fetchAllCache = 'city';
    protected $fetchAllOrder = "initial asc";
    
    
    
    
}