<?php
namespace app\manage\controller;
use app\common\model\member\MemberModel;
use app\common\model\member\MoneylogModel;
use app\common\model\miniapp\AuthorizerModel;
use app\common\model\miniapp\DescribeModel;
use app\common\model\miniapp\MiniappModel;
use app\common\model\miniapp\PhotoModel;
use app\common\model\order\OrderModel;
use app\common\model\setting\SettingModel;
use app\common\model\user\ActivitylogModel;
class Miniappshop extends Common{
    protected  $agent = [];
    public function _initialize() {
        parent::_initialize();
        $nowtime = time();
        $this->assign('nowtime',$nowtime);
        $this->assign('tixing',$nowtime-86400*30); //即将过期
        $SettingModel = new SettingModel();
        $agent = $SettingModel->fetchAll(true);
        $this->agent = $agent['agent'];
        $this->assign('agent',$agent['agent']);
    }
    public function index(){
        $member_miniapp_id = (int)$this->request->param('member_miniapp_id');
        $buys = [];
        if(!empty($member_miniapp_id)){
            $auth  = AuthorizerModel::get($member_miniapp_id);
            if(empty($auth)){
                $this->error('参数错误');
            }
            if($auth->member_id != $this->member_id){
                $this->error('参数错误');
            }
            $order = new OrderModel();
            $buys = $order->getBuyIds($this->member_id, $member_miniapp_id);
        }

        if($this->member_id!=1){
            $where = ['is_online'=>1];//非开发者ID只能看上线的模版
        }else{
             $where = [];
        }
        $list = MiniappModel::where($where)->order(['orderby'=>'desc'])->select();
        $this->assign('list', $list);
        $this->assign('member_miniapp_id',$member_miniapp_id);
        $this->assign('buys',$buys);
        return  $this->fetch();
    }
    /*模板详情*/
    public function miappdetail(){
        $miniapp_id = (int) $this->request->param('miniapp_id');
        $MiniModel = new MiniappModel();
        if(!$detail = $MiniModel->find($miniapp_id)){
            $this->error("请选择模板");
        }
        $member_miniapp_id = (int)$this->request->param('member_miniapp_id');
         $buys = '';
        if(!empty($member_miniapp_id)){
            $auth  = AuthorizerModel::get($member_miniapp_id);
            if(empty($auth)){
                $this->error('参数错误');
            }
            if($auth->member_id != $this->member_id){
                $this->error('参数错误');
            }
            $order = new OrderModel();
            $buys = $order->get(['member_id'=>  $this->member_id,'miniapp_id'=>$miniapp_id,'member_miniapp_id'=>$member_miniapp_id]);
        }

        $PhotoModel = new PhotoModel();
        $photos = $PhotoModel->where(['miniapp_id'=>$miniapp_id])->order("orderby desc")->select();
        $DescribeModel = new DescribeModel();
        $describes = $DescribeModel->where(['miniapp_id'=>$miniapp_id])->order("orderby desc")->select();
        $describes_text = [];
        $this->assign('describes',$describes);

        $this->assign('buys',$buys);
        $this->assign('photos',$photos);
        $this->assign('detail',$detail);
        $this->assign('member_miniapp_id',$member_miniapp_id);

        return $this->fetch();
    }


    public function shiyong2(){
        $miniapp_id = (int) $this->request->param('miniapp_id');
        $MiniModel = new MiniappModel();
        if(!$detail = $MiniModel->find($miniapp_id)){
            $this->error("请选择模板");
        }
        $member_miniapp_id = (int)$this->request->param('member_miniapp_id');
        if(empty($member_miniapp_id)){
             $this->error("请选择要使用的程序");
        }
        $auth  = AuthorizerModel::get($member_miniapp_id);
        if(empty($auth)){
            $this->error('参数错误');
        }
        if($auth->member_id != $this->member_id){
            $this->error('参数错误');
        }
        $order = new OrderModel();
        $buys = $order->get(['member_id'=>  $this->member_id,'miniapp_id'=>$miniapp_id,'member_miniapp_id'=>$member_miniapp_id]);
        if(!empty($buys)){
            $this->error('您已经试用过了不能重复试用');
        }
        $guoqi = time() + $detail->expire_day*86400;
//        if($this->request->time() < 1505834639){
//            $guoqi = time() + 360 * 86400;
//        }
        $ordersave = [
            'member_id' => $this->member_id,
            'miniapp_id' => $miniapp_id,
            'member_miniapp_id' => $member_miniapp_id,
            'expir_time'  => $guoqi,
        ];
        $orderModel = new OrderModel();
        if($orderModel->save($ordersave)){
            $AuthorizerModel = new AuthorizerModel();
            $AuthorizerModel->save(['miniapp_id'=>$miniapp_id,'expir_time'=>$guoqi,'status'=>0],['member_miniapp_id'=>$member_miniapp_id]);
        }
        $this->success('试用成功！',url('miniapp/index'));
    }

