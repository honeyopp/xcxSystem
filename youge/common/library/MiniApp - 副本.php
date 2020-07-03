<?php
namespace app\common\library;
use app\common\model\miniapp\AuthorizerModel;
use app\common\model\miniapp\MiniappModel;
use app\common\library\Curl;
use app\common\model\miniapp\TemplatemessageModel;
class MiniApp{
    
    private $memberapp = []; //用户的APP
    
    private $template  = []; //小程序的模板

    private $auth;
    
    private $tp;
    
    private $token;
    
    private $curl;
    
    private $templateMessage = [
        'hotel' => [
            'AT0011'=>[1,2,3,6,7,8,17], // 酒店预定成功通知商家审核通过的时候提醒 酒店名称,预定时间,金额,订单号,入住时间,离店时间,酒店电话
            'AT0012'=> [1,2,3,6,9],//酒店预定失败的时候提醒，取消订单拒绝入住时候提醒：酒店名称，订单号,预定时间,失败原因,订单退款
            'AT0008'=> [2,3,38,4,15,9],//付款提醒的通知  订单编号,订单价格,等待支付,下单时间 酒店名称 支付提醒（请在某某时间钱支付）
            'AT0257'=> [1,10,4],//订单完成通知 订单ID,订单状态,温馨提示（比如您订购的酒店已经完成入住,评价该酒店可以获得积分和红包）
        ]
    ];
    
    //访问分布统计
    public function getweanalysisappidvisitdistribution(){
        $data1 = [
            1=>'"小程序历史列表"', 2=>'"搜索"', 3=>'"会话"', 4=>'"二维码"',5=>'"公众号主页"', 
            6=>'"聊天顶部"',7=>'"系统桌面"', 8=>'"小程序主页"',  9=>'"附近的小程序"',
            10=>'"其他"',11=>'"模板消息"', 12=>'"客服消息"', 13=>'"公众号菜单"', 14=>'"APP分享"',
            15=>'"支付完成页"', 16=>'"长按识别二维码"',17=>'"相册选取二维码"', 18=>'"公众号文章"',
        ];
        $data2=[
            1=>'"0-2s"',2=>'"3-5s"',3=>'"6-10s"',4=>'"11-20s"',5=>'"20-30s"',6=>'"30-50s"',7=>'"50-100s"',8=>'">100s"'
        ];
        $data3=[
            1=>'"1页"',2=>'"2页"',3=>'"3页"',4=>'"4页"',5=>'"5页"',6=>'"6-10页"',7=>'">10页"'
        ];
        $api = 'https://api.weixin.qq.com/datacube/getweanalysisappidvisitdistribution?access_token='.$this->token;
        
        $bg_time= time()-86400*6;
        $datalist = [];
        $dayArr = [];
        $access_source_session_cnt =   $access_staytime_info  = $access_depth_info = []; 
      
        for($i=0;$i<6;$i++){
            $day = $bg_time + $i*86400;
            $dayArr[]='"'.date('m月d日',$day).'"';
            $day =  date('Ymd',$day);
            $data = [
                'begin_date' => $day,//查询半个月的
                'end_date'   => $day
            ];
            $result = $this->curl->post($api, json_encode($data));
            $result = json_decode($result,true);
             $local1=$local2=$local3=[];
            if(!empty($result['list'])){
               
                foreach($result['list'] as $val){
                    if($val['index'] == 'access_source_session_cnt'){
                        foreach($val['item_list'] as $v){
                            $local1[$v['key']] = $v['value'];
                        }
                    }
                    if($val['index'] == 'access_source_session_cnt'){
                        foreach($val['item_list'] as $v){
                            
                             $local2[$v['key']] = $v['value'];
                        }
                    }
                    if($val['index'] == 'access_source_session_cnt'){
                        foreach($val['item_list'] as $v){
                             $local3[$v['key']] = $v['value'];
                        }
                    }
                }
            }
            $access_source_session_cnt[] = $local1;
            $access_staytime_info[] = $local2;
            $access_depth_info[] = $local3;
        }
        
        $series1=$series2=$series3=[];
        foreach($data1 as $k=>$val){
            $local = [];
            foreach($dayArr as  $d=>$v){
                if(isset($access_source_session_cnt[$d][$k])){
                    $local[]=$access_source_session_cnt[$d][$k];
                }else{
                     $local[] = 0;
                }
            }
            $local = join(',',$local);
            $series1[] = '{name:'.$val.',type: \'bar\', stack: \'总量\', label: { normal: { show: true, position: \'insideRight\' } }, data: ['.$local.']}';
        }
        foreach($data2 as $k=>$val){
            $local = [];
            foreach($dayArr as  $d=>$v){
                if(isset($access_staytime_info[$d][$k])){
                    $local[]=$access_staytime_info[$d][$k];
                }else{
                     $local[] = 0;
                }
            }
            $local = join(',',$local);
            $series2[] = '{name:'.$val.',type: \'bar\', stack: \'总量\', label: { normal: { show: true, position: \'insideRight\' } }, data: ['.$local.']}';
        }
        foreach($data3 as $k=>$val){
            $local = [];
            foreach($dayArr as  $d=>$v){
                if(isset($access_depth_info[$d][$k])){
                    $local[]=$access_depth_info[$d][$k];
                }else{
                     $local[] = 0;
                }
            }
            $local = join(',',$local);
            $series3[] = '{name:'.$val.',type: \'bar\', stack: \'总量\', label: { normal: { show: true, position: \'insideRight\' } }, data: ['.$local.']}';
        }
        $return = [
            'dayArr' => join(',',$dayArr),
            'data1'  => join(',',$data1),
            'data2'  => join(',',$data2),
            'data3'  => join(',',$data3),
            'series1'=> join(',',$series1),
            'series2'=> join(',',$series2),
            'series3'=> join(',',$series3),
        ];
        return $return;
    }
    
