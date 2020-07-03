<?php
namespace app\miniapp\controller\mendian;
use app\miniapp\controller\Common;
use app\common\model\mendian\OrderModel;
class Order extends Common {
    
    public function index() {
        $where = $search = [];
        $search['user_id'] = (int)$this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = $search['user_id'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = OrderModel::where($where)->count();
        $list = OrderModel::where($where)->order(['order_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
}