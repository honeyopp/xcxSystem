<?php
namespace app\miniapp\controller\tongcheng;
use app\miniapp\controller\Common;
use app\common\model\tongcheng\CommentModel;
class Comment extends Common {
    
    public function index() {
        $where = $search = [];
        $search['user_id'] = (int)$this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = $search['user_id'];
        }
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CommentModel::where($where)->count();
        $list = CommentModel::where($where)->order(['comment_id'=>'desc'])->paginate(10, $count);
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
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('会员不能为空',null,101);
            }
            $data['info_id'] = (int) $this->request->param('info_id');
            if(empty($data['info_id'])){
                $this->error('信息id不能为空',null,101);
            }
            $data['content'] = (int) $this->request->param('content');
            if(empty($data['content'])){
                $this->error('内容不能为空',null,101);
            }
            $data['reply'] = (int) $this->request->param('reply');
            if(empty($data['reply'])){
                $this->error('回复不能为空',null,101);
            }
            $data['reply_time'] = (int) $this->request->param('reply_time');
            if(empty($data['reply_time'])){
                $this->error('回复时间不能为空',null,101);
            }
            $data['zan_num'] = (int) $this->request->param('zan_num');
            if(empty($data['zan_num'])){
                $this->error('赞数不能为空',null,101);
            }
            
            
            $CommentModel = new CommentModel();
            $CommentModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $comment_id = (int)$this->request->param('comment_id');
         $CommentModel = new CommentModel();
         if(!$detail = $CommentModel->get($comment_id)){
             $this->error('请选择要编辑的信息评论',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在信息评论");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('会员不能为空',null,101);
            }
            $data['info_id'] = (int) $this->request->param('info_id');
            if(empty($data['info_id'])){
                $this->error('信息id不能为空',null,101);
            }
            $data['content'] = (int) $this->request->param('content');
            if(empty($data['content'])){
                $this->error('内容不能为空',null,101);
            }
            $data['reply'] = (int) $this->request->param('reply');
            if(empty($data['reply'])){
                $this->error('回复不能为空',null,101);
            }
            $data['reply_time'] = (int) $this->request->param('reply_time');
            if(empty($data['reply_time'])){
                $this->error('回复时间不能为空',null,101);
            }
            $data['zan_num'] = (int) $this->request->param('zan_num');
            if(empty($data['zan_num'])){
                $this->error('赞数不能为空',null,101);
            }

            
            $CommentModel = new CommentModel();
            $CommentModel->save($data,['comment_id'=>$comment_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $comment_id = (int)$this->request->param('comment_id');
         $CommentModel = new CommentModel();
       
        if(!$detail = $CommentModel->find($comment_id)){
            $this->error("不存在该信息评论",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该信息评论', null, 101);
        }
        $CommentModel->where(['comment_id'=>$comment_id])->delete();
        $this->success('操作成功');
    }
   
}