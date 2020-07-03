<?php
namespace app\common\model\miniapp;
use app\common\model\CommonModel;
use think\Cache;
use app\common\library\Curl;
use app\common\model\setting\ComponentVerifyTicketModel;
class  AuthorizerModel extends CommonModel{
    protected $pk       = 'member_miniapp_id';
    protected $table    = 'member_miniapp';
    
    protected $cache    = 'member_miniapp_id';
    
    
    public function getToken($id){
        $detail = $this->get($id);
        $nowtime = time();
        if($detail->authorizer_refresh_token_expir_time > $nowtime){
            return  $detail->authorizer_access_token;
        }
        $ComponentVerifyTicketModel = new ComponentVerifyTicketModel();
        $api = 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token='.$ComponentVerifyTicketModel->getToken();
        $curl = new Curl();
        $data = [
            'component_appid' => config('weixin.appid'),
            'authorizer_appid' => $detail->authorizer_appid,
            'authorizer_refresh_token'=>$detail->authorizer_refresh_token,
        ];
        $data = json_encode($data);
        $result = $curl->post($api, $data);
        $result = json_decode($result,true);
        if(empty($result['authorizer_access_token'])){
            die('NO ACCESS'.  var_export($result,true));
        }
        //var_dump($result);
        //die;
        $save = [
            'authorizer_access_token' => $result['authorizer_access_token'],
            'authorizer_refresh_token_expir_time'=>$nowtime+7000,
            'authorizer_refresh_token'=> $result['authorizer_refresh_token'],
        ];
        $this->save($save,['member_miniapp_id'=>$id]);
        return $result['authorizer_access_token'];
    }
    
    public function flushCacheData($appid){
          $key = $this->cache.'_'.$appid;
          $data = $this->get($appid);
          if(empty($data)) return false;
          $return = [
              'appkey' => $data->appkey,
              'expir_time' => $data->expir_time,
          ];
          Cache::set($key, $return);
          return $return;
    }
    
    public function cacheData($appid){
         $appid = (int)$appid;
         $key = $this->cache.'_'.$appid;
         $return = Cache::get($key);
         if(empty($return)){
             $data = $this->get($appid);
             if(empty($data)) return false;
             $return = [
                 'authorizer_appid'=> $data->authorizer_appid,
                 'appkey' => $data->appkey,
                 'expir_time' => $data->expir_time,
             ];
            Cache::set($key, $return);
            return $return;
         }
         return $return;
    }
    
}