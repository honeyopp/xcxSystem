<?php
namespace app\admin\controller\miniapp;
use app\admin\controller\Common;
use app\common\model\member\MemberModel;
use app\common\model\miniapp\AppointModel;
use app\common\model\miniapp\MiniappModel;

class Appoint extends Common {
    
    public function index() {
        $miniapp_id = (int) $this->request->param('miniapp_id');
        $MiniappModel = new MiniappModel();
        if(!$detail = $MiniappModel->find($miniapp_id)){
            $this->error('请选择小程序');
        }
        $where = $search = [];
        $count = AppointModel::where($where)->count();
        $list = AppointModel::where($where)->order(['appoint_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $memberIds = [];
        $where['miniapp_id'] = $miniapp_id;
        foreach ($list as $val){
            $memberIds[$val->member_id] = $val->member_id;
        }
        $MemberModel = new MemberModel();
        $member = $MemberModel->itemsByIds($memberIds);
        $this->assign('member',$member);
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('detail',$detail);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    
    public function create() {
        $miniapp_id = (int) $this->request->param('miniapp_id');
        $MiniappModel = new MiniappModel();
        if(!$detail = $MiniappModel->find($miniapp_id)){
            $this->error('请选择小程序');
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $AppointModel = new AppointModel();
            $data['miniapp_id'] = $miniapp_id;
            $data['member_id'] = (int) $this->request->param('member_id');
            if($AppointModel->where(['miniapp_id'=>$miniapp_id,'member_id'=>$data['member_id']])->select()){
                $this->error('该会员已经是体验用户了',null,101);
            }
            if(empty($data['member_id'])){
                $this->error('用户不能为空',null,101);
            }
            $AppointModel->save($data);
            $this->success('操作成功',null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }

    public function delete() {
        if($this->request->method() == 'POST'){
             $appoint_id = $_POST['appoint_id'];
        }else{
            $appoint_id = $this->request->param('appoint_id');
        }
        $data = [];
        if (is_array($appoint_id)) {
            foreach ($appoint_id as $k => $val) {
                $appoint_id[$k] = (int) $val;
            }
            $data = $appoint_id;
        } else {
            $data[] = $appoint_id;
        }
        if (!empty($data)) {
            $AppointModel = new AppointModel();
            $AppointModel->where(array('appoint_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}