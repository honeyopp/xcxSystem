<?php

namespace app\miniapp\controller\waimai;

use app\miniapp\controller\Common;
use app\common\model\waimai\ProductModel;
use app\common\model\waimai\CategoryModel;

class Product extends Common {

    public function index() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }


        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ProductModel::where($where)->count();
        $list = ProductModel::where($where)->order(['product_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);

        $CategoryModel = new CategoryModel();
        $data = $CategoryModel->fetchItems($this->miniapp_id);
        $this->assign('cats', $data);
        return $this->fetch();
    }

    public function create() {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['cat_id'] = (int) $this->request->param('cat_id');
            if (empty($data['cat_id'])) {
                $this->error('分类不能为空', null, 101);
            }
            $data['name'] = $this->request->param('name');
            if (empty($data['name'])) {
                $this->error('商品名称不能为空', null, 101);
            }
            $data['photo'] = $this->request->param('photo');
            if (empty($data['photo'])) {
                $this->error('图片不能为空', null, 101);
            }
            $data['price'] = (int) ($this->request->param('price')*100);
            if (empty($data['price'])) {
                $this->error('价格不能为空', null, 101);
            }
            $data['dabao'] = (int) ($this->request->param('dabao')*100);
            $data['monthnum'] = (int) $this->request->param('monthnum');
            $data['totalnum'] = (int) $this->request->param('totalnum');
            $data['is_online'] = $this->request->param('is_online');
            $data['orderby'] = (int) $this->request->param('orderby');


            $ProductModel = new ProductModel();
            $ProductModel->save($data);
            $this->success('操作成功', null);
        } else {
            $CategoryModel = new CategoryModel();
            $data = $CategoryModel->fetchItems($this->miniapp_id);
            $this->assign('cats', $data);
            return $this->fetch();
        }
    }

    public function edit() {
        $product_id = (int) $this->request->param('product_id');
        $ProductModel = new ProductModel();
        if (!$detail = $ProductModel->get($product_id)) {
            $this->error('请选择要编辑的外卖', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在外卖");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['cat_id'] = (int) $this->request->param('cat_id');
            if (empty($data['cat_id'])) {
                $this->error('分类不能为空', null, 101);
            }
            $data['name'] = $this->request->param('name');
            if (empty($data['name'])) {
                $this->error('商品名称不能为空', null, 101);
            }
            $data['photo'] = $this->request->param('photo');
            if (empty($data['photo'])) {
                $this->error('图片不能为空', null, 101);
            }
            $data['price'] = (int) ($this->request->param('price')*100);
            if (empty($data['price'])) {
                $this->error('价格不能为空', null, 101);
            }
            $data['dabao'] = (int) ($this->request->param('dabao')*100);
            $data['monthnum'] = (int) $this->request->param('monthnum');
            $data['totalnum'] = (int) $this->request->param('totalnum');
            $data['is_online'] = $this->request->param('is_online');
            $data['orderby'] = (int) $this->request->param('orderby');


            $ProductModel = new ProductModel();
            $ProductModel->save($data, ['product_id' => $product_id]);
            $this->success('操作成功', null);
        } else {
            $this->assign('detail', $detail);
            $CategoryModel = new CategoryModel();
            $data = $CategoryModel->fetchItems($this->miniapp_id);
            $this->assign('cats', $data);
            return $this->fetch();
        }
    }



}
