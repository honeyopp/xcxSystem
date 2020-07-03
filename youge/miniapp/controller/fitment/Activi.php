<?php
namespace app\miniapp\controller\fitment;
use app\miniapp\controller\Common;
use app\common\model\fitment\ActiviModel;
class Activi extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ActiviModel::where($where)->count();
        $list = ActiviModel::where($where)->order(['activity_id'=>'desc'])->paginate(10, $count);
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
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('展示图片不能为空',null,101);
            }
            $data['bg_date'] = $this->request->param('bg_date');  
            if(empty($data['bg_date'])){
                $this->error('开始日期不能为空',null,101);
            }
            $data['end_date'] = $this->request->param('end_date');  
            if(empty($data['end_date'])){
                $this->error('结束日期不能为空',null,101);
            }
            $data['address'] = $this->request->param('address');  
            if(empty($data['address'])){
                $this->error('活动地址不能为空',null,101);
            }
            $data['lat'] =  $this->request->param('lat');
            $data['lng'] = $this->request->param('lng');
            $data['introduce'] = $this->request->param('introduce');  
            $data['rule'] = $this->request->param('rule');  
            $data['warning'] = $this->request->param('warning');  
            $data['is_end'] = $this->request->param('is_end');
            $ActiviModel = new ActiviModel();
            $ActiviModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $activity_id = (int)$this->request->param('activity_id');
         $ActiviModel = new ActiviModel();
         if(!$detail = $ActiviModel->get($activity_id)){
             $this->error('请选择要编辑的优惠活动',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在优惠活动");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('活动标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('展示图片不能为空',null,101);
            }
            $data['bg_date'] = $this->request->param('bg_date');  
            if(empty($data['bg_date'])){
                $this->error('开始日期不能为空',null,101);
            }
            $data['end_date'] = $this->request->param('end_date');  
            if(empty($data['end_date'])){
                $this->error('结束日期不能为空',null,101);
            }
            $data['address'] = $this->request->param('address');  
            if(empty($data['address'])){
                $this->error('活动地址不能为空',null,101);
            }
            $data['lat'] =  $this->request->param('lat');
            $data['lng'] =  $this->request->param('lng');
            $data['introduce'] = $this->request->param('introduce');  
            $data['rule'] = $this->request->param('rule');  
            $data['warning'] = $this->request->param('warning');  
            $data['is_end'] = $this->request->param('is_end');
            $ActiviModel = new ActiviModel();
            $ActiviModel->save($data,['activity_id'=>$activity_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        $activity_id = (int)$this->request->param('activity_id');
         $ActiviModel = new ActiviModel();
        if(!$detail = $ActiviModel->find($activity_id)){
            $this->error("不存在该优惠活动",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该优惠活动', null, 101);
        }
        $ActiviModel->where(['activity_id'=>$activity_id])->delete();
        $this->success('操作成功');
    }
   
}