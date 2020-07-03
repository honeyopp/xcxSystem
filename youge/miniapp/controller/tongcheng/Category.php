<?php

namespace app\miniapp\controller\tongcheng;

use app\miniapp\controller\Common;
use app\common\model\tongcheng\CategoryModel;

class Category extends Common
{

    public function index()
    {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CategoryModel::where($where)->count();
        $list = CategoryModel::where($where)->order(['category_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
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
            $data['name'] = $this->request->param('name');
            if (empty($data['name'])) {
                $this->error('分类名称不能为空', null, 101);
            }
            $where['member_miniapp_id'] = $this->miniapp_id;
            $count = CategoryModel::where($where)->count();
            if ($count >= 20) {
                $this->error('您最多添加20个分类', null, 101);
            }
            $data['ico'] = $this->request->param('ico');
            if (empty($data['ico'])) {
                $this->error('图标不能为空', null, 101);
            }
            $data['color'] = (string) $this->request->param('color');
            if(empty($data['color'])){
                $this->error('请选择颜色',null,101);
            }
            $data['orderby'] = (int)$this->request->param('orderby');
            $CategoryModel = new CategoryModel();
            $CategoryModel->save($data);
            $this->success('操作成功', null);
        } else {
            return $this->fetch();
        }
    }


    public function baototo()
    {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $where['member_miniapp_id'] = $this->miniapp_id;
            $count = CategoryModel::where($where)->count();
            if ($count >= 20) {
                $this->error('您最多添加20个分类', null, 101);
            }
            $data['name'] = $this->request->param('name');
            if (empty($data['name'])) {
                $this->error('分类名称不能为空', null, 101);
            }
            $data['ico'] = $this->request->param('ico');
            if (empty($data['ico'])) {
                $this->error('图标不能为空', null, 101);
            }
            $data['color'] = (string) $this->request->param('color');
            if(empty($data['color'])){
                $this->error('请选择颜色',null,101);
            }
            $data['orderby'] = (int)$this->request->param('orderby');
            $CategoryModel = new CategoryModel();
            $CategoryModel->save($data);
            $this->success('操作成功', null);
        } else {
            return $this->fetch();
        }
    }

    public function edit()
    {
        $category_id = (int)$this->request->param('category_id');
        $CategoryModel = new CategoryModel();
        if (!$detail = $CategoryModel->get($category_id)) {
            $this->error('请选择要编辑的分类管理', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在分类管理");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['name'] = $this->request->param('name');
            if (empty($data['name'])) {
                $this->error('分类名称不能为空', null, 101);
            }
            $data['ico'] = $this->request->param('ico');
            if (empty($data['ico'])) {
                $this->error('图标不能为空', null, 101);
            }
            $data['color'] = (string) $this->request->param('color');
            if(empty($data['color'])){
                $this->error('请选择颜色',null,101);
            }
            $data['orderby'] = (int)$this->request->param('orderby');
            $CategoryModel = new CategoryModel();
            $CategoryModel->save($data, ['category_id' => $category_id]);
            $this->success('操作成功', null);
        } else {
            $this->assign('detail', $detail);
            return $this->fetch();
        }
    }

    public function delete()
    {

        $category_id = (int)$this->request->param('category_id');
        $CategoryModel = new CategoryModel();

        if (!$detail = $CategoryModel->find($category_id)) {
            $this->error("不存在该分类管理", null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该分类管理', null, 101);
        }
        $CategoryModel->where(['category_id' => $category_id])->delete();
        $this->success('操作成功');
    }

}