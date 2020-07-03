<?php
namespace app\miniapp\controller\shop;
use app\miniapp\controller\Common;
use app\common\model\shop\SkuModel;
class Sku extends Common {
    
    public function index() {
        $where = $search = [];

        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = SkuModel::where($where)->count();
        $list = SkuModel::where($where)->order(['order_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
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
            $data['member_miniapp_id'] = (int) $this->request->param('member_miniapp_id');
            if(empty($data['member_miniapp_id'])){
                $this->error('不能为空',null,101);
            }
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('不能为空',null,101);
            }
            $data['type_id'] = (int) $this->request->param('type_id');
            if(empty($data['type_id'])){
                $this->error('不能为空',null,101);
            }
            $data['goods_id'] = (int) $this->request->param('goods_id');
            if(empty($data['goods_id'])){
                $this->error('不能为空',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            if(empty($data['price'])){
                $this->error('不能为空',null,101);
            }
            $data['num'] = (int) $this->request->param('num');
            if(empty($data['num'])){
                $this->error('不能为空',null,101);
            }
            $data['add_time'] = (int) $this->request->param('add_time');
            if(empty($data['add_time'])){
                $this->error('不能为空',null,101);
            }
            $data['add_ip'] = (int) $this->request->param('add_ip');
            if(empty($data['add_ip'])){
                $this->error('不能为空',null,101);
            }
            
            
            $SkuModel = new SkuModel();
            $SkuModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $order_id = (int)$this->request->param('order_id');
         $SkuModel = new SkuModel();
         if(!$detail = $SkuModel->get($order_id)){
             $this->error('请选择要编辑的订单管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在订单管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['member_miniapp_id'] = (int) $this->request->param('member_miniapp_id');
            if(empty($data['member_miniapp_id'])){
                $this->error('不能为空',null,101);
            }
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('不能为空',null,101);
            }
            $data['type_id'] = (int) $this->request->param('type_id');
            if(empty($data['type_id'])){
                $this->error('不能为空',null,101);
            }
            $data['goods_id'] = (int) $this->request->param('goods_id');
            if(empty($data['goods_id'])){
                $this->error('不能为空',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            if(empty($data['price'])){
                $this->error('不能为空',null,101);
            }
            $data['num'] = (int) $this->request->param('num');
            if(empty($data['num'])){
                $this->error('不能为空',null,101);
            }
            $data['add_time'] = (int) $this->request->param('add_time');
            if(empty($data['add_time'])){
                $this->error('不能为空',null,101);
            }
            $data['add_ip'] = (int) $this->request->param('add_ip');
            if(empty($data['add_ip'])){
                $this->error('不能为空',null,101);
            }

            
            $SkuModel = new SkuModel();
            $SkuModel->save($data,['order_id'=>$order_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $order_id = (int)$this->request->param('order_id');
         $SkuModel = new SkuModel();
       
        if(!$detail = $SkuModel->find($order_id)){
            $this->error("不存在该订单管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该订单管理', null, 101);
        }
        $SkuModel->where(['order_id'=>$order_id])->delete();
        $this->success('操作成功');
    }
   
}