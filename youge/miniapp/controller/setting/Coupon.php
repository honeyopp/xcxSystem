<?php
namespace app\miniapp\controller\setting;
use app\miniapp\controller\Common;
use app\common\model\setting\SettingcouponModel;
class Coupon extends Common {
//array(4) { ["money"]=> string(3) "222" ["expire_day"]=> string(4) "2222" ["use_day"]=> string(3) "222" ["need_money"]=> string(4) "2222" }
    public function index() {
        $CouponModel  = new SettingcouponModel();
        $coupon = $CouponModel->find($this->miniapp_id);
        if($this->request->method() == "POST"){
            //新用户登录处理
           $login = $_POST['data']['login'];
           $setting['login']['money']  =  ((int) $login['money'] * 100);
           $setting['login']['expire_day'] =  (int) $login['expire_day'] <= 0 ? 7  : (int) $login['expire_day'];
           $setting['login']['use_day'] = (int) $login['use_day'] <=0 ? 0 :  (int) $login['use_day'];
           $setting['login']['need_money'] = (int) $login['need_money']  <= (int) $login['money'] ? ((int)$login['money'] * 2) * 100 : (int) $login['need_money'] * 100;
           //新用户下单数据源处理
           $order = $_POST['data']['order'];
            $setting['order']['money']  =  ((int) $order['money'] * 100);
            $setting['order']['expire_day'] =  (int) $order['expire_day'] <= 0 ? 7  : (int) $order['expire_day'];
            $setting['order']['use_day'] = (int) $order['use_day'] <=0 ? 0 :  (int) $order['use_day'];
            $setting['order']['need_money'] = (int) $order['need_money']  <= (int) $order['money'] ? ((int)$order['money'] * 2) * 100 : (int) $order['need_money'] * 100;

            $setting =  serialize($setting);
            if(empty($coupon)){
                $data['member_miniapp_id'] = $this->miniapp_id;
                $data['value'] = $setting;
                $CouponModel->save($data);
            }else{
                $data['value'] = $setting;
                $CouponModel->save($data,['member_miniapp_id'=>$this->miniapp_id]);
            }
            $this->success("操作成功",null,101);
        }else{

            $this->assign('coupon',unserialize($coupon['value']));
            return $this->fetch();
        }
    }

}