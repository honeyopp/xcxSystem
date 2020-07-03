<?php
namespace app\miniapp\controller\fitment;
use app\miniapp\controller\Common;
use app\common\model\fitment\GroupModel;
class Group extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = GroupModel::where($where)->count();
        $list = GroupModel::where($where)->order(['group_id'=>'desc'])->paginate(10, $count);
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
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('活动标题不能为空',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            if(empty($data['price'])){
                $this->error('立省不能为空',null,101);
            }
            $data['num'] = (int) $this->request->param('num');
            $data['bg_date'] = $this->request->param('bg_date');  
            if(empty($data['bg_date'])){
                $this->error('开始日期不能为空',null,101);
            }
            $data['end_date'] = $this->request->param('end_date');  
            if(empty($data['end_date'])){
                $this->error('结束日期不能为空',null,101);
            }
            $data['is_end'] = $this->request->param('is_end');  
            $data['introduce'] = $this->request->param('introduce');  
            $data['rule'] = $this->request->param('rule');  
            $data['warning'] = $this->request->param('warning');  
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('列表图片不能为空',null,101);
            }
            
            
            $GroupModel = new GroupModel();
            $GroupModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $group_id = (int)$this->request->param('group_id');
         $GroupModel = new GroupModel();
         if(!$detail = $GroupModel->get($group_id)){
             $this->error('请选择要编辑的小区团装',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在小区团装");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('活动标题不能为空',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            if(empty($data['price'])){
                $this->error('立省不能为空',null,101);
            }
            $data['num'] = (int) $this->request->param('num');
            $data['bg_date'] = $this->request->param('bg_date');  
            if(empty($data['bg_date'])){
                $this->error('开始日期不能为空',null,101);
            }
            $data['end_date'] = $this->request->param('end_date');  
            if(empty($data['end_date'])){
                $this->error('结束日期不能为空',null,101);
            }
            $data['is_end'] = $this->request->param('is_end');  
            $data['introduce'] = $this->request->param('introduce');  
            $data['rule'] = $this->request->param('rule');  
            $data['warning'] = $this->request->param('warning');  
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('列表图片不能为空',null,101);
            }

            
            $GroupModel = new GroupModel();
            $GroupModel->save($data,['group_id'=>$group_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $group_id = (int)$this->request->param('group_id');
         $GroupModel = new GroupModel();
       
        if(!$detail = $GroupModel->find($group_id)){
            $this->error("不存在该小区团装",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该小区团装', null, 101);
        }
        $GroupModel->where(['group_id'=>$group_id])->delete();
        $this->success('操作成功');
    }
   
}