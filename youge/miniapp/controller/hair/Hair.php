<?php

namespace app\miniapp\controller\hair;

use app\miniapp\controller\Common;
use app\common\model\hair\HairModel;

class Hair extends Common
{


    public function create()
    {
        $HairModel = new HairModel();
        $detail = $HairModel->find($this->miniapp_id);
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['title'] = $this->request->param('title');
            if (empty($data['title'])) {
                $this->error('店铺名称不能为空', null, 101);
            }
            $data['lat'] = $this->request->param('lat');
            if (empty($data['lat'])) {
                $this->error('经度不能为空', null, 101);
            }
            $data['lng'] = $this->request->param('lng');
            if (empty($data['lng'])) {
                $this->error('纬度不能为空', null, 101);
            }
            $data['address'] = $this->request->param('address');
            if (empty($data['address'])) {
                $this->error('地址不能为空', null, 101);
            }
            if (empty($detail)) {
                $data['member_miniapp_id'] = $this->miniapp_id;
                $HairModel->save($data);
            } else {
                $HairModel->save($data,['member_miniapp_id'=>$this->miniapp_id]);
            }
            $this->success('操作成功', null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
}


}