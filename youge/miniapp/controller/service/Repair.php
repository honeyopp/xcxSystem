<?php
namespace app\miniapp\controller\service;
use app\common\model\miniapp\MiniappModel;
use app\common\model\service\CategoryModel;
use app\common\model\service\RepairskuModel;
use app\miniapp\controller\Common;
use app\common\model\service\RepairModel;
class Repair extends Common {
    
    public function index() {
        $where = $search = [];
        $search['category_id'] = (int)$this->request->param('category_id');
        if (!empty($search['category_id'])) {
            $where['category_id'] = $search['category_id'];
        }
                $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title|title2'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $MiniappModel = new MiniappModel();
        $detail =  $MiniappModel->select();
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = RepairModel::where($where)->count();
        $list = RepairModel::where($where)->order(['repair_id'=>'desc'])->paginate(10, $count);
        $categoryIds = [];
        foreach ($list as $val){
            $categoryIds[$val->category_id] = $val->category_id;
        }
        $CategoryModel = new CategoryModel();
        $category = $CategoryModel->itemsByIds($categoryIds);
        $this->assign('category',$category);
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
            $data['photo'] = (string) $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('列表图片不能为空',null,101);
            }
            $data['category_id'] = (int) $this->request->param('category_id');
            if(empty($data['category_id'])){
                $this->error('分类不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['title2'] = $this->request->param('title2');  
            if(empty($data['title2'])){
                $this->error('副标题不能为空',null,101);
            }
            $data['price'] = ((int) $this->request->param('price')) * 100;
            if(empty($data['price'])){
                $this->error('预约价格不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('介绍不能为空',null,101);
            }
            $data['is_hot'] = (int) $this->request->param('is_hot');
            $data['orderby'] = (int) $this->request->param('orderby');
            $types = $_POST['data'];
            foreach ($types['price'] as $key => $val) {
                if (empty($types['name'][$key]) || empty($types['price'][$key]) || empty($types['hd_price'][$key])) {
                    $this->error('服务项目有空值', null, 101);
                }
            }
            if (empty($types)) {
                $this->error('至少填写一个服务项目', null, 'json');
            }
            $RepairModel = new RepairModel();
            $RepairModel->save($data);
            $types = empty($_POST['data']) ? '' : $_POST['data'];
            $type_data = [];
            foreach ($types['price'] as $key => $val) {
                if (empty($types['name'][$key]) || empty($types['price'][$key]) || empty($types['hd_price'][$key])) {
                    $this->error('服务项目有空值', null, 101);
                } else {
                    $type_data[] = [
                        'repair_id' => $RepairModel->repair_id,
                        'member_miniapp_id' => $this->miniapp_id,
                        'name' => $types['name'][$key],
                        'price' => (int)$types['price'][$key] ,
                        'hd_price' => (int)$types['hd_price'][$key],
                    ];
                }
            }
            $RepairskuModel = new RepairskuModel();
            $RepairskuModel->saveAll($type_data);
            $this->success('操作成功',null);
        } else {
            $CategoryModel = new CategoryModel();
            $category =  $CategoryModel->where(['member_miniapp_id'=>$this->miniapp_id,'type'=>1])->select();
            $this->assign('category',$category);
            return $this->fetch();
        }
    }
    public function edit(){
         $repair_id = (int)$this->request->param('repair_id');
         $RepairModel = new RepairModel();
         if(!$detail = $RepairModel->get($repair_id)){
             $this->error('请选择要编辑的维修类管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在维修类管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('列表图片不能为空',null,101);
            }
            $data['category_id'] = (int) $this->request->param('category_id');
            if(empty($data['category_id'])){
                $this->error('分类不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['title2'] = $this->request->param('title2');  
            if(empty($data['title2'])){
                $this->error('副标题不能为空',null,101);
            }
            $data['price'] = ((int) $this->request->param('price')) * 100;
            if(empty($data['price'])){
                $this->error('预约价格不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('介绍不能为空',null,101);
            }
            $data['is_hot'] = (int) $this->request->param('is_hot');
            $data['orderby'] = (int) $this->request->param('orderby');
            $types = $_POST['data'];
             foreach ($types['price'] as $key => $val) {
                 if (empty($types['name'][$key]) || empty($types['price'][$key]) || empty($types['hd_price'][$key])) {
                     $this->error('服务项目有空值', null, 101);
                 }
             }
             if (empty($types)) {
                 $this->error('至少填写一个服务项目', null, 'json');
             }
             $type_data = [];
             foreach ($types['price'] as $key => $val) {
                 if (empty($types['name'][$key]) || empty($types['price'][$key]) || empty($types['hd_price'][$key])) {
                     $this->error('服务项目有空值', null, 101);
                 } else {
                     $type_data[] = [
                         'repair_id' => $repair_id,
                         'member_miniapp_id' => $this->miniapp_id,
                         'name' => $types['name'][$key],
                         'price' => (int)$types['price'][$key] ,
                         'hd_price' => (int)$types['hd_price'][$key],
                     ];
                 }
             }
             $RepairskuModel = new RepairskuModel();
             $RepairskuModel->where(['repair_id'=>$repair_id])->delete();
             $RepairskuModel->saveAll($type_data);
            $RepairModel = new RepairModel();
            $RepairModel->save($data,['repair_id'=>$repair_id]);
            $this->success('操作成功',null);
         }else{
             $RepairskuModel = new RepairskuModel();
             $sku = $RepairskuModel->where(['repair_id'=>$repair_id])->select();
             $this->assign('sku',$sku);
             $CategoryModel = new CategoryModel();
             $category =  $CategoryModel->where(['member_miniapp_id'=>$this->miniapp_id,'type'=>1])->select();
             $this->assign('category',$category);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $repair_id = (int)$this->request->param('repair_id');
         $RepairModel = new RepairModel();
       
        if(!$detail = $RepairModel->find($repair_id)){
            $this->error("不存在该维修类管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该维修类管理', null, 101);
        }
        $RepairModel->where(['repair_id'=>$repair_id])->delete();
        $this->success('操作成功');
    }
   
}