<?php
namespace app\api\controller\shop;
use app\api\controller\Common;
use app\common\model\setting\ActivityModel;
use app\common\model\shop\BannerModel;
use app\common\model\shop\CategoryModel;
use app\common\model\shop\CommentModel;
use app\common\model\shop\CommentphotoModel;
use app\common\model\shop\ContentModel;
use app\common\model\shop\GoodsModel;
use app\common\model\shop\TypeModel;
use app\common\model\user\UserModel;

class Data extends Common{

    /**
     * 获取首页数据 //banner 以及 二级分类
     */
    public function getData(){
       //获取banner
        $data['banner'] = [];
        $BannerModel = new BannerModel();
        $where['member_miniapp_id'] = $this->appid;
        $list = $BannerModel->where($where)->order('orderby desc')->limit(0,50)->select();
        foreach ($list as $val){
            $data['banner'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
       // 获取二级分类
        $CategoryModel = new CategoryModel();
        $category = $CategoryModel->where(['member_miniapp_id' => $this->appid])->order("orderby desc")->select();
        $tree = [];
        foreach ($category as $val) {
            $tree[$val->category_id] = [
                'pid' => $val->pid,
                'category_id' => $val->category_id,
                'category_name' => $val->type_name,
                'check' => false,
                'is_show' => 0,
            ];
            $tree[$val->category_id]['children'] = [];
        }
        foreach ($tree as $k => $item) {
            if ($item['pid'] != 0) {
                $tree[$item['pid']]['children'][] = &$tree[$k];
                unset($tree[$k]);
            }
        }
        //去掉key；
        $data['category'] = [];
        foreach ($tree as $key => $val) {
            $data['category'][] = $val;
        }
        $this->result($data,200,'数据初始化成功','json');
    }


    public function getIndex(){
        $keyword = (string) $this->request->param('keyword');
        if(!empty($keyword)){
            $where['goods_name'] = ["LIKE",'%' .$keyword .'%'];
        }
        $category_id = (int) $this->request->param('category_id');
        if(!empty($category_id)){
            $where['category_id'] = $category_id;
        }
        $orderby  = (int) $this->request->param('orderby');
        // 0 默认排序  1 销量排序  2价格 高到低   3 价格 低到高
        $order = '';
        switch ($orderby){
            case 0:
                $order = "orderby desc";
                break;
            case 1:
                $order = "sales_volume desc";
                break;
            case 2:
                $order = "shop_price desc";
                break;
            case 3:
                $order = "shop_price asc";
                break;
        }
        $where['member_miniapp_id'] = $this->appid;
        $where['is_online'] = 1;
        $where['is_delete'] = 0;
        $GoodsModel = new GoodsModel();
        $list  = $GoodsModel->where($where)->order($order)->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'goods_id' => $val->goods_id,
                'photo' => IMG_URL . getImg($val->photo),
                'goods_name' => $val->goods_name,
                'shop_price' => round($val->shop_price/100,2),
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }

    /**
     * 商品详情
     */
    public function goodsDetail(){
         $goods_id = (int) $this->request->param('goods_id');
         $GoodsModel = new GoodsModel();
         if(!$goods = $GoodsModel->find($goods_id)){
             $this->result('',400,'不存在商品','json');
         }
         if($goods->member_miniapp_id != $this->appid || $goods->is_delete == 1){
             $this->result('',400,'不存在商品','json');
         }
         if($goods->is_online == 0){
             $this->result('',400,'商品下架了','json');
         }
        //活动列表
        $where['is_online'] = 1;
        $where['member_miniapp_id'] = $this->appid;
        $date = date("Y-m-d");
        $where['bg_date'] = ['<=',$date];
        $where['end_date'] = ['>=',$date];
        $ActivityModel = new ActivityModel();
        $list = $ActivityModel->where($where)->order("orderby desc")->limit(0,5)->select();
        $data['activity'] = [];
        foreach($list as $val){
            $data['activity'][] = [
                'activity_id' => $val->activity_id,
                'title'   => $val->title,
                'money'  => round($val->money/100,2),
                'need_money' => round($val->need_money/100,2),
                'expire_day' => $val->expire_day,
                'use_day'  => $val->use_day,
                'is_newuser' => $val->is_newuser,
                'num'     => $val->num,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
            ];
        }
        if($this->limit_bg  <= 1) {
            $contents = ContentModel::where(['member_miniapp_id' => $this->appid, 'goods_id' => $goods_id])->order(['orderby' => 'asc'])->select();
            $contentArr = [];
            foreach ($contents as $val) {
                $contentArr[] = [
                    'content' => $val->content,
                    'photo' => empty($val->photo) ? '' : IMG_URL . getImg($val->photo)
                ];
            }
            $serviceIds = explode(',', $goods->service_ids);
            $service = [];
            foreach ($serviceIds as $val) {
                if (!empty(config('dataattr.group')[$val])) {
                    $service[] = config('dataattr.group')[$val];
                }
            }
            $TypeModel= new TypeModel();
            $sku_where['goods_id'] = $goods_id;
            $sku_where['is_delete'] = 0;
            $_sku = $TypeModel->where($sku_where)->limit(0,20)->select();
             $sku = [];
            foreach ($_sku as $val){
                    $sku[] = [
                        'type_id' => $val->type_id,
                        'type_name' => $val->type_name,
                        'price' =>  round($val->price/100,2),
                        'surplus_num' => $val->surplus_num,
                    ];
            }
            $data['goods'] = [
                'goods_id' => $goods->goods_id,
                'goods_name' => $goods->goods_name,
                'photo' => IMG_URL . getImg($goods->photo),
                'price' => round($goods->price / 100, 2),
                'shop_price' => round($goods->shop_price / 100, 2),
                'mail_price' => round($goods->mail_price / 100, 2),
                'is_mail' => $goods->is_mail,
                'give_integral' => $goods->give_integral,
                'user_integral' => $goods->user_integral,
                'like_num' => $goods->like_num,
                'sales_volume' => $goods->sales_volume,
                'surplus_num' => $goods->surplus_num,
                'brief' => $goods->brief,
                'spec' => $goods->spec,
                'ctn' => $goods->ctn,
                'service' => $service,
                'sku' => $sku,
                'contents' => $contentArr,
            ];
        }
        $CommentModel = new CommentModel();
        $_where['member_miniapp_id'] = $this->appid;
        $_where['goods_id'] = $goods_id;
        $list = $CommentModel->where($_where)->order("comment_id desc")->limit($this->limit_bg,$this->limit_num)->select();
        $photoIds = $userIds = $roomIds = $hotelIds = [];
        foreach ($list as $val){
            $photoIds[$val->comment_id] = $val->comment_id;
            $userIds[$val->user_id] = $val->user_id;
        }
        $CommentphotoModel = new CommentphotoModel();
        $UserModel = new UserModel();
        $users = $UserModel->itemsByIds($userIds);
        $photoIds = empty($photoIds) ? 0 : $photoIds;
        $photo_where['comment_id'] = ["IN",$photoIds];
        $photo = $CommentphotoModel->where($photo_where)->select();
        $photos = [];
        foreach ($photo as $val){
            $photos[$val->comment_id][] = IMG_URL . getImg($val->photo);
        }
        $data['comment'] = [];
        foreach ($list as $val){
            $data['comment'] [] = [
                'comment_id' => $val->comment_id,
                'user_id'    => $val->user_id,
                'user_nick_name' => empty($users[$val->user_id])  ? '' : $users[$val->user_id]->nick_name,
                'user_face'  => empty($users[$val->user_id]) ? '' : $users[$val->user_id]->face,
                'score'     => round($val->score/10,1),
                'content'    => $val->content,
                'content_time' => date("Y-m-d",$val->add_time),
                'reply'      => $val->reply,
                'reply_time'  => empty($val->reply_time) ? '' : date("Y-m-d",$val->reply_time),
                'photos'    => empty($photos[$val->comment_id]) ? [] : $photos[$val->comment_id],
            ];
        }
       $data['more']  = count($data['comment']) == $this->limit_num ? 1: 0;
      $this->result($data,200,'数据初始化成功','json');
    }

}