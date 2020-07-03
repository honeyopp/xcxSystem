<?php
namespace app\miniapp\controller\publicuse;
use app\miniapp\controller\Common;
use app\common\model\publicuse\CategoryModel;
class Category extends Common {
    public function index() {
        $where = $search = [];
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CategoryModel::where($where)->count();
        $list = CategoryModel::where($where)->order(['category_id'=>'desc'])->paginate(10, $count);
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
            $data['category_name'] =  $this->request->param('category_name');
            if(empty($data['category_name'])){
                $this->error('分类名称不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');
            $data['color'] = $this->request->param('color');
            $data['orderby'] = (int) $this->request->param('orderby');
            $CategoryModel = new CategoryModel();
            $CategoryModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    public function edit(){
         $category_id = (int)$this->request->param('category_id');
         $CategoryModel = new CategoryModel();
         if(!$detail = $CategoryModel->get($category_id)){
             $this->error('请选择要编辑的分类设置',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在分类设置");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['category_name'] =  $this->request->param('category_name');
            if(empty($data['category_name'])){
                $this->error('分类名称不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图标不能为空',null,101);
            }
            $data['color'] = $this->request->param('color');  
            if(empty($data['color'])){
                $this->error('背景颜色不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }

            
            $CategoryModel = new CategoryModel();
            $CategoryModel->save($data,['category_id'=>$category_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    public function delete() {
   
        $category_id = (int)$this->request->param('category_id');
         $CategoryModel = new CategoryModel();
       
        if(!$detail = $CategoryModel->find($category_id)){
            $this->error("不存在该分类设置",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该分类设置', null, 101);
        }
        $CategoryModel->where(['category_id'=>$category_id])->delete();
        $this->success('操作成功');
    }
}