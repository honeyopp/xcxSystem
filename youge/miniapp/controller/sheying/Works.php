<?php
namespace app\miniapp\controller\sheying;
use app\common\model\sheying\CategoryModel;
use app\common\model\sheying\PhotoModel;
use app\miniapp\controller\Common;
use app\common\model\sheying\WorksModel;
class Works extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = WorksModel::where($where)->count();
        $list = WorksModel::where($where)->order(['works_id'=>'desc'])->paginate(10, $count);
        $catIds=  [];
        foreach ($list as $val){
            $catIds[$val->category_id] = $val->category_id;
        }
        $CategoryModel = new CategoryModel();
        $this->assign('category',$CategoryModel->itemsByIds($catIds));
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
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['category_id'] = (int) $this->request->param('category_id');
            if(empty($data['category_id'])){
                $this->error('分类不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            if (empty($_POST['imgs'])) {
                $this->error('请上传图片', null, 101);
            }
            $imgs = $_POST['imgs'];
            $data['photo'] = $imgs[0];
            $WorksModel = new WorksModel();
            $WorksModel->save($data);
            $data2 = [];
            $works_id = $WorksModel->works_id;
            foreach ($imgs as $val) {
                $data2[] = [
                    'member_miniapp_id' => $this->miniapp_id,
                    'works_id' => $works_id,
                    'photo' => $val,
                ];
            }
            $PhotoModel = new PhotoModel();
            $PhotoModel->saveAll($data2);
            $this->success('操作成功',null);
        } else {
            $CategoryModel = new CategoryModel();
            $cate = $CategoryModel->where(['member_miniapp_id'=>$this->miniapp_id])->limit(0,20)->select();
            $this->assign('cate',$cate);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $works_id = (int)$this->request->param('works_id');
         $WorksModel = new WorksModel();
         if(!$detail = $WorksModel->get($works_id)){
             $this->error('请选择要编辑的客片管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在客片管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['category_id'] = (int) $this->request->param('category_id');
            if(empty($data['category_id'])){
                $this->error('分类不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
             if (empty($_POST['imgs'])) {
                 $this->error('请上传图片', null, 101);
             }
             $imgs = $_POST['imgs'];
             $data['photo'] = $imgs[0];
             $data2 = [];
             foreach ($imgs as $val) {
                 $data2[] = [
                     'member_miniapp_id' => $this->miniapp_id,
                     'works_id' => $works_id,
                     'photo' => $val,
                 ];
             }
             $PhotoModel = new PhotoModel();
             $PhotoModel->where(['works_id'=>$works_id])->delete();
             $PhotoModel->saveAll($data2);
            $WorksModel = new WorksModel();
            $WorksModel->save($data,['works_id'=>$works_id]);
            $this->success('操作成功',null);
         }else{
             $PhotoModel = new PhotoModel();
             $photo = $PhotoModel->where(['works_id'=>$works_id])->limit(0,50)->select();
             $this->assign('photo',$photo);
             $CategoryModel = new CategoryModel();
             $cate = $CategoryModel->where(['member_miniapp_id'=>$this->miniapp_id])->limit(0,20)->select();
             $this->assign('cate',$cate);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    public function delete() {
   
        $works_id = (int)$this->request->param('works_id');
         $WorksModel = new WorksModel();
       
        if(!$detail = $WorksModel->find($works_id)){
            $this->error("不存在该客片管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该客片管理', null, 101);
        }
        $WorksModel->where(['works_id'=>$works_id])->delete();
        $this->success('操作成功');
    }
   
}