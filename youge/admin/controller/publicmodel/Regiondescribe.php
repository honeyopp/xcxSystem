<?php
namespace app\admin\controller\publicmodel;
use app\admin\controller\Common;
use app\common\model\publicmodel\RegiondescribeModel;
class Regiondescribe extends Common {
    
    public function index() {
        $where = $search = [];

        $count = RegiondescribeModel::where($where)->count();
        $list = RegiondescribeModel::where($where)->order(['describe_id'=>'desc'])->paginate(10, $count);
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
            $data['region_id'] = (int) $this->request->param('region_id');
            if(empty($data['region_id'])){
                $this->error('区域id不能为空',null,101);
            }
            $data['member_miniapp_id'] = (int) $this->request->param('member_miniapp_id');
            if(empty($data['member_miniapp_id'])){
                $this->error('用户小程序id不能为空',null,101);
            }
            $data['describe'] = $this->request->param('describe');  
            if(empty($data['describe'])){
                $this->error('描述不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            
            
            $RegiondescribeModel = new RegiondescribeModel();
            $RegiondescribeModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $describe_id = (int)$this->request->param('describe_id');
         $RegiondescribeModel = new RegiondescribeModel();
         if(!$detail = $RegiondescribeModel->get($describe_id)){
             $this->error('请选择要编辑的区域描述',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['region_id'] = (int) $this->request->param('region_id');
            if(empty($data['region_id'])){
                $this->error('区域id不能为空',null,101);
            }
            $data['member_miniapp_id'] = (int) $this->request->param('member_miniapp_id');
            if(empty($data['member_miniapp_id'])){
                $this->error('用户小程序id不能为空',null,101);
            }
            $data['describe'] = $this->request->param('describe');  
            if(empty($data['describe'])){
                $this->error('描述不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }

            
            $RegiondescribeModel = new RegiondescribeModel();
            $RegiondescribeModel->save($data,['describe_id'=>$describe_id]);
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
            $RegiondescribeModel = new RegiondescribeModel();
            $RegiondescribeModel->where(array('describe_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}