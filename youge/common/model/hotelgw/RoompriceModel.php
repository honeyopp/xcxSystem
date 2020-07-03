<?php
namespace app\common\model\hotelgw;
use app\common\model\CommonModel;

class  RoompriceModel extends CommonModel{
    protected $pk       = 'price_id';
    protected $table    = 'hotelgw_price';


    public function removeBooked($room_id,$bg_date,$end_date,$num=1){
        $room_id = (int)$room_id;
        $bg_date  = htmlspecialchars($bg_date);
        $end_date = htmlspecialchars($end_date);
        $num = (int)$num;
        return  $this->db()->execute("update ".config('database.prefix').$this->table." set `room_num` = `room_num` -{$num}  where `room_id` = '{$room_id}' and (`day` between '{$bg_date}' AND '{$end_date}' )");
    }
    /**
     * 单日期查询酒店下所有房间的价格以及剩余数；
     * @hotel_id (int) 酒店的ID；
     * @miniapp_id (int) 当前小程序的ID；
     * @dsate date 要查询的日期 如：2017-05-06
     * @online bool default false 是否过滤当日下架酒店；
     * @return array 返回当前酒店的所有 当天价格以及房间剩余；
     */
    public function backPrice($miniapp_id,$date,$online = false){
        $room_where['member_miniapp_id']  =  $where['member_miniapp_id'] = (int) $miniapp_id;
        $where['day'] = $date;
        $data = $this->where($where)->select();
        $room_where['is_online'] = 1;
        //查询所有的酒店
        $room = RoomModel::where($room_where)->select();
        $rooms = $prices =  [];
        foreach ($data as $val){
            $prices[$val->room_id] = $val;
        }
        //返回数据
        foreach ($room as $val){
            $rooms[$val->room_id] = [
                //当天价格
                'hotelprice_id' => empty($prices[$val->room_id]) ? 0 : $prices[$val->room_id]->price_id,
                'room_id' => $val->room_id,
                'price' => empty($prices[$val->room_id]) ? $val->price : $prices[$val->room_id]->price,
                //当天剩余房间数
                'surplus_num' => empty($prices[$val->room_id]) ? $val->day_num : $val->day_num - $prices[$val->room_id]->room_num,
                //该房源房间
                'room_num_init' =>  $val->day_num,
                'room_num' => empty($prices[$val->room_id]) ? $val->day_num : $prices[$val->room_id]->room_num,
                //当天是否上架
                'is_online' => empty($prices[$val->room_id]) ? $val->is_online : $prices[$val->room_id]->is_online,
                'title'  => $val->title,
                'area'  => $val->area,
                'photo' => $val->photo,
                'is_wifi' => $val->is_wifi,
            ];
        }
//        过滤已下架房源
        if($online== true){
            foreach ($rooms as $key=>$val) {
                if($val['is_online'] == 0){
                    unset($rooms[$key]);
                }
            }
        }
        return $rooms;
    }


    public function backGwPrice($miniapp_id,$date,$online = false){
        $room_where['member_miniapp_id']  =  $where['member_miniapp_id'] = (int) $miniapp_id;
        $where['day'] = $date;
        $data = $this->where($where)->select();
        $room_where['is_online'] = 1;
        //查询所有的酒店
        $room = RoomModel::where($room_where)->select();
        $rooms = $prices =  [];
        foreach ($data as $val){
            $prices[$val->room_id] = $val;
        }
        //返回数据
        foreach ($room as $val){
            $rooms[$val->room_id] = [
                //当天价格
                'price_id' => empty($prices[$val->room_id]) ? 0 : $prices[$val->room_id]->price_id,
                'room_id' => $val->room_id,
                'price' => empty($prices[$val->room_id]) ? $val->price : $prices[$val->room_id]->price,
                //当天剩余房间数
                'surplus_num' => empty($prices[$val->room_id]) ? $val->day_num : $val->day_num - $prices[$val->room_id]->room_num,
                //该房源房间
                'room_num_init' =>  $val->day_num,
                'room_num' => empty($prices[$val->room_id]) ? $val->day_num : $prices[$val->room_id]->room_num,
                //当天是否上架
                'is_online' => empty($prices[$val->room_id]) ? $val->is_online : $prices[$val->room_id]->is_online,
                'title'  => $val->title,
                'area'  => $val->area,
                'photo' => $val->photo,
                'is_wifi' => $val->is_wifi,
            ];
        }
//        过滤已下架房源
        if($online== true){
            foreach ($rooms as $key=>$val) {
                if($val['is_online'] == 0){
                    unset($rooms[$key]);
                }
            }
        }
        return $rooms;
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