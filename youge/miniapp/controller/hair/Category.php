<?php
namespace app\miniapp\controller\hair;
use app\common\model\hair\PriceModel;
use app\miniapp\controller\Common;
use app\common\model\hair\CategoryModel;
class Category extends Common {
    
    public function index() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        
        
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
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('分类名称不能为空',null,101);
            }
            $data['describe'] = $this->request->param('describe');  
            if(empty($data['describe'])){
                $this->error('基本描述不能为空',null,101);
            }
            $data['ico'] = $this->request->param('ico');  
            if(empty($data['ico'])){
                $this->error('图标不能为空',null,101);
            }
            $types = $_POST['data'] ? $_POST['data'] : [];
            if (empty($types)) {
                $this->error('至少填写一个项目', null, 'json');
            }
            $data2 = [];
            $i = 0;
            foreach ($types['name'] as $key => $val) {
                $i++;
                if (empty($types['name'][$key]) || empty($types['price'][$key]) || empty($types['vip_price'][$key])) {
                    $this->error("第{$i}个项目信息不全", null, 101);
                }
                $data2[] = [
                    'name' => $types['name'][$key],
                    'price' => $types['price'][$key],
                    'vip_price' => $types['vip_price'][$key],
                    'member_miniapp_id' => $this->miniapp_id,
                ];
            }
            $CategoryModel = new CategoryModel();
            $CategoryModel->save($data);
            $category_id = $CategoryModel->category_id;
            foreach ($data2 as $key=>$val){
                    $data2[$key]['category_id'] = $category_id;
            }
            $PriceModel = new PriceModel();
            $PriceModel->saveAll($data2);
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
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('分类名称不能为空',null,101);
            }
            $data['describe'] = $this->request->param('describe');  
            if(empty($data['describe'])){
                $this->error('基本描述不能为空',null,101);
            }
            $data['ico'] = $this->request->param('ico');  
            if(empty($data['ico'])){
                $this->error('图标不能为空',null,101);
            }
             $types = $_POST['data'] ? $_POST['data'] : [];
             if (empty($types)) {
                 $this->error('至少填写一个项目', null, 'json');
             }
             $data2 = [];
             $i = 0;
             foreach ($types['name'] as $key => $val) {
                 $i++;
                 if (empty($types['name'][$key]) || empty($types['price'][$key]) || empty($types['vip_price'][$key])) {
                     $this->error("第{$i}个项目信息不全", null, 101);
                 }
                 $data2[] = [
                     'name' => $types['name'][$key],
                     'price' => $types['price'][$key],
                     'vip_price' => $types['vip_price'][$key],
                     'member_miniapp_id' => $this->miniapp_id,
                     'category_id' => $category_id,
                 ];
             }

             $PriceModel = new PriceModel();
             $PriceModel->where(['member_miniapp_id'=>$this->miniapp_id,'category_id'=>$category_id])->delete();
             $PriceModel->saveAll($data2);
            $CategoryModel = new CategoryModel();
            $CategoryModel->save($data,['category_id'=>$category_id]);
            $this->success('操作成功',null);
         }else{
            $PriceModel = new PriceModel();
            $where['member_miniapp_id'] = $this->miniapp_id;
            $where['category_id'] = $category_id;
            $list  = $PriceModel->where($where)->limit(0,50)->select();
            $this->assign('list',$list);
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