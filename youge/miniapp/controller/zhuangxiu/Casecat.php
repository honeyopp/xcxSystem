<?php
namespace app\miniapp\controller\zhuangxiu;
use app\miniapp\controller\Common;
use app\common\model\zhuangxiu\CasecatModel;
class Casecat extends Common {
    
    public function index() {
        $where = $search = [];
        $search['cat_name'] = $this->request->param('cat_name');
        if (!empty($search['cat_name'])) {
            $where['cat_name'] = array('LIKE', '%' . $search['cat_name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CasecatModel::where($where)->count();
        $list = CasecatModel::where($where)->order(['cat_id'=>'desc'])->paginate(10, $count);
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
            $data['cat_name'] = $this->request->param('cat_name');  
            if(empty($data['cat_name'])){
                $this->error('分类名称不能为空',null,101);
            }
            
            
            $CasecatModel = new CasecatModel();
            $CasecatModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $cat_id = (int)$this->request->param('cat_id');
         $CasecatModel = new CasecatModel();
         if(!$detail = $CasecatModel->get($cat_id)){
             $this->error('请选择要编辑的效果图分类',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在效果图分类");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['cat_name'] = $this->request->param('cat_name');  
            if(empty($data['cat_name'])){
                $this->error('分类名称不能为空',null,101);
            }

            
            $CasecatModel = new CasecatModel();
            $CasecatModel->save($data,['cat_id'=>$cat_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $cat_id = (int)$this->request->param('cat_id');
         $CasecatModel = new CasecatModel();
       
        if(!$detail = $CasecatModel->find($cat_id)){
            $this->error("不存在该效果图分类",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该效果图分类', null, 101);
        }
        $CasecatModel->where(['cat_id'=>$cat_id])->delete();
        $this->success('操作成功');
    }
   
}