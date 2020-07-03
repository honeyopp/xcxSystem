<?php

namespace app\miniapp\controller\shop2;
use app\common\model\shop\CategoryModel;
use app\common\model\shop\ContentModel;
use app\common\model\shop\TypeModel;
use app\miniapp\controller\Common;
use app\common\model\shop\GoodsModel;
class Goods extends Common
{

    public function index(){
        $where = $search = [];
        $search['goods_name'] = $this->request->param('goods_name');
        if(!empty($search['goods_name'])){
            $where['goods_name'] = ["LIKE",'%' . $search['goods_name'] . '%'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = GoodsModel::where($where)->count();
        $list = GoodsModel::where($where)->order(['goods_id' => 'desc'])->paginate(10, $count);
        $CategoryModel = new CategoryModel();
        $CategoryIds = [];
        foreach ($list as $val){
             $CategoryIds[$val->category_id] = $val->category_id;
        }
        $category = $CategoryModel->itemsByIds($CategoryIds);
        $this->assign('category',$category);
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
            $goods_id = 0;
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['category_id'] = $this->request->param('category_id');
            if (empty($data['category_id'])) {
                $this->error('商品分类不能为空', null, 101);
            }
            $data['goods_name'] = $this->request->param('goods_name');
            if (empty($data['goods_name'])) {
                $this->error('商品名称不能为空', null, 101);
            }
            $data['photo'] =  (string) $this->request->param('photo');
            if (empty($data['photo'])) {
                $this->error('商品图片不能为空', null, 101);
            }
            $data['give_integral'] = (int)$this->request->param('give_integral');
            $data['user_integral'] = (int)$this->request->param('user_integral');
            $data['is_mail'] =  (int) $this->request->param('is_mail');
            $data['mail_price'] = (float)$this->request->param('mail_price') * 100;
            $data['shop_price'] = (float)$this->request->param('shop_price') * 100;
            $data['price'] = (int)$this->request->param('price') * 100;
            $service_ids = $_POST['service'] ? $_POST['service'] : [];
            $data['service_ids'] = implode(',', $service_ids);
            $data['brief'] = $this->request->param('brief');
            if (empty($data['brief'])) {
                $this->error('商品简略不能为空', null, 101);
            }
            $data['spec'] = (string) $this->request->param('spec');
            $data['ctn'] = (string) $this->request->param('ctn');
            $data['is_online'] = $this->request->param('is_online');
            $data['is_hot'] = $this->request->param('is_hot');
            $data['orderby'] = (int)$this->request->param('orderby');
            $types = $_POST['data'];
            $data['surplus_num']  = 0;
            foreach ($types['num'] as $key => $val) {
                if (empty($types['type_name'][$key]) || empty($types['price'][$key]) || empty($types['num'][$key])) {
                    $this->error('规格参数有空值', null, 101);
                } else {
                      $data['surplus_num'] +=  $val;
                }
            }
            if (empty($types)) {
                $this->error('至少填写一个规格', null, 'json');
            }
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
            $type_data = [];
            foreach ($types['price'] as $key => $val) {
                if (empty($types['type_name'][$key]) || empty($types['price'][$key]) || empty($types['num'][$key])) {
                    $this->error('规格参数有空值', null, 101);
                } else {
                    $type_data[] = [
                        'goods_id' => $GoodsModel->goods_id,
                        'member_miniapp_id' => $this->miniapp_id,
                        'type_name' => $types['type_name'][$key],
                        'price' => (float)$types['price'][$key] * 100,
                        'surplus_num' => (int)$types['num'][$key],
                    ];
                }

            }
            $TypeModel = new TypeModel();
            $TypeModel->saveAll($type_data);
            $this->success('操作成功', null);
        } else {
            $CategoryModel = new CategoryModel();
            $category = $CategoryModel->where(['member_miniapp_id' => $this->miniapp_id, 'pid' => 0])->select();
            $this->assign('category', $category);
            return $this->fetch();
        }
    }
    /*
     * ajax 获取子类；
     * */
    public function ajaxCate()
    {
        $category_id = (int)$this->request->param('category_id');
        $CategoryModel = new CategoryModel();
        if (!$cate = $CategoryModel->find($category_id)) {
            $this->result('', 400, '不存在父级', 'json');
        }
        if ($cate->member_miniapp_id != $this->miniapp_id) {
            $this->result('', 400, '不存在父级', 'json');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['pid'] = $category_id;
        $list = $CategoryModel->where($where)->order('orderby desc')->select();
        $this->result($list, 200, '数据初始化成功', 'json');
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
            $data['category_id'] = $this->request->param('category_id');
            if (empty($data['category_id'])) {
                $this->error('商品分类不能为空', null, 101);
            }
            $data['goods_name'] = $this->request->param('goods_name');
            if (empty($data['goods_name'])) {
                $this->error('商品名称不能为空', null, 101);
            }
            $data['photo'] =  (string) $this->request->param('photo');
            if (empty($data['photo'])) {
                $this->error('商品图片不能为空', null, 101);
            }
            $data['give_integral'] = (int)$this->request->param('give_integral');
            $data['user_integral'] = (int)$this->request->param('user_integral');
            $data['is_mail'] =  (int) $this->request->param('is_mail');
            $data['mail_price'] = (float)$this->request->param('mail_price') * 100;
            $data['shop_price'] = (float)$this->request->param('shop_price') * 100;
            $data['price'] = (int)$this->request->param('price') * 100;
            $service_ids = $_POST['service'] ? $_POST['service'] : [];
            $data['service_ids'] = implode(',', $service_ids);
            $data['brief'] = $this->request->param('brief');
            if (empty($data['brief'])) {
                $this->error('商品简略不能为空', null, 101);
            }
            $data['spec'] = (string) $this->request->param('spec');
            $data['ctn'] = (string) $this->request->param('ctn');
            $data['is_online'] = $this->request->param('is_online');
            $data['is_hot'] = $this->request->param('is_hot');
            $data['orderby'] = (int)$this->request->param('orderby');
            $types = $_POST['data'];
            $data['surplus_num']  = 0;
            foreach ($types['num'] as $key => $val) {
                if (empty($types['type_name'][$key]) || empty($types['price'][$key]) || empty($types['num'][$key])) {
                    $this->error('规格参数有空值', null, 101);
                } else {
                    $data['surplus_num'] +=  $val;
                }
            }
            if (empty($types)) {
                $this->error('至少填写一个规格', null, 'json');
            }
            $type_data = [];
            foreach ($types['price'] as $key => $val) {
                if (empty($types['type_name'][$key]) || empty($types['price'][$key]) || empty($types['num'][$key])) {
                    $this->error('规格参数有空值', null, 101);
                } else {
                    $type_data[] = [
                        'goods_id' => $goods_id,
                        'member_miniapp_id' => $this->miniapp_id,
                        'type_name' => $types['type_name'][$key],
                        'price' => (float)$types['price'][$key] * 100,
                        'surplus_num' => (int)$types['num'][$key],
                    ];
                }
            }
            $TypeModel = new TypeModel();
            $TypeModel->where(['goods_id'=>$goods_id,'is_delete'=>0])->delete();
            $TypeModel->saveAll($type_data);
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
            $category = $CategoryModel->where(['member_miniapp_id'=>$this->miniapp_id,'pid'=>0])->order("orderby desc")->limit(0, 20)->select();
            $service = explode(',', $detail->service_ids);
            $service_ids = [];
            foreach ($service as $val) {
                $service_ids[$val] = $val;
            }

            $TypeModel = new TypeModel();
            $where['goods_id'] = $goods_id;
            $where['is_delete'] = 0;
            $sku = $TypeModel->where($where)->select();
            $cate = $CategoryModel->find($detail->category_id);
            $cate_id = empty($cate) ? 0 : $cate->category_id;
            $this->assign('cate',$cate);
            $this->assign('cate_id',$cate_id);
            $this->assign('sku',$sku);
            $this->assign('service', $service_ids);
            $this->assign('category', $category);
            $this->assign('detail', $detail);
            return $this->fetch();
        }
    }


    /*shanchu*/
    public function deleteType(){
        $type_id = (int) $this->request->param('type_id');
        $TypeModel = new TypeModel();
        if(!$type = $TypeModel->find($type_id)){
            $this->result('',400,'不存在SKU','json');
        }
        if($type->member_miniapp_id != $this->miniapp_id){
            $this->result('',400,'不存在KSU','json');
        }
        $data['is_delete'] = 1;
        if($type->is_delete == 1){
            $this->result('',200,'操作成功','json');
        }
        $TypeModel->save($data,['type_id'=>$type_id]);
        $this->result('',200,'操作成功','json');
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
        $GoodsModel->where(['goods_id' => $goods_id])->delete();
        $this->success('操作成功');
    }

}