<?php
namespace app\miniapp\controller\school;
use app\miniapp\controller\Common;
use app\common\model\school\VoteModel;
class Vote extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = VoteModel::where($where)->count();
        $list = VoteModel::where($where)->order(['vote_id'=>'desc'])->paginate(10, $count);
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
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['bg_time'] = (int) strtotime($this->request->param('bg_time'));
            if(empty($data['bg_time'])){
                $this->error('开始时间不能为空',null,101);
            }
            if($data['bg_time'] > $this->request->time()){
                $this->error('开始时间不得大于当前时间',NULL,101);
            }
            $data['end_time'] = (int) strtotime($this->request->param('end_time'));
            if(empty($data['end_time'])){
                $this->error('结束时间不能为空',null,101);
            }
            if($data['end_time'] < $this->request->time()){
                $this->error('当前时间不得小于当前时间',null,101);
            }
            $data['rule'] = $this->request->param('rule');  
            if(empty($data['rule'])){
                $this->error('活动规则不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('活动介绍不能为空',null,101);
            }
            
            
            $VoteModel = new VoteModel();
            $VoteModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $vote_id = (int)$this->request->param('vote_id');
         $VoteModel = new VoteModel();
         if(!$detail = $VoteModel->get($vote_id)){
             $this->error('请选择要编辑的互动投票',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在互动投票");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['bg_time'] = (int) strtotime($this->request->param('bg_time'));
            if(empty($data['bg_time'])){
                $this->error('开始时间不能为空',null,101);
            }
             if($data['bg_time'] > $this->request->time()){
                 $this->error('开始时间不得大于当前时间',NULL,101);
             }
            $data['end_time'] = (int) strtotime($this->request->param('end_time'));
            if(empty($data['end_time'])){
                $this->error('结束时间不能为空',null,101);
            }
             if($data['end_time'] < $this->request->time()){
                 $this->error('当前时间不得小于当前时间',null,101);
             }
            $data['rule'] = $this->request->param('rule');  
            if(empty($data['rule'])){
                $this->error('活动规则不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('活动介绍不能为空',null,101);
            }
            $VoteModel = new VoteModel();
            $VoteModel->save($data,['vote_id'=>$vote_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    public function delete() {
   
        $vote_id = (int)$this->request->param('vote_id');
         $VoteModel = new VoteModel();
       
        if(!$detail = $VoteModel->find($vote_id)){
            $this->error("不存在该互动投票",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该互动投票', null, 101);
        }
        $VoteModel->where(['vote_id'=>$vote_id])->delete();
        $this->success('操作成功');
    }
   
}