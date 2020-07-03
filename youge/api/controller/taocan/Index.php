<?php
namespace app\api\controller\taocan;
use app\api\controller\Common;
use app\common\model\city\CityModel;
use app\common\model\taocan\CommentModel;
use app\common\model\taocan\CommentphotoModel;
use app\common\model\taocan\DestinationjoinModel;
use app\common\model\taocan\DestinationModel;
use app\common\model\taocan\TaocanDetailModel;
use app\common\model\taocan\TaocanModel;
use app\common\model\taocan\TaocanpackagepriceModel;
use app\common\model\taocan\TaocanphotoModel;
use app\common\model\user\UserModel;
class Index extends Common{
    public function getList(){
        $order = (int) $this->request->param('order');
        $nav_id = (int) $this->request->param('nav_id');
        $type = (int) $this->request->param('type');
        $keywords =  (string) $this->request->param('keywords');
        $city_id = (int) $this->request->param('city_id');
        $destination_id = (int) $this->request->param('destination_id');
        $where['member_miniapp_id'] = $this->appid;
        $where['city_id'] = $city_id;
        $where['is_delete'] = 0;
        $where['is_online'] = 1;
        if(!empty($nav_id)){
            $where['nav_id'] = $nav_id;
        }
        if(!empty($type)){
            $where['type'] = $type;
        }
        $orderby  = '';
        switch($order){
            case 1:
                $orderby = ' orderby  desc ';
                break;
            case 2:
                $orderby = ' price asc ';
                break;
            case 3:
                $orderby = ' price desc ';
                break;
            case 4:
                $orderby = ' score desc ';
                break;
        }
        if(!empty($destination_id)){
            $DestinationjoinModel = new DestinationjoinModel();
            $destinations =  $DestinationjoinModel->where(['destination_id'=>$destination_id])->select();

                $taocanIds = [];
                foreach ($destinations as $val){
                    $taocanIds[$val->taocan_id] = $val->taocan_id;
                }
            $taocanIds = empty($taocanIds) ? 0 :   $taocanIds;
                $where["taocan_id"] = ['IN',$taocanIds];


        }
        if(!empty($keywords)){
            $keyword = htmlspecialchars($keywords);
            $where['taocan_name|address'] = ['LIKE','%'.$keyword.'%'];
        }
        $TaocanModel = new TaocanModel();
        $data['totalNum'] = $TaocanModel->where($where)->count();
        $list = $TaocanModel->where($where)->order($orderby)->limit($this->limit_bg,$this->limit_num)->select();
         if (empty($list)){
             $data['list'] = [];
             $this->result($data,'200','没有数据了','json');
         }
        $CityModel = new CityModel();
        $cityIds = [];
        foreach ($list as $val){
            $cityIds[$val->city_id] = $val->city_id;
        }
        $citys = $CityModel->itemsByIds($cityIds);
        foreach ($list as $val){
            $data['list'][] = [
                'taocan_id'  => $val->taocan_id,
                'store_id'   => $val->store_id,
                'photo'      => IMG_URL . getImg($val->photo),
                'city'       => empty($citys[$val->city_id]) ? '' : $citys[$val->city_id]->city_name,
                'order_num'  => $val->order_num,
                'price'      => sprintf("%.2f",$val->price/100),
                'taocan_name' => $val->taocan_name,
                'type'        => empty(config('dataattr.taocantypenames')[$val->type]) ? ' ': config('dataattr.taocantypenames')[$val->type],
                'is_hot'      => $val->is_hot,
                'province' => empty(config('province')[$val->province_id]) ? '' : config('province')[$val->province_id]['name'],
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,'200','数据初始化成功','json');
    }

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

        $detail  = TaocanDetailModel::get($taocan_id);
        if(empty($detail)){
            $this->result([],'400','参数错误','json');
        }
        $return = [
            'taocan_id' =>  (int) $taocan->taocan_id,
            'taocan_name' => $taocan->taocan_name,
            'taocan_tel' => $taocan->taocan_tel,
            'banner'  => IMG_URL . getImg($taocan->banner),
            'lat' => $taocan->lat,
            'lng' => $taocan->lng,
            'address' => $taocan->address,
            'score'     => round($taocan->score/10,1),
            'praise_num' => $taocan->praise_num,
            'bad_num' => $taocan->bad_num,
            'restrict' => $detail->restrict,
            'usetime' => $detail->usetime,
            'service' => $detail->service,
            'method' => $detail->method,
            'other' => $detail->other,
            'plus' => $detail->plus,
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
        $TaocanpackagepriceModel= new TaocanpackagepriceModel();
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
        $TaocanpackagepriceModel= new TaocanpackagepriceModel();
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

    /*
     *  目的地信息；
     * 可以是城市id 或者省份id；
     * */

    public function destination1(){
        $city_id = (int) $this->request->param('city_id');
        $province_id = (int) $this->request->param('province_id');
        $CityModel = new CityModel();
        if(!empty($city_id) && empty($province_id)){
            if(!$city = $CityModel->find($city_id)){
                $this->result([],'400','不存在城市','json');
            };
            $province_id = $city->province_id;
        }
        if(empty($province_id)){
            $this->result([],'400','参数错误','json');
        }
        $where['member_miniapp_id'] = $this->appid;
        $where['province_id'] = $province_id;
        $data['province'] = [];
        $provinces = config('province');
        foreach ($provinces as $val){
            $data['province'][] = [
                'province_id' => $val['id'],
                'province_name' => $val['name'],
            ];
        }
        $data['this_province_name'] = empty(config('province')[$province_id]) ? '' : config('province')[$province_id]['name'];
        $data['citys'] = [];
        $citys = $CityModel->where($where)->select();
        foreach ($citys as $val){
            $data['citys'][] = [
                'city_id' => $val->city_id,
                'city_name' => $val->city_name,
            ];
        }
        $keywords =  (string) $this->request->param('keywords');
        //追加Where
        if(!empty($keywords)){
            $keyword = htmlspecialchars($keywords);
            $where['title|title2'] = ['LIKE','%'.$keyword.'%'];
        }
        $DestinationModel = new DestinationModel();
        $destinations1 = $DestinationModel->where($where)->order('orderby desc')->limit(0,10)->select();
        $data['list'] =  $destinations = [];
        $where['is_delete'] = 0;
        foreach ($destinations1 as $val){
            $destinations [] = [
                'destination_id' => $val->destination_id,
                'title'  =>  $val->title,
                'title2'  =>  $val->title2,
                'photo'  =>  IMG_URL . getImg($val->photo),
            ];
        }
        $data['destination_num']  = $length =  floor(sizeof($destinations)/2) + (floor(sizeof($destinations)%2) > 0 ? 1:0 );
        for($i=0;$i<$length;$i++)
        {
            $data['list'][] = array_slice($destinations, $i * 2 ,2);
        }
        $this->result($data,'200','数据初始化成功','json');
    }



    /*
  *  目的地信息；
  * 可以是城市id 或者省份id；
  * */

    public function destination(){
        $CityModel = new CityModel();
        $where['member_miniapp_id'] = $this->appid;
        $city = $CityModel->where($where)->order("orderby desc")->select();
        $data['city'] = [];
        foreach ($city as $val){
            $data['city'][] = [
                'city_id' => $val->city_id,
                'city_name' => $val->city_name,
            ];
        }
        $city_id = (int) $this->request->param('city_id');
        if($city_id == 0){
            $_where['city_id'] = $city[0]->city_id;
        }else{
            $_where['city_id'] = $city_id;
        }
        $_city =  $CityModel->find($_where['city_id']);
        if(empty($_city)){
            $this->result('',200,'参数错误','json');
        }
        if($_city->member_miniapp_id != $this->appid){
            $this->result('',200,'参数错误','json');
        }
        $city_where['member_miniapp_id'] = $this->appid;
        $city_where['province_id'] = $_city->province_id;
       $tjcity =   $CityModel->where($city_where)->order('orderby desc')->select();
        $data['tjcity'] = [];
        foreach ($tjcity as $val){
            $data['tjcity'][] = [
                'city_id' => $val->city_id,
                'city_name' => $val->city_name,
            ];
        }
        $data['this_city_id'] = $_where['city_id'];
        $_where['member_miniapp_id'] = $this->appid;
        $_where['is_delete'] = 0;
        $DestinationModel = new DestinationModel();
        $destinations1 = $DestinationModel->where($_where)->order('orderby desc')->limit(0,10)->select();
        $data['list'] =  $destinations = [];
        foreach ($destinations1 as $val){
            $destinations [] = [
                'destination_id' => $val->destination_id,
                'title'  =>  $val->title,
                'title2'  =>  $val->title2,
                'photo'  =>  IMG_URL . getImg($val->photo),
            ];
        }
        $data['destination_num']  = $length =  floor(sizeof($destinations)/2) + (floor(sizeof($destinations)%2) > 0 ? 1:0 );
        $data['list'] = $destinations;
        $this->result($data,'200','数据初始化成功','json');
    }


    public function  getComment(){
        $taocan_id = (int) $this->request->param('taocan_id');
        $type = (int) $this->request->param('type');
        $TaocanModel = new TaocanModel();
        if(!$taocan = $TaocanModel->find($taocan_id)){
            $this->result([],400,'不存在民宿','json');
        }
        if($taocan->member_miniapp_id != $this->appid){
            $this->result([],400,'不存在民宿','json');
        }
        $where['taocan_id'] = $taocan_id;
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