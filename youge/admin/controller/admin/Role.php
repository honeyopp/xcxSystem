<?php
namespace app\admin\controller\admin;
use app\admin\controller\Common;
use app\common\model\admin\RoleModel;
use think\Config;
class Role extends Common{
    
    public function index(){
        $where = $search = [];
        $search['role_name'] = $this->request->param('role_name');
        if (!empty($search['role_name'])) {
            $where['role_name'] = array('LIKE', '%' . $search['role_name'] . '%');
        }
        $count = RoleModel::where($where)->count();
        $list = RoleModel::where($where)->order(['add_time'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    public function delete() {
        if($this->request->method() == 'POST'){
             $role_id = empty($_POST['role_id']) ? array() : $_POST['role_id'];
        }else{
            $role_id = $this->request->param('role_id');
        }
        $data = [];
        if (is_array($role_id)) {
            foreach ($role_id as $k => $val) {
                $role_id[$k] = (int) $val;
            }
            $data = $role_id;
        } else {
            $data[] = $role_id;
        }
        if (!empty($data)) {
            $RoleModel = new RoleModel();
            $RoleModel->where(array('role_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
    public function create() {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['role_name'] = $this->request->param('role_name');
            if(empty($data['role_name'])){
                $this->error('请填写权限组名称！',null,101);
            }
            $RoleModel = new RoleModel();
            $RoleModel->save($data);
            $this->success('操作成功',null,100); //100代表关闭掉弹出层
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $role_id = $this->request->param('role_id');
         $RoleModel = new RoleModel();
         if(!$detail = $RoleModel->get($role_id)){
             $this->error('请选择要编辑的权限组',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['role_name'] = $this->request->param('role_name');
            if(empty($data['role_name'])){
                $this->error('请填写权限组名称！',null,101);
            }
            $RoleModel->save($data,['role_id'=>$role_id]);
            $this->success('操作成功',null,100); //100代表关闭掉弹出层
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function setting(){
        $role_id = $this->request->param('role_id');
        $RoleModel = new RoleModel();
        if(!$detail = $RoleModel->get($role_id)){
            $this->error('请选择要编辑的权限组',null,101);
        }
        if ($this->request->method() == 'POST') {
            $menuIds = empty($_POST['menu_id']) ? [] : $_POST['menu_id']; 
            $data = [];
            $data['role_auth'] = join('|',$menuIds);
            $RoleModel->save($data,['role_id'=>$role_id]);
            $this->success('授权成功',null,100);
        }else{
            $this->assign('menus',Config::get('admin'));
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }
    
}