    //留存
    public function getweanalysisappiddailyretaininfo(){
        $return = []; //趋势只能一天天的查询，那么循环7次最多了 否则系统受不了
       
        $api = 'https://api.weixin.qq.com/datacube/getweanalysisappiddailyretaininfo?access_token='.$this->token;
        
        $dayArr = [];
        
        $legend = ['"新增用户留存"','"活跃用户留存"'];
        
        $arr1 = $arr2 = [];
        
        $bg_time= time()-86400*6;
        for($i=0;$i<6;$i++){
            $day = $bg_time + $i*86400;
            $data = [
                'begin_date' => date('Ymd',$day),//查询半个月的
                'end_date'   => date('Ymd',$day)
            ];
             $dayArr[]='"'.date('m月d日',$day).'"';
             $result = $this->curl->post($api, json_encode($data));
             $result = json_decode($result,true);
             if(!empty($result)){
                 $arr1[]=empty($result['visit_uv_new']['value']) ? 0 :$result['visit_uv_new']['value'];
                 $arr2[]=empty($result['visit_uv']['value']) ? 0 :$result['visit_uv']['value'];
             }else{
                 $arr1[]=0;
                 $arr2[]=0;
             }
        }
       
        $return=[
            'legend' => join(',',$legend),
            'date'   => join(',',$dayArr),
            'series' => '{ name:\'新增用户留存\', type:\'line\',stack:\'总量\', data:['.join(',',$arr1).']},'
            . '{ name:\'活跃用户留存\', type:\'line\',stack:\'总量\', data:['.join(',',$arr2).']}'
         
        ];
        
        return $return;
    }
    
    
    
    
    //访问趋势
    public function getweanalysisappiddailyvisittrend(){
        
        $return = []; //趋势只能一天天的查询，那么循环7次最多了 否则系统受不了
       
        $api = 'https://api.weixin.qq.com/datacube/getweanalysisappiddailyvisittrend?access_token='.$this->token;
        
        $dayArr = [];
        
        $legend = ['"打开次数"','"访问次数"','"访问人数"','"新用户数"','"人均停留时长(秒)"','"次均停留时长(秒)"','"平均访问深度"'];
        
        $arr1 = $arr2 = $arr3 =$arr4 = $arr5 = $arr6 = $arr7= [];
        
        $bg_time= time()-86400*6;
        for($i=0;$i<6;$i++){
            $day = $bg_time + $i*86400;
            $data = [
                'begin_date' => date('Ymd',$day),//查询半个月的
                'end_date'   => date('Ymd',$day)
            ];
             $dayArr[]='"'.date('m月d日',$day).'"';
             $result = $this->curl->post($api, json_encode($data));
             $result = json_decode($result,true);
             if(!empty($result['list'])){
                 $arr1[]=$result['list']['session_cnt'];
                 $arr2[]=$result['list']['visit_pv'];
                 $arr3[]=$result['list']['visit_uv'];
                 $arr4[]=$result['list']['visit_uv_new'];
                 $arr5[]=$result['list']['stay_time_uv'];
                 $arr6[]=$result['list']['stay_time_session'];
                 $arr7[]=$result['list']['visit_depth'];
             }else{
                 $arr1[]=0;
                 $arr2[]=0;
                 $arr3[]=0;
                 $arr4[]=0;
                 $arr5[]=0;
                 $arr6[]=0;
                 $arr7[]=0;
             }
        }
       
        $return=[
            'legend' => join(',',$legend),
            'date'   => join(',',$dayArr),
            'series' => '{ name:\'打开次数\', type:\'line\',stack:\'总量\', data:['.join(',',$arr1).']},'
            . '{ name:\'访问次数\', type:\'line\',stack:\'总量\', data:['.join(',',$arr2).']},'
            . '{ name:\'访问人数\', type:\'line\',stack:\'总量\', data:['.join(',',$arr3).']},'
            . '{ name:\'新用户数\', type:\'line\',stack:\'总量\', data:['.join(',',$arr4).']},'
            . '{ name:\'人均停留时长\', type:\'line\',stack:\'总量\', data:['.join(',',$arr5).']},'
            . '{ name:\'次均停留时长\', type:\'line\',stack:\'总量\', data:['.join(',',$arr6).']},'
            . '{ name:\'平均访问深度\', type:\'line\',stack:\'总量\', data:['.join(',',$arr7).']}'
         
        ];
        
        return $return;
    }
    
