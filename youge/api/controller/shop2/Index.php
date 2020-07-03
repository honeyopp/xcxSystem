<?php
namespace app\api\controller\shop2;

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
class Index extends Common{


    /*
     * 获取首页
     */
    public function  getIndex(){
        $GoodsModel = new GoodsModel();
        if($this->limit_bg <= 1) {
            //获取banner
            $data['banner'] = [];
            $BannerModel = new BannerModel();
            $where['member_miniapp_id'] = $this->appid;
            $list = $BannerModel->where($where)->order('orderby desc')->limit(0, 50)->select();
            foreach ($list as $val) {
                $data['banner'][] = [
                    'photo' => IMG_URL . getImg($val->photo),
                ];
            }
            //获取分类；
            $CategoryModel = new CategoryModel();
            $categorys = $CategoryModel->where(['member_miniapp_id' => $this->appid, 'pid' => ['<>',0], 'is_hot' => 1])
                ->order('orderby desc')
                ->select();
            $data['category'] = [];
            foreach ($categorys as $val) {
                $data['category'][] = [
                    'category_id' => $val->category_id,
                    'name' => $val->type_name,
                    'color' => $val->color,
                    'ico' => IMG_URL . getImg($val->photo),
                ];
            }
            //获取推荐商品；
            $hotgoods = $GoodsModel->where(['member_miniapp_id' => $this->appid, 'is_hot' => 1, 'is_online' => 1])
                ->order("orderby desc")
                ->limit(0, 9)
                ->select();
            $data['hotlist'] = [];
            foreach ($hotgoods as $val) {
                $data['hotlist'][] = [
                    'goods_id' => $val->goods_id,
                    'photo' => IMG_URL . getImg($val->photo),
                    'goods_name' => $val->goods_name,
                    'shop_price' => round($val->shop_price / 100, 2),
                ];

            }
        }
        $goods = $GoodsModel->where(['member_miniapp_id'=>$this->appid,'is_online'=>1])
            ->order("orderby desc")
            ->limit($this->limit_bg,$this->limit_num)
            ->select();
        $data['list'] = [];
        foreach ($goods as $val){
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



    public function getData(){
        // 获取二级分类
        $CategoryModel = new CategoryModel();
        $category = $CategoryModel->where(['member_miniapp_id' => $this->appid])->order("orderby desc")->select();
        $tree = [];
        foreach ($category as $val) {
            $tree[$val->category_id] = [
                'pid' => $val->pid,
                'category_id' => $val->category_id,
                'name' => $val->type_name,
                'ico'    => IMG_URL . getImg($val->photo),
                'color' => $val->color,
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
}