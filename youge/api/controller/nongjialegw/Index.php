<?php
namespace  app\api\controller\nongjialegw;

use app\api\controller\Common;
use app\common\model\nongjiale\NewscommentModel;
use app\common\model\nongjiale\NewscontentModel;
use app\common\model\nongjiale\NewsModel;
use app\common\model\nongjiale\ProjectModel;
use app\common\model\nongjiale\RoomModel;
use app\common\model\nongjiale\RoompriceModel;
use app\common\model\nongjiale\StoreModel;
use app\common\model\nongjiale\StorephotoModel;
use app\common\model\nongjiale\TaocanModel;
use app\common\model\user\UserModel;

class  Index extends Common{
    /*
     *banner   农庄简介 农庄相册 农庄动态 农庄套餐
     * */
    public function index(){
       $where['member_miniapp_id'] = $this->appid;
       $StoreModel = new StoreModel();
       $store = $StoreModel->where($where)->find();
       if(empty($store)){
           $this->result([],'200','请设置网站数据','json');
       }
       $photo = [];
       $StorephotoModel= new StorephotoModel();
       $photo_where['store_id'] = $store->store_id;
       $storePhoto =  $StorephotoModel->where($photo_where)->order("orderby desc")->limit('0.20')->select();
       foreach ($storePhoto as $val){
           $photo [] =[
               'photo' => IMG_URL . getImg($val->photo),
           ];
       }
       $news = [];
       $NewsModel = new NewsModel();
       $_news = $NewsModel->where(['member_miniapp_id'=>$this->appid])
                         ->limit(0,2)
                         ->order('news_id desc')
                         ->select();
        foreach ( $_news as $val){
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
            $news[] = [
                'id' => $val->news_id,
                'title' => $val->title,
                'author' => $val->author,
                'type' =>$type,
                'add_time' => date('m/d'),
                'photo1' => empty($val->photo1)?'':IMG_URL.getImg($val->photo1),
                'photo2' => empty($val->photo2)?'':IMG_URL.getImg($val->photo2),
                'photo3' => empty($val->photo3)?'':IMG_URL.getImg($val->photo3),
                'comment_num' => $val->comment_num,
                'views' => $val->views,
                'share_num'=>$val->share_num,
            ];
        }
       $taocan = [];
        $TaocanModel = new TaocanModel();
        $_taocan = $TaocanModel->where(['member_miniapp_id'=>$this->appid,'is_hot'=>1,'is_delete'=>0,'is_online'=>1])
                               ->order('orderby desc')->limit(0,1)
                               ->select();
        foreach ($_taocan as $val){
            $taocan[] = [
                'taocan_id' => $val->taocan_id,
                'photo' => IMG_URL . getImg($val->photo),
                'price'  => sprintf("%.2f",$val->price/100),
                'title'  => $val->title,
                'order_num' => $val->order_num,
                'is_hot' => $val->is_hot,
            ];
        }
       $xiangmu = [];

        $ProjectModel = new ProjectModel();
        $_xiangmu =  $ProjectModel->where(['member_miniapp_id'=>$this->appid])
                                  ->order('orderby desc')
                                  ->limit(0,3)
                                  ->select();
        foreach ($_xiangmu as $val){
            $xiangmu[] = [
                'project_id'  => $val->project_id,
                'photo'      => IMG_URL . getImg($val->photo),
                'content'   => $val->content,
                'title'    => $val->title,
            ];
        }
       $data = [
           'banner' => IMG_URL . getImg($store->banner),
           'introduce' => $store->introduce,
           'store_weixin' => $store->store_weixin,
           'store_company' => $store->store_company,
           'traffic' => $store->traffic,
           'store_tel' => $store->store_tel,
           'address' => $store->address,
           'store_name' => $store->store_name,
           'lat' => $store->lat,
           'lng' => $store->lng,
           'photo' => $photo,
           'news' => $news,
           'project' => $xiangmu,
           'taocan' => $taocan,
       ];

      $this->result($data,'200','数据初始化成功','json');
    }
    public  function getXiangmu (){
        $data['list'] = [];

        $ProjectModel = new ProjectModel();
        $_xiangmu =  $ProjectModel->where(['member_miniapp_id'=>$this->appid])
            ->order('orderby desc')
            ->limit($this->limit_bg,$this->limit_num)
            ->select();
        foreach ($_xiangmu as $val){
            $data['list'][] = [
                'project_id'  => $val->project_id,
                'photo'      => IMG_URL . getImg($val->photo),
                'content'   => $val->content,
                'title'    => $val->title,
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,'200','数据初始化成功','json');
    }
    public function getNews(){
        $data['list'] = [];
        $NewsModel = new NewsModel();
        $_news = $NewsModel->where(['member_miniapp_id'=>$this->appid])
            ->limit($this->limit_bg,$this->limit_num)
            ->order('news_id desc')
            ->select();
        foreach ( $_news as $val){
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
            $data['list'][] = [
                'id' => $val->news_id,
                'title' => $val->title,
                'author' => $val->author,
                'type' =>$type,
                'add_time' => date('m/d'),
                'photo1' => empty($val->photo1)?'':IMG_URL.getImg($val->photo1),
                'photo2' => empty($val->photo2)?'':IMG_URL.getImg($val->photo2),
                'photo3' => empty($val->photo3)?'':IMG_URL.getImg($val->photo3),
                'comment_num' => $val->comment_num,
                'views' => $val->views,
                'share_num'=>$val->share_num,
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,'200','数据初始化成功','json');
    }
    public function detail(){
        $id = (int)$this->request->param('id');

        $ToutiaoModel = new NewsModel();
        if (!$detail = $ToutiaoModel->get($id)) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }

        $contents =NewscontentModel::where(['member_miniapp_id'=>  $this->appid,'news_id'=>$id])->order(['orderby'=>'asc'])->select();

        $contentArr = [];
        foreach($contents as $val){
            $contentArr[]=[
                'content' => $val->content,
                'photo'   => empty($val->photo) ? '' : IMG_URL.getImg($val->photo)
            ];
        }
        $data = [
            'id' => $id,
            'title' => $detail->title,
            'author' => $detail->author,
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
        $CommentModel = new NewscommentModel();
        if(!$detail = $CommentModel->get($commentId)){
            $this->result('', 400, '没有该数据1', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有该数据2', 'json');
        }
        $CommentModel->IncDecCol($commentId, 'zan_num');
        $this->result('', 200, '操作成功', 'json');
    }
    public function datas(){
        $where = ['member_miniapp_id'=>  $this->appid];
        $toutiao = NewsModel::where($where)->order(['news_id'=>'desc'])->limit($this->limit_bg,$this->limit_num)->select();
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
                'id' => $val->news_id,
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
    public function getCommentList(){
        $id = (int)$this->request->param('id');

        $ToutiaoModel = new NewsModel();
        if (!$detail = $ToutiaoModel->get($id)) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }
        $comments = NewscommentModel::where(['member_miniapp_id'=>  $this->appid,'news_id'=>$id])->order(['comment_id'=>'desc'])->limit($this->limit_bg,$this->limit_num)->select();
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

        $ToutiaoModel = new NewsModel();
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
            'news_id' => $id,
            'member_miniapp_id' => $this->appid,
            'content' => $content,
            'user_id' => $this->user->user_id,
        ];

        $CommentModel = new NewscommentModel();
        $CommentModel->save($data);
        $ToutiaoModel->IncDecCol($id, 'comment_num');
        $this->result('', 200, '评论成功', 'json');
    }
    public function share(){
        $id = (int)$this->request->param('id');

        $ToutiaoModel = new NewsModel();
        if (!$detail = $ToutiaoModel->get($id)) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }
        $ToutiaoModel->IncDecCol($id,'share_num');
        $this->result('', 200, '操作成功', 'json');
    }
    public function detail2(){
        $id = (int)$this->request->param('id');

        $ToutiaoModel = new NewsModel();
        if (!$detail = $ToutiaoModel->get($id)) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有要查看的头条', 'json');
        }
        $data = [
            'id' => $id,
            'title' => $detail->title,
            'author' => $detail->author,
            'nav'   =>!empty($nav) ? $nav->nav_name:'',
            'video_url' => $detail->video_url,
            'comment_num' => $detail->comment_num,
            'views' => $detail->views,
            'share_num' => $detail->share_num,
        ];
        $ToutiaoModel->IncDecCol($id,'views');
        $this->result($data, 200, '获取数据成功', 'json');
    }
    public function getTaocan(){
        $TaocanModel = new TaocanModel();
        $where['member_miniapp_id'] = $this->appid;
        $where['is_online'] = 1;
        $where['is_delete'] = 0;
        $data['totalNum'] = $TaocanModel->where($where)->count();
        $list = $TaocanModel->where($where)->order("orderby desc")->limit($this->limit_bg,$this->limit_num)->select();
        if (empty($list)){
            $data['list'] = [];
            $this->result($data,'200','没有数据了','json');
        }
        foreach ($list as $val){
            $data['list'][] = [
                'taocan_id' => $val->taocan_id,
                'photo' => IMG_URL . getImg($val->photo),
                'price'  => sprintf("%.2f",$val->price/100),
                'title'  => $val->title,
                'order_num' => $val->order_num,
                'is_hot' => $val->is_hot,
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,'200','数据初始化成功','json');
    }

    public function getRooms(){
        $bg_date = date('Y-m-d',strtotime($this->request->param('bg_date')));
        $end_date = date('Y-m-d',strtotime($this->request->param('end_date')));
        $where['member_miniapp_id'] = $this->appid;
        $where['is_online'] = 1;
        $where['is_delete'] = 0;
        $room = RoomModel::where($where)->select();
        $roomids = $room_num = [];
        foreach($room as $val){
            $roomids[$val->room_id] = $val->room_id;
            $room_num[$val->room_id] = $val->day_num;
        }
        //计算客满和日期价格
        $manroom = $roomprice = $unline = [];
        if(!empty($roomids)){
            $prices = RoompriceModel::where(['room_id'=>['IN',$roomids],'day'=>['BETWEEN',[$bg_date,$end_date]]])->limit($this->limit_bg,$this->limit_num)->select();
            if(!empty($prices)){
                foreach($prices as $val){
                    if($val->room_num >= $room_num[$val->room_id]){
                        $manroom[$val->room_id] = $val->room_id;
                    }
                    if($val->is_online==0){
                        $unline[$val->room_id]  = $val->room_id;
                    }
                    if($val->price>0 && $val->day == $bg_date){
                        $roomprice[$val->room_id] = $val->price;
                    }
                }
            }
        }
        //返回房屋列表
        $return = [];
        $config =config('dataattr.hotelbedtype');
        foreach($room as $val){
            if(!isset($unline[$val->room_id])){
                $return['list'][] =[
                    'id'    => $val->room_id,
                    'title' => $val->title,
                    'area'  => $val->area,
                    'photo' => IMG_URL.  getImg($val->photo),
                    'appropriate_num'=>$val->appropriate_num,
                    'is_wifi'       => $val->is_wifi,
                    'price'         => isset($roomprice[$val->room_id]) ? round($roomprice[$val->room_id]/100,2) : round($val->price/100,2),
                ];
            }
        }
        $return['more']  = count($return['list']) == $this->limit_num ? 1: 0;
        $return['bg_date'] = $bg_date;
        $return['end_date'] = $end_date;
        $this->result($return,'200','加载房型成功','json');
    }

}