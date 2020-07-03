<?php
namespace app\miniapp\controller\sheying;
use app\miniapp\controller\Common;
use app\common\model\sheying\SheyingModel;
class Sheying extends Common {

    public function create() {
        $SheyingModel = new SheyingModel();
        $detail = $SheyingModel->find($this->miniapp_id);
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
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
            $data['trade'] = $this->request->param('trade');  
            if(empty($data['trade'])){
                $this->error('营业时间不能为空',null,101);
            }
            $data['mobile'] = $this->request->param('mobile');  
            if(empty($data['mobile'])){
                $this->error('联系方式不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('文本框不能为空',null,101);
            }
            if(empty($detail)){
                $SheyingModel->save($data);
            }else{
                $SheyingModel->save($data,['member_miniapp_id'=>$this->miniapp_id]);
            }
            $this->success('操作成功',null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }

   
}