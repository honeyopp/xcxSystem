<?php
namespace app\admin\controller\miniapp;
use app\admin\controller\Common;
use app\common\model\miniapp\DescribeModel;
use app\common\model\miniapp\MiniappModel;

class Describe extends Common {
    
    public function index() {
        $where = $search = [];
        $miniapp_id = (int) $this->request->param('miniapp_id');
        $MiniappModel = new MiniappModel();
        if(!$miniapp = $MiniappModel->find($miniapp_id)){
            $this->error('请选择模板');
        }
        $where['miniapp_id'] = $miniapp_id;
        $count = DescribeModel::where($where)->count();
        $list = DescribeModel::where($where)->order(['describe_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        $this->assign('miniapp',$miniapp);
        return $this->fetch();
    }
    
    public function create() {
        $miniapp_id = (int) $this->request->param('miniapp_id');
        $MiniappModel = new MiniappModel();
        if(!$miniapp = $MiniappModel->find($miniapp_id)){
            $this->error('请选择模板');
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['miniapp_id'] = $miniapp_id;
            $data['describe'] = $this->request->param('describe');  
            if(empty($data['describe'])){
                $this->error('描述内容不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $DescribeModel = new DescribeModel();
            $DescribeModel->save($data);
            $this->success('操作成功',null);
        } else {
            $this->assign('miniapp',$miniapp);
            return $this->fetch();
        }
    }
    public function edit(){
         $describe_id = (int)$this->request->param('describe_id');
         $DescribeModel = new DescribeModel();
         if(!$detail = $DescribeModel->get($describe_id)){
             $this->error('请选择要编辑的小程序描述',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['miniapp_id'] = (int) $this->request->param('miniapp_id');
            if(empty($data['miniapp_id'])){
                $this->error('小程序不能为空',null,101);
            }
            $data['describe'] = $this->request->param('describe');  
            if(empty($data['describe'])){
                $this->error('描述内容不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }

            
            $DescribeModel = new DescribeModel();
            $DescribeModel->save($data,['describe_id'=>$describe_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    public function delete() {
        if($this->request->method() == 'POST'){
             $describe_id = $_POST['describe_id'];
        }else{
            $describe_id = $this->request->param('describe_id');
        }
        $data = [];
        if (is_array($describe_id)) {
            foreach ($describe_id as $k => $val) {
                $describe_id[$k] = (int) $val;
            }
            $data = $describe_id;
        } else {
            $data[] = $describe_id;
        }
        if (!empty($data)) {
            $DescribeModel = new DescribeModel();
            $DescribeModel->where(array('describe_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}