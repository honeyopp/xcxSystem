<?php
namespace app\miniapp\controller\job;
use app\common\model\job\CompanyModel;
use app\miniapp\controller\Common;
use app\common\model\job\OrderModel;
class Order extends Common {
    
    public function index() {
        $where = $search = [];
        $search['company_id'] = (int)$this->request->param('company_id');
        if (!empty($search['company_id'])) {
            $where['company_id'] = $search['company_id'];
        }
                $search['price_id'] = (int)$this->request->param('price_id');
        if (!empty($search['price_id'])) {
            $where['price_id'] = $search['price_id'];
        }
                $search['status'] = (int)$this->request->param('status');
        if (!empty($search['status'])) {
            $where['status'] = $search['status'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = OrderModel::where($where)->count();
        $list = OrderModel::where($where)->order(['order_id'=>'desc'])->paginate(10, $count);
        $CompanyIds = [];
        foreach ($list as  $val){
            $CompanyIds[$val->company_id] = $val->company_id;
        }
        $CompanyModel = new CompanyModel();
        $company = $CompanyModel->itemsByIds($CompanyIds);
        $page = $list->render();
        $this->assign('company',$company);
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    

    

    
    public function delete() {
   
        $order_id = (int)$this->request->param('order_id');
         $OrderModel = new OrderModel();
       
        if(!$detail = $OrderModel->find($order_id)){
            $this->error("不存在该VIP出售记录",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该VIP出售记录', null, 101);
        }
        $OrderModel->where(['order_id'=>$order_id])->delete();
        $this->success('操作成功');
    }
   
}