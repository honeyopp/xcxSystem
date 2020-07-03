<?php
namespace app\api\controller;
use app\common\model\mobile\CodeModel;
use app\common\model\setting\ActivityModel;
use app\common\model\setting\SettingcouponModel;
use app\common\model\user\ActivitylogModel;
use app\common\model\user\AddressModel;
use app\common\model\user\CouponModel;
use app\common\model\user\UserModel;
class User extends  Common{
    protected $checklogin = true;
    //修改信息
    public function editinfo (){
        $data['real_name'] = $this->request->param('real_name');
        $data['nick_name'] = $this->request->param('nick_name');
        $data['sex']  = (int) $this->request->param('sex');
        $data['birthday'] = $this->request->param('birthday');
    }
    //绑定手机号；更换手机号;
    public function bindMobile(){
        $this->checklogin = true;
        $data['mobile'] = $this->request->param('mobile');
        $code =  $this->request->param('code');
        if(empty( $data['mobile']) || empty($code)){
            $this->result([],400,'手机号或验证码输入不正确','json');
        }
        $CodeModel = new CodeModel();
        if (!$codeInfo = $CodeModel->get(['mobile' => $data['mobile']])) {
            $this->result([], 400 ,'验证码不正确','json');
        }
        if ($codeInfo['code_time'] < $_SERVER['REQUEST_TIME'] - 600) {
            $CodeModel->save(['err_num' => $codeInfo['err_num'] + 1], ['code_id' => $codeInfo['code_id']]);
            $this->result([], 400 ,'短信验证码已经过期','json');
        }
        if ($codeInfo['code'] != $code) {
            $CodeModel->save(['err_num' => $codeInfo['err_num'] + 1], ['code_id' => $codeInfo['code_id']]);
            $this->result([], 400 ,'验证码不正确','json');
        }
        if ($codeInfo['err_num'] > 5) {
            $this->result([], 400 ,'请重新获取验证码','json');
        }

        $UserModel = new UserModel();
       $UserModel->save($data,['user_id'=>$this->user->user_id]);
        $data = [
            'user_id'   => $this->user->user_id,
            'open_id'   => $this->user->open_id,
            'face'      => $this->user->face,
            'nick_name' => $this->user->nick_name,
            'real_name' => $this->user->real_name,
            'mobile'    => $data['mobile'],
            'sex'       => $this->user->sex,
            'birthday'  => $this->user->birthday,
            'is_lock'    => $this->user->is_lock,
            'day'        => $this->user->day,
            'last_time'  => $this->user->last_time,
            'last_ip'    => $this->user->last_ip,
        ];
        $settngCouponModel = new SettingcouponModel();
        if($coupondetail = $settngCouponModel->find($this->appid)){
            $coupon = unserialize($coupondetail->value)['login'];
            if($coupon['money'] > 0){
                $CouponModel = new CouponModel();
                $CouponModel->save([
                    'user_id' => $this->user->user_id,
                    'member_miniapp_id' => $this->appid,
                    'way'    => 2,
                    'need_money' => $coupon['need_money'],
                    'money'   =>  $coupon['money'],
                    'expir_time' => $coupon['expire_day'] <= 0 ? 7 * 86400 + time() : $coupon['expire_day'] * 86400 + time() ,
                    'can_use_time' => $coupon['use_day'] <= 0 ? $this->request->time() : $coupon['use_day'] * 86400 + time(),
                ]);
            }
        }

        $this->result($data,200,"绑定手机号:{$data['mobile']}成功",'json');
    }
    /**
     * 领取红包；
     * @param activity_id (int) 活动优惠券的id;
     */
    public function Receive(){
        $activity_id = (int) $this->request->param('activity_id');
        $ActivityModel = new ActivityModel();
        $ActivitylogModel = new ActivitylogModel();
        $log_where['user_id'] = $this->user->user_id;
        $log_where['activity_id'] = $activity_id;
        if($ActivitylogModel->where($log_where)->find()){
            $this->result([],400,'您领取过该优惠券了','json');
        }
        if(!$activity = $ActivityModel->find($activity_id)){
            $this->result([],400,'红包已领完','json');
        }
        if($activity->member_miniapp_id != $this->appid){
            $this->result([],400,'红包已领完','json');
        }
        $date = date("Y-m-d",time());
        if($activity->bg_date > $date){
            $this->result([],400,'活动还未开始','json');
        }
        if($activity->end_date < $date){
            $this->result([],400,'活动已结束','json');
        }
        if($activity->num <= 0 ){
            $this->result([],400,'红包已领完','json');
        }
        if($activity->money == 0){
            $this->result([],400,'红包已领完','json');
        }
        if($activity->is_newuser == 0){
            if($this->user->add_time > $this->request->time() + 7* 86400){
                $this->result([],400,'该活动不允许新用户惨与','json');
            }
        }
        $CouponModel = new CouponModel();
        $CouponModel->save([
                'user_id' => $this->user->user_id,
                'member_miniapp_id' => $this->appid,
                'way'    => 1,
                'need_money' => $activity->need_money,
                'money'   => $activity->money,
                'expir_time' => $activity->expire_day <=0 ? 7 * 86400 + time() : $activity->expire_day * 86400 + time(),
                'can_use_time' => $activity->use_day <= 0 ? $this->request->time() : $activity->use_day * 86400 + time(),
                'type'  => 1,

        ]);
         // 在插入log；(还没有做)
        $ActivitylogModel->save([
            'user_id' => $this->user->user_id,
            'activity_id' => $activity_id,
        ]);
       $ActivityModel->save([
           'num' => $activity->num - 1,
       ],['activity_id'=>$activity_id]);
    $this->result([],200,'领取成功','json');
    }
    /*
     *获取用户优惠券列表；
     * 0 全部  1未使用 2已使用 3已过期;
     **/
    public function getCoupon(){
        $type = (int) $this->request->param('type');
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        switch ($type){
            case 1:
              $where['is_can'] = 0;
              $where['expir_time'] = ['>',$this->request->time()];
              $where['can_use_time'] = ['<',$this->request->time()];
              break;
            case 2:
                $where['is_can'] = 1;
                break;
            case 3:
                $where['is_can'] = 0;
                $where['expir_time'] = ['<',$this->request->time()];
        }
        $CouponModel = new CouponModel();
        $data['totalNum'] = $CouponModel->where($where)->count();
        $list = $CouponModel->where($where)->order("coupon_id desc")->limit($this->limit_bg,$this->limit_num)->select();
        if (empty($list)){
            $data['list '] = [];
            $this->result($data,'200','数据初始化成功','json');
        }
        $data['list'] = [];
        foreach ($list as $val){
            $status = $status_mean = '';
            if($val->is_can == 0 && $val->expir_time > $this->request->time() && $val->can_use_time < $this->request->time()){
                $status = 1;
                $status_mean = '未使用';
            }elseif ($val->is_can == 0 && $val->expir_time > $this->request->time() ){
                $status = 4;
                $status_mean = '未到使用时间';
            }

            if($val->is_can == 0 && $val->expir_time < $this->request->time()){
                $status = 3 ;
                $status_mean = '已过期';
            }elseif($val->is_can == 1){
                $status = 2 ;
                $status_mean = '已使用';
            }
            $data['list'][] = [
                'coupon_id' => $val->coupon_id,
                'way'     => $val->way,
                'way_mean' => empty(config('dataattr.couponwaynames')[$val->way]) ? '' : config('dataattr.couponwaynames')[$val->way],
                'need_money' => sprintf("%.0f",$val->need_money/100),
                'money'    => sprintf("%.0f",$val->money/100),
                'expir_time' => date('Y-m-d',$val->expir_time),
                'can_use_time' => date('Y-m-d',$val->can_use_time),
                'status'  => $status,
                'status_mean' => $status_mean,
            ];
        }
        $data['more']  = count($data['list']) >= $this->limit_num ? 1: 0;
       $this->result($data,'200','数据初始化成功','json');
    }
    /**
     * 获取用户下单时可以使用的红包列表;
     */
    public function getUseCoupon(){
        $type = (int)$this->request->param('type');
        $money = (int)($this->request->param('money') * 100);
        if(!empty($type)){
            $where['type'] = $type;
        }
        $where['need_money'] = ['<=',$money];
        $where['is_can'] = 0;
        $where['expir_time'] = ['>',$this->request->time()];
        $where['can_use_time'] =['<',$this->request->time()];
        $where['user_id'] = $this->user->user_id;
        $CouponModel = new CouponModel();
        $data['totalNum'] = $CouponModel->where($where)->count();
        $list = $CouponModel->where($where)->order("")->limit($this->limit_bg,$this->limit_num)->select();
        if(empty($list)){
            $data['list'] = [];
            $this->result($data,200,'数据初始化成功','json');
        }
        $data['list'] = [];
        foreach ($list as $val){
            $status = $status_mean = '';
            if($val->is_can == 0 && $val->expir_time > $this->request->time() && $val->can_use_time < $this->request->time()){
                $status = 1;
                $status_mean = '可以使用';
            }elseif ($val->is_can == 0 && $val->expir_time > $this->request->time() ){
                $status = 4;
                $status_mean = '不可使用';
            }

            if($val->is_can == 0 && $val->expir_time < $this->request->time()){
                $status = 3 ;
                $status_mean = '不可使用';
            }elseif($val->is_can == 1){
                $status = 2 ;
                $status_mean = '不可使用';
            }
            $data['list'][] = [
                'coupon_id' => $val->coupon_id,
                'way'     => $val->way,
                'way_mean' => empty(config('dataattr.couponwaynames')[$val->way]) ? '' : config('dataattr.couponwaynames')[$val->way],
                'need_money' => sprintf("%.2f",$val->need_money/100),
                'money'    => sprintf("%.2f",$val->money/100),
                'expir_time' => date('Y-m-d',$val->expir_time),
                'can_use_time' => date('Y-m-d',$val->can_use_time),
                'status'  => $status,
                'status_mean' => $status_mean,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     *  获取收货地址
     */
    public function getAddress(){
        $AddressModel = new AddressModel();
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $where['is_delete'] = 0;
        $list = $AddressModel->where($where)->order('is_default desc,address_id desc')->limit(0,10)->select();
        $data = [];
        foreach ($list as $val){
                $data[] = [
                    'address_id' => $val->address_id,
                    'name' => $val->name,
                    'mobile' => $val->mobile,
                    'address' => $val->address,
                    'gps_addr' => $val->gps_addr,
                    'idcard'    => $val->idcard,

                    'merge_addr' => $val->gps_addr . $val->address,
                    'lng' => (float) $val->lng,
                    'lat' => (float) $val->lat,
                    'is_default' => $val->is_default,
                ];
        }
       $this->result($data,200,'数据初始化成功','json');
    }
    /*
     *  获取默认地址；
     */
    public function getDefault(){
        $AddressModel = new AddressModel();
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $where['is_delete'] = 0;
        $detail = $AddressModel->where($where)->order("is_default desc,address_id desc")->find();
        if(empty($detail)){
            $this->result('',200,'数据初始化成功','json');
        }
        $data = [
            'address_id' => $detail->address_id,
            'name' => $detail->name,
            'mobile' => $detail->mobile,
            'address' => $detail->address,
            'gps_addr' => $detail->gps_addr,
            'idcard'    => $detail->idcard,
            'merge_addr' => $detail->gps_addr . $detail->address,
            'lng' => (float) $detail->lng,
            'lat' => (float) $detail->lat,
            'is_default' => $detail->is_default,
        ];
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 新增地址；
     */
    public function setAddress(){
            $AddressModel = new AddressModel();
            $where['member_miniapp_id'] = $this->appid;
            $where['user_id'] = $this->user->user_id;
            $where['is_delete'] = 0;
            $count = $AddressModel->where($where)->count();
            if($count >= 10){
                $this->result('',400,'您最多只能添加10个地址','json');
            }
            //如果是第一次添加地址  则为默认
            if($count == 0){
                $data['is_default'] = 1;
            }
            $data['user_id'] = $this->user->user_id;
            $data['member_miniapp_id'] = $this->appid;
            $data['name'] = (string) $this->request->param('name');
            if(empty($data['name'])){
               $this->result('',400,'请输入联系人','json');
            }
            $data['mobile'] = (string) $this->request->param('mobile');
            if(empty($data['mobile'])){
                $this->result('',400,'请输入联系方式','json');
            }
            $data['idcard'] = (string) $this->request->param('idcard');
            $data['address'] = (string) $this->request->param('address');
            $data['gps_addr'] = (string) $this->request->param('gps_addr');
            $data['lng'] = (string) $this->request->param('lng');
            $data['lat'] = (string) $this->request->param('lat');
            if(empty($data['lat']) || empty($data['lng']) || empty($data['gps_addr'])){
                $this->result('',400,'请您先获取定位','json');
            }
            $is_default = (int) $this->request->param('is_default');
            if($is_default == 1){
                $Update = [
                    'member_miniapp_id' => $this->appid,
                    'user_id' => $this->user->user_id,
                    'is_delete' => 0,
                ];
                $AddressModel->Update(['is_default'=>0],$Update);
                $data['is_default'] = 1;
            }
        $AddressModel->save($data);
         $data['address_id'] = $AddressModel->address_id;
        $this->result($data,200,'操作成功','json');
    }
    /*
     * 修改地址
     */
    public function editAddress(){
        $address_id = (int) $this->request->param('address_id');
        $AddressModel = new AddressModel();
        if(!$address = $AddressModel->find($address_id)){
            $this->result('',400,'不存在地址','json');
        }
        if($address->user_id != $this->user->user_id || $address->is_delete == 1){
            $this->result('',400,'不存在地址','json');
        }
        $data['name'] = (string) $this->request->param('name');
        if(empty($data['name'])){
            $this->result('',400,'请输入联系人','json');
        }
        $data['mobile'] = (string) $this->request->param('mobile');
        if(empty($data['mobile'])){
            $this->result('',400,'请输入联系方式','json');
        }
        $data['address'] = (string) $this->request->param('address');
        $data['gps_addr'] = (string) $this->request->param('gps_addr');
        $data['idcard'] = (string) $this->request->param('idcard');
        $data['lng'] = (string) $this->request->param('lng');
        $data['lat'] = (string) $this->request->param('lat');
        if(empty($data['lat']) || empty($data['lng']) || empty($data['gps_addr'])){
            $this->result('',400,'请您先获取定位','json');
        }
        $is_default = (int) $this->request->param('is_default');
        if($is_default == 1){
            $Update = [
                'member_miniapp_id' => $this->appid,
                'user_id' => $this->user->user_id,
                'is_delete' => 0,
            ];
            $AddressModel->Update(['is_default'=>0],$Update);
            $data['is_default'] = 1;
        }
        $AddressModel->save($data,['address_id'=>$address_id]);
        $this->result('',200,'操作成功','json');
    }
    /*
     * 地址详情
     */
    public function addressDetail(){
        $address_id = (int) $this->request->param('address_id');
        $AddressModel = new AddressModel();
        if(!$address = $AddressModel->find($address_id)){
            $this->result('',400,'不存在地址','json');
        }
        if($address->user_id != $this->user->user_id || $address->is_delete == 1){
            $this->result('',400,'不存在地址','json');
        }
        $data = [
            'address_id' => $address->address_id,
            'name' => $address->name,
            'mobile' => $address->mobile,
            'address' => $address->address,
            'gps_addr' => $address->gps_addr,
            'idcard'    => $address->idcard,
            'lng' => $address->lng,
            'lat' => $address->lat,
            'is_default' => $address->is_default,
        ];
        $this->result($data,200,'操作成功','json');
    }
    /*
     *
     * 设置默认
     *
     */
     public function setDefault(){
         $address_id = (int) $this->request->param('address_id');
         $AddressModel = new AddressModel();
         if(!$address = $AddressModel->find($address_id)){
             $this->result('',400,'不存在地址','json');
         }
         if($address->user_id != $this->user->user_id || $address->is_delete == 1){
             $this->result('',400,'不存在地址','json');
         }
             $Update = [
                 'member_miniapp_id' => $this->appid,
                 'user_id' => $this->user->user_id,
                 'is_delete' => 0,
             ];
         $AddressModel->Update(['is_default'=>0],$Update);
         $data['is_default'] = 1;
         $AddressModel->save($data,['address_id'=>$address_id]);
         $this->result('',200,'操作成功','json');
     }
    /*
     *  删除地址；
     *  @Param address_id 地址id
     *
     **/
    public function delAddress(){
        $address_id = (int) $this->request->param('address_id');
        $AddressModel = new AddressModel();
        if(!$addr = $AddressModel->find($address_id)){
            $this->result('',400,'不存在地址','json');
        }
        if($addr->user_id != $this->user->user_id) {
            $this->result('', 400, '不存在地址', 'json');
        }
        $data['is_delete'] = 1;
        if($addr->is_delete == 1){
            $this->result('',200,'操作成功','json');
        }
        $AddressModel->save($data,['address_id'=>$address_id]);
        $this->result('',200,'操作成功','json');
    }
}