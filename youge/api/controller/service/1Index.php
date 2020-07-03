<?php

namespace app\api\controller\service;

use app\api\controller\Common;
use app\common\model\publicuse\BannerModel;
use app\common\model\publicuse\CommentModel;
use app\common\model\service\CategoryModel;
use app\common\model\service\NannyModel;
use app\common\model\service\NannyphotoModel;
use app\common\model\service\RepairModel;
use app\common\model\service\RepairskuModel;
use app\common\model\service\SkillModel;
use app\common\model\setting\ActivityModel;

class Index extends Common
{


    /*
     * 获取首页
     */

    public function getIndex()
    {
        //获取banner
        $banner = BannerModel::where(['member_miniapp_id' => $this->appid])->order("orderby desc")->limit(0, 20)->select();
        $data['banner'] = [];
        foreach ($banner as $val) {
            $data['banner'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        //获取优惠券；
        $acitit_where['is_online'] = 1;
        $acitit_where['member_miniapp_id'] = $this->appid;
        $date = date("Y-m-d");
        $acitit_where['bg_date'] = ['<=', $date];
        $acitit_where['end_date'] = ['>=', $date];
        $ActivityModel = new ActivityModel();
        $data['activity'] = [];
        $activi = $ActivityModel->where($acitit_where)->order("orderby desc")->select();
        $data['activity'] = [];
        foreach ($activi as $val) {
            $data['activity'][] = [
                'activity_id' => $val->activity_id,
                'title' => $val->title,
                'money' => round( $val->money / 100,2),
                'need_money' => round( $val->need_money / 100,2),
                'expire_day' => $val->expire_day,
                'use_day' => $val->use_day,
                'is_newuser' => $val->is_newuser,
                'num' => $val->num,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
            ];
        }
        //获取分类
        $CategoryModel = new CategoryModel();
        $categorys = $CategoryModel->where(['member_miniapp_id' => $this->appid])->select();
        $data['categorys'] = [];

        foreach ($categorys as $val) {
            $data['categorys'][] = [
                'category_id' => $val->category_id,
                'ico' => IMG_URL . getImg($val->photo),
                'name' => $val->name,
                'color' => $val->color,
                'type' => $val->type,
            ];
        }
        $data['lentth'] = ceil(count($data['categorys']) /8);
        //获取推荐服务
        $where['member_miniapp_id'] = $this->appid;
        $where['is_hot'] = 1;
        $RepairModel = new RepairModel();
        $list = $RepairModel->where($where)->order('orderby desc')->limit(0, 63)->select();
        $repair = $categoryIds = [];
        foreach ($list as $val) {
            $repair[$val->category_id][] = [
                'repair_id' => $val->repair_id,
                'photo' => IMG_URL . getImg($val->photo),
                'title' => $val->title,
                'title2' => $val->title2,
            ];
            $categoryIds[$val->category_id] = $val->category_id;
        }

        $category = $CategoryModel->itemsByIds($categoryIds);

        $data['list'] = [];
        foreach ($category as $val) {
            $data['list'][] = [
                'category_name' => $val->name,
                'list' => empty($repair[$val->category_id]) ? [] : $repair[$val->category_id],
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    /*
     * 装修服务列表
     */

    public function getRepair(){
        $category_id = (int) $this->request->param('category_id');
        $CategoryModel = new CategoryModel();
        if(!$category = $CategoryModel->find($category_id)){
            $this->result('',400,'参数错误','json');
        }
        if($category->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        $where['member_miniapp_id'] = $this->appid;
        $where['category_id'] = $category_id;
        $RepairModel = new RepairModel();
        $list = $RepairModel->where($where)->order('is_hot desc,orderby desc')->limit($this->limit_bg, $this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val) {
            $data['list'][] = [
                'repair_id' => $val->repair_id,
                'photo' => IMG_URL . getImg($val->photo),
                'title' => $val->title,
                'title2' => $val->title2,
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 装修服务详情
     */
    public function repairDetail(){
        $repair_id = (int) $this->request->param('repair_id');
        $RepairModel = new RepairModel();
        if(!$detail = $RepairModel->find($repair_id)){
            $this->result('',400,'参数错误','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        $where['repair_id'] = $repair_id;
        $RepairskuModel = new RepairskuModel();
        $skus = $RepairskuModel->where($where)->select();
        $sku = [];
        foreach ($skus as $val){
            $sku[] = [
                'name' => $val->name,
                'price' => $val->price,
                'hd_price' => $val->hd_price,
            ];
        }
        $data = [
            'repair_id' => $detail->repair_id,
            'photo' => IMG_URL . getImg($detail->photo),
            'title' => $detail->title,
            'title2' => $detail->title2,
            'introduce' => $detail->introduce,
            'sku'  => $sku,
            'price' => round($detail->price/100,2),

        ];

        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     *  阿姨列表
     */

    public function getOnnan(){
        $category_id = (int) $this->request->param('category_id');
        if(!empty($category_id)){
            $where['category_id'] = $category_id;
        }
        $type = (int) $this->request->param('type');
        if(!empty($type)){
            $where['type'] = $type;
        }
        //排序 1推荐排序 2预约数排序 3价格升序 4价格降序
        $orderby = (int) $this->request->param('orderby');
        $order = 'orderby desc';
        switch ($orderby){
            case 1:
                  $order = "orderby desc";
                  break;
            case 2:
                  $order = "yvyue_num desc";
                  break;
            case 3:
                $order = "prie desc";
                break;
            case 4:
                $order = "prie asc";
                break;
        }
        $where['member_miniapp_id'] = $this->appid;
        $NannyModel = new NannyModel();
        $list = $NannyModel->where($where)->order($order)->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
                $data['list'][] = [
                    'nanny_id' => $val->nanny_id,
                    'photo'    => IMG_URL . getImg($val->photo),
                    'price'    => $val->prie,
                    'name'     => $val->name,
                    'day'      => $val->day,
                    'age'      => $val->age,
                    'place'      => $val->place,
                    'work'      => $val->work,
                    'views_num'      => $val->views_num,
                    'yvyue_num'      => $val->yvyue_num,
                    'comment_num'      => $val->comment_num,
                ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 阿姨详情
     */
    public function onnanDetail(){
            $nanny_id = (int)$this->request->param('nanny_id');
        $NannyModel = new NannyModel();
            if (!$detail = $NannyModel->find($nanny_id)) {
                $this->result('', 400, '参数错误', 'json');
            }
            if ($detail->member_miniapp_id != $this->appid) {
                $this->result('', 400, '参数错误', 'json');
            }
            $NannyphotoModel = new NannyphotoModel();
            $NannyModel->where(['nanny_id'=>$nanny_id])->setInc('views_num');
            $photo = $NannyphotoModel->where(['nanny_id' => $nanny_id])->select();
            $photos = [];
            foreach ($photo as $val) {
                $photos[] = IMG_URL . getImg($val->photo);
            }
            $SkillModel = new SkillModel();
            $skillIds = explode(',',$detail->skill);
            $skill = $SkillModel->whereIN('skill_id',$skillIds)->select();
            $pIds = [];
            foreach ($skill as $val){
                $pIds[$val->pid] = $val->pid;
            }
            $pskill = $SkillModel->whereIN('skill_id',$pIds)->select();
          $skills =   array_merge($pskill,$skill);
         $tree = [];
        foreach ($skills as $val) {
            $tree[$val->skill_id] = [
                'pid' => $val->pid,
                'skill_id' => $val->skill_id,
                'name' => $val->name,
            ];
            $tree[$val->skill_id]['children'] = [];
        }
        foreach ($tree as $k => $item) {
            if ($item['pid'] != 0) {
                $tree[$item['pid']]['children'][] = &$tree[$k];
                unset($tree[$k]);
            }
        }
        $data = [
            'nanny_id' => $nanny_id,
            'name' => $detail->name,
            'photo' => IMG_URL . getImg($detail->photo),
            'prie' => $detail->prie,
            'day' => $detail->day,
            'yv_price' => round($detail->yv_price/100,2),
            'age' => $detail->age,
            'place' => $detail->place,
            'work' => $detail->work,
            'home' => $detail->home,
            'type' => $detail->type,
            'education' => $detail->education,
            'nation' => $detail->nation,
            'certificates' => $detail->certificates,
            'evaluate' => $detail->evaluate,
            'views_num' => $detail->views_num,
            'yvyue_num' => $detail->yvyue_num,
            'comment_num' => $detail->comment_num,
            'photos' => $photos,
            'skills' => $tree,
        ];
        $this->result($data,200,'数据初始化成功','json');
    }


}