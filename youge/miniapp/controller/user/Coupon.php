<?php
namespace app\miniapp\controller\user;

use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
use app\common\model\user\CouponModel;

class Coupon extends Common
{
    public function index(){
        $where = $search = [];
        $search['user_id'] = (int)$this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = $search['user_id'];
        }
        $search['way'] = (int)$this->request->param('way');
        if (!empty($search['way'])) {
            $where['way'] = $search['way'];
        }
        $search['is_can'] = $this->request->param('is_can');
        if (!empty($search['is_can'])) {
            $where['is_can'] = $search['is_can'] == 2 ? 0 : 1;
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CouponModel::where($where)->count();
        $list = CouponModel::where($where)->order(['coupon_id' => 'desc'])->paginate(10, $count);
        $userIds = [];
        foreach ($list as $val){
            $userIds[$val->user_id] = $val->user_id;
        }
        $UserModel = new UserModel();
        $page = $list->render();
        $this->assign('users',$UserModel->itemsByIds($userIds));
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }
    public function create()
    {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['user_id'] = (int)$this->request->param('user_id');
            if (empty($data['user_id'])) {
                $this->error('会员不能为空', null, 101);
            }
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['way'] = 1;
            $data['need_money'] = ((int)$this->request->param('need_money')) * 100;
            if (empty($data['need_money'])) {
                $this->error('最低使用条件不能为空', null, 101);
            }

            $data['money'] = ((int)$this->request->param('money')) * 100;
            if (empty($data['money'])) {
                $this->error('面额不能为空', null, 101);
            }
            $data['expir_time'] = (int)strtotime($this->request->param('expir_time'));
            if (empty($data['expir_time'])) {
                $this->error('过期时间不能为空', null, 101);
            }
            $data['can_use_time'] = (int)strtotime($this->request->param('can_use_time'));
            if (empty($data['can_use_time'])) {
                $this->error('可以使用时间不能为空', null, 101);
            }
            $data['is_can'] = $this->request->param('is_can');

            $CouponModel = new CouponModel();
            $CouponModel->save($data);
            $this->success('操作成功', null);
        } else {
            return $this->fetch();
        }
    }
    public function edit()
    {
        $coupon_id = (int)$this->request->param('coupon_id');
        $CouponModel = new CouponModel();
        if (!$detail = $CouponModel->get($coupon_id)) {
            $this->error('请选择要编辑的用户红包', null, 101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在红包',null,101);
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['need_money'] = (float) $this->request->param('need_money');
            if (empty($data['need_money'])) {
                $this->error('最低使用条件不能为空', null, 101);
            }
            $data['money'] = (float)$this->request->param('money');
            if (empty($data['money'])) {
                $this->error('面额不能为空', null, 101);
            }
            $data['expir_time'] = (int)strtotime($this->request->param('expir_time'));
            if (empty($data['expir_time'])) {
                $this->error('过期时间不能为空', null, 101);
            }
            $data['can_use_time'] = (int)strtotime($this->request->param('can_use_time'));
            if (empty($data['can_use_time'])) {
                $this->error('可以使用时间不能为空', null, 101);
            }
            $data['is_can'] = (int) $this->request->param('is_can');
            $CouponModel = new CouponModel();
            $CouponModel->save($data, ['coupon_id' => $coupon_id]);
            $this->success('操作成功', null);
        } else {
            $this->assign('detail', $detail);
            return $this->fetch();
        }
    }
    public function delete(){
        if ($this->request->method() == 'POST') {
            $coupon_id = $_POST['coupon_id'];
        } else {
            $coupon_id = $this->request->param('coupon_id');
        }
        $data = [];
        if (is_array($coupon_id)) {
            foreach ($coupon_id as $k => $val) {
                $coupon_id[$k] = (int)$val;
            }
            $data = $coupon_id;
        } else {
            $data[] = $coupon_id;
        }
        if (!empty($data)) {
            $CouponModel = new CouponModel();
            $CouponModel->where(array('coupon_id' => array('IN', $data)))->delete();
        }
        $this->success('操作成功');
    }

}