<?php
namespace app\miniapp\controller\fitment;
use app\common\model\fitment\ActiviModel;
use app\common\model\fitment\DesignerModel;
use app\common\model\fitment\GroupModel;
use app\common\model\fitment\WorkModel;
use app\miniapp\controller\Common;
use app\common\model\fitment\EncrollModel;
class Encroll extends Common {
    
    public function group() {
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
        $where['type'] = 3;
        $count = EncrollModel::where($where)->count();
        $list = EncrollModel::where($where)->order(['encroll_id'=>'desc'])->paginate(10, $count);
        $groupIds = [];
        foreach ($list as $val){
            $groupIds[$val->type_id] = $val->type_id;
        }
        $GroupModel = new GroupModel();
        $this->assign('types',$GroupModel->itemsByIds($groupIds));
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }



    public function activit() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }

        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = array('LIKE', '%' . $search['mobile'] . '%');
        }

        $where['type'] = 1;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = EncrollModel::where($where)->count();
        $list = EncrollModel::where($where)->order(['encroll_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $ActiviModel= new ActiviModel();
        $activiIds = [];
        foreach ($list as $val){
            $activiIds[$val->type_id] = $val->type_id;
        }
        $this->assign('types',$ActiviModel->itemsByIds($activiIds));
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }


    public function work() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }

        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = array('LIKE', '%' . $search['mobile'] . '%');
        }

        $where['type'] = 4;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = EncrollModel::where($where)->count();
        $list = EncrollModel::where($where)->order(['encroll_id'=>'desc'])->paginate(10, $count);
        $workIds = [];
        foreach ($list as $val){
            $workIds[$val->type_id] = $val->type_id;
        }
        $WorkModel = new WorkModel();
        $this->assign('types',$WorkModel->itemsByIds($workIds));
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }


    public function shejishi() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }

        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = array('LIKE', '%' . $search['mobile'] . '%');
        }
        $where['type'] = 2;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = EncrollModel::where($where)->count();
        $list = EncrollModel::where($where)->order(['encroll_id'=>'desc'])->paginate(10, $count);
        $typeIds = [];
        foreach ($list as $val){
            $typeIds[$val->type_id] = $val->type_id;
        }
        $DesignerModel = new DesignerModel();
        $this->assign('types',$DesignerModel->itemsByIds($typeIds));
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }


    public function delete() {
   
        $encroll_id = (int)$this->request->param('encroll_id');
         $EncrollModel = new EncrollModel();
       
        if(!$detail = $EncrollModel->find($encroll_id)){
            $this->error("不存在该预约",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该预约', null, 101);
        }
        $EncrollModel->where(['encroll_id'=>$encroll_id])->delete();
        $this->success('操作成功');
    }
   
}