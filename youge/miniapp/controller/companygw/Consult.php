<?php
namespace app\miniapp\controller\companygw;
use app\miniapp\controller\Common;
use app\common\model\companygw\ConsultModel;
class Consult extends Common {
    
    public function index() {
        $where = $search = [];
        $search['product_name'] = $this->request->param('product_name');
        if (!empty($search['product_name'])) {
            $where['product_name'] = array('LIKE', '%' . $search['product_name'] . '%');
        }
        
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        
        $search['tel'] = $this->request->param('tel');
        if (!empty($search['tel'])) {
            $where['tel'] = array('LIKE', '%' . $search['tel'] . '%');
        }

        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ConsultModel::where($where)->count();
        $list = ConsultModel::where($where)->order(['consult_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    

    

    
    public function delete() {
   
        $consult_id = (int)$this->request->param('consult_id');
         $ConsultModel = new ConsultModel();
       
        if(!$detail = $ConsultModel->find($consult_id)){
            $this->error("不存在该用户咨询",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该用户咨询', null, 101);
        }
        $ConsultModel->where(['consult_id'=>$consult_id])->delete();
        $this->success('操作成功');
    }
   
}