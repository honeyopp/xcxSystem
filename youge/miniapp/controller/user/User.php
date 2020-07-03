<?php

namespace app\miniapp\controller\user;

use app\miniapp\controller\Common;
use app\common\model\user\UserModel;

class User extends Common {

    public function index() {
        $where = $search = [];
        $search['nick_name'] = $this->request->param('nick_name');
        if (!empty($search['nick_name'])) {
            $where['nick_name'] = array('LIKE', '%' . $search['nick_name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = UserModel::where($where)->count();
        $list = UserModel::where($where)->order(['user_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function manage() {
        $where = $search = [];
        $search['nick_name'] = $this->request->param('nick_name');
        if (!empty($search['nick_name'])) {
            $where['nick_name'] = array('LIKE', '%' . $search['nick_name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['is_manage'] = 1;
        $count = UserModel::where($where)->count();
        $list = UserModel::where($where)->order(['user_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function unmanage() {
        $user_id = (int) $this->request->param('user_id');
        if (empty($user_id)) {
            $this->error('不存在会员', null, 101);
        }
        $UserModel = new UserModel();
        if (!$user = $UserModel->find($user_id)) {
            $this->error('不存在会员', null, 101);
        }
        if ($user->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在会员', null, 101);
        }
        $UserModel->save(['is_manage' =>0], ['user_id' => $user_id]);
        $this->success('操作成功！');
    }

    public function managecreate() {
        if ($this->request->method() == 'POST') {
            $user_id = (int) $this->request->param('user_id');
            if (empty($user_id)) {
                $this->error('不存在会员', null, 101);
            }
            $UserModel = new UserModel();
            if (!$user = $UserModel->find($user_id)) {
                $this->error('不存在会员', null, 101);
            }
            if ($user->member_miniapp_id != $this->miniapp_id) {
                $this->error('不存在会员', null, 101);
            }

            $UserModel->save(['is_manage' => 1], ['user_id' => $user_id]);
            $this->success('操作成功！', url('user.user/manage'));
        } else {
            return $this->fetch();
        }
    }

    public function select() {
        $where = $search = [];
        $search['nick_name'] = $this->request->param('nick_name');
        if (!empty($search['nick_name'])) {
            $where['nick_name'] = array('LIKE', '%' . $search['nick_name'] . '%');
        }
        $search['sex'] = (int) $this->request->param('sex');
        if (!empty($search['sex'])) {
            $where['sex'] = $search['sex'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = UserModel::where($where)->count();
        $list = UserModel::where($where)->order(['user_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    /*
     * 锁定会员
     */

    public function lock() {
        $user_id = (int) $this->request->param('user_id');
        $UserModel = new UserModel();
        if (!$user = $UserModel->find($user_id)) {
            $this->error('不存在会员', null, 101);
        }
        if ($user->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在会员', null, 101);
        }
        $data['is_lock'] = 1;
        if ($user->is_lock == 1) {
            $data['is_lock'] = 0;
        }
        $UserModel->save($data, ['user_id' => $user->user_id]);
        $this->success('操作成功', null, 101);
    }

}
