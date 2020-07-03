<?php
namespace app\api\controller\tongcheng;
use app\api\controller\Common;
use app\common\model\setting\SkinModel;
use app\common\model\tongcheng\CategoryModel;
use app\common\model\tongcheng\OrderModel;
use app\common\model\tongcheng\PriceModel;
use app\common\model\tongcheng\CommentModel;
use app\common\model\tongcheng\InfoModel;
use app\common\model\tongcheng\InfophotoModel;

class Member extends Common{
    protected $checklogin = true;
    /*
     * 评论信息
     */
    public function comment(){
        $info_id = (int) $this->request->param('info_id');
        $InfoModel = new InfoModel();
        if(!$info = $InfoModel->find($info_id)){
            $this->result('',400,'不存在信息','json');
        }
        if($info->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在信息','json');
        }
        $InfoModel->where(['info_id'=>$info_id])->setInc('comment_num');
        $data['member_miniapp_id'] = $this->appid;
        $data['info_id'] = $info_id;
        $data['user_id'] = $this->user->user_id;
        $data['info_user_id'] = $info->user_id;
        $data['content']  = (string) $this->request->param('content');
        if(empty($data['content'])){
            $this->result('',400,'请输入内容','json');
        }
        $CommentModel = new CommentModel();
        $CommentModel->save($data);
        $this->result('',200,'操作成功','json');
    }
    /*
     * 发布信息；
     */
    public function addInfo(){
        $price_id = (int) $this->request->param('price_id');
        $price = [];
        if(!empty($price_id)){
            $PriceModel = new PriceModel();
            if(!$price = $PriceModel->find($price_id)){
                $this->result('',200,'参数错误','json');
            }
            if ($price->member_miniapp_id != $this->appid){
                $this->result('',200,'参数错误','json');
            }
        }
        $category_id = (int) $this->request->param('category_id');
        if(empty($category_id)){
            $this->result('',400,'请选择分类','json');
        }
        $data['user_id'] = $this->user->user_id;
        $data['member_miniapp_id'] = $this->appid;
        $data['category_id'] = $category_id;
        $data['info'] = (string) $this->request->param('info');
        if(empty($data['info'])){
            $this->result('',400,'请填写信息','json');
        }
        $data['tel'] = (string) $this->request->param('tel');
        if(empty($data['tel'])){
            $this->result('',400,'请填写联系方式','json');
        }
        $data['lat']  = $this->request->param('lat');
        $data['lng']  = $this->request->param('lng');
        if(empty($data['lat']) || empty($data['lng'])){
            $this->result('',400,'请定位','json');
        }
        $data['address'] = (string) $this->request->param('address');
        if(empty($data['address'])){
            $this->result('',400,'请输入地址','json');
        }
        $InfoModel = new InfoModel();
        $InfoModel->save($data);
       $info_id = $InfoModel->info_id;
        $_img  = file_get_contents("php://input");
        $_img =  json_decode($_img,true);
        $img = $_img['photo'];
        if(!empty($img)){
            $photo = [];
            foreach ($img as $val) {
                $photo[] = [
                    'info_id' => $info_id,
                    'member_miniapp_id' => $this->appid,
                    'photo' => $val,
                ];
            }
        $InfophotoModel = new InfophotoModel();
        $InfophotoModel->saveAll($photo);
      }
         //先发布 后支付 ；
        if(!empty($price)){
            $this->pay($price,$info_id);
        }else{
            $this->result('',200,'操作成功','json');
        }
    }
    /*
     * 置顶支付
     * @param obj price 价格表的id；
     */
    protected function pay($price,$info_id){
        $OrderModel = new OrderModel();
        $OrderModel->save([
            'user_id' => $this->user->user_id,
            'member_miniapp_id' => $this->appid,
            'total_price' => $price->price,
            'need_pay' => $price->price,
            'day_num' => $price->day_num,
            'info_id' => $info_id,
        ]);
        $order_id = $OrderModel->order_id;
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($this->appid);
        define('WX_APPID',$setting['mini_appid']);
        define('WX_MCHID',$setting['mini_mid']);
        define('WX_KEY',$setting['mini_apicode'] );
        define('WX_APPSECRET',$setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH',ROOT_PATH.'/attachs/wxapiclient/'.$setting['apiclient_cert']);
        define('WX_SSLKEY_PATH',ROOT_PATH.'/attachs/wxapiclient/'.$setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST','0.0.0.0');
        define('WX_CURL_PROXY_PORT',0);
        define('WX_REPORT_LEVENL',0);
        require_once ROOT_PATH."/weixinpay/lib/WxPay.Api.php";
        require_once ROOT_PATH."/weixinpay/example/WxPay.JsApiPay.php";
        $tools = new \JsApiPay();
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("置顶服务".$order_id);
        $input->SetAttach($order_id);
        $input->SetOut_trade_no(WX_MCHID.rand(1000,9999).$order_id);
        $input->SetTotal_fee($price->price);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        // $input->SetGoods_tag();
        $input->SetNotify_url("https://".$_SERVER['HTTP_HOST']."/api/weixin/notifzhiding/appid/".$this->appid.'.html');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($this->request->param('openid'));
        $order = \WxPayApi::unifiedOrder($input);
        //var_dump($order);die;
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $this->result(['order'=>  json_decode($jsApiParameters,true)], '200', '创建支付成功！', 'json');
    }
    /*
     *
     * 楼主回复
     */
    public function reply(){
        $comment_id = (int) $this->request->param('comment_id');
        $CommentModel  = new CommentModel();
        if(!$detail = $CommentModel->find($comment_id)){
            $this->result('',400,'参数错误','json');
        }
        if($detail->info_user_id != $this->user->user_id){
            $this->result('',400,'参数错误','json');
        }
        $data['reply'] = (string) $this->request->param('reply');
        if(empty($data['reply'])){
            $this->result('',400,'请输入回复内容','json');
        }
        $data['reply_time'] = $this->request->time();
        $CommentModel->save($data,['comment_id'=>$comment_id]);
        $this->result('',200,'评论成功','json');
    }
    /*
     * 获取我的发布
     */
    public function getInfo(){
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $InfoModel = new InfoModel();
        $list = $InfoModel->where($where)->limit($this->limit_bg,$this->limit_num)->select();
        $infoIds =  $categoryIds = [];
        foreach ($list as $val){
            $infoIds[$val->info_id] = $val->info_id;
            $categoryIds[$val->category_id] = $val->category_id;
        }
        $InfophotoModel = new InfophotoModel();
        $photo =  $InfophotoModel->where(['info_id'=>['IN',$infoIds]])->select();
        $photos = [];
        foreach ($photo as $val){
            $photos[$val->info_id][] = IMG_URL . getImg($val->photo);
        }
        $CategoryModel = new CategoryModel();
        $category = $CategoryModel->itemsByIds($categoryIds);
        $time = time();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'info_id' => $val->info_id,
                'info'  => $val->info,
                'photo' => empty($photos[$val->info_id]) ? [] : $photos[$val->info_id],
                'category_name' => empty($category[$val->category_id]) ? '' :  $category[$val->category_id]->name,
                'user_name' => $this->user->nick_name,
                'user_face' =>  getImg($this->user->face),
                'tel'  => $val->tel,
                'view_num'  => $val->view_num,
                'zan_num'  => $val->zan_num,
                'comment_num'  => $val->comment_num,
                'add_time'  => date("m月d日 H:i",$val->add_time),
                'address' => $val->address,
                'is_top' => $val->expire_time < $time ? 0 : 1,
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }
}