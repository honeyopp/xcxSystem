<?php

namespace app\miniapp\controller\companygw;

use app\common\model\companygw\ProductModel;
use app\miniapp\controller\Common;
use app\common\model\companygw\ContentModel;

class Product extends Common
{


    public function index()
    {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ProductModel::where($where)->count();
        $list = ProductModel::where($where)->order(['product_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }

    public function create2()
    {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['product_name'] = $this->request->param('title');
            if (empty($data['product_name'])) {
                $this->error('头条标题不能为空', null, 101);
            }
            $data['price'] = (int)$this->request->param('price');
            $data['version'] = (string)$this->request->param('version');
            $data['photo'] = (string)$this->request->param('photo');
            $dl = empty($_POST['dl']) ? [] : $_POST['dl'];
            if (empty($dl)) {
                $this->error('文章段落内容不能为空');
            }
            $dlarr = [];
            $i = 0;
            foreach ($dl as $val) {
                $i++;
                if (empty($val['photo']) && empty($val['content'])) {
                    $this->error('第' . $i . '段落内容不能为空！');
                } else {
                    $dlarr[] = [
                        'member_miniapp_id' => $this->miniapp_id,
                        'photo' => $val['photo'],
                        'content' => $val['content'],
                        'orderby' => $i,
                        'type' => 3,
                    ];
                }
            }
            $ToutiaoModel = new ProductModel();
            if ($ToutiaoModel->save($data)) {
                foreach ($dlarr as $k => $val) {
                    $dlarr[$k]['toutiao_id'] = $ToutiaoModel->product_id;
                }
                $ContentModel = new ContentModel();
                $ContentModel->saveAll($dlarr);
            }
            $this->success('发布文章成功！', url('companygw.product/index'));
        } else {
            return $this->fetch();
        }
    }

    public function edit2()
    {
        $product_id = (int)$this->request->param('product_id');
        $ToutiaoModel = new ProductModel();
        if (!$detail = $ToutiaoModel->get($product_id)) {
            $this->error('请选择要编辑的头条', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在头条");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['product_name'] = $this->request->param('title');
            if (empty($data['product_name'])) {
                $this->error('头条标题不能为空', null, 101);
            }
            $data['price'] = (int)$this->request->param('price');
            $data['version'] = $this->request->param('version');
            $data['photo'] = $this->request->param('photo');
            $dl = empty($_POST['dl']) ? [] : $_POST['dl'];
            if (empty($dl)) {
                $this->error('文章段落内容不能为空');
            }
            $dlarr = [];
            $i = 0;
            foreach ($dl as $val) {
                $i++;
                if (empty($val['photo']) && empty($val['content'])) {
                    $this->error('第' . $i . '段落内容不能为空！');
                } else {
                    $dlarr[] = [
                        'member_miniapp_id' => $this->miniapp_id,
                        'photo' => $val['photo'],
                        'content' => $val['content'],
                        'orderby' => $i,
                        'type' => 3,
                    ];
                }
            }
            $ToutiaoModel = new ProductModel();
            $ToutiaoModel->save($data, ['product_id' => $product_id]);
            $ContentModel = new ContentModel();
            $ContentModel->where(['toutiao_id' => $product_id, 'type' => 3])->delete();// 先删除内容
            foreach ($dlarr as $k => $val) {
                $dlarr[$k]['toutiao_id'] = $product_id;
            }
            $ContentModel->saveAll($dlarr);
            $this->success('编辑文章成功！');
        } else {
            $ContentModel = new ContentModel();
            $this->assign('toutiao',$detail);
            $this->assign('contents',$ContentModel->where(['toutiao_id'=>$product_id,'type' => 3])->order(['orderby'=>'asc'])->select());
            return $this->fetch();
        }
}

public
function delete()
{
    $product_id = (int)$this->request->param('product_id');
    $ToutiaoModel = new ProductModel();
    if (!$detail = $ToutiaoModel->get($product_id)) {
        $this->error('不存在产品', null, 101);
    }
    if ($detail->member_miniapp_id != $this->miniapp_id) {
        $this->error("不存在产品");
    }


    $ToutiaoModel->where(['product_id' => $product_id])->delete();
    $ContentModel = new ContentModel();
    $ContentModel->where(['toutiao_id' => $product_id, 'type' => 3])->delete();
    $this->success('操作成功！');
}


}
