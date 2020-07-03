<?php
namespace app\miniapp\controller\pinche;
use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
use app\common\model\pinche\PincheModel;
class Pinche extends Common {
    
    public function index() {
        $where = $search = [];
          $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = array('LIKE', '%' . $search['mobile'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = PincheModel::where($where)->count();
        $list = PincheModel::where($where)->order(['pinche_id'=>'desc'])->paginate(10, $count);
        $userIds = [];
        foreach ($list as $val ){
            $userIds[$val->user_id] = $val->user_id;
        }
        $UserModel =  new UserModel();
        $user = $UserModel->itemsByIds($userIds);
        $this->assign('user',$user);
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
            $data['type'] = (int) $this->request->param('type');
            if(empty($data['type'])){
                $this->error('类型不能为空',null,101);
            }
            $data['user_id'] = (int) $this->request->param('user_id');
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('姓名不能为空',null,101);
            }
            $data['mobile'] = $this->request->param('mobile');  
            if(empty($data['mobile'])){
                $this->error('联系方式不能为空',null,101);
            }
            $data['sex'] = (int)$this->request->param('sex');
            $data['begin'] = $this->request->param('begin');  
            if(empty($data['begin'])){
                $this->error('出发地不能为空',null,101);
            }
            $data['end'] = $this->request->param('end');  
            if(empty($data['end'])){
                $this->error('目的地不能为空',null,101);
            }
            $data['channel'] = (int) $this->request->param('channel');
            $data['car'] = (string) $this->request->param('car');
            $data['bg_time'] = (int) strtotime($this->request->param('bg_time'));
            if(empty($data['bg_time'])){
                $this->error('出发日期不能为空',null,101);
            }
            $data['vacancy'] = (int) $this->request->param('vacancy');
            if(empty($data['vacancy'])){
                $this->error('空位不能为空',null,101);
            }
            $data['demand'] = (string) $this->request->param('demand');
            $PincheModel = new PincheModel();
            $PincheModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $pinche_id = (int)$this->request->param('pinche_id');
         $PincheModel = new PincheModel();
         if(!$detail = $PincheModel->get($pinche_id)){
             $this->error('请选择要编辑的拼车管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在拼车管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['type'] = (int) $this->request->param('type');
            if(empty($data['type'])){
                $this->error('类型不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('姓名不能为空',null,101);
            }
             $data['sex'] = (int)$this->request->param('sex');
            $data['mobile'] = $this->request->param('mobile');  
            if(empty($data['mobile'])){
                $this->error('联系方式不能为空',null,101);
            }
            $data['begin'] = $this->request->param('begin');  
            if(empty($data['begin'])){
                $this->error('出发地不能为空',null,101);
            }
            $data['end'] = $this->request->param('end');  
            if(empty($data['end'])){
                $this->error('目的地不能为空',null,101);
            }
            $data['channel'] = (string)$this->request->param('channel');
            $data['bg_time'] = (int) strtotime($this->request->param('bg_time'));
            if(empty($data['bg_time'])){
                $this->error('出发日期不能为空',null,101);
            }
            $data['vacancy'] = (int) $this->request->param('vacancy');
            if(empty($data['vacancy'])){
                $this->error('空位不能为空',null,101);
            }
            $data['car'] = (string) $this->request->param('car');
            $data['demand'] = $this->request->param('demand');  

            $PincheModel = new PincheModel();
            $PincheModel->save($data,['pinche_id'=>$pinche_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }


    public function ok(){
        $pinche_id = (int)$this->request->param('pinche_id');
        $PincheModel = new PincheModel();
        if(!$detail = $PincheModel->find($pinche_id)){
            $this->error("不存在该拼车管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该拼车管理', null, 101);
        }
        if($detail->status == 1){
            $this->success('操作成功');
        }
        $data['status'] = 1;
        $PincheModel->save($data,['pinche_id'=>$pinche_id]);
        $this->success('操作成功');
    }

    public function delete() {
   
        $pinche_id = (int)$this->request->param('pinche_id');
         $PincheModel = new PincheModel();
       
        if(!$detail = $PincheModel->find($pinche_id)){
            $this->error("不存在该拼车管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该拼车管理', null, 101);
        }
        $PincheModel->where(['pinche_id'=>$pinche_id])->delete();
        $this->success('操作成功');
    }
   
}