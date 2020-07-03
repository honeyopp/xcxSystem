<?php
namespace app\miniapp\controller\company;
use app\miniapp\controller\Common;
use app\common\model\company\CatModel;
class Cat extends Common {
    
    public function index() {
        $where = $search = [];
        $search['cat_name'] = $this->request->param('cat_name');
        if (!empty($search['cat_name'])) {
            $where['cat_name'] = array('LIKE', '%' . $search['cat_name'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CatModel::where($where)->count();
        $list = CatModel::where($where)->order(['cat_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    
    public function create() {
        $CatModel = new CatModel();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['cat_name'] = $this->request->param('cat_name');  
            if(empty($data['cat_name'])){
                $this->error('分类名称不能为空',null,101);
            }
            $data['pid'] = (int) $this->request->param('pid');

            $CatModel->save($data);
            $this->success('操作成功',null);
        } else {
            $cats = $CatModel->where(['member_miniapp_id'=>$this->miniapp_id,'pid'=>0])->select();
            $this->assign('cats',$cats);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $cat_id = (int)$this->request->param('cat_id');
         $CatModel = new CatModel();
         if(!$detail = $CatModel->get($cat_id)){
             $this->error('请选择要编辑的商家分类',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在商家分类");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['cat_name'] = $this->request->param('cat_name');  
            if(empty($data['cat_name'])){
                $this->error('分类名称不能为空',null,101);
            }
            $data['pid'] = (int) $this->request->param('pid');

            
            $CatModel = new CatModel();
            $CatModel->save($data,['cat_id'=>$cat_id]);
            $this->success('操作成功',null);
         }else{
             $cats = $CatModel->where(['member_miniapp_id'=>$this->miniapp_id,'pid'=>0])->select();
             $this->assign('cats',$cats);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $cat_id = (int)$this->request->param('cat_id');
         $CatModel = new CatModel();
       
        if(!$detail = $CatModel->find($cat_id)){
            $this->error("不存在该商家分类",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该商家分类', null, 101);
        }
        $CatModel->where(['cat_id'=>$cat_id])->delete();
        $this->success('操作成功');
    }
   
}