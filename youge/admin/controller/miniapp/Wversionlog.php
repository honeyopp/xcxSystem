<?php
namespace app\admin\controller\miniapp;
use app\admin\controller\Common;
use app\common\model\miniapp\WversionlogModel;
class Wversionlog extends Common {
    
    public function index() {
        $where = $search = [];

        $count = WversionlogModel::where($where)->count();
        $list = WversionlogModel::where($where)->order(['version_log_id'=>'desc'])->paginate(10, $count);
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
            $data['miniapp_id'] = (int) $this->request->param('miniapp_id');
            if(empty($data['miniapp_id'])){
                $this->error('小程序不能为空',null,101);
            }
            $data['this_version_num'] = $this->request->param('this_version_num');  
            if(empty($data['this_version_num'])){
                $this->error('当前版本号不能为空',null,101);
            }
            $data['describe'] = $this->request->param('describe');  
            if(empty($data['describe'])){
                $this->error('升级内容不能为空',null,101);
            }
            
            
            $WversionlogModel = new WversionlogModel();
            $WversionlogModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $version_log_id = (int)$this->request->param('version_log_id');
         $WversionlogModel = new WversionlogModel();
         if(!$detail = $WversionlogModel->get($version_log_id)){
             $this->error('请选择要编辑的模板升级日志',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['miniapp_id'] = (int) $this->request->param('miniapp_id');
            if(empty($data['miniapp_id'])){
                $this->error('小程序不能为空',null,101);
            }
            $data['this_version_num'] = $this->request->param('this_version_num');  
            if(empty($data['this_version_num'])){
                $this->error('当前版本号不能为空',null,101);
            }
            $data['describe'] = $this->request->param('describe');  
            if(empty($data['describe'])){
                $this->error('升级内容不能为空',null,101);
            }

            
            $WversionlogModel = new WversionlogModel();
            $WversionlogModel->save($data,['version_log_id'=>$version_log_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        if($this->request->method() == 'POST'){
             $version_log_id = $_POST['version_log_id'];
        }else{
            $version_log_id = $this->request->param('version_log_id');
        }
        $data = [];
        if (is_array($version_log_id)) {
            foreach ($version_log_id as $k => $val) {
                $version_log_id[$k] = (int) $val;
            }
            $data = $version_log_id;
        } else {
            $data[] = $version_log_id;
        }
        if (!empty($data)) {
            $WversionlogModel = new WversionlogModel();
            $WversionlogModel->where(array('version_log_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}