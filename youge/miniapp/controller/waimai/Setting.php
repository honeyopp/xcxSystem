<?php

/**
 * @fileName    setting.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/20 0020
 */

namespace app\miniapp\controller\waimai;

use app\common\model\waimai\WaimaisettingModel;
use app\miniapp\controller\Common;

class Setting extends Common {

    /**
     * 设置小程序基本信息;
     * @param $data 一大堆数据;
     */
    public function create() {
        $WaimaisettingModel = new WaimaisettingModel();
        $setting = $WaimaisettingModel->where(['member_miniapp_id' => $this->miniapp_id])->find();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['qijia'] = (int) ($this->request->param('qijia') * 100);
            $data['peisong'] = (int) ($this->request->param('peisong') * 100);
            $data['is_online'] = (int) $this->request->param('is_online');

            if (!$setting) {
                $WaimaisettingModel->save($data);
                $this->success('操作成功', null);
            } else {
                $data['member_miniapp_id'] = $this->miniapp_id;
                $WaimaisettingModel->save($data, ['member_miniapp_id' => $this->miniapp_id]);
                $this->success('操作成功', null);
            }
        } else {
            $this->assign('detail', $setting);
            return $this->fetch();
        }
    }

}
