<?php
namespace app\admin\controller\admin;
use app\admin\controller\Common;
use app\common\model\admin\AdminModel;
use app\common\model\admin\RoleModel;
use think\Loader;
use think\Config;

class Admin extends Common {
    
     public function index() {
        $where = $search = [];
        $search['username'] = $this->request->param('username');
        if (!empty($search['username'])) {
            $where['username'] = array('LIKE', '%' . $search['username'] . '%');
        }
        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = array('LIKE', '%' . $search['mobile'] . '%');
        }

        $count = AdminModel::where($where)->count();
        $list = AdminModel::where($where)->order(['add_time'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        // 渲染模板输出
        $roleModel = new RoleModel();
        $this->assign('roles', $roleModel->fetchAll(FALSE));
        return $this->fetch();
    }
    
    public function menus(){
         $admin_id = $this->request->param('admin_id');
         $AdminModel = new AdminModel();
         if(!$detail = $AdminModel->get($admin_id)){
             $this->error('请选择要编辑的管理员',null,101);
         }
        if ($this->request->method() == 'POST') {
            $menuIds = empty($_POST['menu_id']) ? [] : $_POST['menu_id']; 
            $data = [];
            $data['auth_code'] = join('|',$menuIds);
            $AdminModel->save($data,['admin_id'=>$admin_id]);
            $this->success('授权成功',null,100);
        }else{
            $this->assign('menus',Config::get('admin'));
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $admin_id = $this->request->param('admin_id');
         $AdminModel = new AdminModel();
         if(!$detail = $AdminModel->get($admin_id)){
             $this->error('请选择要编辑的管理员',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $password = $this->request->param('password');
            $data['role_id'] = (int) $this->request->param('role_id');
            $data['mobile'] = $this->request->param('mobile');
            $data['real_name'] = $this->request->param('real_name');
            $validate = Loader::validate('Admin');
            if (!$validate->batch(false)->scene('edit')->check($data)) {
                $this->error($validate->getError(), null, 101); //多条消息的返回
            }
            if (!isMobile($data['mobile'])) {
                $this->error('手机号码格式不正确', null, 101);
            }
            $AdminModel = new AdminModel();
            if(!empty($password) && $password != '******'){
                $data['password'] = md5($password);
            }
            $AdminModel->save($data,['admin_id'=>$admin_id]);
            $this->success('操作成功',null,100); //100代表关闭掉弹出层
         }else{
            $this->assign('detail',$detail);
            $roleModel = new RoleModel();
            $this->assign('roles', $roleModel->fetchAll(FALSE));
            return $this->fetch();  
         }
    }

    public function create() {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['username'] = $this->request->param('username');
            $data['password'] = $this->request->param('password');
            $data['role_id'] = (int) $this->request->param('role_id');
            $data['mobile'] = $this->request->param('mobile');
            $data['real_name'] = $this->request->param('real_name');

            $validate = Loader::validate('Admin');
            if (!$validate->batch(false)->scene('create')->check($data)) {
                $this->error($validate->getError(), null, 101); //多条消息的返回
            }
            if (!isMobile($data['mobile'])) {
                $this->error('手机号码格式不正确', null, 101);
            }
            $AdminModel = new AdminModel();
            $detail = $AdminModel->get(['username' => $data['username']]);
            if (!empty($detail)) {
                $this->error('账号' . $data['username'] . '已经存在', null, 101);
            }
            $data['password'] = md5($data['password']);
            $AdminModel->save($data);
            $this->success('操作成功',null,100); //100代表关闭掉弹出层
        } else {
            $roleModel = new RoleModel();
            $this->assign('roles', $roleModel->fetchAll(FALSE));
            return $this->fetch();
        }
    }

    public function lock() {
        if($this->request->method() == 'POST'){ //TP BUG
             $admin_id = $_POST['admin_id'];
        }else{
            $admin_id = $this->request->param('admin_id');
        }
        $data = [];
        if (is_array($admin_id)) {
            foreach ($admin_id as $k => $val) {
                if ($val != $this->adminId) {
                    $admin_id[$k] = (int) $val;
                } else {
                    unset($admin_id[$k]);
                }
            }
            $data = $admin_id;
        } else {
            if ($admin_id == $this->adminId) {
                $this->error('不能锁定自己', null, 101);
            }
            $data[] = $admin_id;
        }
        if (!empty($data)) {
            $AdminModel = new AdminModel();
            $AdminModel->save(['is_delete' => 1], ['admin_id' => ['IN', $data]]);
        }
        $this->success('操作成功');
    }

    public function unlock() {
        $admin_id = (int) $this->request->param('admin_id');
        if ($admin_id == $this->adminId) {
            $this->error('不能操作自己', null, 101);
        }
        $AdminModel = new AdminModel();
        $AdminModel->save(['is_delete' => 0], ['admin_id' => $admin_id]);
        $this->success('操作成功');
    }

   
}