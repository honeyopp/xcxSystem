<?php
namespace app\common\model\city;
use app\common\model\CommonModel;
class  CityModel extends CommonModel{
    protected $pk       = 'city_id';
    protected $table    = 'city';
    protected $fetchAllCache = 'city';
    protected $fetchAllOrder = "orderby desc";

}