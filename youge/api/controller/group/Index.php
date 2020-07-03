<?php
 namespace app\api\controller\group;
 use app\api\controller\Common;
 use app\common\model\group\CategoryModel;
 use app\common\model\group\GoodsModel;

 class Index extends Common{


     /*
      * 获取首页数据 以及 头部分类
      * @param category_id 分类id 默认 为 排序最高的分类id；
      * @param $keyword  string|int 产品id 或者 标题关键字
      *
      */
     public function getIndex(){
         //获取分类
         $CategoryModel = new CategoryModel();
         $category = $CategoryModel->where(['member_miniapp_id'=>$this->appid])->order('orderby desc')->select();
         if(empty($category)){
             $this->result('',400,'请等待管理员上线','json');
         }
         $data['category'] = [];
         foreach ($category as $val){
                $data['category'][] = [
                    'category_id' => $val->category_id,
                    'category_name' => $val->category_name,
                ];
         }
         $actegory_id = (int) $this->request->param('category_id');
         if(empty($actegory_id)){
             $actegory_id = $data['category'][0]['category_id'];
         }
         $keyword = $this->request->param('keyword');
         if((int) $keyword != 0){
             $where['goods_id'] = $keyword;
         }else{
             if(!empty($keyword)){
                   $where['goods_name'] = ['LIKE','%' . $keyword . '%'];
             }
         }
         //存储当前的分类id
         $data['this_category_id'] = $actegory_id;
         $where['category_id'] = $actegory_id;
         $where['member_miniapp_id'] = $this->appid;
         $where['is_delete'] = 0;
         $where['is_online'] = 1;
         $where['bg_time'] = ['<',$this->request->time()];
         $GoodsModel = new GoodsModel();
         $list  = $GoodsModel->where($where)->order('orderby desc')->limit($this->limit_bg,$this->limit_num)->select();
         $data['list'] = [];
         foreach ($list as $val){
                $data['list'][] = [
                    'goods_id' => $val->goods_id,
                    'photo' => IMG_URL . getImg($val->photo),
                    'goods_name' => $val->goods_name,
                    'price' =>  round($val->price/100,2),
                    'group_price' => round($val->group_price/100,2),
                    'alone_price' => round( $val->alone_price/100,2),
                    'is_mail' => $val->is_mail,
                    'group_num' => $val->group_num,
                    'people_num' => $val->people_num,
                ];
         }
         $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
         $this->result($data,200,'数据初始化成功','json');
     }
 }