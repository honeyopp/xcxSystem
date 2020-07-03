<?php
namespace app\miniapp\controller\school;
use app\common\model\school\VoteModel;
use app\miniapp\controller\Common;
use app\common\model\school\PlayerModel;
class Player extends Common {
    
    public function index() {
        $where = $search = [];
        $search['player_name'] = $this->request->param('player_name');
        if (!empty($search['player_name'])) {
            $where['player_name'] = array('LIKE', '%' . $search['player_name'] . '%');
        }
        $vote_id = (int) $this->request->param('vote_id');
        $VoteModel = new VoteModel();
        if(!$vote = $VoteModel->find($vote_id)){
            $this->error('不存在活动',null,101);
        }
        if($vote->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在活动',null,101);
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = PlayerModel::where($where)->count();
        $list = PlayerModel::where($where)->order(['vote_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('vote_id',$vote_id);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    
    public function create() {
        $vote_id = (int) $this->request->param('vote_id');
        $VoteModel = new VoteModel();
        if(!$vote = $VoteModel->find($vote_id)){
            $this->error('不存在活动',null,101);
        }
        if($vote->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在活动',null,101);
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['player_name'] = $this->request->param('player_name');  
            if(empty($data['player_name'])){
                $this->error('选手名称不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('选手图片不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('介绍不能为空',null,101);
            }
            $PlayerModel = new PlayerModel();
            $data['number'] = (int) $this->request->param('number');
            if(empty($data['number'])){
                $this->error('编号不能为空',null,101);
            }
            $where['vote_id'] = $vote_id;
            $where['number'] = $data['number'];
            if($play =  $PlayerModel->where($where)->find()){
                $this->error('这个编号已经存在了',null,101);
            }
            $PlayerModel->save($data);
            $this->success('操作成功',null);
        } else {
            $this->assign('vote_id',$vote_id);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $vote_id = (int)$this->request->param('vote_id');
         $PlayerModel = new PlayerModel();
         if(!$detail = $PlayerModel->get($vote_id)){
              $this->error('请选择要编辑的互动投票',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
              $this->error("不存在互动投票");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['player_name'] = $this->request->param('player_name');  
            if(empty($data['player_name'])){
                $this->error('选手名称不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('选手图片不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('介绍不能为空',null,101);
            }
            $data['vote_num'] = $this->request->param('vote_num');  
            if(empty($data['vote_num'])){
                $this->error('投票数不能为空',null,101);
            }
            $data['view_num'] = (int) $this->request->param('view_num');
            if(empty($data['view_num'])){
                $this->error('浏览数不能为空',null,101);
            }
             $data['number'] = (int) $this->request->param('number');
             if(empty($data['number'])){
                 $this->error('编号不能为空',null,101);
             }
             $where['vote_id'] = $vote_id;
             $where['number'] = $data['number'];
             if($play =  $PlayerModel->where($where)->find()){
                 $this->error('这个编号已经存在了',null,101);
             }

            $PlayerModel->save($data,['vote_id'=>$vote_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $vote_id = (int)$this->request->param('vote_id');
         $PlayerModel = new PlayerModel();
       
        if(!$detail = $PlayerModel->find($vote_id)){
            $this->error("不存在选手",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在选手', null, 101);
        }
        $PlayerModel->where(['vote_id'=>$vote_id])->delete();
        $this->success('操作成功');
    }
   
}