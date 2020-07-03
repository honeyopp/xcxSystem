<?php
namespace app\miniapp\controller\sheying;
use app\common\model\sheying\CategoryModel;
use app\miniapp\controller\Common;
use app\common\model\sheying\EnrollModel;
class Enroll extends Common {
    
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
        $count = EnrollModel::where($where)->count();
        $list = EnrollModel::where($where)->order(['enroll_id'=>'desc'])->paginate(10, $count);
        $catIds=  [];
        foreach ($list as $val){
            $catIds[$val->category_id] = $val->category_id;
        }
        $CategoryModel = new CategoryModel();
        $this->assign('category',$CategoryModel->itemsByIds($catIds));
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);

        return $this->fetch();
    }
    


    public function delete() {
   
        $enroll_id = (int)$this->request->param('enroll_id');
         $EnrollModel = new EnrollModel();
       
        if(!$detail = $EnrollModel->find($enroll_id)){
            $this->error("不存在该预约管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该预约管理', null, 101);
        }
        $EnrollModel->where(['enroll_id'=>$enroll_id])->delete();
        $this->success('操作成功');
    }
   
}