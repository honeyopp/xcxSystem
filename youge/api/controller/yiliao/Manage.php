<?php
namespace app\api\controller\yiliao;
use app\api\controller\Common;
use app\common\model\hospital\EnrollModel;

class  Manage extends  Common{
    protected $checklogin = true;


    /*
     * 预约； 挂号  预约门诊
     */

    public function enroll(){
        $data['user_id'] = $this->user->user_id;
        $data['type'] = (int) $this->request->param('type');
        $data['member_miniapp_id'] = $this->appid;
        $data['name'] = (string) $this->request->param('name');
        if(empty($data['name'])){
            $this->result('',400,'请输入联系人','json');
        }
        $data['mobile'] = (string) $this->request->param('mobile');
        if(empty($data['mobile'])){
            $this->result('',400,'请输入联系方式','json');
        }
        $data['date'] = $this->request->param('date');
        $data['sex'] = (int) $this->request->param('sex');
        $data['intention'] = (string) $this->request->param('intention');
        $data['demand'] = (string) $this->request->param('demand');
        $EnrollModel = new EnrollModel();
        $EnrollModel->save($data);
        $this->result('',200,'操作成功','json');
    }

    /*
     * 我的预约
     */
    public function  getEnroll(){
        $where['user_id'] = $this->user->user_id;
        $EnrollModel = new EnrollModel();
        $list = $EnrollModel->where($where)->order("enroll_id desc")->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'type' => $val->type,
                'name' => $val->name,
                'mobile' => $val->mobile,
                'date' => $val->date,
                'intention' => $val->intention,
                'demand' => $val->demand,
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }

}