<?php
namespace app\miniapp\controller\companygw;
use app\miniapp\controller\Common;
use app\common\model\companygw\CompanyModel;
class Company extends Common {

    public function create() {
        $CompanyModel = new CompanyModel();
        $detail = $CompanyModel->find($this->miniapp_id);
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['company_name'] = $this->request->param('company_name');  
            if(empty($data['company_name'])){
                $this->error('公司名称不能为空',null,101);
            }
            $data['lat'] = $this->request->param('lat');  
            if(empty($data['lat'])){
                $this->error('经度不能为空',null,101);
            }
            $data['lng'] = $this->request->param('lng');  
            if(empty($data['lng'])){
                $this->error('纬度不能为空',null,101);
            }
            $data['address'] = $this->request->param('address');
            if(empty($data['address'])){
                $this->error('地址不能为空',null,101);
            }
            $data['traffic'] = $this->request->param('traffic');  
            if(empty($data['traffic'])){
                $this->error('交通信息不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('负责人名称不能为空',null,101);
            }
            $data['mobile'] = $this->request->param('mobile');  
            if(empty($data['mobile'])){
                $this->error('联系方式 不能为空',null,101);
            }
            $data['describe'] = $this->request->param('describe');
            if(empty($data['describe'])){
                $this->error('公司介绍不能为空',null,101);
            }
            if($detail){
                $CompanyModel->save($data,['member_miniapp_id'=>$this->miniapp_id]);
            }else{
                $CompanyModel->save($data);
            }
            $this->success('操作成功',null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }

   
}