    //获取一周的趋势
    public function getweanalysisappiddailysummarytrend(){
        $return = []; //趋势只能一天天的查询，那么循环7次最多了 否则系统受不了
       
        $api = 'https://api.weixin.qq.com/datacube/getweanalysisappiddailysummarytrend?access_token='.$this->token;
        
        $dayArr = [];
        
        $legend = ['"累计用户数"','"转发次数"','"转发人数"'];
        
        $arr1 = $arr2 = $arr3 = [];
        
        $bg_time= time()-86400*6;
        for($i=0;$i<6;$i++){
            $day = $bg_time + $i*86400;
            $data = [
                'begin_date' => date('Ymd',$day),//查询半个月的
                'end_date'   => date('Ymd',$day)
            ];
             $dayArr[]='"'.date('m月d日',$day).'"';
             $result = $this->curl->post($api, json_encode($data));
             $result = json_decode($result,true);
            // var_dump($result);
             if(!empty($result['list'])){
                 $arr1[]=$result['list'][0]['visit_total'];
                 $arr2[]=$result['list'][0]['share_pv'];
                 $arr3[]=$result['list'][0]['share_uv'];
             }else{
                 $arr1[]=0;
                 $arr2[]=0;
                 $arr3[]=0;
             }
            // break;
        }
       
        $return=[
            'legend' => join(',',$legend),
            'date'   => join(',',$dayArr),
            'series' => '{ name:\'累计用户数\', type:\'line\',stack:\'总量\', data:['.join(',',$arr1).']},'
            . '{ name:\'转发次数\', type:\'line\',stack:\'总量\', data:['.join(',',$arr2).']},'
            . '{ name:\'转发人数\', type:\'line\',stack:\'总量\', data:['.join(',',$arr3).']}'
         
        ];
        
        return $return;
    }
    
    public function __construct($member_app_id) {
        $this->auth = new AuthorizerModel();
        $this->memberapp = $this->auth->find($member_app_id);
        $this->token = $this->auth->getToken($member_app_id);
        $this->curl = new Curl();
    }
    
