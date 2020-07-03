<?php
namespace app\miniapp\controller\ktv;
use app\miniapp\controller\Common;
use app\common\model\ktv\KtvModel;
class Ktv extends Common {
    public function create() {
        $detail = KtvModel::find($this->miniapp_id);
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['lat'] =  $this->request->param('lat');
            if(empty($data['lat'])){
                $this->error('经度不能为空',null,101);
            }
            $data['lng'] =  $this->request->param('lng');
            if(empty($data['lng'])){
                $this->error('纬度不能为空',null,101);
            }
            $data['address'] =  $this->request->param('address');
            if(empty($data['address'])){
                $this->error('地址不能为空',null,101);
            }
            $data['trade'] = $this->request->param('trade');
            if(empty($data['trade'])){
                $this->error('营业时间不能为空',null,101);
            }
            $data['ktv_name'] = $this->request->param('ktv_name');
            if(empty($data['ktv_name'])){
                $this->error('店铺名称',null,101);
            }
            $data['tel'] =  $this->request->param('tel');
            if(empty($data['tel'])){
                $this->error('联系方式不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');
            if(empty($data['introduce'])){
                $this->error('介绍不能为空',null,101);
            }
            $KtvModel = new KtvModel();
            if(empty($detail)){
                $data['member_miniapp_id'] = $this->miniapp_id;
                $KtvModel->save($data);
            }else{
                $KtvModel->save($data,['member_miniapp_id',$this->miniapp_id]);
            }
            $this->success('操作成功',null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }
}