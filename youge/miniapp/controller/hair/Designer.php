<?php
namespace app\miniapp\controller\hair;
use app\miniapp\controller\Common;
use app\common\model\hair\DesignerModel;
class Designer extends Common {
    
    public function index() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = DesignerModel::where($where)->count();
        $list = DesignerModel::where($where)->order(['designer_id'=>'desc'])->paginate(10, $count);
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
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('设计师名称不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('头像不能为空',null,101);
            }
            $data['zhiwu'] = $this->request->param('zhiwu');  
            if(empty($data['zhiwu'])){
                $this->error('职务不能为空',null,101);
            }
            $data['tages'] = $this->request->param('tages');  
            if(empty($data['tages'])){
                $this->error('标签不能为空',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            if(empty($data['price'])){
                $this->error('预约价格不能为空',null,101);
            }
            $data['works_num'] = (int) $this->request->param('works_num');

            $data['enroll_num'] = (int) $this->request->param('enroll_num');

            $data['hp_num'] = (int) $this->request->param('hp_num');

            $DesignerModel = new DesignerModel();
            $DesignerModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $designer_id = (int)$this->request->param('designer_id');
         $DesignerModel = new DesignerModel();
         if(!$detail = $DesignerModel->get($designer_id)){
             $this->error('请选择要编辑的设计师管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在设计师管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('设计师名称不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('头像不能为空',null,101);
            }
            $data['zhiwu'] = $this->request->param('zhiwu');  
            if(empty($data['zhiwu'])){
                $this->error('职务不能为空',null,101);
            }
            $data['tages'] = $this->request->param('tages');  
            if(empty($data['tages'])){
                $this->error('标签不能为空',null,101);
            }
            $data['price'] = (int) $this->request->param('price');

            $data['works_num'] = (int) $this->request->param('works_num');

            $data['enroll_num'] = (int) $this->request->param('enroll_num');

            $data['hp_num'] = (int) $this->request->param('hp_num');

            $DesignerModel = new DesignerModel();
            $DesignerModel->save($data,['designer_id'=>$designer_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $designer_id = (int)$this->request->param('designer_id');
         $DesignerModel = new DesignerModel();
       
        if(!$detail = $DesignerModel->find($designer_id)){
            $this->error("不存在该设计师管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该设计师管理', null, 101);
        }
        $DesignerModel->where(['designer_id'=>$designer_id])->delete();
        $this->success('操作成功');
    }
   
}