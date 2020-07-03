<?php
namespace app\api\controller\pinche;

use app\api\controller\Common;
use app\common\model\pinche\PincheModel;

class Manage extends  Common{
    protected $checklogin = true;
    /*
     * 获取我的拼车
     */
    public function getPinche(){
        $PincheModel = new PincheModel();
        $where['user_id'] = $this->user->user_id;
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
   /*
    * 发布拼车
    */
    public function addPinche(){
        $data = [];
        $data['member_miniapp_id'] = $this->appid;
        $data['type'] = (int) $this->request->param('type');
        if($data['type'] >= 3 || $data['type'] <= 0){
          $this->result('',400,'参数错误','json');
        }
        $data['user_id'] = $this->user->user_id;
        $data['name'] = $this->request->param('name');
        if(empty($data['name'])){
          $this->result('',400,'姓名不能为空','json');
        }
        $data['mobile'] = $this->request->param('mobile');
        if(empty($data['mobile'])){
          $this->result('',400,'联系方式不能为空','json');
        }
        $data['begin'] = $this->request->param('begin');
        if(empty($data['begin'])){
          $this->result('',400,'出发地不能为空','json');
        }
        $data['end'] = $this->request->param('end');
        if(empty($data['end'])){
          $this->result('',400,'目的地不能为空','json');
        }
        $data['channel'] = (string) $this->request->param('channel');
        $data['car'] = (string) $this->request->param('car');
        $data['bg_time'] = (int) strtotime($this->request->param('bg_time'));
        $data['sex'] = (int) $this->request->param('sex');
        if(empty($data['bg_time'])){
          $this->result('',400,'出发日期不能为空','json');
        }
        $data['vacancy'] = (int) $this->request->param('vacancy');
        if(empty($data['vacancy'])){
          $this->result('',400,'空位不能为空','json');
        }
        $data['demand'] = (string) $this->request->param('demand');
        $PincheModel = new PincheModel();
        $PincheModel->save($data);
        $this->result('',200,'操作成功','json');
    }
    /*
     *  删除发布
     */
    public function delPinche(){
        $pinche_id = (int) $this->request->param('pinche_id');
        $PincheModel = new PincheModel();
        if(!$pinche = $PincheModel->find($pinche_id)){
            $this->result('',400,'参数错误','json');
        }
        if($pinche->user_id != $this->user->user_id){
            $this->result('',400,'参数错误','json');
        }
        $PincheModel->where(['pinche_id'=>$pinche_id])->delete();
        $this->result('',200,'操作成功','json');
    }
    /*
     * 确认；
     */
    public function ok(){
        $pinche_id = (int) $this->request->param('pinche_id');
        $PincheModel = new PincheModel();
        if(!$pinche = $PincheModel->find($pinche_id)){
            $this->result('',400,'参数错误','json');
        }
        if($pinche->user_id != $this->user->user_id){
            $this->result('',400,'参数错误','json');
        }
        if($pinche->status == 1){
            $this->result('',200,'操作成功','json');
        }
        $data['status'] = 1;
        $PincheModel->save($data,['pinche_id'=>$pinche_id]);
        $this->result('',200,'操作成功','json');
    }

    /*
     * 修改发布
     */
    public function detail(){
        $pinche_id = (int) $this->request->param('pinche_id');
        $PincheModel = new PincheModel();
        if(!$pinche = $PincheModel->find($pinche_id)){
            $this->result('',400,'参数错误','json');
        }
        if($pinche->user_id != $this->user->user_id){
            $this->result('',400,'参数错误','json');
        }
        $data = [
            'pinche_id' => $pinche_id,
            'type'     => $pinche->type,
            'name'     => $pinche->name,
            'mobile'     => $pinche->mobile,
            'begin'     => $pinche->begin,
            'end'     => $pinche->end,
            'date'    => date("Y-m-d",$pinche->bg_time),
            'time'    => date("H:i",$pinche->bg_time),
            'channel'     => $pinche->channel,
            'bg_time'     => $pinche->bg_time,
            'vacancy'     => $pinche->vacancy,
            'car'     => $pinche->car,
            'demand'     => $pinche->demand,
            'sex'     => $pinche->sex,
        ];
        $this->result($data,200,'操作成功','json');
    }

    public function edit(){
        $pinche_id = (int) $this->request->param('pinche_id');
        $PincheModel = new PincheModel();
        if(!$pinche = $PincheModel->find($pinche_id)){
            $this->result('',400,'参数错误','json');
        }
        if($pinche->user_id != $this->user->user_id){
            $this->result('',400,'参数错误','json');
        }
        $data['type'] = (int) $this->request->param('type');
        if($data['type'] >= 3 || $data['type'] <= 0){
            $this->result('',400,'参数错误','json');
        }
        $data['name'] = $this->request->param('name');
        if(empty($data['name'])){
            $this->result('',400,'姓名不能为空','json');
        }
        $data['mobile'] = $this->request->param('mobile');
        if(empty($data['mobile'])){
            $this->result('',400,'联系方式不能为空','json');
        }
        $data['begin'] = $this->request->param('begin');
        if(empty($data['begin'])){
            $this->result('',400,'出发地不能为空','json');
        }
        $data['end'] = $this->request->param('end');
        if(empty($data['end'])){
            $this->result('',400,'目的地不能为空','json');
        }
        $data['channel'] = (string) $this->request->param('channel');
        $data['car'] = (string) $this->request->param('car');
        $data['bg_time'] = (int) strtotime($this->request->param('bg_time'));
        $data['sex'] = (int) $this->request->param('sex');
        if(empty($data['bg_time'])){
            $this->result('',400,'出发日期不能为空','json');
        }
        $data['vacancy'] = (int) $this->request->param('vacancy');
        if(empty($data['vacancy'])){
            $this->result('',400,'空位不能为空','json');
        }
        $data['demand'] = (string) $this->request->param('demand');
        $PincheModel = new PincheModel();
        $PincheModel->save($data,['pinche_id'=>$pinche_id]);
        $this->result($pinche,200,'操作成功','json');
    }
}