<?php
namespace app\miniapp\controller\school;
use app\common\model\school\ClassoneModel;
use app\miniapp\controller\Common;
use app\common\model\school\EntryModel;
class Entry extends Common {
    
    public function index() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = EntryModel::where($where)->count();
        $list = EntryModel::where($where)->order(['entry_id'=>'desc'])->paginate(10, $count);
        $classIds = [];
        foreach ($list as $val){
            $classIds[$val->class_id] = $val->class_id;
        }
        $ClassoneModel = new ClassoneModel();
        $class = $ClassoneModel->itemsByIds($classIds);
        $page = $list->render();
        $this->assign('class',$class);
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    

    public function delete() {
   
        $entry_id = (int)$this->request->param('entry_id');
         $EntryModel = new EntryModel();
       
        if(!$detail = $EntryModel->find($entry_id)){
            $this->error("不存在该报名咨询",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该报名咨询', null, 101);
        }
        $EntryModel->where(['entry_id'=>$entry_id])->delete();
        $this->success('操作成功');
    }
   
}