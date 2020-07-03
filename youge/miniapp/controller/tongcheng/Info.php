<?php

namespace app\miniapp\controller\tongcheng;

use app\common\model\tongcheng\CategoryModel;
use app\common\model\tongcheng\InfophotoModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
use app\common\model\tongcheng\InfoModel;

class Info extends Common
{

    public function index()
    {
        $where = $search = [];
        $search['user_id'] = (int)$this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = $search['user_id'];
        }
        $search['category_id'] = (int)$this->request->param('category_id');
        if (!empty($search['category_id'])) {
            $where['category_id'] = $search['category_id'];
        }
        $search['expire_time'] = $this->request->param('expire_time');
        if (!empty($search['expire_time'])) {
            $where['expire_time'] = array('LIKE', '%' . $search['expire_time'] . '%');
        }
        $search['date'] = $this->request->param('date');
        if (!empty($search['date'])) {
            $where['FROM_UNIXTIME(add_time,"%Y-%m-%d")'] = $search['date'];
        }
        $search['add_time'] = $this->request->param('add_time');
        if (!empty($search['add_time'])) {
            $where['add_time'] = array('LIKE', '%' . $search['add_time'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = InfoModel::where($where)->count();
        $list = InfoModel::where($where)->order(['info_id' => 'desc'])->paginate(10, $count);
        $userIds = [];
        foreach ($list as $val) {
            $userIds[$val->user_id] = $val->user_id;
        }
        $CategoryModel = new CategoryModel();
        $cate = $CategoryModel->where(['member_miniapp_id' => $this->miniapp_id])->limit(0, 20)->select();
        $category = [];
        foreach ($cate as $val) {
            $category[$val->category_id] = $val;
        }
        $this->assign('category', $category);
        $UserModel = new UserModel();
        $user = $UserModel->itemsByIds($userIds);
        $page = $list->render();
        $this->assign('user', $user);
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }

    public function select()
    {
        $where = $search = [];
        $search['user_id'] = (int)$this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = $search['user_id'];
        }
        $search['expire_time'] = $this->request->param('expire_time');
        if (!empty($search['expire_time'])) {
            $where['expire_time'] = array('LIKE', '%' . $search['expire_time'] . '%');
        }
        $search['add_time'] = $this->request->param('add_time');
        if (!empty($search['add_time'])) {
            $where['add_time'] = array('LIKE', '%' . $search['add_time'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = InfoModel::where($where)->count();
        $list = InfoModel::where($where)->order(['info_id' => 'desc'])->paginate(10, $count);
        $userIds = [];
        foreach ($list as $val) {
            $userIds[$val->user_id] = $val->user_id;
        }
        $CategoryModel = new CategoryModel();
        $cate = $CategoryModel->where(['member_miniapp_id' => $this->miniapp_id])->limit(0, 20)->select();
        $category = [];
        foreach ($cate as $val) {
            $category[$val->category_id] = $val;
        }
        $this->assign('category', $category);
        $UserModel = new UserModel();
        $user = $UserModel->itemsByIds($userIds);
        $page = $list->render();
        $this->assign('user', $user);
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
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['user_id'] = (int)$this->request->param('user_id');
            $data['info'] = $this->request->param('info');
            if (empty($data['info'])) {
                $this->error('内容不能为空', null, 101);
            }
            $data['orderby'] = (int)$this->request->param('orderby');
            $data['category_id'] = (int)$this->request->param('category_id');
            if (empty($data['category_id'])) {
                $this->error('请选择分类', null, 101);
            }
            $data['tel'] = $this->request->param('tel');
            $data['expire_time'] = (int)strtotime($this->request->param('expire_time'));
            $data['lat'] = $this->request->param('lat');
            if (empty($data['lat'])) {
                $this->error('纬度不能为空', null, 101);
            }
            $data['lng'] = $this->request->param('lng');
            if (empty($data['lng'])) {
                $this->error('经度不能为空', null, 101);
            }
            $data['address'] = $this->request->param('address');
            if (empty($data['address'])) {
                $this->error('地址不能为空', null, 101);
            }
            $imags = empty($_POST['imgs']) ? [] : $_POST['imgs'];
            $InfoModel = new InfoModel();
            $InfoModel->save($data);
            $info_id = $InfoModel->info_id;
            $data2 = [];
            foreach ($imags as $val) {
                $data2[] = [
                    'info_id' => $info_id,
                    'member_miniapp_id' => $this->miniapp_id,
                    'photo' => $val,
                ];
            }
            $InfophotoModel = new InfophotoModel();
            $InfophotoModel->saveAll($data2);
            $this->success('操作成功', null);
        } else {
            $CategoryModel = new CategoryModel();
            $cate = $CategoryModel->where(['member_miniapp_id' => $this->miniapp_id])->limit(0, 20)->select();
            $this->assign('cate', $cate);
            return $this->fetch();
        }
    }
    public function edit()
    {
        $info_id = (int)$this->request->param('info_id');
        $InfoModel = new InfoModel();
        if (!$detail = $InfoModel->get($info_id)) {
            $this->error('请选择要编辑的发布信息', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在发布信息");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;

            $data['category_id'] = (int)$this->request->param('category_id');
            if (empty($data['category_id'])) {
                $this->error('请选择分类', null, 101);
            }
                $data['info'] = $this->request->param('info');
                if (empty($data['info'])) {
                    $this->error('内容不能为空', null, 101);
                }
                $data['view_num'] = (int)$this->request->param('view_num');

                $data['comment_num'] = (int)$this->request->param('comment_num');

                $data['orderby'] = (int)$this->request->param('orderby');

                $data['tel'] = $this->request->param('tel');

                $data['expire_time'] = (int)strtotime($this->request->param('expire_time'));

                $data['lat'] = $this->request->param('lat');
                if (empty($data['lat'])) {
                    $this->error('纬度不能为空', null, 101);
                }
                $data['lng'] = $this->request->param('lng');
                if (empty($data['lng'])) {
                    $this->error('经度不能为空', null, 101);
                }
                $data['address'] = $this->request->param('address');
                if (empty($data['address'])) {
                    $this->error('地址不能为空', null, 101);
                }
                $imags = empty($_POST['imgs']) ? [] : $_POST['imgs'];
                $InfoModel = new InfoModel();
                $InfoModel->save($data);
                $data2 = [];
                foreach ($imags as $val) {
                    $data2[] = [
                        'info_id' => $info_id,
                        'member_miniapp_id' => $this->miniapp_id,
                        'photo' => $val,
                    ];
                }
                $InfophotoModel = new InfophotoModel();
                $InfophotoModel->where(['info_id' => $info_id])->delete();
                $InfophotoModel->saveAll($data2);
                $InfoModel = new InfoModel();
                $InfoModel->save($data, ['info_id' => $info_id]);
                $this->success('操作成功', null);
            } else {
                $CategoryModel = new CategoryModel();
                $cate = $CategoryModel->where(['member_miniapp_id' => $this->miniapp_id])->limit(0, 20)->select();
                $this->assign('cate', $cate);
                $InfoPhotoModel = new InfophotoModel();
                $photos = $InfoPhotoModel->where(['info_id' => $info_id])->limit(0, 50)->select();
                $this->assign('photo', $photos);
                $this->assign('detail', $detail);
                return $this->fetch();
            }

    }

    public function delete()
        {
            $info_id = (int)$this->request->param('info_id');
            $InfoModel = new InfoModel();
            if (!$detail = $InfoModel->find($info_id)) {
                $this->error("不存在该发布信息", null, 101);
            }
            if ($detail->member_miniapp_id != $this->miniapp_id) {
                $this->error('不存在该发布信息', null, 101);
            }
            $InfoModel->where(['info_id' => $info_id])->delete();
            $this->success('操作成功');
        }
    }