<?php

namespace app\api\controller\customized;

use app\api\controller\Common;
use app\common\model\customized\OrderModel;
use app\common\model\setting\CityModel;
class Order extends Common {

    protected $checklogin = true;

    public function fabu() {
        $data = [];
        $data['member_miniapp_id'] = $this->appid;
        $data['type'] = (int) $this->request->param('type');
        if (empty($data['type'])) {
            $this->result('', 400, '类型不能为空', 'json');
        }
        $data['bg_city'] = (int) $this->request->param('bg_city');
        if (empty($data['bg_city'])) {
            $this->result('', 400, '出发城市不能为空', 'json');
        }
        $data['mb_city'] = (int) $this->request->param('mb_city');
        if (empty($data['mb_city'])) {
            $this->result('', 400, '目的城市不能为空', 'json');
        }
        $data['user_id'] = $this->user->user_id;
        $data['name'] = $this->request->param('name');
        if (empty($data['name'])) {
            $this->result('', 400, '联系人不能为空', 'json');
        }
        $data['mobile'] = $this->request->param('mobile');
        if (empty($data['mobile'])) {
            $this->result('', 400, '联系电话不能为空', 'json');
        }
        $data['bg_date'] = $this->request->param('bg_date');
        if (empty($data['bg_date'])) {
            $this->result('', 400, '出发时间不能为空', 'json');
        }
        $data['end_date'] = $this->request->param('end_date');
        if (empty($data['end_date'])) {
            $this->result('', 400, '回程日期不能为空', 'json');
        }
        $data['num1'] = (int) $this->request->param('num1');
        $data['num2'] = (int) $this->request->param('num2');
        $data['email'] = $this->request->param('email');
        $data['price'] = (int) ($this->request->param('price') * 100);
        $data['content'] = $this->request->param('content');
        $OrderModel = new OrderModel();
        $OrderModel->save($data);
        $this->result('', 200, '发布成功', 'json');
    }

    public function orderlist() {
        $OrderModel = new OrderModel();
        $where = [
            'member_miniapp_id' => $this->appid,
            'user_id'  => $this->user->user_id,
        ];
        $datas = $OrderModel->where($where)->order(['order_id'=>'desc'])->limit($this->limit_bg,  $this->limit_num)->select();
        
        $dataarr = [];
        $cityIds = [];
        foreach($datas as $val){
            $cityIds[$val->bg_city] = $val->bg_city;
            $cityIds[$val->mb_city] = $val->mb_city;
            $dataarr[]=[
                'order_id' => $val->order_id,
                'type' => $val->type == 1? '家庭出游':'公司出游',
                'bg_city' => $val->bg_city,
                'mb_city' => $val->mb_city,
                'name' => $val->name,
                'mobile' => $val->mobile,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
                'num1'    => $val->num1,
                'num2'    => $val->num2,
                'email'   => $val->email,
                'price'   => round($val->price/100,2),
                'content' => $val->content,
                'add_time' => date('Y-m-d H:i:s',$val->add_time),
            ];
        }
        
        $CityModel = new CityModel();
        $citys = $CityModel->itemsByIds($cityIds);
        
        foreach($dataarr as $k=>$v){
            $dataarr[$k]['bg_city_name'] = isset($citys[$v['bg_city']]) ? $citys[$v['bg_city']]['city_name']:'';
            $dataarr[$k]['mb_city_name'] = isset($citys[$v['mb_city']]) ? $citys[$v['mb_city']]['city_name']:'';
        }
        
        $more = count($dataarr) < $this->limit_num ? 0 : 1;
        $this->result(['datas'=>$dataarr,'more'=>$more], 200, '获取数据成功', 'json');
    }

}
