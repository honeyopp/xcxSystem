<?php
namespace app\miniapp\controller\shop;
use app\miniapp\controller\Common;
use app\common\model\shop\CartModel;
class Cart extends Common {
    
    public function index() {
        $where = $search = [];

        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CartModel::where($where)->count();
        $list = CartModel::where($where)->order(['cart_id'=>'desc'])->paginate(10, $count);
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
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('用户不能为空',null,101);
            }
            $data['goods_id'] = (int) $this->request->param('goods_id');
            if(empty($data['goods_id'])){
                $this->error('商品不能为空',null,101);
            }
            $data['sku_id'] = (int) $this->request->param('sku_id');
            if(empty($data['sku_id'])){
                $this->error('SKU不能为空',null,101);
            }
            $data['num'] = (int) $this->request->param('num');
            if(empty($data['num'])){
                $this->error('数量不能为空',null,101);
            }
            
            
            $CartModel = new CartModel();
            $CartModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $cart_id = (int)$this->request->param('cart_id');
         $CartModel = new CartModel();
         if(!$detail = $CartModel->get($cart_id)){
             $this->error('请选择要编辑的购物车',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在购物车");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('用户不能为空',null,101);
            }
            $data['goods_id'] = (int) $this->request->param('goods_id');
            if(empty($data['goods_id'])){
                $this->error('商品不能为空',null,101);
            }
            $data['sku_id'] = (int) $this->request->param('sku_id');
            if(empty($data['sku_id'])){
                $this->error('SKU不能为空',null,101);
            }
            $data['num'] = (int) $this->request->param('num');
            if(empty($data['num'])){
                $this->error('数量不能为空',null,101);
            }

            
            $CartModel = new CartModel();
            $CartModel->save($data,['cart_id'=>$cart_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $cart_id = (int)$this->request->param('cart_id');
         $CartModel = new CartModel();
       
        if(!$detail = $CartModel->find($cart_id)){
            $this->error("不存在该购物车",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该购物车', null, 101);
        }
        $CartModel->where(['cart_id'=>$cart_id])->delete();
        $this->success('操作成功');
    }
   
}