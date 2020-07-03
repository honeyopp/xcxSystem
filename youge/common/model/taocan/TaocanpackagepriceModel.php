<?php
namespace app\common\model\taocan;
use app\common\model\CommonModel;

class  TaocanpackagepriceModel extends CommonModel{
    protected $pk       = 'price_id';
    protected $table    = 'taocan_package_price';

    public function removeBooked($package_id,$date,$num=1){
        $package_id = (int)$package_id;
        $date  = htmlspecialchars($date);
        $num = (int)$num;
        return  $this->db()->execute("update ".config('database.prefix').$this->table." set `package_num` = `package_num` -{$num}  where `package_id` = '{$package_id}' and `day` = {$date}'");
    }

   /**
    * 单日期查询酒店下所有房间的价格以及剩余数；
    * @taocan_id (int) 酒店的ID；
    * @miniapp_id (int) 当前小程序的ID；
    * @dsate date 要查询的日期 如：2017-05-06
    * @online bool default false 是否过滤当日下架酒店；
    * @return array 返回当前酒店的所有 当天价格以及房间剩余；
    */
    public function backPrice($taocan_id,$miniapp_id,$date,$online = false){
        $package_where['taocan_id'] = $where['taocan_id'] = (int) $taocan_id;
        $package_where['member_miniapp_id']  =  $where['member_miniapp_id'] = (int) $miniapp_id;
         $where['day'] = $date;
        $data = $this->where($where)->select();
        $package_where['is_online'] = 1;
        //查询所有的酒店
        $package = PackageModel::where($package_where)->select();
        $packages = $prices =  [];
        foreach ($data as $val){
                $prices[$val->package_id] = $val;
        }
        //返回数据
        foreach ($package as $val){
              $packages[$val->package_id] = [
                  //当天价格
                  'price_id' => empty($prices[$val->package_id]) ? 0 : $prices[$val->package_id]->price_id,
                  'package_id' => $val->package_id,
                  'price' => empty($prices[$val->package_id]) ? $val->price : $prices[$val->package_id]->price,
                  //当天剩余房间数
                  'surplus_num' => empty($prices[$val->package_id]) ? $val->day_num : $val->day_num - $prices[$val->package_id]->day_num,
                  //该房源房间
                  '_num_init' =>  $val->day_num,
                  'package_num' => empty($prices[$val->package_id]) ? $val->day_num : $prices[$val->package_id]->day_num,
                  //当天是否上架
                  'is_online' => empty($prices[$val->package_id]) ? $val->is_online : $prices[$val->package_id]->is_online,
                  'title'  => $val->title,
                 // 'area'  => $val->area,
                  'photo' => $val->photo,
                  'is_cancel' => $val->is_cancel,
                  'is_changes' => $val->is_changes,
                  'details' => $val->details,
                  'cancel' => $val->cancel,
                  'changes' => $val->changes,
                  'especially' => $val->especially,
              ];
        }
//        过滤已下架房源
        if($online== true){
            foreach ($packages as $key=>$val) {
                if($val['is_online'] == 0){
                    unset($packages[$key]);
                }
            }
        }
        return $packages;
    }
    public function backDate($bg_date,$end_date){
        $bg_time = strtotime($bg_date);
        $end_time = strtotime($end_date);
        $date = [];
        for($i=$bg_time;$i<$end_time;$i=$i+86400){
            $day = date('Y-m-d',$i);
            $date[$day] = $day;
        }
        return $date;
    }

}