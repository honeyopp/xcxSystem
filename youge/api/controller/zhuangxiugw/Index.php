<?php

namespace app\api\controller\zhuangxiugw;

use app\api\controller\Common;
use app\common\model\fitment\ActiviModel;
use app\common\model\fitment\BannerModel;
use app\common\model\fitment\DesignerModel;
use app\common\model\fitment\DetailModel;
use app\common\model\fitment\DetailphotoModel;
use app\common\model\fitment\ExampleModel;
use app\common\model\fitment\ExamplephotoModel;
use app\common\model\fitment\GroupModel;
use app\common\model\fitment\PhotoModel;
use app\common\model\fitment\SjsalModel;
use app\common\model\fitment\SjsalphotoModel;
use app\common\model\fitment\WorkModel;

class Index extends Common
{
    /*
     * 获得首页数据
     */
    public function getIndex()
    {
        //获取banner；
        $where['member_miniapp_id'] = $this->appid;
        $BannerModel = new BannerModel();
        $banner = $BannerModel->where($where)->order('orderby desc')->limit(0, 50)->select();
        $data['banner'] = [];
        foreach ($banner as $val) {
            $data['banner'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }

        $ExampleModel = new ExampleModel();
        $example = $ExampleModel->where($where)->order('orderby desc')->limit(0, 6)->select();
        $data['example'] = [];
        foreach ($example as $val) {
            $data['example'][] = [
                'example_id' => $val->example_id,
                'title' => $val->title,
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }
    /*
     *  获取经典案例详情；
     *
     */
    public function exampleDdetail()
    {
        $example_id = (int)$this->request->param('example_id');
        $ExampleModel = new ExampleModel();
        if (!$detail = $ExampleModel->find($example_id)) {
            $this->result('', 400, '不存在经典案例', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '不存在经典案例', 'json');
        }
        $ExamplephotoModel = new ExamplephotoModel();
        $where['example_id'] = $example_id;
        $list = $ExamplephotoModel->where($where)->limit(0, 50)->select();
        $data['list'] = [];
        foreach ($list as $val) {
            $data['list'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }
    /*
     * 小区团装；
     */
    public function getGroup()
    {
        $where['member_miniapp_id'] = $this->appid;
        $keyword = (string)$this->request->param('keyword');
        if (!empty($keyword)) {
            $where['title'] = ["LIKE", "%" . $keyword . "%"];
        }
        $GroupModel = new GroupModel();
        $now = date("Y-m-d",time());
        $where['bg_date'] = ['<', $now];
        $where['member_miniapp_id'] = $this->appid;
        $list = $GroupModel->where($where)->limit($this->limit_bg, $this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val) {
            $is_end = 0;
            if ($val->end_date <  $now|| $val->is_end == 1) {
                $is_end = 1;
            }
            $data['list'][] = [
                'group_id' => $val->group_id,
                'title' => $val->title,
                'is_end' => $is_end,
                'price' => $val->price,
                'num' => $val->num,
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }
    /*
     * 团装详情
     */
    public function groupDetail(){
        $group_id = (int) $this->request->param('group_id');
        $GroupModel = new GroupModel();
        if(!$detail = $GroupModel->find($group_id)){
            $this->result('',400,'不存在团装','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在团装','json');
        }
        $bg_time = strtotime($detail->bg_date);
        $end_time = strtotime($detail->end_date);
        $surplus_time = $end_time - $bg_time < 0 ? 0 : $end_time - $bg_time;

        $is_end = 0;
        if ($detail->end_date <  date("Y-m-d",time())|| $detail->is_end == 1) {
            $is_end = 1;
        }
        $data = [
            'group_id' => $detail->group_id,
            'title'    => $detail->title,
            'price'    => $detail->price,
            'num'    => $detail->num,
            'bg_date'    => $detail->bg_date,
            'end_date'    => $detail->end_date,
            'introduce'    => $detail->introduce,
            'rule'    => $detail->rule,
            'warning'    => $detail->warning,
            'is_end'    => $is_end,
            'photo' => IMG_URL . getImg($detail->photo),
            'surplus_time' => $surplus_time,

        ];
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 看工地
     */
    public function getWork(){
        $WorkModel = new WorkModel();
        $where['member_miniapp_id'] = $this->appid;
        $keyword = (string) $this->request->param('keyword');
        if(!empty($keyword)){
            $where['title'] = ['LIKE',"%".$keyword . "%"];
        }
        $list = $WorkModel->where($where)->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
                $data['list'][] = [
                    'work_id' => $val->work_id,
                    'title' => $val->title,
                    'area' => $val->area,
                    'village' => $val->village,
                    'photo' => IMG_URL . getImg($val->photo),
                ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }


    /*
     * 工地详情
     */
    public function workDetail(){
        $work_id = (int) $this->request->param('work_id');
        $WorkModel = new WorkModel();
        if(!$work = $WorkModel->find($work_id)){
            $this->result('',400,'不存在工地','json');
        }
        if($work->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在工地','json');
        }
        $designerIds = explode(',',$work->designer_ids);
        $DesignerModel = new DesignerModel();
        $designers =  $DesignerModel->itemsByIds($designerIds);
        $designer = [];
        foreach ($designers as $val){
            $designer[] = [
                'designer_id' => $val->designer_id,
                'name' => $val->name,
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $DetailModel = new DetailModel();
        $where['member_miniapp_id'] = $this->appid;
        $where['work_id'] = $work_id;
        $detail = $DetailModel->where($where)->order('orderby desc')->limit(0,20)->select();
        $detailIds = [];
        foreach ($detail as $val){
            $detailIds[$val->detail_id] = $val->detail_id;
        }
        $detailIds = empty($detailIds) ? 0 : $detailIds;
        $photo_where['detail_id'] = ['IN',$detailIds];
        $DetailphotoModel = new DetailphotoModel();
        $photos = $DetailphotoModel->where($photo_where)->select();
        $photo = [];
        foreach ($photos as $val){
            $photo[$val->detail_id] [] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $details = [];
        foreach ($detail as $val){
            $details []  = [
                'title' => $val->title,
                'introduce' => $val->introduce,
                'num' => $val->num,
                'boss' => $val->boss,
                'progress' => $val->progress,
                'photos' => empty($photo[$val->detail_id]) ? [] : $photo[$val->detail_id],
            ];
        }

        $data = [
            'work_id' => $work->work_id,
            'title' => $work->title,
            'area' => $work->area,
            'village' => $work->village,
            'company' => $work->company,
            'photo' => IMG_URL . getImg($work->photo),
            'designer' => $designer,
            'detail' => $details,
        ];

        $this->result($data,200,'数据初始化成功','json');
    }

    /*
     * 设计师
     */

    public function getDesigner(){
        $where['member_miniapp_id'] = $this->appid;
        $keyword = (string) $this->request->param('keyword');
        if(!empty($keyword)){
            $where['name'] = ['LIKE',"%".$keyword . "%"];
        }
        $DesignerModel = new DesignerModel();
        $list = $DesignerModel->where($where)->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'designer_id' => $val->designer_id,
                'name' => $val->name,
                'level' => $val->level,
                'experience' => $val->experience,
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     *设计师详情
     *
     */
     public function designerDetail(){
         $designer_id = (int) $this->request->param('designer_id');
         $DesignerModel = new DesignerModel();
         if(!$detail = $DesignerModel->find($designer_id)){
             $this->result('',400,'不存在设计师','json');
         }
         if($detail->member_miniapp_id != $this->appid){
             $this->result('',400,'不存在设计师','json');
         }
         $SjsalModel = new SjsalModel();
         $where['member_miniapp_id'] = $this->appid;
         $where['designer_id'] = $designer_id;
         $examples =  $SjsalModel->where($where)->order('orderby desc')->limit(0,20)->select();
         $example = [];
         foreach ($examples as $val){
                $example[] = [
                    'example_id' => $val->example_id,
                    'title' => $val->title,
                    'photo' => IMG_URL . getImg($val->photo),
                ];
         }
         $data = [
             'designer_id' => $detail->designer_id,
             'name' => $detail->name,
             'photo' => IMG_URL . getImg($detail->photo),
             'experience' => $detail->experience,
             'introduce' => $detail->introduce,
             'level' => $detail->level,
             'example' => $example,
         ];
         $this->result($data,200,'数据初始化成功','json');

     }
    /*
     * 设计师案例详情
     */
    public function designerPhoto(){
        $example_id = (int) $this->request->param('example_id');
        $SjsalModel = new SjsalModel();
        if(!$detail = $SjsalModel->find($example_id)){
             $this->result('',400,'数据不存在','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'数据不存在','json');
        }
        $where['member_miniapp_id'] = $this->appid;
        $where['example_id'] = $example_id;
        $SjsalphotoModel = new SjsalphotoModel();
        $photo = $SjsalphotoModel->where($where)->limit(0,50)->select();
        $data['list'] = [];
        foreach ($photo as $val){
             $data['list'][] = [
                 'photo' => IMG_URL . getImg($val->photo),
             ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    /*
     * 优惠活动
     *
     */

    public function getActivity(){
        $where['member_miniapp_id'] = $this->appid;
        $ActiviModel = new ActiviModel();
        $now = date("Y-m-d",time());
        $where['bg_date'] = ['<', $now];
        $list = $ActiviModel->where($where)->order('end_date desc')->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $is_end = 0;
            if ($val->end_date <  $now || $val->is_end == 1) {
                $is_end = 1;
            }
            $data['list'][] = [
                'activity_id' => $val->activity_id,
                'title'  => $val->title,
                'photo' => IMG_URL . getImg($val->photo),
                'is_end' => $is_end,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
                'address' => $val->address,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }


    /*
     *
     * 活动详情
     */

    public function ActivityDetail(){
          $activity_id = (int) $this->request->param('activity_id');
          $ActiviModel = new ActiviModel();
          if(!$detail = $ActiviModel->find($activity_id)){
              $this->result('',400,'不存在活动','json');
          }
          if($detail->member_miniapp_id != $this->appid){
              $this->result('',400,'不存在活动','json');
          }
        $bg_time = strtotime($detail->bg_date);
        $end_time = strtotime($detail->end_date);
        $surplus_time = $end_time - $bg_time < 0 ? 0 : $end_time - $bg_time;

        $is_end = 0;
        if ($detail->end_date <  date("Y-m-d",time())|| $detail->is_end == 1) {
            $is_end = 1;
        }
        $data = [
            'activity_id' => $detail->activity_id,
            'title'    => $detail->title,
            'bg_date'    => $detail->bg_date,
            'end_date'    => $detail->end_date,
            'introduce'    => $detail->introduce,
            'rule'    => $detail->rule,
            'warning'    => $detail->warning,
            'is_end'    => $is_end,
            'surplus_time' => $surplus_time,
            'photo' => IMG_URL . getImg($detail->photo),

        ];
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 获取图库
     */
    public function getPhotos(){
        $where['member_miniapp_id'] = $this->appid;
        $PhotoModel = new PhotoModel();
        $list = $PhotoModel->where($where)->order("orderby desc")->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

}