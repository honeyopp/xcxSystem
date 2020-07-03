<?php
namespace app\common\model\setting;
use app\common\model\CommonModel;
use app\common\model\setting\SettingModel;
use app\common\library\Curl;
class  ComponentVerifyTicketModel extends CommonModel{
    protected $pk       = 'ticket_id';
    protected $table    = 'component_verify_ticket';


    public function getTicket(){

        return $this->order(['ticket_id'=>'desc'])->find();
    }

    public function getToken(){

        $SettingModel = new SettingModel();
        $setting = $SettingModel->fetchAll();
        if(empty($setting['token']) || $setting['token']['t'] < time()-7000){ //重新获取TOKEN
            $ticket = $this->getTicket();
            $api = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
            $curl = new Curl();
            $datas = [
                'component_appid'         =>  config("weixin.appid"),
                'component_appsecret'     =>  config("weixin.appsecret"),
                'component_verify_ticket' =>  $ticket->component_verify_ticket,
            ];
            $result = $curl->post($api, json_encode($datas));
            $result = json_decode($result,true);
        //var_dump($result);die;
            if(!empty($result['component_access_token'])){
                $datas = [
                    't' => time(),
                    'token' => $result['component_access_token']
                ];
                 $SettingModel->save(['v'=>  serialize($datas)],['k'=>'token']);


                 $setting = $SettingModel->fetchAll(true);

                 return  $setting['token']['token'];
            }else{
                return false;
            }
        }else{
            return $setting['token']['token'];
        }

    }

}
