<?php
namespace app\miniapp\controller\school;
use app\common\model\school\ActivityModel;
use app\miniapp\controller\Common;
use app\common\model\school\ActivityentryModel;
class Activityentry extends Common {
    
    public function index() {
        $where = $search = [];
        $activity_id = (int) $this->request->param('activity_id');
        $ActivityModel= new ActivityModel();
        if(!$activity = $ActivityModel->find($activity_id)){
            $this->error('不存在活动',null,101);
        }
        if($activity->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在活动',null,101);
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ActivityentryModel::where($where)->count();
        $list = ActivityentryModel::where($where)->order(['entry_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    

    

    
    public function delete() {
        $entry_id = (int)$this->request->param('entry_id');
        $ActivityentryModel = new ActivityentryModel();
        if(!$detail = $ActivityentryModel->find($entry_id)){
            $this->error("不存在该查看报名",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该查看报名', null, 101);
        }
        $ActivityentryModel->where(['entry_id'=>$entry_id])->delete();
        $this->success('操作成功');
    }
   
}