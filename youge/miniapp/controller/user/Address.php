<?php

namespace app\miniapp\controller\user;

use app\miniapp\controller\Common;
use app\common\model\user\AddressModel;

class Address extends Common {

    public function index() {
        $where = $search = [];
        $search['user_id'] = (int) $this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = $search['user_id'];
        }
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }

        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = array('LIKE', '%' . $search['mobile'] . '%');
        }

        $search['address'] = $this->request->param('address');
        if (!empty($search['address'])) {
            $where['address'] = array('LIKE', '%' . $search['address'] . '%');
        }


        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = AddressModel::where($where)->count();
        $list = AddressModel::where($where)->order(['address_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function create() {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['user_id'] = (int) $this->request->param('user_id');
            if (empty($data['user_id'])) {
                $this->error('会员不能为空', null, 101);
            }
            $data['name'] = $this->request->param('name');
            if (empty($data['name'])) {
                $this->error('联系人不能为空', null, 101);
            }
            $data['mobile'] = $this->request->param('mobile');
            if (empty($data['mobile'])) {
                $this->error('手机号码不能为空', null, 101);
            }
            $data['address'] = $this->request->param('address');
            if (empty($data['address'])) {
                $this->error('具体地址不能为空', null, 101);
            }
             $data['idcard'] = $this->request->param('idcard');
            $data['gps_addr'] = $this->request->param('gps_addr');
            $data['lng'] = $this->request->param('lng');
            $data['lat'] = $this->request->param('lat');
            $data['is_default'] = $this->request->param('is_default');
            $data['is_delete'] = $this->request->param('is_delete');


            $AddressModel = new AddressModel();
            $AddressModel->save($data);
            $this->success('操作成功', null);
        } else {
            return $this->fetch();
        }
    }

    public function edit() {
        $address_id = (int) $this->request->param('address_id');
        $AddressModel = new AddressModel();
        if (!$detail = $AddressModel->get($address_id)) {
            $this->error('请选择要编辑的会员地址', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在会员地址");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;

            $data['name'] = $this->request->param('name');
            if (empty($data['name'])) {
                $this->error('联系人不能为空', null, 101);
            }
            $data['mobile'] = $this->request->param('mobile');
            if (empty($data['mobile'])) {
                $this->error('手机号码不能为空', null, 101);
            }
            $data['address'] = $this->request->param('address');
            if (empty($data['address'])) {
                $this->error('具体地址不能为空', null, 101);
            }
             $data['idcard'] = $this->request->param('idcard');
            $data['gps_addr'] = $this->request->param('gps_addr');
            $data['lng'] = $this->request->param('lng');
            $data['lat'] = $this->request->param('lat');
            $data['is_default'] = $this->request->param('is_default');
            $data['is_delete'] = $this->request->param('is_delete');
            $AddressModel = new AddressModel();
            $AddressModel->save($data, ['address_id' => $address_id]);
            $this->success('操作成功', null);
        } else {
            $this->assign('detail', $detail);
            return $this->fetch();
        }
    }

    public function delete() {

        $address_id = (int) $this->request->param('address_id');
        $AddressModel = new AddressModel();

        if (!$detail = $AddressModel->find($address_id)) {
            $this->error("不存在该会员地址", null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该会员地址', null, 101);
        }
        $AddressModel->where(['address_id' => $address_id])->save(['is_delete'=>1]);
        $this->success('操作成功');
    }

}
