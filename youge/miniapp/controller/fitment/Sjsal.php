<?php
namespace app\miniapp\controller\fitment;
use app\common\model\fitment\DesignerModel;
use app\common\model\fitment\SjsalphotoModel;
use app\miniapp\controller\Common;
use app\common\model\fitment\SjsalModel;
class Sjsal extends Common {
    
    public function index() {
        $designer_id = (int) $this->request->param('designer_id');
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['designer_id'] = $designer_id;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = SjsalModel::where($where)->count();
        $list = SjsalModel::where($where)->order(['example_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('designer_id',$designer_id);
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    
    public function create() {
        $designer_id = (int) $this->request->param('designer_id');
        $DesignerModel = new DesignerModel();
        if(!$detail = $DesignerModel->find($designer_id)){
            $this->error('不存在设计师',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在设计师',null,101);
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('案例标题不能为空',null,101);
            }
            $data['designer_id'] = $designer_id;
            $data['orderby'] = $this->request->param('orderby');
            $imgs = $_POST['imgs'] ? $_POST['imgs']  : [];
            if(empty($imgs)){
                $this->error('请上传案例图片',null,101);
            }
            $data['photo'] = $imgs[0];
            $SjsalModel = new SjsalModel();
            $SjsalModel->save($data);
            $data2 = [];
            $example_id = $SjsalModel->example_id;
            foreach ($imgs as $val){
                $data2[] = [
                    'member_miniapp_id' => $this->miniapp_id,
                    'example_id' => $example_id,
                    'photo'  => $val,
                ];
            }
            $SjsalphotoModel = new SjsalphotoModel();
            $SjsalphotoModel->saveAll($data2);
            $this->success('操作成功',null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $example_id = (int)$this->request->param('example_id');
         $SjsalModel = new SjsalModel();
         if(!$detail = $SjsalModel->get($example_id)){
             $this->error('不存在案例',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在案例");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('案例标题不能为空',null,101);
            }
            $data['orderby'] = $this->request->param('orderby');
             $imgs = $_POST['imgs'];
             $data['photo'] = $imgs[0];
             $data2 = [];
             $SjsalphotoModel = new SjsalphotoModel();
             $SjsalphotoModel->where(['member_miniapp_id'=>$this->miniapp_id,'example_id'=>$example_id])->delete();
             foreach ($imgs as $val){
                 $data2[] = [
                     'member_miniapp_id' => $this->miniapp_id,
                     'example_id' => $example_id,
                     'photo'  => $val,
                 ];
             }
             $SjsalphotoModel->saveAll($data2);
            $SjsalModel = new SjsalModel();
            $SjsalModel->save($data,['example_id'=>$example_id]);
            $this->success('操作成功',null);
         }else{
             $where['member_miniapp_id'] = $this->miniapp_id;
             $where['example_id'] = $example_id;
             $SjsalphotoModel = new SjsalphotoModel();
             $photo = $SjsalphotoModel->where($where)->limit(0,50)->select();
             $this->assign('photo',$photo);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $example_id = (int)$this->request->param('example_id');
         $SjsalModel = new SjsalModel();
       
        if(!$detail = $SjsalModel->find($example_id)){
            $this->error("不存在该设计师",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该设计师', null, 101);
        }
        $SjsalModel->where(['example_id'=>$example_id])->delete();
        $this->success('操作成功');
    }
   
}