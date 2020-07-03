<?php
namespace app\admin\controller\member;
use app\admin\controller\Common;
use app\common\model\member\MoneylogModel;
class Moneylog extends Common {
    
    public function index() {
        $where = $search = [];

        $count = MoneylogModel::where($where)->count();
        $list = MoneylogModel::where($where)->order(['log_id'=>'desc'])->paginate(10, $count);
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
                $this->error('用户不能为空',null,101);
            }
            $data['log_type'] = (int) $this->request->param('log_type');
            if(empty($data['log_type'])){
                $this->error('消费类型不能为空',null,101);
            }
            $data['money'] = (int) $this->request->param('money');
            if(empty($data['money'])){
                $this->error('消费金额不能为空',null,101);
            }
            $data['this_money'] = (int) $this->request->param('this_money');
            if(empty($data['this_money'])){
                $this->error('当前剩余不能为空',null,101);
            }
            $data['is_consume'] = (int) $this->request->param('is_consume');
            if(empty($data['is_consume'])){
                $this->error('支出收入不能为空',null,101);
            }
            
            
            $MoneylogModel = new MoneylogModel();
            $MoneylogModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $log_id = (int)$this->request->param('log_id');
         $MoneylogModel = new MoneylogModel();
         if(!$detail = $MoneylogModel->get($log_id)){
             $this->error('请选择要编辑的用户余额log',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_id'] = (int) $this->request->param('member_id');
            if(empty($data['member_id'])){
                $this->error('用户不能为空',null,101);
            }
            $data['log_type'] = (int) $this->request->param('log_type');
            if(empty($data['log_type'])){
                $this->error('消费类型不能为空',null,101);
            }
            $data['money'] = (int) $this->request->param('money');
            if(empty($data['money'])){
                $this->error('消费金额不能为空',null,101);
            }
            $data['this_money'] = (int) $this->request->param('this_money');
            if(empty($data['this_money'])){
                $this->error('当前剩余不能为空',null,101);
            }
            $data['is_consume'] = (int) $this->request->param('is_consume');
            if(empty($data['is_consume'])){
                $this->error('支出收入不能为空',null,101);
            }

            
            $MoneylogModel = new MoneylogModel();
            $MoneylogModel->save($data,['log_id'=>$log_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        if($this->request->method() == 'POST'){
             $log_id = $_POST['log_id'];
        }else{
            $log_id = $this->request->param('log_id');
        }
        $data = [];
        if (is_array($log_id)) {
            foreach ($log_id as $k => $val) {
                $log_id[$k] = (int) $val;
            }
            $data = $log_id;
        } else {
            $data[] = $log_id;
        }
        if (!empty($data)) {
            $MoneylogModel = new MoneylogModel();
            $MoneylogModel->where(array('log_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}