<?php
namespace app\miniapp\controller\fitment;
use app\common\model\fitment\ExamplephotoModel;
use app\miniapp\controller\Common;
use app\common\model\fitment\ExampleModel;
class Example extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = $search['title'];
        }
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ExampleModel::where($where)->count();
        $list = ExampleModel::where($where)->order(['example_id'=>'desc'])->paginate(10, $count);
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
            $where['member_miniapp_id'] = $this->miniapp_id;
            $count = ExampleModel::where($where)->count();
            if($count >= 8){
                $this->error('您最多添加8个案例',null,101);
            }
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] =$this->request->param('title');
            $data['orderby'] = (int) $this->request->param('orderby');
            $imgs = $_POST['imgs'];
            $data['photo'] = $imgs[0];
            $data2 = [];
            $ExampleModel = new ExampleModel();
            $ExampleModel->save($data);
            $example_id = $ExampleModel->example_id;
            $ExamplephotoModel = new ExamplephotoModel();
            foreach ($imgs as $val){
                $data2[] = [
                    'member_miniapp_id' => $this->miniapp_id,
                    'example_id' => $example_id,
                    'photo'  => $val,
                ];
            }
            $ExamplephotoModel->saveAll($data2);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $example_id = (int)$this->request->param('example_id');
         $ExampleModel = new ExampleModel();
         if(!$detail = $ExampleModel->get($example_id)){
             $this->error('请选择要编辑的经典案例',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在经典案例");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['title'] =  $this->request->param('title');
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');

             $imgs = $_POST['imgs'];
             $data['photo'] = $imgs[0];
             $data2 = [];
             $ExamplephotoModel = new ExamplephotoModel();
             $ExamplephotoModel->where(['member_miniapp_id'=>$this->miniapp_id,'example_id'=>$example_id])->delete();
             foreach ($imgs as $val){
                 $data2[] = [
                     'member_miniapp_id' => $this->miniapp_id,
                     'example_id' => $example_id,
                     'photo'  => $val,
                 ];
             }
             $ExamplephotoModel->saveAll($data2);
            
            $ExampleModel = new ExampleModel();
            $ExampleModel->save($data,['example_id'=>$example_id]);
            $this->success('操作成功',null);
         }else{
             $ExamplephotoModel = new ExamplephotoModel();
             $where['member_miniapp_id'] = $this->miniapp_id;
             $where['example_id'] = $example_id;
             $photo = $ExamplephotoModel->where($where)->limit(0,50)->select();
             $this->assign('photo',$photo);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $example_id = (int)$this->request->param('example_id');
         $ExampleModel = new ExampleModel();
       
        if(!$detail = $ExampleModel->find($example_id)){
            $this->error("不存在该经典案例",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该经典案例', null, 101);
        }
        $ExampleModel->where(['example_id'=>$example_id])->delete();
        $this->success('操作成功');
    }
   
}