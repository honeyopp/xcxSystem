<?php
/**
 * @fileName    miniapp.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/17 0017
 */
namespace app\manage\controller;
use app\common\model\member\MemberModel;
use app\common\model\miniapp\AuthorizerModel;
use app\common\model\miniapp\MiniappModel;
use app\common\model\order\OrderModel;
use app\common\model\setting\ComponentVerifyTicketModel;
use app\common\library\Curl;
class Miniapp extends Common
{
    //我的小程序
    public function index()
    {
        $search = $where = [];
        $AuthorizerModel = new AuthorizerModel();
        $where['member_id'] = $this->member_id;
        $list = $AuthorizerModel->where($where)->order("member_miniapp_id desc")->paginate(10);
        $miniappIds = [];
        foreach ($list as $val) {
            $miniappIds[$val->miniapp_id] = $val->miniapp_id;
        }
        $MiniappModel = new MiniappModel();
        $page = $list->render();
        $miniapp = $MiniappModel->itemsByIds($miniappIds);
        $this->assign('miniapp', $miniapp);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('search', $search);
        return $this->fetch();
    }
    public function callback()
    {

        $auth_code = $this->request->get('auth_code');
        if (empty($auth_code)) {
            $this->error('授权失败！');
        }
        $ComponentVerifyTicketModel = new ComponentVerifyTicketModel();
        $token = $ComponentVerifyTicketModel->getToken();
        if ($token == false) {
            $this->error('第三方授权失败#002！');
        }
        $api = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=' . $token;
        $data = [
            'component_appid' => config('weixin.appid'),
            'authorization_code' => $auth_code,
        ];
        $curl = new Curl();
        $result = json_decode($curl->post($api, json_encode($data)), true);
        $datas = [];
        // var_dump($result);die;
        if (empty($result['authorization_info']['authorizer_appid'])) {
            $this->error('第三方授权失败#003！');
        }
        $datas['authorizer_appid'] = $result['authorization_info']['authorizer_appid'];

        $AuthorizerModel = new AuthorizerModel();
        $auth = $AuthorizerModel->get(['authorizer_appid' => $datas['authorizer_appid'], 'member_id' => $this->member_id]);
        if (!empty($auth)) {
            $this->error('已经授权过了！');
        }

        $datas['authorizer_access_token'] = $result['authorization_info']['authorizer_access_token'];
        $datas['authorizer_refresh_token'] = $result['authorization_info']['authorizer_refresh_token'];
        $datas['authorizer_refresh_token_expir_time'] = time() + 7000;

        $api = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token=' . $token;
        $data = [
            'component_appid' => config('weixin.appid'),
            'authorizer_appid' => $datas['authorizer_appid'],
        ];
        $result = json_decode($curl->post($api, json_encode($data)), true);
        if (!empty($result['authorizer_info'])) {
            $datas['nick_name'] = $result['authorizer_info']['nick_name'];
			//$datas['nick_dllogo'] = $result['authorizer_info']['nick_dllogo'];
			//$datas['nick_name'] = $result['authorizer_info']['nick_name']; 
            $datas['head_img'] = empty($result['authorizer_info']['head_img']) ? '' :$result['authorizer_info']['head_img'];
            $datas['user_name'] = $result['authorizer_info']['user_name'];
            $datas['qrcode_url'] = $result['authorizer_info']['qrcode_url'];
            $datas['principal_name'] = $result['authorizer_info']['principal_name'];
            $datas['signature'] = $result['authorizer_info']['signature'];
            $datas['member_id'] = $this->member_id;
            $datas['appkey'] = md5(uniqid());
            $AuthorizerModel = new AuthorizerModel();
            $AuthorizerModel->save($datas);
            $this->success('添加成功', url('miniappshop/index',['member_miniapp_id'=>$AuthorizerModel->member_miniapp_id]));
        }
        $this->error('授权失败');
    }

    public function create()
    {   
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';  

        $ComponentVerifyTicketModel = new ComponentVerifyTicketModel();
        $token = $ComponentVerifyTicketModel->getToken();
        if ($token == false) {
            $this->error('第三方授权失败！');
        }
        $api = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=' . $token;
        $data = [
            'component_appid' => config('weixin.appid')
        ];
        $curl = new Curl();
        $result = json_decode($curl->post($api, json_encode($data)));
        if (!empty($result->pre_auth_code)) {
            $url = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid=' . config('weixin.appid') . '&pre_auth_code=' . $result->pre_auth_code . '&redirect_uri=' . urlencode($http_type.$_SERVER['HTTP_HOST'].'/manage/miniapp/callback');
            header("Location:" . $url);
            die;
        } else {
            $this->error('第三方授权失败#1');
        }

    }




    public function refresh()
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';  

