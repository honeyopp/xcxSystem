<?php
namespace app\miniapp\controller\hair;
use app\common\model\hair\PhotoModel;
use app\miniapp\controller\Common;
use app\common\model\hair\WorksModel;
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
            $imgs = $_POST['imgs'] ? $_POST['imgs'] : [];
            if(empty($imgs)){
                $this->error('请上传图片',null,101);
            }
            $data['photo'] = $imgs[0];
            $data['num'] = count($imgs);
            $WorksModel = new WorksModel();
            $WorksModel->save($data);
            $data2 = [];
            $works_id = $WorksModel->works_id;
            foreach ($imgs as $val){
                $data2[] = [
                    'member_miniapp_id' => $this->miniapp_id,
                    'works_id' => $works_id,
                    'photo'  => $val,
                ];
            }
            $PhotoModel = new PhotoModel();
            $PhotoModel->saveAll($data2);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $works_id = (int)$this->request->param('works_id');
         $WorksModel = new WorksModel();
         if(!$detail = $WorksModel->get($works_id)){
             $this->error('请选择要编辑的经典案例',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在经典案例");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
             $imgs = $_POST['imgs'] ? $_POST['imgs'] : [];
             if(empty($imgs)){
                 $this->error('请上传图片',null,101);
             }
             $data['photo'] = $imgs[0];
             $data['num'] = count($imgs);
             $PhotoModel = new PhotoModel();
             $PhotoModel->where(['works_id'=>$works_id])->delete();
             $data2 = [];
             foreach ($imgs as $val){
                 $data2[] = [
                     'member_miniapp_id' => $this->miniapp_id,
                     'works_id' => $works_id,
                     'photo'  => $val,
                 ];
             }

             $PhotoModel->saveAll($data2);
            $WorksModel = new WorksModel();
            $WorksModel->save($data,['works_id'=>$works_id]);
            $this->success('操作成功',null);
         }else{
             $where['member_miniapp_id'] = $this->miniapp_id;
             $where['works_id'] = $works_id;
             $PhotoModel = new PhotoModel();
             $photo = $PhotoModel->where($where)->limit(0,50)->select();
             $this->assign('photo',$photo);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $works_id = (int)$this->request->param('works_id');
         $WorksModel = new WorksModel();
       
        if(!$detail = $WorksModel->find($works_id)){
            $this->error("不存在该经典案例",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该经典案例', null, 101);
        }
        $WorksModel->where(['works_id'=>$works_id])->delete();
        $this->success('操作成功');
    }
   
}