<?php
namespace app\miniapp\controller\shop;
use app\miniapp\controller\Common;
use app\common\model\shop\TypeModel;
class Type extends Common {
    
    public function index() {
        $where = $search = [];

        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = TypeModel::where($where)->count();
        $list = TypeModel::where($where)->order(['type_id'=>'desc'])->paginate(10, $count);
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
            
            
            $TypeModel = new TypeModel();
            $TypeModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $type_id = (int)$this->request->param('type_id');
         $TypeModel = new TypeModel();
         if(!$detail = $TypeModel->get($type_id)){
             $this->error('请选择要编辑的规格',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在规格");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;

            
            $TypeModel = new TypeModel();
            $TypeModel->save($data,['type_id'=>$type_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $type_id = (int)$this->request->param('type_id');
         $TypeModel = new TypeModel();
       
        if(!$detail = $TypeModel->find($type_id)){
            $this->error("不存在该规格",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该规格', null, 101);
        }
        $TypeModel->where(['type_id'=>$type_id])->delete();
        $this->success('操作成功');
    }
   
}