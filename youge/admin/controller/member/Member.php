<?php

namespace app\admin\controller\member;

use app\admin\controller\Common;
use app\common\model\member\MemberModel;
use app\common\model\setting\SettingModel;

class Member extends Common
{

    public function index()
    {
        $where = $search = [];
        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = array('LIKE', '%' . $search['mobile'] . '%');
        }
        $search['is_deposit'] = $this->request->param('is_deposit');
        if (!empty($search['is_deposit'])) {
            $where['is_deposit'] = $search['is_deposit'] >= 2 ? 0 : 1;
        }
        $SettingModel = new SettingModel();
        $agent = $SettingModel->fetchAll(true);
        $this->assign('agent',$agent['agent']);
        $count = MemberModel::where($where)->count();
        $list = MemberModel::where($where)->order(['member_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }
    public function create()
    {
        $MemberModel = new MemberModel();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['mobile'] = $this->request->param('mobile');
            if (empty($data['mobile'])) {
                $this->error('手机号不能为空', null, 101);
            }
            if ($member = $MemberModel->where(['mobile' => $data['mobile']])->select()) {
                $this->error('已存在该用户', null, 101);
            }
            $data['password'] = $this->request->param('password');
            if (empty($data['password'])) {
                $this->error('密码不能为空', null, 101);
            } else {
                $data['password'] = md5($data['password']);
            }
            $data['nick_name'] = $this->request->param('nick_name');
            if (empty($data['nick_name'])) {
                $data['nick_name'] = '用户' . substr($data['mobile'], -4);
            }
            $data['is_lock'] = (int) $this->request->param('is_lock');
            if(!empty($data['is_lock'])){
                $data['is_lock'] = $data['is_lock'] >= 1 ? 1 : 0;
            }
			$data['nick_name'] = $this->request->param('nick_name');
			$data['nick_dllogo'] = $this->request->param('nick_dllogo');
			$data['nick_dltitle'] = $this->request->param('nick_dltitle');
            $data['real_name'] = $this->request->param('real_name');
            $data['email'] = $this->request->param('email');
            $data['qq'] = (string)$this->request->param('qq');
            $data['weixin'] = (string)$this->request->param('weixin');
            $data['money'] = ((int)$this->request->param('money')) * 100;
            $data['sms_num'] = (int)$this->request->param('sms_num');
            $data['is_deposit'] = (int)$this->request->param('is_deposit');
            $MemberModel->save($data);
            $this->success('操作成功', null);
        } else {
            return $this->fetch();
        }
    }

    public function select()
    {
        $where = $search = [];
        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = array('LIKE', '%' . $search['mobile'] . '%');
        }
        $search['is_deposit'] = $this->request->param('is_deposit');
        if (!empty($search['is_deposit'])) {
            $where['is_deposit'] = $search['is_deposit'] >= 2 ? 0 : 1;
        }
        $callback = $this->request->param('callback');
        $count = MemberModel::where($where)->count();
        $list = MemberModel::where($where)->order(['member_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('callback', $callback);
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }

    public function member()
    {
        $member_id = (int)$this->request->param('member_id');
        $MemberModel = new MemberModel();
        if (!$detail = $MemberModel->get($member_id)) {
            $this->error('请选择用户', null, 101);
        }
        $code = authcode($member_id . '|manage|' . $_SERVER['REQUEST_TIME']);
        cookie('memberID', $code);
        $this->success('正在进入加盟商中心', url('manage/index/index'));
    }

    public function edit()
    {
        $member_id = (int)$this->request->param('member_id');
        $MemberModel = new MemberModel();
        if (!$detail = $MemberModel->get($member_id)) {
            $this->error('请选择要编辑的用户管理', null, 101);
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['mobile'] = $this->request->param('mobile');
            if (empty($data['mobile'])) {
                $this->error('手机号不能为空', null, 101);
            }
            $password  =  $this->request->param('password');
            if (!empty($data['password'])) {
                 $data['password'] = md5($password);
            }
            $data['is_lock'] = (int) $this->request->param('is_lock');
            if(!empty($data['is_lock'])){
                $data['is_lock'] = $data['is_lock'] >= 1 ? 1 : 0;
            }
            $data['nick_name'] = $this->request->param('nick_name');
			$data['nick_dltitle'] = $this->request->param('nick_dltitle');
			$data['nick_dllogo'] = $this->request->param('nick_dllogo');
            $data['real_name'] = $this->request->param('real_name');
            $data['email'] = $this->request->param('email');
            $data['qq'] = (string)$this->request->param('qq');
            $data['weixin'] = (string)$this->request->param('weixin');
            $data['money'] = ((int)$this->request->param('money')) * 100;
            $data['sms_num'] = (int)$this->request->param('sms_num');
            $data['is_deposit'] = $this->request->param('is_deposit');
            $data['last_time'] = (int)strtotime($this->request->param('last_time'));
            $data['last_ip'] = $this->request->param('last_ip');
            $MemberModel = new MemberModel();
            $MemberModel->save($data, ['member_id' => $member_id]);
            $this->success('操作成功', null);
        } else {
            $this->assign('detail', $detail);
            return $this->fetch();
        }
    }

    public function delete()
    {
        if ($this->request->method() == 'POST') {
            $member_id = $_POST['member_id'];
        } else {
            $member_id = $this->request->param('member_id');
        }
        $data = [];
        if (is_array($member_id)) {
            foreach ($member_id as $k => $val) {
                $member_id[$k] = (int)$val;
            }
            $data = $member_id;
        } else {
            $data[] = $member_id;
        }
        if (!empty($data)) {
            $MemberModel = new MemberModel();
            $MemberModel->where(array('member_id' => array('IN', $data)))->delete();
        }
        $this->success('操作成功');
    }

    /*
     * 设置 加盟商的等级
     */
    public function settype(){
        $member_id = (int) $this->request->param('member_id');
        $MemberModel = new MemberModel();
        if(!$member = $MemberModel->find($member_id)){
            $this->error('不存在加盟商',null,101);
        }
        if($this->request->isPost()){
            $type = (int) $this->request->param('type');
            $data['type'] = $type;
            $MemberModel->save($data,['member_id'=>$member_id]);
            $this->success('操作成功',null,100);
        }else{
            $SettingModel  = new SettingModel();
            $agent = $SettingModel->fetchAll(true);
            $this->assign('agent',$agent['agent']);
            $this->assign('member',$member);
            return $this->fetch();
        }
    }

}