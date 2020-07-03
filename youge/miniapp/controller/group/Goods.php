<?php

namespace app\miniapp\controller\group;

use app\common\model\group\CategoryModel;
use app\common\model\group\ContentModel;
use app\miniapp\controller\Common;
use app\common\model\group\GoodsModel;

class Goods extends Common
{

    public function index()
    {
        $where = $search = [];
        $search['goods_name'] = $this->request->param('goods_name');
        if (!empty($search['goods_name'])) {
            $where['goods_name'] = array('LIKE', '%' . $search['goods_name'] . '%');
        }
        $search['group'] = (int)$this->request->param('group');
        if (!empty($search['group'])) {
             switch ($search['group']){
                 case 1:
                     $where['bg_time'] = ['<',$this->request->time()];
                     break;
                 case 2:
                     $where['bg_time'] = ['>',$this->request->time()];
                     break;
                 case 3:
                     $where['end_time'] = ['<',$this->request->time()];
                     break;
             }
        }
        $search['kucun'] = (int) $this->request->param('kucun');
        if(!empty($search['kucun'])){
                switch ($search['kucun']){
                    case 1:
                        $where['surplus_num'] = ['>',100];
                        break;
                    case 2:
                        $where['surplus_num'] = ['<',100];
                        break;
                    case 3:
                        $where['surplus_num'] = ['<=',0];
                        break;
                }
        }
        $where['is_delete'] = 0;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = GoodsModel::where($where)->count();
        $list = GoodsModel::where($where)->order(['goods_id' => 'desc'])->paginate(10, $count);
        $catIds = [];
        foreach ($list as $val) {
            $catIds[$val->category_id] = $val->category_id;
        }
        $CategoryModel = new CategoryModel();
        $cats = $CategoryModel->itemsByIds($catIds);
        $page = $list->render();
        $this->assign('cats', $cats);
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
            $data['category_id'] = (int)$this->request->param('category_id');
            if (empty($data['category_id'])) {
                $this->error('商品分类不能为空', null, 101);
            }
            $data['goods_name'] = $this->request->param('goods_name');
            if (empty($data['goods_name'])) {
                $this->error('商品名称不能为空', null, 101);
            }
            $data['price'] = ((float)$this->request->param('price')) * 100;
            if (empty($data['price'])) {
                $this->error('市场原价不能为空', null, 101);
            }
            $data['group_price'] = ((float)$this->request->param('group_price')) * 100;
            if (empty($data['group_price'])) {
                $this->error('拼团价不能为空', null, 101);
            }
            $data['alone_price'] = ((float)$this->request->param('alone_price')) * 100;
            if (empty($data['alone_price'])) {
                $this->error('单独购买价格不能为空', null, 101);
            }
            $data['is_mail'] = (int)$this->request->param('is_mail');
            $data['mail_price'] = (int)$this->request->param('mail_price');
            $service_ids = $_POST['service'] ? $_POST['service'] : [];
            $data['service_ids'] = implode(',', $service_ids);
            $data['bg_time'] = (int)strtotime($this->request->param('bg_time'));
            if (empty($data['bg_time'])) {
                $this->error('开团时间不能为空', null, 101);
            }
            if ($data['bg_time'] > $this->request->time()) {
                $this->error('开始时间不得大于当前时间', null, 101);
            }
            $data['end_time'] = (int)strtotime($this->request->param('end_time'));
            if (empty($data['end_time'])) {
                $this->error('结束时间不能为空', null, 101);
            }
            if ($data['end_time'] < $this->request->time()) {
                $this->error('结束时间不能小于当前时间', null, 101);
            }
            $data['group_num'] = (int)$this->request->param('group_num');
            if (empty($data['group_num'])) {
                $this->error('几人团不能为空', null, 101);
            }
            $data['people_num'] = (int)$this->request->param('people_num');
            $data['surplus_num'] = (int)$this->request->param('surplus_num');
            if (empty($data['surplus_num'])) {
                $this->error('剩余库存不能为空', null, 101);
            }
            $data['brief'] = $this->request->param('brief');
            if (empty($data['brief'])) {
                $this->error('商品简略不能为空', null, 101);
            }
            $data['spec'] = $this->request->param('spec');
            if (empty($data['spec'])) {
                $this->error('规格不能为空', null, 101);
            }
            $data['ctn'] = $this->request->param('ctn');
            if (empty($data['ctn'])) {
                $this->error('包装不能为空', null, 101);
            }
            $data['photo'] = (string)$this->request->param('photo');
            if (empty($data['photo'])) {
                $this->error('列表图片不能为空', null, 101);
            }
            $data['is_online'] = $this->request->param('is_online');
            $data['orderby'] = (int)$this->request->param('orderby');
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
                    ];
                }
            }
            $GoodsModel = new GoodsModel();
            if ($GoodsModel->save($data)) {
                foreach ($dlarr as $k => $val) {
                    $dlarr[$k]['goods_id'] = $GoodsModel->goods_id;
                }
                $ContentModel = new ContentModel();
                $ContentModel->saveAll($dlarr);
            }
            $this->success('操作成功', null);
        } else {
            $CategoryModel = new CategoryModel();
            $where['member_miniapp_id'] = $this->miniapp_id;
            $category = $CategoryModel->where($where)->order("orderby desc")->limit(0, 20)->select();
            $this->assign('category', $category);
            return $this->fetch();
        }
    }

    public function edit()
    {
        $goods_id = (int)$this->request->param('goods_id');
        $GoodsModel = new GoodsModel();
        if (!$detail = $GoodsModel->get($goods_id)) {
            $this->error('请选择要编辑的商品管理', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在商品管理");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['category_id'] = (int)$this->request->param('category_id');
            if (empty($data['category_id'])) {
                $this->error('商品分类不能为空', null, 101);
            }
            $data['goods_name'] = $this->request->param('goods_name');
            if (empty($data['goods_name'])) {
                $this->error('商品名称不能为空', null, 101);
            }
            $data['price'] = ((float)$this->request->param('price')) * 100;
            if (empty($data['price'])) {
                $this->error('市场原价不能为空', null, 101);
            }
            $data['group_price'] = ((float)$this->request->param('group_price')) * 100;
            if (empty($data['group_price'])) {
                $this->error('拼团价不能为空', null, 101);
            }
            $data['alone_price'] = ((float)$this->request->param('alone_price')) * 100;
            if (empty($data['alone_price'])) {
                $this->error('单独购买价格不能为空', null, 101);
            }
            $data['is_mail'] = (int)$this->request->param('is_mail');
            $data['mail_price'] = (int)$this->request->param('mail_price');
            $service_ids = $_POST['service'] ? $_POST['service'] : [];
            $data['service_ids'] = implode(',', $service_ids);
            $data['bg_time'] = (int)strtotime($this->request->param('bg_time'));
            if (empty($data['bg_time'])) {
                $this->error('开团时间不能为空', null, 101);
            }
            if ($data['bg_time'] > $this->request->time()) {
                $this->error('开始时间不得大于当前时间', null, 101);
            }
            $data['end_time'] = (int)strtotime($this->request->param('end_time'));
            if (empty($data['end_time'])) {
                $this->error('结束时间不能为空', null, 101);
            }
            if ($data['end_time'] < $this->request->time()) {
                $this->error('结束时间不能小于当前时间', null, 101);
            }
            $data['group_num'] = (int)$this->request->param('group_num');
            if (empty($data['group_num'])) {
                $this->error('几人团不能为空', null, 101);
            }
            $data['people_num'] = (int)$this->request->param('people_num');
            $data['surplus_num'] = (int)$this->request->param('surplus_num');
            if (empty($data['surplus_num'])) {
                $this->error('剩余库存不能为空', null, 101);
            }
            $data['brief'] = $this->request->param('brief');
            if (empty($data['brief'])) {
                $this->error('商品简略不能为空', null, 101);
            }
            $data['spec'] = $this->request->param('spec');
            if (empty($data['spec'])) {
                $this->error('规格不能为空', null, 101);
            }
            $data['ctn'] = $this->request->param('ctn');
            if (empty($data['ctn'])) {
                $this->error('包装不能为空', null, 101);
            }
            $data['photo'] = (string)$this->request->param('photo');
            if (empty($data['photo'])) {
                $this->error('列表图片不能为空', null, 101);
            }
            $data['is_online'] = $this->request->param('is_online');
            $data['orderby'] = (int)$this->request->param('orderby');

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
                    ];
                }
            }
            $GoodsModel->save($data, ['goods_id' => $goods_id]);
            $ContentModel = new ContentModel();
            $ContentModel->where(['goods_id' => $goods_id])->delete();// 先删除内容
            foreach ($dlarr as $k => $val) {
                $dlarr[$k]['goods_id'] = $goods_id;
            }
            $ContentModel = new ContentModel();
            $ContentModel->saveAll($dlarr);
            $GoodsModel = new GoodsModel();
            $GoodsModel->save($data, ['goods_id' => $goods_id]);
            $this->success('操作成功', null);
        } else {
            $ContentModel = new ContentModel();
            $this->assign('contents', $ContentModel->where(['goods_id' => $goods_id])->order(['orderby' => 'asc'])->select());
            $CategoryModel = new CategoryModel();
            $where['member_miniapp_id'] = $this->miniapp_id;
            $category = $CategoryModel->where($where)->order("orderby desc")->limit(0, 20)->select();
            $service = explode(',', $detail->service_ids);
            $service_ids = [];
            foreach ($service as $val) {
                $service_ids[$val] = $val;

            }

            $this->assign('service', $service_ids);
            $this->assign('category', $category);
            $this->assign('detail', $detail);
            return $this->fetch();
        }
    }

    public function delete()
    {
        $goods_id = (int)$this->request->param('goods_id');
        $GoodsModel = new GoodsModel();
        if (!$detail = $GoodsModel->find($goods_id)) {
            $this->error("不存在该商品管理", null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该商品管理', null, 101);
        }
        $data['is_delete'] = 1 ;
        if($detail->is_delete == 1 ){
            $this->success('操作成功');
        }
        $GoodsModel->save($data,['goods_id' => $goods_id]);
        $this->success('操作成功');
    }

}