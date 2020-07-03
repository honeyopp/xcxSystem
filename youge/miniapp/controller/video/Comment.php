<?php

namespace app\miniapp\controller\video;

use app\common\model\video\CommentModel;
use app\common\model\video\VideoModel;
use app\miniapp\controller\Common;
use app\common\model\user\UserModel;
use app\common\model\toutiao\ToutiaoModel;
class Comment extends Common {

    public function index() {
        $where = $search = [];
        $search['video_id'] = (int) $this->request->param('video_id');
        if (!empty($search['video_id'])) {
            $where['video_id'] = $search['video_id'];
        }
        $search['user_id'] = (int) $this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = $search['user_id'];
        }
        $search['content'] = $this->request->param('content');
        if (!empty($search['content'])) {
            $where['content'] = array('LIKE', '%' . $search['content'] . '%');
        }


        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CommentModel::where($where)->count();
        $list = CommentModel::where($where)->order(['comment_id' => 'desc'])->paginate(10, $count);
        
        $userIds = $toutiaoIds = [];
        foreach($list as $val){
            $userIds[$val->user_id] = $val->user_id;
            $toutiaoIds[$val->video_id] = $val->video_id;
        }
        
        $UserModel = new UserModel();
        $ToutiaoModel = new VideoModel();
        $this->assign('users',$UserModel->itemsByIds($userIds));
        $this->assign('toutiaos',$ToutiaoModel->itemsByIds($toutiaoIds));
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function delete() {
        $comment_id = (int) $this->request->param('comment_id');
        $CommentModel = new CommentModel();

        if (!$detail = $CommentModel->find($comment_id)) {
            $this->error("不存在该头条评论", null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该头条评论', null, 101);
        }
        $CommentModel->where(['comment_id' => $comment_id])->delete();
        $this->success('操作成功');
    }

}
