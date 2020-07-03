<?php
namespace app\miniapp\controller\fitment;
use app\common\model\fitment\DetailphotoModel;
use app\common\model\fitment\WorkModel;
use app\miniapp\controller\Common;
use app\common\model\fitment\DetailModel;
class Detail extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $work_id = (int) $this->request->param('work_id');
        $where['work_id'] = $work_id;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = DetailModel::where($where)->count();
        $list = DetailModel::where($where)->order(['detail_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('work_id',$work_id);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    public function create() {
        $WorkModel = new WorkModel();
        $work_id = (int) $this->request->param('work_id');
        if(!$detail = $WorkModel->find($work_id)){
            $this->error('不存在施工',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在施工',null,101);
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['work_id'] = $work_id;
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('项目描述不能为空',null,101);
            }
            $data['num'] = (int) $this->request->param('num');
            if(empty($data['num'])){
                $this->error('施工人数不能为空',null,101);
            }
            $data['boss'] = $this->request->param('boss');  
            if(empty($data['boss'])){
                $this->error('监管不能为空',null,101);
            }
            $data['progress'] = $this->request->param('progress');  
            if(empty($data['progress'])){
                $this->error('进度不能为空',null,101);
            }
            $data['orderby'] = $this->request->param('orderby');
            $DetailModel = new DetailModel();
            $DetailModel->save($data);
            $detail_id = $DetailModel->detail_id;
            $imgs = $_POST['imgs'];
            $data['photo'] = $imgs[0];
            $data2 = [];
            $DetailphotoModel = new DetailphotoModel();
            foreach ($imgs as $val){
                $data2[] = [
                    'member_miniapp_id' => $this->miniapp_id,
                    'detail_id' => $detail_id,
                    'photo'  => $val,
                ];
            }
            $DetailphotoModel->saveAll($data2);
            $this->success('操作成功',null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $detail_id = (int)$this->request->param('detail_id');
         $DetailModel = new DetailModel();
         if(!$detail = $DetailModel->get($detail_id)){
             $this->error('请选择要编辑的工地项目',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在工地项目");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('项目描述不能为空',null,101);
            }
            $data['num'] = (int) $this->request->param('num');
            if(empty($data['num'])){
                $this->error('施工人数不能为空',null,101);
            }
            $data['boss'] = $this->request->param('boss');  
            if(empty($data['boss'])){
                $this->error('监管不能为空',null,101);
            }
            $data['progress'] = $this->request->param('progress');  
            if(empty($data['progress'])){
                $this->error('进度不能为空',null,101);
            }
            $data['orderby'] = $this->request->param('orderby');  
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $DetailModel = new DetailModel();
             $imgs = $_POST['imgs'];
             $data2 = [];
             $DetailphotoModel = new DetailphotoModel();
              $DetailphotoModel->where(['detail_id'=>$detail_id,'member_miniapp_id'=>$this->miniapp_id])->delete();
             foreach ($imgs as $val){
                 $data2[] = [
                     'member_miniapp_id' => $this->miniapp_id,
                     'detail_id' => $detail_id,
                     'photo'  => $val,
                 ];
             }
             $DetailphotoModel->saveAll($data2);
            $DetailModel->save($data,['detail_id'=>$detail_id]);
            $this->success('操作成功',null);
         }else{
            $DetailphotoModel  = new DetailphotoModel();
            $where['member_miniapp_id'] = $this->miniapp_id;
            $where['detail_id'] = $detail_id;
            $photo = $DetailphotoModel->where($where)->limit(0,50)->select();
            $this->assign('photo',$photo);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    public function delete() {
   
        $detail_id = (int)$this->request->param('detail_id');
         $DetailModel = new DetailModel();
        if(!$detail = $DetailModel->find($detail_id)){
            $this->error("不存在该工地项目",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该工地项目', null, 101);
        }
        $DetailModel->where(['detail_id'=>$detail_id])->delete();
        $this->success('操作成功');
    }
   
}