<?php
namespace app\api\controller\tongcheng;
use app\api\controller\Common;
use app\common\model\tongcheng\AdvertModel;
use app\common\model\tongcheng\CategoryModel;
use app\common\model\tongcheng\CommentModel;
use app\common\model\tongcheng\InfoModel;
use app\common\model\tongcheng\InfophotoModel;
use app\common\model\tongcheng\NewsadvertModel;
use app\common\model\tongcheng\PriceModel;
use app\common\model\toutiao\NavModel;
use app\common\model\toutiao\ToutiaoModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\tongcheng\Newsadvert;

class Index extends Common{

    /*
     * 获取首页信息
     */
    public function getIndex(){
        if($this->limit_bg <= 1) {
            $where['member_miniapp_id'] = $this->appid;
            $CategoryModel = new CategoryModel();
            $list = $CategoryModel->where($where)->limit(0, 20)->select();
            $data['category'] = [];
            foreach ($list as $val) {
                $data['category'][] = [
                    'category_id' => $val->category_id,
                    'ico' => IMG_URL . getImg($val->ico),
                    'name' => $val->name,
                    'color' => $val->color,
                ];
            }
            $data['advert'] = [];
            $AdvertModel = new AdvertModel();
            $_where['member_miniapp_id'] = $this->appid;
            $now = date("Y-m-d H:i:s", time());
            $_where['bg_data'] = ['<', $now];
            $_where['end_data'] = ['>', $now];
            $_where['is_end'] = 0;
            $advert = $AdvertModel->where($_where)->order("orderby desc")->limit(0, 5)->select();
            foreach ($advert as $val) {
                $data['advert'][] = [
                    'photo' => IMG_URL . getImg($val->photo),
                    'info_id' => $val->info_id,

                ];
            }
        }
        $info_where['member_miniapp_id'] = $this->appid;
        $keyword = (string) $this->request->param('keyword');
        if(!empty($keyword)){
            $info_where['info'] = array('LIKE', '%' . $keyword . '%');
        }
        $lat = floatval($this->request->param('lat'));
        $lng = floatval($this->request->param('lng'));

        $type = (int) $this->request->param('type');
        $orderby = 'expire_time desc,orderby desc,add_time desc';
        if($type == 1){
            $orderby =  "ABS(lng-'{$lng}' + lat-'{$lat}') ASC,expire_time desc,orderby desc";
        }
        $data['info'] = [];
        $InfoModel = new InfoModel();

        $info = $InfoModel->where($info_where)->order($orderby)->limit($this->limit_bg,$this->limit_num)->select();
        $infoIds =  $categoryIds = $userIds = [];
        foreach ($info as $val){
            $infoIds[$val->info_id] = $val->info_id;
            $categoryIds[$val->category_id] = $val->category_id;
            $userIds[$val->user_id] = $val->user_id;
        }
        $InfophotoModel = new InfophotoModel();
        $infoIds = empty($infoIds) ? 0 : $infoIds;
        $photo =  $InfophotoModel->where(['info_id'=>['IN',$infoIds]])->select();
        $UserModel = new UserModel();
        $user = $UserModel->itemsByIds($userIds);
        $photos = [];
        foreach ($photo as $val){
            $photos[$val->info_id][] = IMG_URL . getImg($val->photo);
        }
        $CategoryModel = new CategoryModel();
        $category = $CategoryModel->itemsByIds($categoryIds);
        $time = time();
        foreach ($info as $val){
                $data['info'][] = [
                    'info_id' => $val->info_id,
                    'info'  => $val->info,
                    'photo' => empty($photos[$val->info_id]) ? [] : $photos[$val->info_id],
                    'category_name' => empty($category[$val->category_id]) ? '' :  $category[$val->category_id]->name,
                    'user_name' => empty($user[$val->user_id]) ? '系统发送' : $user[$val->user_id]->nick_name,
                    'user_face' => empty($user[$val->user_id]) ? '' :  getImg($user[$val->user_id]->face),
                    'tel'  => $val->tel,
                    'view_num'  => $val->view_num,
                    'comment_num'  => $val->comment_num,
                    'zan_num'  => $val->zan_num,
                    'add_time'  => date("m月d日 H:i",$val->add_time),
                    'address' => $val->address,
                    'is_top' => $val->expire_time < $time ? 0 : 1,
                    'juli'       => empty($lat) ? '（定位中）' : getDistance($lat, $lng, $val->lat, $val->lng),
                ];
        }
        $data['more']  = count($data['info']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 获取信息
     */
    public function getInfo(){
        $lat = floatval($this->request->param('lat'));
        $lng = floatval($this->request->param('lng'));
        $keyword = (string) $this->request->param('keyword');
        if(!empty($keyword)){
            $where['info'] = array('LIKE', '%' . $keyword . '%');
        }
        $category_id = (int) $this->request->param('category_id');
        if(empty($category_id)){
            $this->result('',400,'参数错误','json');
        }
        $where['member_miniapp_id'] = $this->appid;
        $where['category_id'] = $category_id;
        $data['info'] = [];
        $InfoModel = new InfoModel();
        $info = $InfoModel->where($where)->order("expire_time desc,orderby desc,add_time desc")->limit($this->limit_bg,$this->limit_num)->select();
        $infoIds =  $categoryIds = $userIds = [];
        foreach ($info as $val){
            $infoIds[$val->info_id] = $val->info_id;
            $categoryIds[$val->category_id] = $val->category_id;
            $userIds[$val->user_id] = $val->user_id;
        }
        $infoIds = empty($infoIds) ? 0 : $infoIds;
        $InfophotoModel = new InfophotoModel();

        $photo =  $InfophotoModel->where(['info_id'=>['IN',$infoIds]])->select();

        $UserModel = new UserModel();
        $user = $UserModel->itemsByIds($userIds);
        $photos = [];
        foreach ($photo as $val){
            $photos[$val->info_id][] = IMG_URL . getImg($val->photo);
        }

        $CategoryModel = new CategoryModel();
        $category = $CategoryModel->itemsByIds($categoryIds);
        $time = time();

        foreach ($info as $val){
            $data['info'][] = [
                'info_id' => $val->info_id,
                'info'  => $val->info,
                'photo' => empty($photos[$val->info_id]) ? [] : $photos[$val->info_id],
                'category_name' => empty($category[$val->category_id]) ? '' :  $category[$val->category_id]->name,
                'user_name' => empty($user[$val->user_id]) ? '系统发送' : $user[$val->user_id]->nick_name,
                'user_face' => empty($user[$val->user_id]) ? '' :  getImg($user[$val->user_id]->face),
                'tel'  => $val->tel,
                'view_num'  => $val->view_num,
                'comment_num'  => $val->comment_num,
                'zan_num'  => $val->zan_num,
                'add_time'  => date("m月d日 H:i",$val->add_time),
                'address' => $val->address,
                'is_top' => $val->expire_time < $time ? 0 : 1,
                'juli'       => empty($lat) ? '（定位中）' : getDistance($lat, $lng, $val->lat, $val->lng),
            ];
        }
        $data['more']  = count($data['info']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 获取信息
     */
    public function infoDetail(){
       $info_id = (int) $this->request->param('info_id');
       $InfoModel = new InfoModel();
       if(!$info = $InfoModel->find($info_id)){
            $this->result('',400,'不存在信息','json');
       }
       if($info->member_miniapp_id != $this->appid){
           $this->result('',400,'不存在信息','json');
       }
       $InfoModel->where(['info_id'=>$info_id])->setInc('view_num');
       $UserModel = new UserModel();
       $user = $UserModel->find($info->user_id);
       $InfophotoModel = new InfophotoModel();
       $photos = $InfophotoModel->where(['info_id'=>$info_id])->select();
       $CommentModel = new CommentModel();
       $comment = $CommentModel->where(['info_id'=>$info_id])->select();
       $userIds = [];
       foreach ($comment as $val){
           $userIds[$val->user_id] = $val->user_id;
       }
       $users = $UserModel->itemsByIds($userIds);
       $photo = [];
       foreach ($photos as $val){
            $photo[] = IMG_URL . getImg($val->photo);
       }
       $comments = [];
        foreach ($comment as $val){
            $comments[] = [
                'comment_id' => $val->comment_id,
                'user_face' => empty($users[$val->user_id]) ? ''  : getImg($users[$val->user_id]->face),
                'user_name' => empty($users[$val->user_id]) ? '' : $users[$val->user_id]->nick_name,
                'content' => $val->content,
                'reply' => $val->reply,
                'reply_time' => date("m月d日 H:i",$val->reply_time),
                'add_time' => date("m月d日 H:i",$val->add_time),
            ];
        }
        $lat = floatval($this->request->param('lat'));
        $lng = floatval($this->request->param('lng'));
        $data = [
            'info_id' => $info->info_id,
            'info' => $info->info,
            'view_num' => $info->view_num,
            'comment_num' => $info->comment_num,
            'zan_num' => $info->zan_num,
            'tel' => $info->tel,
            'add_time' => date("m月d日 H:i",$info->add_time),
            'address' => $info->address,
            'user_name' => empty($user) ? '系统发送' : $user->nick_name,
            'user_face' => empty($user) ? '' :   getImg($user->face),
            'lng' => $info->lng,
            'lat' => $info->lat,
            'juli'    => empty($lat) ? '（定位中）' : getDistance($lat, $lng, $info->lat, $info->lng),
            'comment' => $comments,
            'photo' => $photo,
        ];
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     *
     */
    public function getCategory(){
        $where['member_miniapp_id'] = $this->appid;
        $CategoryModel = new CategoryModel();
        $list = $CategoryModel->where($where)->limit(0, 20)->select();
        $data['category'] = [];
        foreach ($list as $val) {
            $data['category'][] = [
                'category_id' => $val->category_id,
                'ico' => IMG_URL . getImg($val->ico),
                'name' => $val->name,
                'color' => $val->color,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 获取新闻列表
     */
    /*
     * 获取价格表
     */
    public function getPrice(){
        $PriceModel = new PriceModel();
        $where['member_miniapp_id'] = $this->appid;
        $price = $PriceModel->where($where)->limit(0,20)->select();
        $data = [];
        foreach ($price as $val){
              $data[] = [
                  'price_id' => $val->price_id,
                  'price'  => round($val->price/100,2),
                  'day_num' => $val->day_num,
              ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    /*
     *zan
     *
     */
    public function zan(){
        $info_id = (int) $this->request->param('info_id');
        $InfoModel = new InfoModel();
        if(!$detail = $InfoModel->find($info_id)){
            $this->result('',400,'操作失败','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'操作失败','json');
        }
        $InfoModel->where(['info_id'=>$info_id])->setInc('zan_num');
        $this->result('',200,'操作成功','json');
    }
}