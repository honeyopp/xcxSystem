<?php

namespace app\miniapp\controller\hair;

use app\common\model\hair\DesignerModel;
use app\miniapp\controller\Common;
use app\common\model\hair\CommentModel;
use app\common\model\user\UserModel;

class Comment extends Common
{

    public function index()
    {
        $where = $search = [];
        $search['content'] = $this->request->param('content');
        if (!empty($search['content'])) {
            $where['content'] = array('LIKE', '%' . $search['content'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CommentModel::where($where)->count();
        $list = CommentModel::where($where)->order(['comment_id' => 'desc'])->paginate(10, $count);
        $userIds = $designerIds = [];
        foreach ($list as $val) {
            $userIds[$val->user_id] = $val->user_id;
            $designerIds[$val->designer_id] = $val->designer_id;
        }
        $UserModel = new UserModel();
        $DesignerModel = new DesignerModel();
        $this->assign('users', $UserModel->itemsByIds($userIds));
        $this->assign('designer', $DesignerModel->itemsByIds($designerIds));
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }

    public function delete()
    {
        $comment_id = (int)$this->request->param('comment_id');
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
