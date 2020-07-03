<?php
namespace app\api\controller\pinche;
use app\api\controller\Common;
use app\api\controller\Publicuse;
use app\common\model\pinche\PincheModel;

class  Index extends Common{


    /*
     * 获取首页数据
     */
    public function getIndex(){
        if($this->limit_bg <= 1){
            //获取banner；
            $data = [];
            $PublicModel = new Publicuse($this->appid);
            $PublicModel->getBanner($data['banner']);
        }
        $type = (int) $this->request->param('type');
        if(!empty($type)){
            $where['type'] = $type;
        }
        $vacancy = (int) $this->request->param('vacancy');
        if(!empty($vacancy)){
            $where['vacancy'] = $vacancy;
        }
        $begin  = (string) $this->request->param('begin');
        if(!empty($begin)){
            $where['begin'] = ["LIKE","%{$begin}%"];
        }
        $end  = (string) $this->request->param('end');
        if(!empty($end)){
            $where['end'] = ["LIKE","%{$end}%"];
        }
        $channel  = (string) $this->request->param('channel');
        if(!empty($channel)){
            $where['channel'] = ["LIKE","%{$channel}%"];
        }
        $data['bg_time'] = (int) strtotime($this->request->param('bg_time'));
        $where['bg_time'] = ['>',$this->request->time() + 1800];
        if(!empty($bg_time)){
            $where['bg_time'] = ['>',$bg_time];
        }
        //获取列表；
        $PincheModel = new PincheModel();
        $where['member_miniapp_id'] =$this->appid;
        //之取出半小时以内的
        $list = $PincheModel->where($where)->order('pinche_id desc')->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        $sex = [1=>'男',2=>'女'];
        foreach ($list as $val){
            $data['list'][] = [
                'pinche_id' => $val->pinche_id,
                'type_mean'   => empty(config('dataattr.pinche')[$val->type]) ? '' : config('dataattr.pinche')[$val->type],
                'type'  => $val->type,
                'name'   => $val->name,
                'mobile' => $val->mobile,
                'begin'  => $val->begin,
                'end'  => $val->end,
                'channel'  => $val->channel,
                'bg_time'  => date("Y-m-d H:i",$val->bg_time),
                'vacancy'  => $val->vacancy,
                'car'  => $val->car,
                'demand'  => $val->demand,
                'status'  => $val->status,
                'sex'    => $val->sex,
                'sex_mean'  => empty($sex[$val->sex]) ? '' : $sex[$val->sex],
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }
}