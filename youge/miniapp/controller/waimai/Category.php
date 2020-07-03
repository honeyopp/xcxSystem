<?php
namespace app\miniapp\controller\waimai;
use app\miniapp\controller\Common;
use app\common\model\waimai\CategoryModel;
class Category extends Common {
    
    public function index() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CategoryModel::where($where)->count();
        $list = CategoryModel::where($where)->order(['cat_id'=>'desc'])->paginate(10, $count);
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
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('分类不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            
            
            $CategoryModel = new CategoryModel();
            $CategoryModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $cat_id = (int)$this->request->param('cat_id');
         $CategoryModel = new CategoryModel();
         if(!$detail = $CategoryModel->get($cat_id)){
             $this->error('请选择要编辑的外卖分类',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在外卖分类");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('分类不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');

            
            $CategoryModel = new CategoryModel();
            $CategoryModel->save($data,['cat_id'=>$cat_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $cat_id = (int)$this->request->param('cat_id');
         $CategoryModel = new CategoryModel();
       
        if(!$detail = $CategoryModel->find($cat_id)){
            $this->error("不存在该外卖分类",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该外卖分类', null, 101);
        }
        $CategoryModel->where(['cat_id'=>$cat_id])->delete();
        $this->success('操作成功');
    }
   
}