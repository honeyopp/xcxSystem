<?php
namespace app\api\controller\waimai;
use app\api\controller\Common;
use app\common\model\user\CouponModel;
use app\common\model\waimai\WaimaisettingModel;
use app\common\model\waimai\ProductModel;
use app\common\model\waimai\OrderModel;
use app\common\model\waimai\OrderproductModel;
class Manage extends Common {
    
    protected $checklogin = true;
    protected $checkIsManage = true;
    
    protected $status = [
        0 => '等待支付',
        1 => '已经支付',
        2 => '商家已接单',
        4 => '订单已取消',
        8 => '订单已完成',
    ];
    
    //改变订单状态
    public function status2(){
        $status = (int)$this->request->param('status');
        $id = (int)$this->request->param('id');
        if($status!=4 && $status!=2 && $status!=8){
            $this->result([], 400, '不能设置', 'json');
        }
        $order = new OrderModel();
        $order->save([
            'status' => $status,
        ],['order_id'=>$id,'member_miniapp_id'=>  $this->appid]);
        $this->result([
            'status' => $status,
            'statusmeans' => $this->status[$status],
        ], 200, '获取数据成功', 'json');
    }
    
    //订单列表
    public function orderList() {
        $where = ['member_miniapp_id' => $this->appid];
        $type = $this->request->param('type');
        switch ($type) {
            case 1:
                $where['status'] = 1;
                break;
            case 2:
                $where['status'] = 2;
                break;
            case 3:
                $where['status'] = ['IN',[8,4]];
                break;
            default:
                break;
        }
        $mobile = (string)$this->request->param('mobile');
        if(!empty($mobile)){
            $where['mobile'] = ['LIKE','%'.$mobile.'%'];           
        }

        $datas = [];
        $list = OrderModel::where($where)->order("order_id desc")->limit($this->limit_bg, $this->limit_num)->select();
        if (!empty($list)) {
            $orderIds = [];
            foreach ($list as $k => $val) {
                $orderIds[$val->order_id] = $val->order_id;
            }
            $orderProduct = OrderproductModel::where(['order_id' => ['IN', $orderIds]])->select();
            $productIds = [];

            foreach ($orderProduct as $val) {
                $productIds[$val->product_id] = $val->product_id;
            }
            $ProductModel = new ProductModel();
            $products = $ProductModel->itemsByIds($productIds);

            $data = [];

            foreach ($orderProduct as $val) {
                $data[$val->order_id][] = [
                    'id' => $val->product_id,
                    'name' => isset($products[$val->product_id]['name']) ? $products[$val->product_id]['name'] : '',
                    'photo' => isset($products[$val->product_id]['photo']) ? IMG_URL . getImg($products[$val->product_id]['photo']) : '',
                    'num' => $val->num,
                    'price' => round($val->price / 100, 2)
                ];
            }

            foreach ($list as $val) {
                $datas[] = [
                    'id' => $val->order_id,
                    'total_price' => round($val->total_price / 100, 2),
                    'peisong' => round($val->peisong / 100, 2),
                    'dabao' => round($val->dabao / 100, 2),
                    'pay_hongbao' => round($val->pay_hongbao / 100, 2),
                    'pay_money' => round($val->pay_money / 100, 2),
                    'statusmeans' => $this->status[$val->status],
                    'status' => $val->status,
                    'name' => $val->name,
                    'mobile' => $val->mobile,
                    'address' => $val->address,
                    'gps_addr' => $val->gps_addr,
                    'lng' => (float) $val->lng,
                    'lat' => (float) $val->lat,
                    'products' => empty($data[$val->order_id]) ? [] : $data[$val->order_id]
                ];
            }
        }

        $return = ['list' => $datas];
        $return['more'] = count($datas) > $this->limit_num ? 1 : 0;
        $this->result($return, 200, '数据初始化成功', 'json');
    }
    
    public function  online(){
        $WaimaisettingModel = new WaimaisettingModel();
        $is_online = (int)$this->request->param('is_online');
        $is_online = $is_online==1?1:0;
        $WaimaisettingModel->save(['is_online'=>$is_online],['member_miniapp_id'=>  $this->appid]);
        $this->result([], 200, '获取数据成功', 'json');
    }
    //一次行查询出来
    public function getGoods(){
        $products = ProductModel::where([
            'member_miniapp_id'=>  $this->appid,
        ])->select();
        $return  = [];
        foreach($products as $val){
            $return[] = [
                'id' => $val->product_id,
                'name' => $val->name,
                'photo' => IMG_URL . getImg($val->photo),
                'is_online' => $val->is_online,
            ];
        }
       $this->result($return, 200, '获取数据成功', 'json'); 
    }
    
    public function  status(){
         $is_online = (int)$this->request->param('is_online');   
         $ids = json_decode(htmlspecialchars_decode($this->request->param('ids')),true);
         if(empty($ids)){
             $this->result([], 200, '获取数据成功', 'json');
         }
         $is_online = $is_online ==1 ? 1 :0;
         $ProductModel = new ProductModel();
         $ProductModel->save([
             'is_online' => $is_online,
         ],['product_id'=>['IN',$ids],'member_miniapp_id'=>  $this->appid]);
         $this->result([], 200, '获取数据成功', 'json');
    }
    
}
