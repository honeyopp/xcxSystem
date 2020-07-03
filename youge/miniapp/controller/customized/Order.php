<?php

namespace app\miniapp\controller\customized;

use app\miniapp\controller\Common;
use app\common\model\customized\OrderModel;
use app\common\model\setting\CityModel;
use app\common\model\user\UserModel;
class Order extends Common {
    protected $city = [];
    public function _initialize() {
        parent::_initialize();
        $city = CityModel::where(['member_miniapp_id'=>  $this->miniapp_id])->select();
        $items = [];
        foreach($city as $val){
            $items[$val->city_id] = $val;
        }
        $this->city = $items;
        $this->assign('citys',  $this->city);
    }
    
    public function index() {
        $where = $search = [];
        $search['type'] = (int) $this->request->param('type');
        if (!empty($search['type'])) {
            $where['type'] = $search['type'];
        }
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }

        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = array('LIKE', '%' . $search['mobile'] . '%');
        }


        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = OrderModel::where($where)->count();
        $list = OrderModel::where($where)->order(['order_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        
        $user_ids = [];
        foreach($list as $val){
            $user_ids[$val->user_id] = $val->user_id;
        }
        
        $UserModel = new UserModel();
        $this->assign('users',$UserModel->itemsByIds($user_ids));
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
            $data['type'] = (int) $this->request->param('type');
            if (empty($data['type'])) {
                $this->error('类型不能为空', null, 101);
            }
            $data['bg_city'] = (int) $this->request->param('bg_city');
            if (empty($data['bg_city'])) {
                $this->error('出发城市不能为空', null, 101);
            }
            $data['mb_city'] = (int) $this->request->param('mb_city');
            if (empty($data['mb_city'])) {
                $this->error('目的城市不能为空', null, 101);
            }
            $data['user_id'] = (int) $this->request->param('user_id');
            if (empty($data['user_id'])) {
                $this->error('用户不能为空', null, 101);
            }
            $data['name'] = $this->request->param('name');
            if (empty($data['name'])) {
                $this->error('联系人不能为空', null, 101);
            }
            $data['mobile'] = $this->request->param('mobile');
            if (empty($data['mobile'])) {
                $this->error('联系电话不能为空', null, 101);
            }
            $data['bg_date'] = $this->request->param('bg_date');
            if (empty($data['bg_date'])) {
                $this->error('出发时间不能为空', null, 101);
            }
            $data['end_date'] = $this->request->param('end_date');
            if (empty($data['end_date'])) {
                $this->error('回程日期不能为空', null, 101);
            }
            $data['num1'] = (int) $this->request->param('num1');
            $data['num2'] = (int) $this->request->param('num2');
            $data['email'] = $this->request->param('email');
            $data['price'] = (int) ($this->request->param('price') * 100);
            $data['content'] = $this->request->param('content');


            $OrderModel = new OrderModel();
            $OrderModel->save($data);
            $this->success('操作成功', null);
        } else {
            return $this->fetch();
        }
    }

    public function edit() {
        $order_id = (int) $this->request->param('order_id');
        $OrderModel = new OrderModel();
        if (!$detail = $OrderModel->get($order_id)) {
            $this->error('请选择要编辑的定制游订单', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在定制游订单");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['type'] = (int) $this->request->param('type');
            if (empty($data['type'])) {
                $this->error('类型不能为空', null, 101);
            }
            $data['bg_city'] = (int) $this->request->param('bg_city');
            if (empty($data['bg_city'])) {
                $this->error('出发城市不能为空', null, 101);
            }
            $data['mb_city'] = (int) $this->request->param('mb_city');
            if (empty($data['mb_city'])) {
                $this->error('目的城市不能为空', null, 101);
            }
            $data['user_id'] = (int) $this->request->param('user_id');
            if (empty($data['user_id'])) {
                $this->error('用户不能为空', null, 101);
            }
            $data['name'] = $this->request->param('name');
            if (empty($data['name'])) {
                $this->error('联系人不能为空', null, 101);
            }
            $data['mobile'] = $this->request->param('mobile');
            if (empty($data['mobile'])) {
                $this->error('联系电话不能为空', null, 101);
            }
            $data['bg_date'] = $this->request->param('bg_date');
            if (empty($data['bg_date'])) {
                $this->error('出发时间不能为空', null, 101);
            }
            $data['end_date'] = $this->request->param('end_date');
            if (empty($data['end_date'])) {
                $this->error('回程日期不能为空', null, 101);
            }
            $data['num1'] = (int) $this->request->param('num1');
            $data['num2'] = (int) $this->request->param('num2');
            $data['email'] = $this->request->param('email');
            $data['price'] = (int) ($this->request->param('price') * 100);
            $data['content'] = $this->request->param('content');


            $OrderModel = new OrderModel();
            $OrderModel->save($data, ['order_id' => $order_id]);
            $this->success('操作成功', null);
        } else {
            $this->assign('detail', $detail);
            return $this->fetch();
        }
    }

    public function delete() {

        $order_id = (int) $this->request->param('order_id');
        $OrderModel = new OrderModel();

        if (!$detail = $OrderModel->find($order_id)) {
            $this->error("不存在该定制游订单", null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该定制游订单', null, 101);
        }
        if ($detail->is_delete == 1) {
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $OrderModel->save($data, ['order_id' => $order_id]);
        $this->success('操作成功');
    }

}