        $ComponentVerifyTicketModel = new ComponentVerifyTicketModel();
        $token = $ComponentVerifyTicketModel->getToken();
        $miniapp_id = (int) $this->request->param('miniapp_id');
          $AuthorizerModel= new AuthorizerModel();
         if(!$miniapp = $AuthorizerModel->find($miniapp_id)){
             $this->error('不存在小程序',null,101);
         }
         if($miniapp->member_id != $this->member_id){
             $this->error('不存在小程序',null,101);
         }
        if ($token == false) {
            $this->error('第三方授权失败！');
        }
        $api = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=' . $token;
        $data = [
            'component_appid' => config('weixin.appid'),

        ];
        $curl = new Curl();
        $result = json_decode($curl->post($api, json_encode($data)));
        if (!empty($result->pre_auth_code)) {
            $url = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid=' . config('weixin.appid') . '&pre_auth_code=' . $result->pre_auth_code . '&redirect_uri=' . urlencode($http_type.$_SERVER['HTTP_HOST'].'/manage/miniapp/refreshcallback?id='.$miniapp_id);
            header("Location:" . $url);
            die;
        } else {
            $this->error('第三方授权失败#1');
        }
    }



    public function refreshcallback()
    {
        
        $auth_code = $this->request->get('auth_code');
        if (empty($auth_code)) {
            $this->error('授权失败！');
        }
        $miniapp_id = (int) $this->request->get('id');
        $AuthorizerModel= new AuthorizerModel();
        if(!$miniapp = $AuthorizerModel->find($miniapp_id)){
            $this->error('授权失败',null,101);
        }
        if($miniapp->member_id != $this->member_id){
            $this->error('授权失败',null,101);
        }
        $ComponentVerifyTicketModel = new ComponentVerifyTicketModel();
        $token = $ComponentVerifyTicketModel->getToken();
        if ($token == false) {
            $this->error('第三方授权失败！');
        }
        $api = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=' . $token;
        $data = [
            'component_appid' => config('weixin.appid'),
            'authorization_code' => $auth_code,
        ];
        $curl = new Curl();
        $result = json_decode($curl->post($api, json_encode($data)), true);
        $datas = [];
        // var_dump($result);
        if (empty($result['authorization_info']['authorizer_appid'])) {
            $this->error('第三方授权失败！');
        }
        $datas['authorizer_appid'] = $result['authorization_info']['authorizer_appid'];
        $datas['authorizer_access_token'] = $result['authorization_info']['authorizer_access_token'];
        $datas['authorizer_refresh_token'] = $result['authorization_info']['authorizer_refresh_token'];
        $datas['authorizer_refresh_token_expir_time'] = time() + 7000;
        $api = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token=' . $token;
        $data = [
            'component_appid' => config('weixin.appid'),
            'authorizer_appid' => $datas['authorizer_appid'],
        ];
        $result = json_decode($curl->post($api, json_encode($data)), true);
        if (!empty($result['authorizer_info'])) {
            $datas['nick_name'] = $result['authorizer_info']['nick_name'];
			 //$datas['nick_dllogo'] = $result['authorizer_info']['nick_dllogo'];
            $datas['head_img'] = empty($result['authorizer_info']['head_img']) ? '' : $result['authorizer_info']['head_img'];
            $datas['user_name'] = $result['authorizer_info']['user_name'];
            $datas['qrcode_url'] = $result['authorizer_info']['qrcode_url'];
            $datas['principal_name'] = $result['authorizer_info']['principal_name'];
            $datas['signature'] = $result['authorizer_info']['signature'];
            $datas['member_id'] = $this->member_id;
            $datas['appkey'] = md5(uniqid());
            $AuthorizerModel = new AuthorizerModel();
            $AuthorizerModel->save($datas,['member_miniapp_id'=>$miniapp_id]);
            $this->success('添加成功', url('miniappshop/index',['member_miniapp_id'=>$miniapp_id]));
        }
        $this->error('授权失败');
    }
    /**
     * 分配短信条数；
     * @param member_miniapp_id int 小程序id
     * @param sms_num int 短信条数；
     */
     public function smsnum(){
         $member_miniapp_id = (int) $this->request->param('member_miniapp_id');
         $sms_num = (int) $this->request->param('sms_num');
         $AuthorizerModel = new AuthorizerModel();
         if(!$member_minapp = $AuthorizerModel->find($member_miniapp_id)){
             $this->error('不存在该小程序',null,101);
         }
         if($member_minapp->member_id != $this->member_id){
             $this->error('不存在该小程序',null,101);
         }
         $min_sms_num = config('setting.min_sms_num') < 0 ?  1 : config('setting.min_sms_num') ;
         if($sms_num < $min_sms_num ){
             $this->error("最少分配{$min_sms_num}",null,101);
         }
         if($sms_num <= $member_minapp->sms_num){
             $this->error('不得小于剩余的条数',null,101);
         }
         if($sms_num > $this->member_info->sms_num){
             $this->error('您的账户短信剩余数不足请充值',null,101);
         }
         $data['sms_num'] = $sms_num;
         $AuthorizerModel->save($data,['member_miniapp_id'=>$member_miniapp_id]);
         $MemberModel = new MemberModel();
         $member_data['sms_num'] = $this->member_info->sms_num - $sms_num;
         $MemberModel->save($member_data,['member_id'=>$this->member_id]);
         $this->success('短信分配成功',null,101);
     }
     /**
      * 登录后台
      * @param member_miniapp_id int 小程序id
      */
     public function backstage(){
         $member_miniapp_id = (int) $this->request->param('member_miniapp_id');
         $AuthorizerModel = new AuthorizerModel();
         if(!$member_miniapp = $AuthorizerModel->find($member_miniapp_id)){
             $this->error("不存在小程序",null,101);
         }
         if($member_miniapp->member_id != $this->member_id){
             $this->error('不存在小程序',null,101);
         }
         $code = authcode($member_miniapp->member_miniapp_id .'|' . $member_miniapp->appkey . '|miniapp|' . $_SERVER['REQUEST_TIME']);
         cookie('miniapp', $code);
         $this->success('正在进入小程序后台',url('/miniapp/index/index'));
     }

}