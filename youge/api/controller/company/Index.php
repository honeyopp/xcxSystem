<?php
namespace app\api\controller\company;
use app\api\controller\Common;
use app\common\model\company\CompanyModel;
use app\common\model\company\CompanytelModel;
use app\common\model\company\ConmpanyzanModel;
use app\common\model\company\YuyueModel;
use app\common\model\hunqing\TenderModel;

class Index extends Common{
    protected $checklogin = true;

    /*
     * 拨打电话 回调接口
     * */
    public function call(){
        $company_id = (int) $this->request->param('company_id');
        $CompanyModel = new CompanyModel();
        if(!$company =  $CompanyModel->find($company_id)){
            $this->result([],'400','不存在商家','json');
        }
        if($company->member_miniapp_id != $this->appid){
            $this->result([],'400','不存在商家','json');
        }

        $CompanyModel->where(['company_id'=>$company_id])->setInc('tel_num');
        $CompanytelModel = new CompanytelModel();
        $where['company_id'] = $company_id;
        $where['user_id'] = $this->user->user_id;
        if( !$tel = $CompanytelModel->where($where)->find()){
            $CompanytelModel->save([
                'company_id' => $company_id,
                'user_id' => $this->user->user_id,
                'member_miniapp_id' => $this->appid,
            ]);
        }

        $this->result([],'200','操作成功','json');
    }

   public function deletecall(){
       $tel_id = (int) $this->request->param('id');
       $YuyueModel = new CompanytelModel();
       if(!$zan = $YuyueModel->find($tel_id)){
           $this->result([],'400','不存在','json');
       }
       if($zan->user_id != $this->user->user_id){
           $this->result([],'400','不存在','json');
       }
       $YuyueModel->where(['tel_id'=>$tel_id])->delete();
       $this->result([],'200','操作成功','json');
   }
    public function bespeak(){
        $company_id = (int) $this->request->param('company_id');
        $CompanyModel = new CompanyModel();
        if(!$company =  $CompanyModel->find($company_id)){
            $this->result([],'400','不存在商家','json');
        }
        if($company->member_miniapp_id != $this->appid){
            $this->result([],'400','不存在商家','json');
        }
        $data['name'] = $this->request->param('name');
        if(empty($data['name'])){
            $this->result([],'400','联系人不能为空','json');
        }
        $data['mobile'] = $this->request->param('mobile');
        if(empty($data['mobile'])){
            $this->result([],'400','联系方式不能为空','json');
        }
        $data['content'] = $this->request->param('content');
        if(empty($data['content'])){
            $this->result([],'400','预约不能为空','json');
        }
        $data['user_id'] = $this->user->user_id;
        $data['company_id'] = $company_id;
        $data['member_miniapp_id'] =  $this->appid;
        $YuyueModel = new YuyueModel();
        $YuyueModel->save($data);
        $CompanyModel->where(['company_id'=>$company_id])->setInc('yuyue_num');
        $this->result([],'200','操作成功','json');
    }

    public function deletebespeak(){
        $yuyue_id = (int) $this->request->param('id');
        $YuyueModel = new YuyueModel();
        if(!$zan = $YuyueModel->find($yuyue_id)){
            $this->result([],'400','不存在','json');
        }
        if($zan->user_id != $this->user->user_id){
            $this->result([],'400','不存在','json');
        }
        $YuyueModel->where(['yuyue_id'=>$yuyue_id])->delete();
        $this->result([],'200','操作成功','json');
    }

    public function zan(){
        $company_id = (int) $this->request->param('compay_id');
        $CompanyModel = new CompanyModel();
        if(!$company =  $CompanyModel->find($company_id)){
            $this->result([],'400','不存在商家','json');
        }
        if($company->member_miniapp_id != $this->appid){
            $this->result([],'400','不存在商家','json');
        }

        $ConmpanyzanModel= new ConmpanyzanModel();
        $where['user_id'] = $this->user->user_id;
        $where['compay_id']  = $company_id;
         $zan = $ConmpanyzanModel->where($where)->find();
         if(!empty($zan) ){
             $this->result([],'400','只能赞一次哦','json');
         }
        $data['user_id'] = $this->user->user_id;
        $data['compay_id'] = $company_id;
        $data['member_miniapp_id'] =  $this->appid;
        $ConmpanyzanModel->save($data);
        $CompanyModel->where(['company_id'=>$company_id])->setInc('zan_num');
        $this->result([],'200','操作成功','json');
    }

    public function deleteZan(){
        $zan_id = (int) $this->request->param('id');
        $ConmpanyzanModel = new ConmpanyzanModel();
        if(!$zan = $ConmpanyzanModel->find($zan_id)){
            $this->result([],'400','不存在','json');
        }
        if($zan->user_id != $this->user->user_id){
            $this->result([],'400','不存在','json');
        }
        $where['is_delete'] = 1;
        $ConmpanyzanModel->save($where,['zan_id'=>$zan_id]);
        $this->result([],'200','操作成功','json');
    }

