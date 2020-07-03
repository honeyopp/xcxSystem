<?php
namespace app\miniapp\controller\publicuse;
use app\miniapp\controller\Common;
use app\common\model\publicuse\CommentModel;
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
                $this->error('评论用户不能为空',null,101);
            }
            $data['resource_id'] = (int) $this->request->param('resource_id');
            if(empty($data['resource_id'])){
                $this->error('评论的产品不能为空',null,101);
            }
            $data['content'] = $this->request->param('content');  
            if(empty($data['content'])){
                $this->error('内容不能为空',null,101);
            }
            $data['reply'] = $this->request->param('reply');  
            if(empty($data['reply'])){
                $this->error('回复不能为空',null,101);
            }
            $data['reply_time'] = (int) strtotime($this->request->param('reply_time'));
            if(empty($data['reply_time'])){
                $this->error('回复时间不能为空',null,101);
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
             $this->error('请选择要编辑的评论功能',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在评论功能");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('评论用户不能为空',null,101);
            }
            $data['resource_id'] = (int) $this->request->param('resource_id');
            if(empty($data['resource_id'])){
                $this->error('评论的产品不能为空',null,101);
            }
            $data['content'] = $this->request->param('content');  
            if(empty($data['content'])){
                $this->error('内容不能为空',null,101);
            }
            $data['reply'] = $this->request->param('reply');  
            if(empty($data['reply'])){
                $this->error('回复不能为空',null,101);
            }
            $data['reply_time'] = (int) strtotime($this->request->param('reply_time'));
            if(empty($data['reply_time'])){
                $this->error('回复时间不能为空',null,101);
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
            $this->error("不存在该评论功能",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该评论功能', null, 101);
        }
        $CommentModel->where(['comment_id'=>$comment_id])->delete();
        $this->success('操作成功');
    }
   
}