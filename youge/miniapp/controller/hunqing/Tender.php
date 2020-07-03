<?php
namespace app\miniapp\controller\hunqing;
use app\miniapp\controller\Common;
use app\common\model\hunqing\TenderModel;
class Tender extends Common {
    
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
        $count = TenderModel::where($where)->count();
        $list = TenderModel::where($where)->order(['tender_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    

    public function delete() {
        $tender_id = (int)$this->request->param('tender_id');
         $TenderModel = new TenderModel();
        if(!$detail = $TenderModel->find($tender_id)){
            $this->error("不存在该招标",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该招标', null, 101);
        }
        $TenderModel->where(['tender_id'=>$tender_id])->delete();
        $this->success('操作成功');
    }
   
}