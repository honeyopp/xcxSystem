<?php
namespace app\admin\controller\order;
use app\admin\controller\Common;
use app\common\model\order\OrderModel;
class Order extends Common {
    
    public function index() {
        $where = $search = [];
        $count = OrderModel::where($where)->count();
        $list = OrderModel::where($where)->order(['order_id'=>'desc'])->paginate(10, $count);
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
            $data['member_id'] = (int) $this->request->param('member_id');
            if(empty($data['member_id'])){
                $this->error('用户id不能为空',null,101);
            }
            $data['miniapp_id'] = (int) $this->request->param('miniapp_id');
            if(empty($data['miniapp_id'])){
                $this->error('小程序id不能为空',null,101);
            }
            $data['expire_time'] = (int) $this->request->param('expire_time');
            if(empty($data['expire_time'])){
                $this->error('过期时间不能为空',null,101);
            }
            
            
            $OrderModel = new OrderModel();
            $OrderModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $order_id = (int)$this->request->param('order_id');
         $OrderModel = new OrderModel();
         if(!$detail = $OrderModel->get($order_id)){
             $this->error('请选择要编辑的订单管理',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_id'] = (int) $this->request->param('member_id');
            if(empty($data['member_id'])){
                $this->error('用户id不能为空',null,101);
            }
            $data['miniapp_id'] = (int) $this->request->param('miniapp_id');
            if(empty($data['miniapp_id'])){
                $this->error('小程序id不能为空',null,101);
            }
            $data['expire_time'] = (int) $this->request->param('expire_time');
            if(empty($data['expire_time'])){
                $this->error('过期时间不能为空',null,101);
            }

            
            $OrderModel = new OrderModel();
            $OrderModel->save($data,['order_id'=>$order_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        if($this->request->method() == 'POST'){
             $order_id = $_POST['order_id'];
        }else{
            $order_id = $this->request->param('order_id');
        }
        $data = [];
        if (is_array($order_id)) {
            foreach ($order_id as $k => $val) {
                $order_id[$k] = (int) $val;
            }
            $data = $order_id;
        } else {
            $data[] = $order_id;
        }
        if (!empty($data)) {
            $OrderModel = new OrderModel();
            $OrderModel->where(array('order_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}