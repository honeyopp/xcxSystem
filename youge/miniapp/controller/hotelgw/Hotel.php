<?php
namespace app\miniapp\controller\hotelgw;
use app\miniapp\controller\Common;
use app\common\model\hotelgw\HotelModel;
class Hotel extends Common {
    


    public function create() {
        $HotelModel = new HotelModel();
        $detail = $HotelModel->find($this->miniapp_id);
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['hotel_name'] = $this->request->param('hotel_name');  
            if(empty($data['hotel_name'])){
                $this->error('酒店名称不能为空',null,101);
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
            $data['logo'] = $this->request->param('logo');  
            if(empty($data['logo'])){
                $this->error('Logo不能为空',null,101);
            }
            $data['banner'] = $this->request->param('banner');  
            if(empty($data['banner'])){
                $this->error('Banner不能为空',null,101);
            }
            $data['hotel_service'] = $this->request->param('hotel_service');  
            if(empty($data['hotel_service'])){
                $this->error('服务标签不能为空',null,101);
            }
            $data['describe'] = $this->request->param('describe');  
            if(empty($data['describe'])) {
                $this->error('酒店介绍不能为空', null, 101);
            }
            if($detail){
                $HotelModel->save($data,['member_miniapp_id'=>$this->miniapp_id]);
            }else{
                $HotelModel->save($data);
            }

            $this->success('操作成功',null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }

   
}