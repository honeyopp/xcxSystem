<?php

namespace app\api\controller\school;

use app\api\controller\Common;
use app\common\model\school\ActivitycontentModel;
use app\common\model\school\ActivityentryModel;
use app\common\model\school\ActivityModel;
use app\common\model\school\BannerModel;
use app\common\model\school\ClassoneModel;
use app\common\model\school\ContentModel;
use app\common\model\school\PhotoModel;
use app\common\model\school\PlayerModel;
use app\common\model\school\SchoolModel;
use app\common\model\school\TeacherModel;
use app\common\model\school\VoteModel;


class Index extends Common
{

    /**
     *
     * 教育官网首页 banner photos  list
     */
    public function getIndex()
    {
        //获取banner
        $data['banner'] = [];
        $BannerModel = new BannerModel();
        $bannerList = $BannerModel->where(['member_miniapp_id' => $this->appid])->order('orderby desc')->limit(0, 10)->select();
        foreach ($bannerList as $val) {
            $data['banner'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }

        //获取相册；
        $data['photos'] = [];
        $PhotoModel = new PhotoModel();
        $photoList = $PhotoModel->where(['member_miniapp_id' => $this->appid])->order('orderby desc')->limit(0, 20)->select();
        foreach ($photoList as $val) {
            $data['photos'] [] = [
                'photo' => IMG_URL . getImg($val->photo),
                'title' => $val->title,
            ];
        }
        // 获取3 个课程介绍；
        $ClassoneModel = new ClassoneModel();
        $data['class'] = [];
        $classList = $ClassoneModel->where(['member_miniapp_id' => $this->appid, 'type' => 1])->order('orderby desc')->limit(0, 3)->select();
        foreach ($classList as $val) {
            $data['class'] [] = [
                'class_id' => $val->class_id,
                'photo' => IMG_URL . getImg($val->photo),
                'title' => $val->title,
                'price' => $val->price == 0 ? '免费' : sprintf("%.2f", $val->price)
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    /**
     *  学校介绍；
     */
    public function getIntroduce()
    {
        $SchoolModel = new SchoolModel();
        $school = $SchoolModel->find($this->appid);
        $data['introduce'] = [];
        if (!empty($school)) {
            $data['introduce'] = $school->introduce;
        }
        $data['photos'] = [];
        $PhotoModel = new PhotoModel();
        $photoList = $PhotoModel->where(['member_miniapp_id' => $this->appid])->order('orderby desc')->limit(0, 20)->select();
        foreach ($photoList as $val) {
            $data['photos'] [] = [
                'photo' => IMG_URL . getImg($val->photo),
                'title' => $val->title,
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }



    /*
     *  teacher 师资力量
     */


    public function getTeacher()
    {
        $where['member_miniapp_id'] = $this->appid;
        $TeacherModel = new TeacherModel();
        $list = $TeacherModel->where($where)->order("orderby desc")->limit(0, 50)->select();
        $data['list'] = [];
        foreach ($list as $val) {
            $data['list'][] = [
                'photo' => IMG_URL . getImg($val->photo),
                'name' => $val->name,
                'zhiwu' => $val->zhiwu,
                'introduce' => $val->introduce,
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }


    /*
     *  课程介绍
     */
    public function getClass(){
        $keyword = (string) $this->request->param('keyword');
        if(!empty($keyword)){
            $where['title'] = ['LIKE',"%{{$keyword}}"];
        }
        $where['member_miniapp_id'] = $this->appid;
        $where['type'] = 1;
        $ClassoneModel = new ClassoneModel();
        $data['list'] = [];
        $classList = $ClassoneModel->where($where)->order('orderby desc')->limit($this->limit_bg, $this->limit_num)->select();
        foreach ($classList as $val) {
            $data['list'] [] = [
                'class_id' => $val->class_id,
                'photo' => IMG_URL . getImg($val->photo),
                'title' => $val->title,
                'price' => $val->price == 0 ? '免费' : sprintf("%.2f", $val->price)
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    /*
     * 课程详情
     */

    public function classDetail(){
        $class_id = (int)$this->request->param('class_id');
        $ClassoneModel = new ClassoneModel();
        if (!$detail = $ClassoneModel->get($class_id)) {
            $this->result('', 400, '不存在课程', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '不存在课程', 'json');
        }
//        if($detail->type != 1){
//            $this->result('', 400, '不存在课程', 'json');
//        }
        $contents =ContentModel::where(['member_miniapp_id'=>  $this->appid,'class_id'=>$class_id])->order(['orderby'=>'asc'])->select();
        $contentArr = [];
        foreach($contents as $val){
            $contentArr[]=[
                'content' => $val->content,
                'photo'   => empty($val->photo) ? '' : IMG_URL.getImg($val->photo)
            ];
        }
        $data = [
            'class_id' => $detail->class_id,
            'photo' => IMG_URL . getImg($detail->photo),
            'title' => $detail->title,
            'price' => $detail->price == 0 ? '免费' : sprintf("%.2f", $detail->price),
            'contents'=>$contentArr,
            'add_time'=>date('Y-m-d H:i:s',$detail->add_time),
        ];
        $this->result($data,200,'数据初始化成功','json');
    }

    /*
     * 学员风采
     */
    public function getStudent(){
        $where['member_miniapp_id'] = $this->appid;
        $where['type'] = 2;
        $ClassoneModel = new ClassoneModel();
        $data['list'] = [];
        $classList = $ClassoneModel->where($where)->order('orderby desc')->limit($this->limit_bg, $this->limit_num)->select();
        foreach ($classList as $val) {
            $data['list'] [] = [
                'class_id' => $val->class_id,
                'title' => $val->title,
                'add_time' => date("Y-m-d",$val->add_time),
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    /*
     * 活动 列表；
     */
    public function getActivity(){
        $ActivityModel  = new ActivityModel();
        $where['member_miniapp_id'] = $this->appid;
        $list = $ActivityModel->where($where)->order("orderby desc")->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'activity_id' => $val->activity_id,
                 'title'  => $val->title,
                 'date'  => $val->date,
                 'photo' => IMG_URL . getImg($val->photo),
                 'price'  => $val->price == 0 ? '免费' : sprintf("%.2f", $val->price),
                 'addr'  => $val->addr,
                 'num'  => $val->num,
                 'already_num'  => $val->already_num,
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
       $this->result($data,200,'数据初始化成功','json');
    }

    /*
     * 互动营销；
     */
    public function getVote(){
        $where['member_miniapp_id'] = $this->appid;
       // $where['bg_time'] = ['<',$this->request->time()];
       // $where['end_time'] = ['>',$this->request->time()];
        $VoteModel = new VoteModel();
        $list = $VoteModel->where($where)->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'photo' => IMG_URL . getImg($val->photo),
                'title' => $val->title,
                'vote_id' => $val->vote_id,
                'end_time' => $val->end_time,
                'bg_time'  => $val->bg_time,
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data,200,'数据初始化成功','json');
    }


    /*
     * 互动营销详情
     */

    public function voteDetail(){
        $vote_id = (int) $this->request->param('vote_id');
        $VoteModel = new VoteModel();
        if(!$detail = $VoteModel->find($vote_id)){
            $this->result('',400,'不存在互动','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在互动','json');
        }
        $VoteModel->where(['vote_id'=>$vote_id])->setInc('view_num');
        $PlayerModel = new PlayerModel();
        $where['member_miniapp_id'] = $this->appid;
        $where['vote_id'] = $vote_id;
        $list = $PlayerModel->where($where)->order("number asc")->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        $data['photo'] = IMG_URL .getImg($detail->photo);
        $data['bg_time'] = $detail->bg_time;
        $data['end_time'] = $detail->end_time;
        $data['is_end'] =  $detail->end_time < $this->request->time() ? 1 : 0;
        $data['vote_num'] = $detail->vote_num;
        $data['view_num'] = $detail->view_num;
        $data['entry_num'] = $detail->entry_num;
        $data['rule'] = $detail->rule;
        $data['introduce'] = $detail->introduce;
        $data['title'] = $detail->title;
        foreach ($list as $val){
            $data['list'][] = [
                'player_name' => $val->player_name,
                'photo'  => IMG_URL . getImg($val->photo),
                'number' => $val->number,
                'view_num' => $val->view_num,
                'vote_num' => $val->vote_num,
                'player_id' => $val->player_id,
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 选手详情；
     */
    public function playerDetail(){
      $player_id = (int) $this->request->param('player_id');
      $PlayerModel = new PlayerModel();
      if(!$player = $PlayerModel->find($player_id)){
          $this->result('',400,'不存在参赛选手','json');
      }
      if($player->member_miniapp_id != $this->appid) {
          $this->result('', 400, '不存在参赛选手', 'json');
      }
      $VoteModel = new VoteModel();
      $vote = $VoteModel->find($player->vote_id);
      $PlayerModel->where(['player_id'=>$player_id])->setInc('view_num');
      $data = [
          'player_id' => $player->player_id,
          'player_name' => $player->player_name,
          'photo' =>  IMG_URL . getImg($player->photo),
          'player_introduce' =>  $player->introduce,
          'vote_num' => $player->vote_num,
          'view_num' => $player->view_num,
          'number' => $player->number,
          'rule'  => empty($vote) ? '' : $vote->rule,
          'introduce'  => empty($vote) ? '' : $vote->introduce,
      ];
    $this->result($data,200,'数据初始化成功','json');
    }

    /*
     * 联系我们
     */
    public function about(){
        $SchoolModel = new SchoolModel();
        $school = $SchoolModel->find($this->appid);
        $data = [];
        if (!empty($school)) {
            $data['company_name'] = $school->company_name;
            $data['lat'] = (float) $school->lat;
            $data['lng'] = (float) $school->lng;
            $data['address'] = $school->address;
            $data['name'] = $school->name;
            $data['tel'] = $school->tel;
            $data['traffic'] = $school->traffic;
            $data['weixin'] = $school->weixin;
        }
        $this->result($data,200,'数据初始化成功','json');
    }

}
