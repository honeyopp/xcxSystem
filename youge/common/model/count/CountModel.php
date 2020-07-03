<?php
namespace app\common\model\count;
use think\Model;

class CountModel extends Model {
    //计算时间的 默认前7天的时间
    public function getDate(){
        $search['bg_time'] = input('bg_time');
        $search['end_time'] =input('end_time');
        $data['EndDate'] = empty($search['end_time']) ? date('Y-m-d',time())  : trim($search['end_time']) ;
        $data['BingDate'] = empty($search['bg_time']) ?  date('Y-m-d', strtotime('-6 days')) : $BingDate = trim($search['bg_time']);
        if((strtotime($data['BingDate']) + 86400*20) < strtotime($data['EndDate'])){
            $data['BingDate'] = date('Y-m-d',strtotime($data['EndDate'])-86400*20);
        }elseif ((strtotime($data['BingDate'])) > strtotime($data['EndDate'])){
            $data['BingDate'] = date('Y-m-d',strtotime($data['EndDate'])-86400*20);
        }
        for($i = strtotime($data['BingDate']); $i <= strtotime($data['EndDate']); $i += 86400) {
            $data['day'][date("Y-m-d", $i)] = 0 ;
        }
        return $data;
    }
    //检查日期
    public function checkDate($days,$info,$name='num',$day = 'day'){
        $data=array('date'=>'','num'=>'');
        foreach ($days as $key=>$val){
            foreach ($info as $key1=>$val1){
                if($key == $info[$key1]->$day){
                    $days[$key] =  $val1->$name;
                    continue;
                }
            }

        }
        foreach ($days as $key=>$val){
            $data['date'] .= $key . ',';
            $data['num'] .= $val . ',';
        }
        $data['date'] = substr($data['date'],0,strlen($data['date'])-1);
        $data['num'] = substr($data['num'],0,strlen($data['num'])-1);
        return $data;
    }


}