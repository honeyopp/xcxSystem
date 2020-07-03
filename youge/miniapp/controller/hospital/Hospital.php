<?php
namespace app\miniapp\controller\hospital;
use app\miniapp\controller\Common;
use app\common\model\hospital\HospitalModel;
class Hospital extends Common {
    

    
    public function create() {
        $HospitalModel = new HospitalModel();
        $detail = $HospitalModel->find($this->miniapp_id);
        if ($this->request->method() == 'POST') {
            $data = [];

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
            $data['hospital_name'] = $this->request->param('hospital_name');  
            if(empty($data['hospital_name'])){
                $this->error('医院名称不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('联系人不能为空',null,101);
            }
            $data['mobile'] = $this->request->param('mobile');  
            if(empty($data['mobile'])){
                $this->error('联系方式不能为空',null,101);
            }
            $data['traffic'] = $this->request->param('traffic');  
            if(empty($data['traffic'])){
                $this->error('交通信息不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('医院介绍不能为空',null,101);
            }
            if(empty($detail)){

                $data['member_miniapp_id'] = $this->miniapp_id;
                $HospitalModel->save($data);
            }else{
                $HospitalModel->save($data,['member_miniapp_id'=>$this->miniapp_id]);
            }
            $this->success('操作成功',null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }

   
}