    //查看一下模版的关键字设置
    public function testTemplate(){
         $api = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/library/get?access_token='.$this->token;
        $data = $this->curl->post($api, '{"id":"AT0257"}');    
        $data = json_decode($data,true);
        var_dump($data);
        //die;
    }
    

    
    //对应上面属性的说明
    public function sendTemplateMessage($template,$openid,$form_id,$page='',$datas=[]){
        $TemplatemessageModel = new TemplatemessageModel();
        $templatedata = $TemplatemessageModel->get(['member_miniapp_id'=>$this->memberapp->member_miniapp_id,'template'=>$template]);
        if(empty($templatedata['template_id'])) return false;
        $keyword = [];
        $i =1;
        foreach($datas as $val){
            $keyword['keyword'.$i] =[
                "value" => $val,
                "color" => "#173177",
            ];
            $i++;
        }
        $jsonArr = [
            "touser"=>$openid,
            "template_id"=>$templatedata['template_id'],
            "page" => $page,
            "form_id" => $form_id,
            "data" => $keyword,
           // "emphasis_keyword"=> "keyword1.DATA" 
        ];
        $api= 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$this->token;
        $result = $this->curl->post($api, json_encode($jsonArr,true)); //忽略发送结果
        return $result;
    }
    
    //设置模版提醒
    public function setTemplate(){
        //$TemplatemessageModel = new TemplatemessageModel();
        $this->tp = new MiniappModel();
        $this->template = $this->tp->get($this->memberapp->miniapp_id);
        $templateMessage = [];
        switch ($this->template['miniapp_dir']){
            case 'hotel':
            case 'minsu':
                $templateMessage = $this->templateMessage['hotel'];
                break;
            default:
                return false;
                break;
        }
       
        //设置模版
        if(!empty($templateMessage)){
            $TemplatemessageModel = new TemplatemessageModel();
            $myTemp = $TemplatemessageModel->getTemplateId($this->memberapp->member_miniapp_id);
            $api = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/add?access_token='.$this->token;
            $saveArr = [];
            foreach($templateMessage as $k=>$val){
                if(!isset($myTemp[$k])){
                    $jsonArr = [
                        'id'=> $k,
                        'keyword_id_list'=>$val,
                    ];
                    $json = json_encode($jsonArr);
                    $result = $this->curl->post($api, $json);
                    $result = json_decode($result,true);
                    if(!empty($result['template_id'])){
                        $saveArr[]=[
                            'member_miniapp_id'=>$this->memberapp->member_miniapp_id,
                            'template'=>$k,
                            'template_id'=>$result['template_id']
                        ];
                    }
                }
            }
            if(!empty($saveArr)){
                $TemplatemessageModel = new TemplatemessageModel();
                $TemplatemessageModel->saveAll($saveArr);
            }            
        }
        return true;
    }
    
    
    //设置DOMAIN
    public function domain(){
       $api = 'https://api.weixin.qq.com/wxa/modify_domain?access_token='.$this->token;
       $domain = [
            "action"=>"set",
            "requestdomain" => [
                'https://'.$_SERVER['HTTP_HOST']
            ],
            "wsrequestdomain" => [
                'wss://'.$_SERVER['HTTP_HOST']
            ],
            "uploaddomain" => [
                'https://'.$_SERVER['HTTP_HOST']
            ],
            "downloaddomain" => [
                'https://'.$_SERVER['HTTP_HOST']
            ],
        ];
        $domain = json_encode($domain);
        $result = $this->curl->post($api,$domain);
        //var_dump($result);
        return $this;
    }
    
