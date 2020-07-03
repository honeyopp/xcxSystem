<?php
namespace app\miniapp\controller\nongjialegw;

use app\common\model\nongjiale\CommentModel;
use app\common\model\nongjiale\CommentphotoModel;
use app\common\model\nongjiale\RoomModel;
use app\common\model\taocan\TaocanModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
class Comment extends Common {
    
    public function index() {
        $where = $search = [];
        $search['score'] = (int)$this->request->param('score');
        if (!empty($search['score'])) {
            switch ($search['score']){
                case 1 :
                    $where['score'] = ['between',['10','20']];
                    break;
                case 2 :
                    $where['score'] = ['between',['25','35']];
                    break;
                case 3 :
                    $where['score'] = ['between',['40','50']];
                    break;
            }
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CommentModel::where($where)->count();
        $list = CommentModel::where($where)->order(['comment_id'=>'desc'])->paginate(10, $count);
        $photoIds = $userIds = $roomIds = $taocanIds = [];
        foreach ($list as $val){
            if ($val->type == 1) {
                $taocanIds[$val->product_id] = $val->product_id;
            } elseif ($val->type == 2) {
                $roomIds[$val->product_id] = $val->product_id;
            }
            $photoIds[$val->comment_id] = $val->comment_id;
            $userIds[$val->user_id] = $val->user_id;

        }
        $CommentphotoModel = new CommentphotoModel();
        $UserModel = new UserModel();
        $TaocanModel= new \app\common\model\nongjiale\TaocanModel();
        $RoomModel = new RoomModel();
        $photoIds = empty($photoIds) ? 0 : $photoIds;
        $photo_where['comment_id'] = ["IN",$photoIds];
        $photo_where['member_miniapp_id'] = $this->miniapp_id;
        $photo = $CommentphotoModel->where($photo_where)->select();
        $photos = [];
        foreach ($photo as $val){
            $photos[$val->comment_id][] = $val;
        }
        $page = $list->render();
        $this->assign('photos',$photos);
        $this->assign('user',$UserModel->itemsByIds($userIds));
        $this->assign('hotel',$TaocanModel->itemsByIds($taocanIds));
        $this->assign('room',$RoomModel->itemsByIds($roomIds));
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }


    public function delete() {
        $comment_id = (int) $this->request->param('comment_id');
        $CommentModel = new CommentModel();
        if(!$comment = $CommentModel->find($comment_id)){
            $this->error('不存在评论',null,101);
        }
        if($comment->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在评论2',null,101);
        }
        $where['comment_id'] = $comment_id;
        $CommentModel->where($where)->delete();
        $CommentphotoModel = new CommentphotoModel();
        $CommentphotoModel->where($where)->delete();
        $this->success('操作成功');
    }

    public function reply(){
        $comment_id = (int) $this->request->param('comment_id');
        $CommentModel = new CommentModel();
        if(!$comment = $CommentModel->find($comment_id)){
            $this->error('不存在评论',null,101);
        }
        if($comment->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在评论2',null,101);
        }
        if($this->request->method() == "POST"){
            $data['reply'] = (string) $this->request->param('reply');
            $data['reply_ip']  = $this->request->ip();
            $data['reply_time'] = $this->request->time();
            $CommentModel->save($data,['comment_id'=>$comment->comment_id]);
            $this->success(' 操作成功',null,100);
        }else{
            $this->assign('detail',$comment);
        }
        return $this->fetch();
    }
   
}