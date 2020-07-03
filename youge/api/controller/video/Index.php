<?php

namespace app\api\controller\video;
use app\api\controller\Common;
use app\common\model\user\UserModel;
use app\common\model\video\CommentModel;
use app\common\model\video\TagModel;
use app\common\model\video\TypeModel;
use app\common\model\video\VideojoinModel;
use app\common\model\video\VideoModel;

class Index extends Common {
    //获取首页 推荐视频 排序最大的视频
    public function  index(){
         $where['member_miniapp_id'] = $this->appid;
        $type_id = (int) $this->request->param('type_id');
        $data['tags'] = [];
        if(!empty($type_id)){
              $where['type_id'] = $type_id;
              $TagModel = new TagModel();
              $where['member_miniapp_id'] = $this->appid;
              $where['type_id'] = $type_id;
              $tags = $TagModel->where($where)->limit(0,20)->select();
              foreach ($tags as $val){
                  $data['tags'][] = [
                      'tag_name'  => $val->tag_name,
                      'tag_id'   => $val->tag_id,
                  ];
              }
        }

         $VideoModel = new VideoModel();
         $list = $VideoModel->where($where)->order('orderby desc')->limit($this->limit_bg,$this->limit_num)->select();
         $data['list']  = [];
         foreach ($list as $val){
            $data['list'][] = [
                'share_num'  => $val->share_num,
                'views'      => $val->views,
                'title'      => $val->title,
                'photo'      => IMG_URL .getImg($val->photo),
                'link'       => $val->link,
                'video_id'   => $val->video_id,
                'type_id'    => $val->type_id,
            ];
         }
        $data['more'] = count($data['list']) < $this->limit_num ? 0:1;
        $this->result($data, 200, '获取数据成功', 'json');
    }
    //普通头条
    public function detail(){
        $id = (int)$this->request->param('id');

        $ToutiaoModel = new VideoModel();
        if (!$detail = $ToutiaoModel->get($id)) {
            $this->result('', 400, '该视频不存在或者已下架', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '该视频不存在或者已下架', 'json');
        }
        $data = [
            'share_num'  => $detail->share_num,
            'views'      => $detail->views,
            'title'      => $detail->title,
            'photo'      => IMG_URL .getImg($detail->photo),
            'link'       => $detail->link,
            'video_id'   => $detail->video_id,
            'type_id'    => $detail->type_id,
        ];
        $ToutiaoModel->IncDecCol($id,'views');
        $this->result($data, 200, '获取数据成功', 'json');
    }