    public function upload(){
        $this->tp = new MiniappModel();
        $this->template = $this->tp->get($this->memberapp->miniapp_id);
        
        $api = 'https://api.weixin.qq.com/wxa/commit?access_token='.$this->token;
        $ext = [ 
                    'extAppid'=>  $this->memberapp->authorizer_appid,
                    'ext'     => [
                        "apiurl"=> "https://".$_SERVER['HTTP_HOST'],
                        "appid" => $this->memberapp->member_miniapp_id,
                        "appkey"=> $this->memberapp->appkey
                    ],
                    "window"=>[
                        "navigationBarTitleText"=>"0000"        
                    ]
                ];
        $ext = json_encode($ext);
        $ext = str_replace('0000',$this->memberapp->nick_name,$ext);
        $data = [
            'template_id' =>$this->template->template_id,
            'ext_json' => $ext,
            'user_version' => $this->template->version,
            'user_desc' => '0000',
        ];
        $data = json_encode($data);
        $data =str_replace('0000', '巅峰互联全程提供技术支持', $data);
        $result = $this->curl->post($api, $data);
        //var_dump($result);
        //if($result['errcode']!=0){
           // var_dump($result);
       // }
       //file_put_contents('./aaa.txt',  var_export($result,true));
        $this->auth->save([
            'status'    => 1,
            'version'   =>  $this->template->version,
        ],['member_miniapp_id'=>$this->memberapp->member_miniapp_id]);
        return $this;
    }
    
    //获取体验小程序二维码
    public function getQrcode(){
        $api = 'https://api.weixin.qq.com/wxa/get_qrcode?access_token='.$this->token;
        $result = $this->curl->get($api);
        return $result;//图片的代码
    }


    //获取体验小程序二维码
    public function getcode($path){
        $api = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='.$this->token;
        $data = ['path'=>$path];
        $result = $this->curl->post($api,json_encode($data));
        return $result;//图片的代码
    }


    public function getCategory(){
        $api = 'https://api.weixin.qq.com/wxa/get_category?access_token='.$this->token;
        $result = $this->curl->get($api);
        return json_decode($result,true);
    }
    
    public function getPage(){
        $api = 'https://api.weixin.qq.com/wxa/get_page?access_token='.$this->token;
        $result = $this->curl->get($api);
        return json_decode($result,true);
    }
    
    //提交审核代码
    public function commit($tag,$page,$title,$category){
        $api = 'https://api.weixin.qq.com/wxa/submit_audit?access_token='.$this->token;
        
        $data = '{"item_list": [{
                        "address":"'.$page.'",
			"tag":"'.$tag.'",
			"first_class": "'.$category['first_class'].'",
			"second_class":"'.$category['second_class'].'",
                        "third_class": "'.(!empty($category['third_class'])?$category['third_class']:'').'",
                        "first_id":'.$category['first_id'].',
                        "second_id":'.$category['second_id'].',
                        "third_id" :"'.(!empty($category['third_id'])?$category['third_id']:'').'",    
			"title": "'.$title.'"
                        }]}';
        $result = $this->curl->post($api,$data);
        //var_dump($result);
        $result = json_decode($result,true);
        if($result['errcode']==0){
            $this->auth->save([
                'status'    => 2,
            ],['member_miniapp_id'=>$this->memberapp->member_miniapp_id]);
        }
        return $result;
    }
    
    
    public function look(){
        $api = 'https://api.weixin.qq.com/wxa/get_latest_auditstatus?access_token='.$this->token;
        $result = $this->curl->get($api);
        $result = json_decode($result,true);
        if($result['errcode']==0 ){ //审核成功了
            if($this->memberapp->status == 2 && $result['status'] == 0){
                $this->auth->save([
                    'status'    => 3,
                ],['member_miniapp_id'=>$this->memberapp->member_miniapp_id]);
            }
            if($this->memberapp->status == 2 && $result['status'] == 1){
                $this->auth->save([
                    'status'    => 4,
                     'error'    => $result['reason'],
                ],['member_miniapp_id'=>$this->memberapp->member_miniapp_id]);
            }
        }
        return $result;
        
    }
    
    public function fabu(){
        $api = 'https://api.weixin.qq.com/wxa/release?access_token='.$this->token;
        $data='{}';
        $result = $this->curl->post($api,$data);
        //var_dump($result);
        $result = json_decode($result,true);
        if($result['errcode']==0){
            $this->auth->save([
                'status'    => 8,
            ],['member_miniapp_id'=>$this->memberapp->member_miniapp_id]);
        }
        return $result;
    }
    
    
    
}