   public function share(){
       $company_id = (int) $this->request->param('company_id');
       $CompanyModel = new CompanyModel();
       if(!$company =  $CompanyModel->find($company_id)){
           $this->result([],'400','不存在商家','json');
       }
       if($company->member_miniapp_id != $this->appid){
           $this->result([],'400','不存在商家','json');
       }
       $CompanyModel->where(['company_id'=>$company_id])->setInc('share_num');
       $this->result([],'200','操作成功','json');
   }

    //预约过的商家
    public function yuyueShaop(){
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $YuyueModel = new YuyueModel();
        $list = $YuyueModel->where($where)->order('yuyue_id desc')->limit($this->limit_bg,$this->limit_num)->select();
        $data['num'] = $YuyueModel->where($where)->count();
        $companyIds =  [];
        foreach ($list as $val){
            $companyIds[$val->company_id] = $val->company_id;
        }
        $CompanyModel  = new CompanyModel();
        $companys = $CompanyModel->itemsByIds($companyIds);
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] =[
                'title' => empty($companys[$val->company_id]) ? '' : $companys[$val->company_id]->title,
                'logo' => empty($companys[$val->company_id]) ? '' : IMG_URL . getImg($companys[$val->company_id]->logo),
                'yuyue_id' => $val->yuyue_id,
                'company_name' => empty($companys[$val->company_id]) ? '' : $companys[$val->company_id]->company_name,
                'vip' => empty($companys[$val->company_id]) ? '' : $companys[$val->company_id]->vip,
                'id'  => empty($companys[$val->company_id]) ? '' : $companys[$val->company_id]->company_id,
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }


    public function telShop(){
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $YuyueModel = new CompanytelModel();
        $list = $YuyueModel->where($where)->order('tel_id desc')->limit($this->limit_bg,$this->limit_num)->select();
        $data['num'] = $YuyueModel->where($where)->count();
        $companyIds =  [];
        foreach ($list as $val){
            $companyIds[$val->company_id] = $val->company_id;
        }
        $CompanyModel  = new CompanyModel();
        $companys = $CompanyModel->itemsByIds($companyIds);
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] =[
                'title' => empty($companys[$val->company_id]) ? '' : $companys[$val->company_id]->title,
                'logo' => empty($companys[$val->company_id]) ? '' : IMG_URL . getImg($companys[$val->company_id]->logo),
                'yuyue_id' => $val->tel_id,
                'company_name' => empty($companys[$val->company_id]) ? '' : $companys[$val->company_id]->company_name,
                'vip' => empty($companys[$val->company_id]) ? '' : $companys[$val->company_id]->vip,
                'id'  => empty($companys[$val->company_id]) ? '' : $companys[$val->company_id]->company_id,
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }


    public function zanShaop(){
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $where['is_delete'] = 0;
        $YuyueModel = new ConmpanyzanModel();
        $list = $YuyueModel->where($where)->order('zan_id desc')->limit($this->limit_bg,$this->limit_num)->select();
        $data['num'] = $YuyueModel->where($where)->count();
        $companyIds =  [];
        foreach ($list as $val){
            $companyIds[$val->compay_id] = $val->compay_id;
        }
        $CompanyModel  = new CompanyModel();
        $companys = $CompanyModel->itemsByIds($companyIds);
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] =[
                'title' => empty($companys[$val->compay_id]) ? '' : $companys[$val->compay_id]->title,
                'logo' => empty($companys[$val->compay_id]) ? '' : IMG_URL . getImg($companys[$val->compay_id]->logo),
                'yuyue_id' => $val->zan_id,
                'company_name' => empty($companys[$val->compay_id]) ? '' : $companys[$val->compay_id]->company_name,
                'vip' => empty($companys[$val->compay_id]) ? '' : $companys[$val->compay_id]->vip,
                'id'  => empty($companys[$val->compay_id]) ? '' : $companys[$val->compay_id]->company_id,
                ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }


    public function tender(){
        $data['member_miniapp_id'] = $this->appid;
        $data['user_id'] = $this->user->user_id;
        $data['hssy'] = (int) $this->request->param('hssy');
        $data['hsch'] =  (int) $this->request->param('hsch');
        $data['hslf'] = (int) $this->request->param('hslf');
        $data['hlgp'] = (int) $this->request->param('hlgp');
        $data['xngz'] = (int) $this->request->param('xngz');
        $data['hyjd'] = (int) $this->request->param('hyjd');
        $data['hczp'] = (int) $this->request->param('hczp');
        $data['hlsy'] = (int) $this->request->param('hlsy');
        $data['name'] = $this->request->param('name');
        if(empty($data['name'])){
            $this->result([],'400','联系人姓名不能为空','json');
        }
        $data['mobile'] = $this->request->param('mobile');
        if(empty($data['mobile'])){
            $this->result([],'400','联系人不能为空','json');
        }
        $data['content'] = $this->request->param('content');
        if(empty($data['content'])){
            $this->result([],'400','备注不能为空','json');
        }
        $TenderModel = new TenderModel();
        $TenderModel->save($data);
        $this->result([],200,'操作成功','json');
    }
}