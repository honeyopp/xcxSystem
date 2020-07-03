<?php

namespace app\api\controller\school;

use app\api\controller\Common;
use app\common\model\school\ActivitycontentModel;
use app\common\model\school\ActivityentryModel;
use app\common\model\school\ActivityModel;
use app\common\model\school\ClassoneModel;
use app\common\model\school\EntryModel;
use app\common\model\school\PlayerModel;
use app\common\model\school\UservoteModel;
use app\common\model\school\VoteModel;
use app\miniapp\controller\school\Activityentry;

class Member extends Common
{
    protected $checklogin = true;

    public function consult()
    {
        $class_id = (int)$this->request->param('class_id');
        $ClassoneModel = new ClassoneModel();
        $class = $ClassoneModel->find($class_id);
        if (!empty($class) && $class->member_miniapp_id == $this->appid && $class->type == 1) {
            $data['class_id'] = $class_id;
        }

        $data['name'] = (string)$this->request->param('name');
        if (empty($data['name'])) {
            $this->result([], 400, '请输入联系人', 'json');
        }
        $data['message'] = (string)$this->request->param('message');

        $data['mobile'] = (string)$this->request->param('mobile');
        if (empty($data['mobile'])) {
            $this->result([], 400, '请输入联系方式', 'json');
        }
        $data['user_id'] = $this->user->user_id;
        $data['member_miniapp_id'] = $this->appid;
        $EntryModel = new EntryModel();
        $EntryModel->save($data);
        $this->result('', 200, '操作成功', 'json');
    }


    /*
     * 活动报名；
     **/

    public function activity()
    {
        $activity_id = (int)$this->request->param('activity_id');
        $ActivityModel = new ActivityModel();
        if (!$activity = $ActivityModel->find($activity_id)) {
            $this->result('', 400, '不存在活动', 'json');
        }
        if ($activity->member_miniapp_id != $this->appid) {
            $this->result('', 400, '不存在活动', 'json');
        }
        if ($activity->already_num > $activity->num) {
            $this->result('', 400, '已经满员了哟', 'json');
        }
        $ActivityentryModel = new ActivityentryModel();
        if ($entry = $ActivityentryModel->where(['user_id' => $this->user->user_id, 'activity_id' => $activity_id])->find()) {
            $this->result('', 400, '您已经报过名了', 'json');
        }
        $data['activity_id'] = $activity_id;
        $data['name'] = (string)$this->request->param('name');
        if (empty($data['name'])) {
            $this->result([], 400, '请输入联系人', 'json');
        }
        $ActivityModel->where(['activity_id' => $activity_id])->setInc('already_num');
        $data['message'] = (string)$this->request->param('message');
        $data['mobile'] = (string)$this->request->param('mobile');
        if (empty($data['mobile'])) {
            $this->result([], 400, '请输入联系方式', 'json');
        }
        $data['user_id'] = $this->user->user_id;
        $data['member_miniapp_id'] = $this->appid;
        $ActivityentryModel->save($data);
        $this->result('', 200, '操作成功', 'json');
    }

    /*
     * 投票
     */

    public function vote(){
        $player_id = (int)$this->request->param('player_id');
        $PlayModel = new PlayerModel();
        if (!$play = $PlayModel->find($player_id)) {
            $this->result('', 400, '不存在选手', 'json');
        }
        if ($play->member_miniapp_id != $this->appid) {
            $this->result('', 400, '不存在选手', 'json');
        }
        $VoteModel = new VoteModel();
        if (!$vote = $VoteModel->find($play->vote_id)) {
            $this->result('', 400, '请稍后重试', 'json');
        }
        if ($vote->bg_time > $this->request->time()) {
            $this->result('', 400, '活动未开始', 'json');
        }
        if ($vote->end_time < $this->request->time()) {
            $this->result('', 400, '活动已结束', 'json');
        }
        $UservoteModel = new UservoteModel();
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id']  = $this->user->user_id;
        $where['vote_id'] = $play->vote_id;
        $user = $UservoteModel->where($where)->order('add_time desc')->find();
        if(!empty($user) && $user->add_time + 86400 > $this->request->time()){
            $this->result('',400,'您24小时之内只能投票一次','json');
        }
        $data['user_id'] = $this->user->user_id;
        $data['player_id'] = $player_id;
        $data['member_miniapp_id'] = $this->appid;
        $data['vote_id'] = $vote->vote_id;
        $UservoteModel->save($data);
        $PlayModel->where(['player_id'=>$player_id])->setInc('vote_num');
        $VoteModel->where(['vote_id'=>$play->vote_id])->setInc('vote_num');
        $this->result('',200,'操作成功','json');
    }



    /*
 *   活动详情；
 */
    public function ActivityDetail(){
        $activity_id = (int)$this->request->param('activity_id');

        $ActivityModel = new ActivityModel();
        if (!$detail = $ActivityModel->get($activity_id)) {
            $this->result('', 400, '不存在课程', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '不存在课程', 'json');
        }
//        if($detail->type != 1){
//            $this->result('', 400, '不存在课程', 'json');
//        }
        $contents = ActivitycontentModel::where(['member_miniapp_id'=>  $this->appid,'activity_id'=>$activity_id])->order(['orderby'=>'asc'])->select();
        $contentArr = [];
        foreach($contents as $val){
            $contentArr[]=[
                'content' => $val->content,
                'photo'   => empty($val->photo) ? '' : IMG_URL.getImg($val->photo)
            ];
        }
        $ActivityentryModel = new ActivityentryModel();
        $where['member_miniapp_id'] = $this->appid;
        $where['activity_id'] = $activity_id;
        $entry = $ActivityentryModel->where($where)->limit(0,5)->select();
        $users = [];
        foreach ($entry as $val){
            $users[] = $val->name;
        }
        $entry = $ActivityentryModel->where(['user_id'=>$this->user->user_id,'activity_id'=>$activity_id])->find();
        $data = [
            'activity_id' => $detail->activity_id,
            'title'  => $detail->title,
            'date'  => $detail->date,
            'users' => $users,
            'price'  => $detail->price == 0 ? '免费' : sprintf("%.2f", $val->price),
            'addr'  => $detail->addr,
            'num'  => $detail->num,
            'is_on' => empty($entry) ? 0 : 1,
            'already_num'  => $detail->already_num,
            'contents'=>$contentArr,
            'add_time'=>date('Y-m-d H:i:s',$detail->add_time),
        ];
        $this->result($data,200,'数据初始化成功','json');
    }
}