    public function share(){
        $id = (int)$this->request->param('id');

        $ToutiaoModel = new ToutiaoModel();
        if (!$detail = $ToutiaoModel->get($id)) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }
        $ToutiaoModel->IncDecCol($id,'share_num');
        $this->result('', 200, '操作成功', 'json');
    }




    //点赞评论
    public function zan(){
        $openid = $this->request->param('openid');
        if(empty($openid)){
            $this->result('', 100, '未登录', 'json');
        }
        $UserModel = new UserModel();
        if(!$this->user = $UserModel->get(['open_id'=>$openid,'member_miniapp_id'=>  $this->appid])){
            $this->result('', 100, '未登录', 'json');
        }
        if($this->user->is_lock == 1){
            $this->result('', 100, '账户已被锁定', 'json');
        }
        $commentId = (int)$this->request->param('id');
        $CommentModel = new CommentModel();
        if(!$detail = $CommentModel->get($commentId)){
            $this->result('', 400, '没有该数据', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有该数据', 'json');
        }
        $CommentModel->IncDecCol($commentId, 'zan_num');
        $this->result('', 200, '操作成功', 'json');
    }

    public function getCommentList(){
        $id = (int)$this->request->param('id');
        $ToutiaoModel = new VideoModel();
        if (!$detail = $ToutiaoModel->get($id)) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }
        $comments = CommentModel::where(['member_miniapp_id'=>  $this->appid,'video_id'=>$id])->order(['comment_id'=>'desc'])->limit($this->limit_bg,$this->limit_num)->select();
        $user_ids = [];
        foreach($comments as $val){
            $user_ids[$val->user_id] = $val->user_id;
        }
        $UserModel = new UserModel();
        $users = $UserModel->itemsByIds($user_ids);
        $dataarr = [];
        foreach($comments as $val){
            $dataarr[] = [
                'id' => $val->comment_id,
                'nick_name' => isset($users[$val->user_id]) ? $users[$val->user_id]['nick_name']:'',
                'face'   => isset($users[$val->user_id]) ? $users[$val->user_id]['face']:'',
                'content' => $val->content,
                'add_time' => date('Y-m-d h:i:s',$val->add_time),
                'zan_num'=>$val->zan_num,
            ];
        }
        $return = [
            'datas' => $dataarr,
            'more'  => count($comments) < $this->limit_num? 0:1,
        ];
        $this->result($return, 200, '操作成功', 'json');
    }

    public function comment(){
        $openid = $this->request->param('openid');
        if(empty($openid)){
            $this->result('', 100, '未登录', 'json');
        }
        $UserModel = new UserModel();
        if(!$this->user = $UserModel->get(['open_id'=>$openid,'member_miniapp_id'=>  $this->appid])){
            $this->result('', 100, '未登录', 'json');
        }
        if($this->user->is_lock == 1){
            $this->result('', 100, '账户已被锁定', 'json');
        }
        $id = (int)$this->request->param('id');

        $ToutiaoModel = new VideoModel();
        if (!$detail = $ToutiaoModel->get($id)) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }

        $content = $this->request->param('content');
        if(empty($content)){
            $this->result('', 400, '评论内容不能为空', 'json');
        }

        $data = [
            'video_id' => $id,
            'member_miniapp_id' => $this->appid,
            'content' => $content,
            'user_id' => $this->user->user_id,
        ];
        $CommentModel = new CommentModel();
        $CommentModel->save($data);
        $this->result('', 200, '评论成功', 'json');
    }


    public function getType(){
        $where['member_miniapp_id'] = $this->appid;
        $TypeModel = new TypeModel();
        $list = $TypeModel->where($where)->order("orderby desc")->limit(0,20)->select();
        $data = [];
        foreach ($list as $val){
            $data[]  = [
                'type_name' => $val->type_name,
                'type_id'   => $val->type_id,
                'photo'     => IMG_URL .getImg($val->photo),
            ];
        }

        $this->result($data,200,'数据初始化成功','json');
    }

//    标签列表；

  public function getTag(){
        $tag_id = (int) $this->request->param('tag_id');
        $TagModel = new TagModel();
        if(!$tag = $TagModel->find($tag_id)){
            $data['list'] = [];
            $data['more'] = 0;
            $this->result($data, 200, '获取数据成功', 'json');
        }
          $type_id = (int) $this->request->param('type_id');
          $data['tags'] = [];
          if(!empty($type_id)){
              $where['type_id'] = $type_id;
              $TagModel = new TagModel();
              $where['member_miniapp_id'] = $this->appid;
              $where['type_id'] = $type_id;
              $tags = $TagModel->where($where)->limit(0,20)->select();
              foreach ($tags as $val){
                  $data['tags'][] = [
                      'tag_name'  => $val->tag_name,
                      'tag_id'   => $val->tag_id,
                  ];
              }
          }
        if($tag->member_miniapp_id != $this->appid){
            $data['list'] = [];
            $data['more'] = 0;
            $this->result($data, 200, '获取数据成功', 'json');
        }
        $VideojoinModel= new VideojoinModel();
        $tagList = $VideojoinModel->where(['tag_id'=>$tag_id])->limit($this->limit_bg,$this->limit_num)->select();
        $videoIds = [];
        foreach ($tagList as $val){
            $videoIds[$val->video_id] =  $val->video_id;
        }
        $VideoModel = new VideoModel();
        $videoIds = empty($videoIds) ? 0 : $videoIds;
        $where['video_id'] = ['IN',$videoIds];
        $vlist = $VideoModel->where($where)->order('orderby desc')->select();
        $data['list'] = [];
        foreach ($vlist as $val){
                $data['list'][] = [
                    'video_id' => $val->video_id,
                    'share_num'  => $val->share_num,
                    'views'      => $val->views,
                    'title'      => $val->title,
                    'photo'      => IMG_URL .getImg($val->photo),
                    'link'       => $val->link,
                    'type_id'    => $val->type_id,
                ];
        }
      $data['more'] = count($data['list']) < $this->limit_num ? 0:1;
      $this->result($data, 200, '获取数据成功', 'json');
  }

}