<?php
namespace app\miniapp\controller\service;
use app\common\model\service\NannyModel;
use app\common\model\service\RepairModel;
use app\miniapp\controller\Common;
use app\common\model\service\EnrollModel;
class Enroll extends Common {
    
    public function weixiu() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = $search['name'];
        }
        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = $search['mobile'];
        }
        $where['type'] = 1;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = EnrollModel::where($where)->count();
        $list = EnrollModel::where($where)->order(['enroll_id'=>'desc'])->paginate(10, $count);
        $repairIds = [];
        $RepairModel  = new RepairModel();
        foreach ($list as $val){
            $repairIds[$val->type_id] = $val->type_id;
        }
        $type = $RepairModel->itemsByIds($repairIds);
        $this->assign('type',$type);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function ayi() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = $search['name'];
        }
        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = $search['mobile'];
        }
        $where['type'] = 2;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = EnrollModel::where($where)->count();
        $list = EnrollModel::where($where)->order(['enroll_id'=>'desc'])->paginate(10, $count);
        $nannyIds = [];
        foreach ($list as $val){
            $nannyIds[$val->type_id] = $val->type_id;
        }
        $NannyModel = new NannyModel();
         $type =  $NannyModel->itemsByIds($nannyIds);
        $this->assign('type',$type);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
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


    public function ok() {

        $enroll_id = (int)$this->request->param('enroll_id');
        $EnrollModel = new EnrollModel();

        if(!$detail = $EnrollModel->find($enroll_id)){
            $this->error("不存在该预约管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该预约管理', null, 101);
        }
        $data['status'] = 2;
        $EnrollModel->save($data,['enroll_id'=>$enroll_id]);
        $this->success('操作成功');
    }


    public function no() {
        $enroll_id = (int)$this->request->param('enroll_id');
        $EnrollModel = new EnrollModel();

        if(!$detail = $EnrollModel->find($enroll_id)){
            $this->error("不存在该预约管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该预约管理', null, 101);
        }
        $data['status'] = $detail->status == 0 ?   6 :4;
        $EnrollModel->save($data,['enroll_id'=>$enroll_id]);
        $this->success('操作成功');
    }


    public function tuikuan(){
        $enroll_id = (int)$this->request->param('enroll_id');
        $EnrollModel = new EnrollModel();
        if(!$detail = $EnrollModel->find($enroll_id)){
            $this->error("不存在该预约管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该预约管理', null, 101);
        }
        if($this->request->method() == "POST"){
            if($detail->status < 3 || $detail->status > 4){
                $this->error('不可退款',null);
            }
            $price = ((float) $this->request->param('price')) * 100;
            if($price > $detail->pay_money){
                $this->error('不可大于当余额',null,101);
            }
            $data['status'] = 5;
            $EnrollModel->save($data,['enroll_id'=>$enroll_id]);
            $EnrollModel->refund($enroll_id,$price);
            $this->success('退款成功');
        }else{
            $this->assign('enroll_id',$enroll_id);
            return $this->fetch();
        }


    }


}