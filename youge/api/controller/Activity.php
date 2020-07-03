<?php
namespace app\api\controller;
use app\common\model\setting\ActivityModel;
use app\common\model\user\ActivitylogModel;

class  Activity extends  Common{
    /**
     * 获取红包活动;
     */
    public function geTactivity(){
        $where['is_online'] = 1;
        $where['member_miniapp_id'] = $this->appid;
        $date = date("Y-m-d");
        $where['bg_date'] = ['<=',$date];
        $where['end_date'] = ['>=',$date];
        $ActivityModel = new ActivityModel();
        $ActivitylogModel = new ActivitylogModel();
        $list = $ActivityModel->where($where)->order("orderby desc")->limit(0,5)->select();
        if(empty($list)) {
            $this->result([], '200', '数据初始化成功', 'json');
        }
        $activity = [];
        foreach($list as $val){
            $activity[] = [
                'activity_id' => $val->activity_id,
                'title'   => $val->title,
                'money'  => round($val->money/100,2),
                'need_money' => round($val->need_money/100,2),
                'expire_day' => $val->expire_day,
                'use_day'  => $val->use_day,
                'is_newuser' => $val->is_newuser,
                'num'     => $val->num,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
            ];
        }
        $this->result($activity,'200','数据请求成功','json');
    }
}