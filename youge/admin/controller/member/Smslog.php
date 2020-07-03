<?php
namespace app\admin\controller\member;
use app\admin\controller\Common;
use app\common\model\member\SmslogModel;
class Smslog extends Common {
    
    public function index() {
        $where = $search = [];

        $count = SmslogModel::where($where)->count();
        $list = SmslogModel::where($where)->order(['sms_log'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    
    public function create() {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_id'] = (int) $this->request->param('member_id');
            if(empty($data['member_id'])){
                $this->error('用户不能为空',null,101);
            }
            $data['log_type'] = (int) $this->request->param('log_type');
            if(empty($data['log_type'])){
                $this->error('使用途径不能为空',null,101);
            }
            $data['sms_num'] = (int) $this->request->param('sms_num');
            if(empty($data['sms_num'])){
                $this->error('短信条数不能为空',null,101);
            }
            $data['member_miniapp_id'] = (int) $this->request->param('member_miniapp_id');
            if(empty($data['member_miniapp_id'])){
                $this->error('分配小程序不能为空',null,101);
            }
            $data['this_sms_num'] = (int) $this->request->param('this_sms_num');
            if(empty($data['this_sms_num'])){
                $this->error('当前剩余不能为空',null,101);
            }
            $data['is_consume'] = (int) $this->request->param('is_consume');
            if(empty($data['is_consume'])){
                $this->error('使用类型不能为空',null,101);
            }
            
            
            $SmslogModel = new SmslogModel();
            $SmslogModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $sms_log = (int)$this->request->param('sms_log');
         $SmslogModel = new SmslogModel();
         if(!$detail = $SmslogModel->get($sms_log)){
             $this->error('请选择要编辑的短信日志',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_id'] = (int) $this->request->param('member_id');
            if(empty($data['member_id'])){
                $this->error('用户不能为空',null,101);
            }
            $data['log_type'] = (int) $this->request->param('log_type');
            if(empty($data['log_type'])){
                $this->error('使用途径不能为空',null,101);
            }
            $data['sms_num'] = (int) $this->request->param('sms_num');
            if(empty($data['sms_num'])){
                $this->error('短信条数不能为空',null,101);
            }
            $data['member_miniapp_id'] = (int) $this->request->param('member_miniapp_id');
            if(empty($data['member_miniapp_id'])){
                $this->error('分配小程序不能为空',null,101);
            }
            $data['this_sms_num'] = (int) $this->request->param('this_sms_num');
            if(empty($data['this_sms_num'])){
                $this->error('当前剩余不能为空',null,101);
            }
            $data['is_consume'] = (int) $this->request->param('is_consume');
            if(empty($data['is_consume'])){
                $this->error('使用类型不能为空',null,101);
            }

            
            $SmslogModel = new SmslogModel();
            $SmslogModel->save($data,['sms_log'=>$sms_log]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        if($this->request->method() == 'POST'){
             $sms_log = $_POST['sms_log'];
        }else{
            $sms_log = $this->request->param('sms_log');
        }
        $data = [];
        if (is_array($sms_log)) {
            foreach ($sms_log as $k => $val) {
                $sms_log[$k] = (int) $val;
            }
            $data = $sms_log;
        } else {
            $data[] = $sms_log;
        }
        if (!empty($data)) {
            $SmslogModel = new SmslogModel();
            $SmslogModel->where(array('sms_log'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}