    public function shiyong(){
        $miniapp_id = (int) $this->request->param('miniapp_id');
        $MiniModel = new MiniappModel();
        if(!$detail = $MiniModel->find($miniapp_id)){
            $this->error("请选择模板");
        }
        $member_miniapp_id = (int)$this->request->param('member_miniapp_id');
        $buys = '';
        if(!empty($member_miniapp_id)){
            $order = new OrderModel();
            $buys = $order->get(['member_id'=>  $this->member_id,'miniapp_id'=>$miniapp_id,'member_miniapp_id'=>$member_miniapp_id]);
            if(!empty($buys)){
                $this->error('您已经试用过了不能再重复试用！');
            }
        }

        $AuthorizerModel = new AuthorizerModel();
        $myApp = $AuthorizerModel->where(['member_id'=>  $this->member_id])->select();
        if(empty($myApp)){
            $this->error('您还没有添加小程序授权！',url('miniapp/create'));
        }

        $this->assign('member_miniapp_id',$member_miniapp_id);
        $this->assign('myApp',$myApp);

        $this->assign('miniapp_id',$miniapp_id);
        $this->assign('detail',$detail);
        return $this->fetch();
    }

    public function used(){
        $order_id = $this->request->param('order_id');
        $order = new OrderModel();
        if(empty($order_id)){
            $this->error('请选择要使用的模版');
        }
        if(!$detail = $order->get($order_id)){
            $this->error('请选择要使用的模版');
        }
        if($detail['member_id']!= $this->member_id){
            $this->error('不可使用');
        }
        if($detail['expir_time']< time()){
            $this->error('模版已经过期');
        }
        $AuthorizerModel = new AuthorizerModel();
        $AuthorizerModel->save(['expir_time'=>$detail['expir_time'],'miniapp_id'=>$detail['miniapp_id']],['member_miniapp_id'=>$detail['member_miniapp_id']]);
        $this->success('使用成功！',url('miniapp/index'));
    }

    public function buy2(){
        $miniapp_id = (int) $this->request->param('miniapp_id');
        $MiniModel = new MiniappModel();
        if(!$detail = $MiniModel->find($miniapp_id)){
            $this->error("请选择模板");
        }
        $member_miniapp_id = (int)$this->request->param('member_miniapp_id');
        if(empty($member_miniapp_id)){
             $this->error("请选择要使用的程序");
        }
        $auth  = AuthorizerModel::get($member_miniapp_id);
        if(empty($auth)){
            $this->error('参数错误');
        }
        if($auth->member_id != $this->member_id){
            $this->error('参数错误');
        }

        $price = $detail->price;
        if(!empty($this->agent[$this->member_info->type])){
            $price = $price * ($this->agent[$this->member_info->type]['discount']/100);
        }
        if($this->member_info->money<$price){
            $this->error('账户余额不足请充值！');
        }
        $order = new OrderModel();
        $buys = $order->get(['member_id'=>  $this->member_id,'miniapp_id'=>$miniapp_id,'member_miniapp_id'=>$member_miniapp_id]);
        $MemberModel = new MemberModel();
        $money =   $this->member_info->money - $price;
        if($MemberModel->save(['money'=>$money],['member_id'=>  $this->member_id])){
            $MoneyLogModel = new MoneylogModel();
            $MoneyLogModel->save([
                'member_id' => $this->member_id,
                'way'       => 2,
                'money'     => $price,
                'this_money' => $money,
                'is_consume' => 1
            ]);
            if(!empty($buys)){
                $nowtime = time();
                $time = 0;
                if($buys['expir_time'] > $nowtime){
                    $time = $buys['expir_time'] +config('setting.service_day')*86400;
                }else{
                    $time = $nowtime +config('setting.service_day')*86400;
                }
                $OrderModel = new OrderModel();
                $OrderModel->save(['expir_time'=>$time],['order_id'=>$buys['order_id']]);
                $AuthorizerModel = new AuthorizerModel();
                $AuthorizerModel->save(['expir_time'=>$time,'miniapp_id'=>$miniapp_id],['member_miniapp_id'=>$member_miniapp_id]);
            }else{
                $OrderModel = new OrderModel();
                $guoqi = time() + config('setting.service_day')*86400;
                $ordersave = [
                    'member_id' => $this->member_id,
                    'miniapp_id' => $miniapp_id,
                    'member_miniapp_id' => $member_miniapp_id,
                    'expir_time'  => $guoqi,
                ];
                $OrderModel->save($ordersave);
                $AuthorizerModel = new AuthorizerModel();
                $AuthorizerModel->save(['expir_time'=>$guoqi,'miniapp_id'=>$miniapp_id],['member_miniapp_id'=>$member_miniapp_id]);
            }
            $this->success('操作成功！',url('miniapp/index'));
        }
    }
    //购买应用
    public function buy(){
        $miniapp_id = (int) $this->request->param('miniapp_id');
        $type = (int)  $this->request->param('type');
        $MiniModel = new MiniappModel();
        if(!$detail = $MiniModel->find($miniapp_id)){
            $this->error("请选择模板");
        }
        $member_miniapp_id = (int)$this->request->param('member_miniapp_id');

        $AuthorizerModel = new AuthorizerModel();
        $myApp = $AuthorizerModel->where(['member_id'=>  $this->member_id])->select();
        if(empty($myApp)){
            $this->error('您还没有添加小程序授权！',url('miniapp/create'));
        }
        $this->assign('member_miniapp_id',$member_miniapp_id);
        $this->assign('myApp',$myApp);
        $this->assign('miniapp_id',$miniapp_id);
        $this->assign('detail',$detail);
        $this->assign('type',$type);
        return $this->fetch();

    }


}
