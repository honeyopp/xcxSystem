<?php

namespace app\api\controller\toutiao;
use app\api\controller\Common;
use app\common\model\toutiao\ToutiaoModel;
use app\common\model\toutiao\CommentModel;
use app\common\model\toutiao\NavModel;
use app\common\model\user\UserModel;
use app\common\model\toutiao\ContentModel;
class Toutiao extends Common {
    
    
    //获取头条分类以及首页推荐数据
    public function  index(){
        $nav = NavModel::where(['member_miniapp_id'=>  $this->appid,'is_show'=>1])->order(['orderby'=>'desc'])->select();
        $toutiao = ToutiaoModel::where(['member_miniapp_id'=>  $this->appid])->order(['toutiao_id'=>'desc'])->limit(0,  $this->limit_num)->select();
        $return  = [];
        $reNav = [];
        foreach($nav as $val){
            $reNav[]=[
                'id' => $val->nav_id,
                'name' => $val->nav_name,
            ];
        }
        $toutiaoArr = [];
        foreach($toutiao as $val){
            $type = 2;
            if($val->type ==2){
                $type = 1;
            }elseif(empty($val->photo1)&& empty($val->photo2)&& empty($val->photo3)){
                $type = 2;
            }elseif(!empty($val->photo1)&& !empty($val->photo2)&& !empty($val->photo3)){
                $type =4;
            }else if(!empty($val->photo1)){
                $type =3;
            }
            $toutiaoArr[] =[
               'id' => $val->toutiao_id,
                'title' => $val->title,
                'author' => $val->author,
                'type' =>$type,  //这里的TYPE   需要重新定义  1 代表视频大图 2代表纯文字，3代表小图一张，4代表3张图
                'add_time' => date('m/d'),
                      'photo1' => empty($val->photo1)?'':IMG_URL.getImg($val->photo1),
                'photo2' => empty($val->photo2)?'':IMG_URL.getImg($val->photo2),
                'photo3' => empty($val->photo3)?'':IMG_URL.getImg($val->photo3),
                'comment_num' => $val->comment_num,
                'views' => $val->views,
                'share_num'=>$val->share_num,
            ];
        }
        
        $return = [
            'nav' => $reNav,
            'toutiao' => $toutiaoArr,
            'more' => count($toutiaoArr) < $this->limit_num ? 0:1,
        ];
        $this->result($return, 200, '获取数据成功', 'json');
    }
    
    //普通头条
    public function detail(){
        $id = (int)$this->request->param('id');
      
        $ToutiaoModel = new ToutiaoModel();
        if (!$detail = $ToutiaoModel->get($id)) {
             $this->result('', 400, '没有要查看的头条', 'json'); 
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有要查看的头条', 'json'); 
        }
        
        $contents =ContentModel::where(['member_miniapp_id'=>  $this->appid,'toutiao_id'=>$id])->order(['orderby'=>'asc'])->select();
        
        $contentArr = [];
        foreach($contents as $val){
            $contentArr[]=[
                'content' => $val->content,
                'photo'   => empty($val->photo) ? '' : IMG_URL.getImg($val->photo)
            ];
        }
        $nav = NavModel::get($detail->nav_id);
        $data = [
            'id' => $id,
            'title' => $detail->title,
            'author' => $detail->author,
            'nav'   =>!empty($nav) ? $nav->nav_name:'',
            'video_url' => $detail->video_url,
            'comment_num' => $detail->comment_num,
            'views' => $detail->views,
            'share_num' => $detail->share_num,
            'photo1' => empty($detail->photo1)? '' :  IMG_URL.getImg($detail->photo1),
            'contents'=>$contentArr,
            'add_time'=>date('Y-m-d H:i:s',$detail->add_time),
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

    //视频头条
    public function detail2(){
        $id = (int)$this->request->param('id');
      
        $ToutiaoModel = new ToutiaoModel();
         if (!$detail = $ToutiaoModel->get($id)) {
             $this->result('', 400, '没有要查看的头条', 'json'); 
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有要查看的头条', 'json'); 
        }
        $nav = NavModel::get($detail->nav_id);
        $data = [
            'id' => $id,
            'title' => $detail->title,
            'author' => $detail->author,
            'nav_id' => $detail->nav_id,
            'nav'   =>!empty($nav) ? $nav->nav_name:'',
            'video_url' => $detail->video_url,
            'comment_num' => $detail->comment_num,
            'views' => $detail->views,
            'share_num' => $detail->share_num,
        ];
        $ToutiaoModel->IncDecCol($id,'views');
        $this->result($data, 200, '获取数据成功', 'json'); 
    }
    
    //获得分页数据
    public function datas(){
        $id = (int)$this->request->param('id');
        $where = ['member_miniapp_id'=>  $this->appid];
        if(!empty($id)){
            $where['nav_id'] = $id;
        }
        $toutiao = ToutiaoModel::where($where)->order(['toutiao_id'=>'desc'])->limit($this->limit_bg,$this->limit_num)->select();
        $toutiaoArr = [];
        foreach($toutiao as $val){
            $type = 2;
            if($val->type ==2){
                $type = 1;
            }elseif(empty($val->photo1)&& empty($val->photo2)&& empty($val->photo3)){
                $type = 2;
            }elseif(!empty($val->photo1)&& !empty($val->photo2)&& !empty($val->photo3)){
                $type =4;
            }else if(!empty($val->photo1)){
                $type =3;
            }
            $toutiaoArr[] =[
               'id' => $val->toutiao_id,
                'title' => $val->title,
                'author' => $val->author,
                'type' => $type,  //这里的TYPE   需要重新定义  1 代表视频大图 2代表纯文字，3代表小图一张，4代表3张图
                'add_time' => date('m/d'),
                'photo1' => empty($val->photo1)?'':IMG_URL.getImg($val->photo1),
                'photo2' => empty($val->photo2)?'':IMG_URL.getImg($val->photo2),
                'photo3' => empty($val->photo3)?'':IMG_URL.getImg($val->photo3),
                'comment_num' => $val->comment_num,
                'views' => $val->views,
                'share_num'=>$val->share_num,
            ];
        }
        $return = [
            'toutiao' => $toutiaoArr,
            'more' => count($toutiaoArr) < $this->limit_num ? 0:1,
        ];
        $this->result($return, 200, '获取数据成功', 'json');
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
      
        $ToutiaoModel = new ToutiaoModel();
        if (!$detail = $ToutiaoModel->get($id)) {
             $this->result('', 400, '没有要查看的头条', 'json'); 
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有要查看的头条', 'json'); 
        }
        $comments = CommentModel::where(['member_miniapp_id'=>  $this->appid,'toutiao_id'=>$id])->order(['comment_id'=>'desc'])->limit($this->limit_bg,$this->limit_num)->select();
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
      
        $ToutiaoModel = new ToutiaoModel();
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
            'toutiao_id' => $id,
            'member_miniapp_id' => $this->appid,
            'content' => $content,
            'user_id' => $this->user->user_id,
        ];
        
        $CommentModel = new CommentModel();
        $CommentModel->save($data);
        $ToutiaoModel->IncDecCol($id, 'comment_num');
        $this->result('', 200, '评论成功', 'json'); 
    }



    
}