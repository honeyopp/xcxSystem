<?php
namespace app\miniapp\controller\job;
use app\miniapp\controller\Common;
use app\common\model\job\PrivilegeModel;
class Privilege extends Common {
    public function create() {
        $PrivilegeModel = new PrivilegeModel();
        $detail = $PrivilegeModel->find($this->miniapp_id);
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['privilege'] = $this->request->param('privilege');  
            if(empty($data['privilege'])){
                $this->error('特权说明不能为空',null,101);
            }
            $data['explain'] = $this->request->param('explain');  
            if(empty($data['explain'])){
                $this->error('充值说明不能为空',null,101);
            }
            $PrivilegeModel = new PrivilegeModel();
            if(empty($detail)){

                $PrivilegeModel->save($data);
            }else{
                $PrivilegeModel->save($data,['member_miniapp_id'=>$this->miniapp_id]);
            }
            $this->success('操作成功',null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }
}