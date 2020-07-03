<?php
namespace app\miniapp\controller\shop;
use app\common\model\shop\ContentModel;
use app\miniapp\controller\Common;
use app\common\model\shop\CategoryModel;
class Category extends Common {
    
    public function index() {
        // 获取二级分类
        $CategoryModel = new CategoryModel();
        $category = $CategoryModel->where(['member_miniapp_id' => $this->miniapp_id])->order("orderby desc")->select();
        $tree = [];
        foreach ($category as $val) {
            $tree[$val->category_id] = [
                'pid' => $val->pid,
                'category_id' => $val->category_id,
                'category_name' => $val->type_name,
                'check' => false,
                'is_show' => 0,
            ];
            $tree[$val->category_id]['children'] = [];
        }
        foreach ($tree as $k => $item) {
            if ($item['pid'] != 0) {
                $tree[$item['pid']]['children'][] = &$tree[$k];
                unset($tree[$k]);
            }
        }
       $this->assign('tree',$tree);
        return $this->fetch();
    }
    
    public function create() {
        $category_id = (int) $this->request->param('pid');
        $CategoryModel = new CategoryModel();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['type_name'] = $this->request->param('type_name');  
            if(empty($data['type_name'])){
                $this->error('分类名称不能为空',null,101);
            }
            $data['pid'] = (int) $this->request->param('pid');
            $data['orderby'] = (int) $this->request->param('orderby');
            $CategoryModel->save($data);
            if($category_id != 0){
                $this->success('操作成功',null,100);
            }else{
                $this->success('操作成功',null);
            }

        } else {
            $this->assign('category_id',$category_id);
            $pid = $CategoryModel->where(['member_miniapp_id'=>$this->miniapp_id,'pid'=>0])->select();
            $this->assign('pid',$pid);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $category_id = (int)$this->request->param('category_id');
         $CategoryModel = new CategoryModel();
         if(!$detail = $CategoryModel->get($category_id)){
             $this->error('请选择要编辑的商品分类',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在商品分类");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['type_name'] = $this->request->param('type_name');  
            if(empty($data['type_name'])){
                $this->error('分类名称不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            $CategoryModel = new CategoryModel();
            $CategoryModel->save($data,['category_id'=>$category_id]);
            $this->success('操作成功',null,100);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    public function delete() {
        $category_id = (int)$this->request->param('category_id');
        $CategoryModel = new CategoryModel();
        if(!$detail = $CategoryModel->find($category_id)){
            $this->error("不存在该商品分类",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该商品分类', null, 101);
        }
        $CategoryModel->where(['category_id'=>$category_id,])->delete();
        $CategoryModel->where(['pid'=>$category_id])->delete();
        $this->success('操作成功');
    }
   
}