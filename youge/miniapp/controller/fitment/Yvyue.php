<?php
namespace app\miniapp\controller\fitment;
use app\miniapp\controller\Common;
use app\common\model\fitment\YvyueModel;
class Yvyue extends Common {
    
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
        $count = YvyueModel::where($where)->count();
        $list = YvyueModel::where($where)->order(['yvyue_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function delete() {
        $yvyue_id = (int)$this->request->param('yvyue_id');
        $YvyueModel = new YvyueModel();
        if(!$detail = $YvyueModel->find($yvyue_id)){
            $this->error("不存在该装修预约",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该装修预约', null, 101);
        }
        $YvyueModel->where(['yvyue_id'=>$yvyue_id])->delete();
        $this->success('操作成功');
    }
   
}