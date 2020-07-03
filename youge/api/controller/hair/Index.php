<?php
namespace app\api\controller\hair;
use app\api\controller\Common;
use app\common\model\hair\BannerModel;
use app\common\model\hair\CategoryModel;
use app\common\model\hair\CommentModel;
use app\common\model\hair\DesignerModel;
use app\common\model\hair\HairModel;
use app\common\model\hair\PhotoModel;
use app\common\model\hair\PriceModel;
use app\common\model\hair\SjsalModel;
use app\common\model\hair\SjsalphotoModel;
use app\common\model\hair\WorksModel;
use app\common\model\user\UserModel;
use app\common\model\setting\ActivityModel;

class Index extends  Common{

    /*
     * 获取首页数据 banner 地址 一个排序最高的红包 设计师列表
     */
    public function getIndex(){
//       获取banner
       if($this->limit_bg <= 1) {
           $where['member_miniapp_id']  = $this->appid;
           $BannerModel = new BannerModel();
           $banner = $BannerModel->where($where)->order('orderby desc')->limit(0, 50)->select();
           $data['banner'] = [];
           foreach ($banner as $val) {
               $data['banner'][] = [
                   'photo' => IMG_URL . getImg($val->photo),
               ];
           }
           $data['hair']  = [];
           $HairModel = new HairModel();
           $hair = $HairModel->field('title,lat,lng,address')->find($this->appid);
           if($hair){
               $lat = (float) $this->request->param('lat');
               $lng = (float) $this->request->param('lng');
               $data['hair'] = $hair;
               $data['hair']['juli'] = empty($lat) ? '定位中...' : getDistance($lat, $lng, $hair->lat, $hair->lng);

           }
           //获取一个排序最高的红包
           $acitit_where['is_online'] = 1;
           $acitit_where['member_miniapp_id'] = $this->appid;
           $date = date("Y-m-d");
           $acitit_where['bg_date'] = ['<=',$date];
           $acitit_where['end_date'] = ['>=',$date];
           $ActivityModel = new ActivityModel();
           $data['activity'] = [];
           $activi = $ActivityModel->where($acitit_where)->order("orderby desc")->find();
           if(!empty($activi)){
               $data['activity'][] = [
                   'activity_id' => $activi->activity_id,
                   'title'   => $activi->title,
                   'money'  => sprintf("%.0f",$activi->money/100),
                   'need_money' => sprintf("%.0f",$activi->need_money/100),
                   'expire_day' => $activi->expire_day,
                   'use_day'  => $activi->use_day,
                   'is_newuser' => $activi->is_newuser,
                   'num'     => $activi->num,
                   'bg_date' => $activi->bg_date,
                   'end_date' => $activi->end_date,
               ];
           }
       }
     // 获取设计师列表；
        $DesignerModel= new DesignerModel();
        $d_where['member_miniapp_id'] = $this->appid;
        $list = $DesignerModel->where($d_where)->order("orderby desc")->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
               $tag = array_slice(explode(',',$val->tages),0,3);
                $data['list'][] = [
                    'designer_id' => $val->designer_id,
                    'name' => $val->name,
                    'zhiwu' => $val->zhiwu,
                    'tages' => $tag,
                    'price' => $val->price,
                    'works_num' => $val->works_num,
                    'enroll_num' => $val->enroll_num,
                    'hp_num' => $val->hp_num,
                    'photo' => IMG_URL .getImg($val->photo),
                ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 作品列表
     */
    public function wokrs(){
        $where['member_miniapp_id'] = $this->appid;
        $WorksModel = new WorksModel();
        $list = $WorksModel->where($where)->limit(0,50)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'works_id' => $val->works_id,
                'title' => $val->title,
                'num' => $val->num,
                'photo' => IMG_URL .  getImg($val->photo),
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 作品详情
     */
    public function worksDetail(){
        $works_id = (int) $this->request->param('works_id');
        $WorksModel = new WorksModel();
        if(!$works = $WorksModel->find($works_id)){
            $this->result('',400,'不存在作品','json');
        }
        if($works->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在作品','json');
        }
        $PhotoModel = new PhotoModel();
        $where['member_miniapp_id'] = $this->appid;
        $where['works_id'] = $works_id;
        $list = $PhotoModel->where($where)->limit(0,50)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    public function sjsal(){
        $id = (int) $this->request->param('id');
        $where['designer_id'] = $id;
        $where['member_miniapp_id'] = $this->appid;
        $WorksModel = new SjsalModel();
        $list = $WorksModel->where($where)->limit(0,50)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'works_id' => $val->works_id,
                'title' => $val->title,
                'num' => $val->num,
                'photo' => IMG_URL .  getImg($val->photo),
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    public function sjsalDetail(){
        $works_id = (int) $this->request->param('works_id');
        $WorksModel = new SjsalModel();
        if(!$works = $WorksModel->find($works_id)){
            $this->result('',400,'不存在作品','json');
        }
        if($works->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在作品','json');
        }
        $PhotoModel = new SjsalphotoModel();
        $where['member_miniapp_id'] = $this->appid;
        $where['works_id'] = $works_id;
        $list = $PhotoModel->where($where)->limit(0,50)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 查看设计师评论
     **/
    public function comment(){
        $designer_id = (int) $this->request->param('designer_id');
        $DesignerModel = new DesignerModel();
        if(!$designer = $DesignerModel->find($designer_id)){
            $this->result('',400,'不存在设计师','json');
        }
        if($designer->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在设计师','json');
        }
        $where['member_miniapp_id'] = $this->appid;
        $where['designer_id'] = $designer_id;
        $CommentModel = new CommentModel();
        $list = $CommentModel->where($where)->limit($this->limit_bg,$this->limit_num)->select();
        $userIds = [];
        foreach ($list as $val){
            $userIds[$val->user_id] = $val->user_id;
        }
        $UserModel = new UserModel();
        $user = $UserModel->itemsByIds($userIds);
        $data['designer_name'] = $designer->name;
        $data['designer_photo'] = IMG_URL . getImg($designer->photo);
        $data['designer_hp_num'] = $designer->hp_num;
        $data['designer_zhiwu'] = $designer->zhiwu;
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'comment_id' => $val->comment_id,
                'face'   => empty($user[$val->user_id]) ? '' :  getImg($user[$val->user_id]->face),
                'nick_name' => empty($user[$val->user_id]) ? '' :$user[$val->user_id]->nick_name,
                'content' => $val->content,
                'reply' => $val->reply,
                'add_time' => date('Y-m-d H:i:s',$val->add_time),
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 获取价格表；
     */
    public function getPrice(){
        $where['member_miniapp_id'] = $this->appid;
        $CategoryModel = new CategoryModel();
        $cate = $CategoryModel->where($where)->limit(0,20)->select();
        $PriceModel = new PriceModel();
        $price = $PriceModel->where($where)->limit(0,100)->select();
        $prices = [];
        foreach ($price as $val){
            $prices[$val->category_id][] = [
                'name' => $val->name,
                'vip_price' => $val->vip_price,
                'price' => $val->price,
            ];
        }
        $data['list'] = [];
        foreach ($cate as $val){
            $data['list'][] = [
                'category_id' => $val->category_id,
                'name' => $val->name,
                'describe' => $val->describe,
                'ico' => IMG_URL . getImg($val->ico),
                'price' => empty($prices[$val->category_id]) ? [] : $prices[$val->category_id],
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 获取分类
     */
    public function  getCate(){
        $designer_id = (int) $this->request->param('designer_id');
        $DesignerModel = new DesignerModel();
        if(!$designer = $DesignerModel->find($designer_id)){
            $this->result('',400,'不存在设计师','json');
        }
        if($designer->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在设计师','json');
        }
        $where['member_miniapp_id'] = $this->appid;
        $CategoryModel = new CategoryModel();
        $cate = $CategoryModel->where($where)->limit(0,20)->select();
        $data['designer_name'] = $designer->name;
        $data['designer_photo'] = IMG_URL . getImg($designer->photo);
        $data['list'] = [];
        foreach ($cate as $val){
            $data['list'][] = [
                'category_id' => $val->category_id,
                'name' => $val->name,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
}