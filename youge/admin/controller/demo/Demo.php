<?php
namespace app\admin\controller\demo;
use app\admin\controller\Common;
use app\common\model\demo\DemoModel;
class Demo extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        
                $search['details'] = (int)$this->request->param('details');
        if (!empty($search['details'])) {
            $where['details'] = $search['details'];
        }
        
        $count = DemoModel::where($where)->count();
        $list = DemoModel::where($where)->order(['demo_id'=>'desc'])->paginate(10, $count);
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
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['orderby'] = $this->request->param('orderby');  
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['photos'] =  $this->request->param('photos','','SecurityEditorHtml');
            if(empty($data['photos'])){
                $this->error('111不能为空',null,101);
            }
            $data['details'] = (int) $this->request->param('details');
            if(empty($data['details'])){
                $this->error('222不能为空',null,101);
            }
            $data['cat_id'] = $this->request->param('cat_id');  
            $data['is_show'] = $this->request->param('is_show');  
            $data['is_index'] = $this->request->param('is_index');  
            $data['add_time'] = (int) strtotime($this->request->param('add_time'));
            $data['add_ip'] = (int) strtotime($this->request->param('add_ip'));
            
            
            $DemoModel = new DemoModel();
            $DemoModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $demo_id = (int)$this->request->param('demo_id');
         $DemoModel = new DemoModel();
         if(!$detail = $DemoModel->get($demo_id)){
             $this->error('请选择要编辑的demo',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['orderby'] = $this->request->param('orderby');  
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['photos'] =  $this->request->param('photos','','SecurityEditorHtml');
            if(empty($data['photos'])){
                $this->error('111不能为空',null,101);
            }
            $data['details'] = (int) $this->request->param('details');
            if(empty($data['details'])){
                $this->error('222不能为空',null,101);
            }
            $data['cat_id'] = $this->request->param('cat_id');  
            $data['is_show'] = $this->request->param('is_show');  
            $data['is_index'] = $this->request->param('is_index');  
            $data['add_time'] = (int) strtotime($this->request->param('add_time'));
            $data['add_ip'] = (int) strtotime($this->request->param('add_ip'));

            
            $DemoModel = new DemoModel();
            $DemoModel->save($data,['demo_id'=>$demo_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        if($this->request->method() == 'POST'){
             $demo_id = $_POST['demo_id'];
        }else{
            $demo_id = $this->request->param('demo_id');
        }
        $data = [];
        if (is_array($demo_id)) {
            foreach ($demo_id as $k => $val) {
                $demo_id[$k] = (int) $val;
            }
            $data = $demo_id;
        } else {
            $data[] = $demo_id;
        }
        if (!empty($data)) {
            $DemoModel = new DemoModel();
            $DemoModel->where(array('demo_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}