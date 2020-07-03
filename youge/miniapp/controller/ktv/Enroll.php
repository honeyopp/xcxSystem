<?php
namespace app\miniapp\controller\ktv;
use app\common\model\ktv\RoomModel;
use app\miniapp\controller\Common;
use app\common\model\ktv\EnrollModel;
class Enroll extends Common {
    
    public function index() {
        $where = $search = [];
        $search['room_id'] = (int)$this->request->param('room_id');
        if (!empty($search['room_id'])) {
            $where['room_id'] = $search['room_id'];
        }
                $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = array('LIKE', '%' . $search['mobile'] . '%');
        }
        $search['status'] = $this->request->param('status');
        if($search['status'] != 10 && ! empty($search['status'])){
            $where['status'] = $search['status'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = EnrollModel::where($where)->count();
        $list = EnrollModel::where($where)->order(['enroll_id'=>'desc'])->paginate(10, $count);
        $roomIds = [];
        foreach ($list as $val){
            $roomIds[$val->room_id] = $val->room_id;
        }
        $status = ['等待消费','商家已接单','拒绝预约','已消费'];
        $this->assign('status',$status);
        $RoomModel = new RoomModel();
        $this->assign('rooms',$RoomModel->itemsByIds($roomIds));
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    /*
     * 拒绝预约
     */
    public function no(){
        $enroll_id = (int)$this->request->param('enroll_id');
        $EnrollModel = new EnrollModel();

        if(!$detail = $EnrollModel->find($enroll_id)){
            $this->error("不存在该预约管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该预约管理', null, 101);
        }
        if($detail->status != 0){
            $this->error('已处理预约',null,101);
        }
        $data['status'] = 2;
        $EnrollModel->save($data,['enroll_id'=>$enroll_id]);
        $this->success('操作成功');
    }
    public function yes(){
        $enroll_id = (int)$this->request->param('enroll_id');
        $EnrollModel = new EnrollModel();
        if(!$detail = $EnrollModel->find($enroll_id)){
            $this->error("不存在该预约管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该预约管理', null, 101);
        }
        if($detail->status != 0){
            $this->error('已处理预约',null,101);
        }
        $data['status'] = 1;
        $EnrollModel->save($data,['enroll_id'=>$enroll_id]);
        $this->success('操作成功');
    }
    public function ok(){
        $enroll_id = (int)$this->request->param('enroll_id');
        $EnrollModel = new EnrollModel();

        if(!$detail = $EnrollModel->find($enroll_id)){
            $this->error("不存在该预约管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该预约管理', null, 101);
        }
        if($detail->status != 1){
            $this->error('为接受此预约',null,101);
        }
        $data['status'] = 3;
        $EnrollModel->save($data,['enroll_id'=>$enroll_id]);
        $this->success('操作成功');
    }
    public function delete() {
        $enroll_id = (int)$this->request->param('enroll_id');
         $EnrollModel = new EnrollModel();
        if(!$detail = $EnrollModel->find($enroll_id)){
            $this->error("不存在该预约管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该预约管理', null, 101);
        }
        $EnrollModel->where(['enroll_id'=>$enroll_id])->delete();
        $this->success('操作成功');
    }
   
}