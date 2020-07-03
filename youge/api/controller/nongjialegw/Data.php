<?php
namespace app\api\controller\nongjialegw;
use app\api\controller\Common;
use app\common\model\nongjiale\CommentModel;
use app\common\model\nongjiale\TaocanModel;
use app\common\model\nongjiale\TaocanphotoModel;
use app\common\model\nongjiale\TaocanpriceModel;
use app\common\model\nongjiale\CommentphotoModel;
use app\common\model\user\UserModel;

class  Data extends  Common{
    public function detail(){
        $taocan_id = (int)$this->request->param('taocan_id');
        if(empty($taocan_id)){
            $this->result([],'400','参数错误','json');
        }
        $taocan= TaocanModel::get($taocan_id);
        if(empty($taocan)){
            $this->result([],'400','参数错误','json');
        }
        if($taocan['member_miniapp_id']!= $this->appid){
            $this->result([],'400','参数错误','json');
        }
        if($taocan['is_delete'] == 1 || $taocan['is_online'] == 0){
            $this->result([],'400','还未上架','json');
        }
        $return = [
            'taocan_id' => $taocan->taocan_id,
            'taocan_name' => $taocan->title,
            'banner'  => IMG_URL . getImg($taocan->banner),
            'lat' => $taocan->lat,
            'lng' => $taocan->lng,
            'address' => $taocan->address,
            'score'     => round($taocan->score/10,1),
            'praise_num' => $taocan->praise_num,
            'bad_num' => $taocan->bad_num,
            'restrict' => $taocan->restrict,
            'usetime' => $taocan->usetime,
            'service' => $taocan->service,
            'method' => $taocan->method,
            'other' => $taocan->other,
            'plus' => $taocan->plus,
        ];
        $date = [];
        $weekarray = array("日","一","二","三","四","五","六");
        for($i = 0; $i < 8; $i++){
            $date['day'][] = [
                'day' =>   date('m-d', strtotime('+'.$i.' day')),
                'date' => date('Y-m-d', strtotime('+'.$i.' day')),
                'week' => $weekarray[date('w',strtotime('+'.$i.' day'))],
            ];
        }
        $date['date'] = date("Y-m-d",time());
        $datas = [
            'detail' => $return,
            'date'   => $date,
        ];

        $photoArr = [];
        $photos = TaocanphotoModel::where(['taocan_id'=>$taocan_id])->select();
        foreach($photos as $val){
            $photoArr[]=IMG_URL.getImg($val->photo);
        }
        $datas['num'] = 1;
        if(!empty($photoArr)){
            $datas['photos'] = $photoArr;
            $datas['num'] = count($photoArr) + 1;
        }
        $TaocanpackagepriceModel= new TaocanpriceModel();
        //数据已在模型处理好并过滤下架产品
        $package = $TaocanpackagepriceModel->backPrice($taocan_id,$this->appid,date('Y-m-d'),true);
        $datas['package'] = [];
        foreach ($package as $val){
            $datas['package'][] = [
                'price_id' => $val['price_id'],
                'package_id' => $val['package_id'],
                'price' =>  sprintf("%.2f",$val['price']/100),
                'title'  => $val['title'],
                'is_cancel' => $val['is_cancel'],
                'is_changes' => $val['is_changes'],
                'details' => $val['details'],
                'cancel' => $val['cancel'],
                'changes' => $val['changes'],
                'especially' => $val['especially'],
                'is_show'    => 0,
                'photo'   => IMG_URL . getImg($val['photo']),
            ];
        }
        $this->result($datas,'200','加载数据成功','json');
    }


    //获取酒店的套餐和价格
    public function price(){
        $taocan_id = (int)$this->request->param('taocan_id');
        if(empty($taocan_id)){
            $this->result([],'400','参数错误','json');
        }
        $taocan= TaocanModel::get($taocan_id);
        if(empty($taocan)){
            $this->result([],'400','参数错误','json');
        }
        if($taocan['member_miniapp_id']!= $this->appid){
            $this->result([],'400','参数错误','json');
        }
        if($taocan['is_delete'] == 1 || $taocan['is_online'] == 0){
            $this->result([],'400','还未上架','json');
        }
        $date =  date('Y-m-d',strtotime($this->request->param('date')));
        $TaocanpackagepriceModel= new TaocanpriceModel();
        $package= $TaocanpackagepriceModel->backPrice($taocan_id,$this->appid,$date,true);
        $datas['package'] = [];
        foreach ($package as $val){
            $datas['package'][] = [
                'price_id' => $val['price_id'],
                'package_id' => $val['package_id'],
                'price' =>  sprintf("%.2f",$val['price']/100),
                'title'  => $val['title'],
                'is_cancel' => $val['is_cancel'],
                'is_changes' => $val['is_changes'],
                'details' => $val['details'],
                'cancel' => $val['cancel'],
                'changes' => $val['changes'],
                'especially' => $val['especially'],
                'is_show'    => 0,
                'photo'   => IMG_URL . getImg($val['photo']),
            ];
        }

        $this->result($datas,'200','数据初始化成功','json');
    }

    //获取定点评论：

    /**
     * @return bool
     */
    public function getComment(){
        $taocan_id = (int) $this->request->param('taocan_id');
        $type = (int) $this->request->param('type');
        $TaocanModel = new TaocanModel();
        if(!$taocan = $TaocanModel->find($taocan_id)){
            $this->result([],400,'不存在套餐','json');
        }
        if($taocan->member_miniapp_id != $this->appid){
            $this->result([],400,'不存在套餐','json');
        }
        $where['product_id'] = $taocan_id;
        switch ($type){
            case 1:
                $where['score'] = [">=",40];
                break;
            case 2:
                $where['score'] = [['>=',25],['<=',35]];
                break;
            case 3:
                $where['score'] = ['<=',20];

        }
        $CommentModel = new CommentModel();
        $data['totalNum'] = $CommentModel->where($where)->count();
        $list = $CommentModel->where($where)->order("comment_id desc")->limit($this->limit_bg,$this->limit_num)->select();
        if (empty($list)){
            $data['list'] = [];
            $this->result($data,200,'没有数据了','json');
        }
        $photoIds = $userIds = $roomIds = $minsuIds = [];
        foreach ($list as $val){
            $photoIds[$val->comment_id] = $val->comment_id;
            $userIds[$val->user_id] = $val->user_id;
        }
        $CommentphotoModel = new CommentphotoModel();
        $UserModel = new UserModel();
        $users = $UserModel->itemsByIds($userIds);
        $photoIds = empty($photoIds) ? 0 : $photoIds;
        $photo_where['comment_id'] = ["IN",$photoIds];
        $photo = $CommentphotoModel->where($photo_where)->select();
        $photos = [];
        foreach ($photo as $val){
            $photos[$val->comment_id][] = IMG_URL . getImg($val->photo);
        }
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'] [] = [
                'comment_id' => $val->comment_id,
                'user_id'    => $val->user_id,
                'user_nick_name' => empty($users[$val->user_id])  ? '' : $users[$val->user_id]->nick_name,
                'user_face'  => empty($users[$val->user_id]) ? '' : $users[$val->user_id]->face,
                'score'     => round($val->score/10,1),
                'content'    => $val->content,
                'content_time' => date("Y-m-d",$val->add_time),
                'reply'      => $val->reply,
                'reply_time'  => empty($val->reply_time) ? '' : date("Y-m-d",$val->reply_time),
                'photos'    => empty($photos[$val->comment_id]) ? [] : $photos[$val->comment_id],
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,'200','数据初始化成功','json');
    }

}