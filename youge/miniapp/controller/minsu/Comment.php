<?php
namespace app\miniapp\controller\minsu;
use app\common\model\minsu\CommentphotoModel;
use app\common\model\minsu\MinsuModel;
use app\common\model\minsu\RoomModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
use app\common\model\minsu\CommentModel;
class Comment extends Common {
    
    public function index() {
        $where = $search = [];
        $search['minsu_id'] = (int)$this->request->param('minsu_id');
        if (!empty($search['minsu_id'])) {
            $where['minsu_id'] = $search['minsu_id'];
        }
           $search['room_id'] = (int)$this->request->param('room_id');
        if (!empty($search['room_id'])) {
            $where['room_id'] = $search['room_id'];
        }
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
        $photoIds = $userIds = $roomIds = $minsuIds = [];
        foreach ($list as $val){
            $photoIds[$val->comment_id] = $val->comment_id;
            $userIds[$val->user_id] = $val->user_id;
            $roomIds[$val->room_id] = $val->room_id;
            $minsuIds[$val->minsu_id] = $val->minsu_id;
        }
        $CommentphotoModel = new CommentphotoModel();
        $UserModel = new UserModel();
        $RoomModel = new RoomModel();
        $minsuModel = new MinsuModel();
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
        $this->assign('minsu',$minsuModel->itemsByIds($minsuIds));
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
   
}