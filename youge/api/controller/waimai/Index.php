<?php
namespace app\api\controller\waimai;
use app\api\controller\Common;
use app\common\model\user\CouponModel;
use app\common\model\waimai\CategoryModel;
use app\common\model\waimai\ProductModel;
use app\common\model\waimai\WaimaisettingModel;
use app\common\model\setting\ActivityModel;
class Index extends Common {
    
    public function setting(){
        $setting = WaimaisettingModel::get($this->appid);
        
        $sett = [
            'qijia'     => empty($setting['qijia']) ? 0 : round($setting['qijia']/100,2),
            'peisong'   => empty($setting['peisong'])? 0 : round($setting['peisong']/100,2),
            'is_online' => empty($setting['is_online'])? 0 :$setting['is_online'],
        ];
        $this->result($sett, 200, '获取数据成功', 'json');
    }
    
    public function index(){
        $aWhere = [];
        $aWhere['is_online'] = 1;
        $aWhere['member_miniapp_id'] = $this->appid;
        $date = date("Y-m-d");
        $aWhere['bg_date'] = ['<=',$date];
        $aWhere['end_date'] = ['>=',$date];
        $ActivityModel = new ActivityModel();
        $list = $ActivityModel->where($aWhere)->order("orderby desc")->limit(0,5)->select();
        $activity = [];
        foreach($list as $val){
            $activity[] = [
                'activity_id' => $val->activity_id,
                'title'   => $val->title,
                'money'  => sprintf("%.2f",$val->money/100),
                'need_money' => sprintf("%.2f",$val->need_money/100),
                'expire_day' => $val->expire_day,
                'use_day'  => $val->use_day,
                'is_newuser' => $val->is_newuser,
                'num'     => $val->num,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
            ];
        }
        $CategoryModel = new CategoryModel();
        $catList  = $CategoryModel->fetchItems($this->appid,['orderby'=>'desc']);
        $cats = [];
        foreach($catList as $k=>$val){
            $cats[]=[
                'id' => $val->cat_id,
                'name' => $val->name,
                'num'  => 0,
                'bottom' => 0,
            ];
        }
        
        $products = ProductModel::where(['member_miniapp_id'=>  $this->appid,'is_online'=>1])->order(['orderby'=>'desc'])->select();
        $pros = [];
        
        foreach($products as $val){
            $pros[]=[
                'id' => $val->product_id,
                'cat' => $val->cat_id,
                'name' => $val->name,
                'photo'=> IMG_URL .  getImg($val->photo),
                'price' => round($val->price/100,2),
                'dabao' => round($val->dabao/100,2),
                'monthnum' => $val->monthnum,
                'totalnum' => $val->totalnum,
                'totalprice' => 0,
                'buynum' => 0,
            ];
        }
        
        
        $setting = WaimaisettingModel::get($this->appid);
        
        $sett = [
            'qijia'     => empty($setting['qijia']) ? 0 : round($setting['qijia']/100,2),
            'peisong'   => empty($setting['peisong'])? 0 : round($setting['peisong']/100,2),
            'is_online' => empty($setting['is_online'])? 0 :$setting['is_online'],
        ];
        
        $return = [
            'cats' => $cats,
            'activity' => $activity,
            'products' => $pros,
            'setting'  => $sett,
        ];
        $this->result($return, 200, '获取数据成功', 'json');
    }
    
    
    
    
    
}
