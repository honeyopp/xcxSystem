<?php
namespace app\api\controller\sheying;
use app\api\controller\Common;
use app\common\model\sheying\EnrollModel;

class Member extends Common{
    protected $checklogin = true;

    /*
     * 预约
     * */
    public function enorll(){
        $data['member_miniapp_id'] = $this->appid;
        $data['user_id'] = $this->user->user_id;

        $data['category_id'] = (int) $this->request->param('category_id');
        if(empty($data['category_id'])){
           $this->result('',400,'预约类型不能为空','json');
        }
        $data['name'] = $this->request->param('name');
        if(empty($data['name'])){
           $this->result('',400,'姓名不能为空','json');
        }
        $data['mobile'] = $this->request->param('mobile');
        if(empty($data['mobile'])){
           $this->result('',400,'电话不能为空','json');
        }
        $data['remarks'] = $this->request->param('remarks');
        $data['date'] = $this->request->param('date');
        if(empty($data['date'])){
           $this->result('',400,'预约日期不能为空','json');
        }
        $EnrollModel = new EnrollModel();
        $EnrollModel->save($data);
        $this->result('',200,'数据初始化成功','json');
    }
}