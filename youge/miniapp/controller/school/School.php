<?php
namespace app\miniapp\controller\school;
use app\miniapp\controller\Common;
use app\common\model\school\SchoolModel;
class School extends Common {
    

    
    public function create() {
        $SchoolModel = new SchoolModel();
        $detail = $SchoolModel->get($this->miniapp_id);
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['company_name'] = $this->request->param('company_name');  
            if(empty($data['company_name'])){
                $this->error('公司名称不能为空',null,101);
            }
            $data['lat'] =  (float)  $this->request->param('lat');
            if(empty($data['lat'])){
                $this->error('经度不能为空',null,101);
            }
            $data['lng'] = (float) $this->request->param('lng');
            if(empty($data['lng'])){
                $this->error('纬度不能为空',null,101);
            }
            $data['address'] = $this->request->param('address');  
            if(empty($data['address'])){
                $this->error('地址不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('联系人不能为空',null,101);
            }
            $data['tel'] = $this->request->param('tel');  
            if(empty($data['tel'])){
                $this->error('电话不能为空',null,101);
            }
            $data['traffic'] = $this->request->param('traffic');  
            if(empty($data['traffic'])){
                $this->error('交通信息不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('学校介绍不能为空',null,101);
            }

            if(empty($detail)){
                $SchoolModel->save($data);
            }else{
                $SchoolModel->save($data,['member_miniapp_id'=>$this->miniapp_id]);
            }
            $this->success('操作成功',null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }
    


   
}