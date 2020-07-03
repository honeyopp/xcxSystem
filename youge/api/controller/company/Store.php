<?php
namespace app\api\controller\company;
use app\api\controller\Common;
use app\common\model\company\AreaModel;
use app\common\model\company\CompanyModel;
use app\common\model\company\PicsModel;
use app\common\model\company\YuyueModel;
use app\common\model\miniapp\PhotoModel;

class Store extends  Common{
    protected $checklogin = true;
    protected $status = 0; // 0 不是商家 1 是商家 2审核中 3 审核未通过商家；
    protected $cpmpany = [];
    public function _initialize(){
        parent::_initialize();
        $CompanyModel = new CompanyModel();
        $company  =  $CompanyModel->where(['member_miniapp_id'=>$this->appid,'user_id'=>$this->user->user_id])->find();
       $this->cpmpany = $company;
       if(empty($company)){
           $this->is_store = 0;
       }else{
           switch ($company->audit){
               case 0:
                   $this->status  = 2;
                   break;
               case 1:
                   $this->status  = 1;
                   break;
               case 2:
                   $this->status  = 3;
                   break;
           }
       }


    }
    public function getStatus (){
        $data['status'] =   $this->status;
        $this->result($data,200,'数据初始化成功','json');
    }

    public function storeAdd(){
            if($this->status != 0){
                $this->result([],400,'您已经提交过了或正在审核中','json');
            }
        $data['member_miniapp_id'] = $this->appid;
        $data['user_id'] = $this->user->user_id;
        //$data['status'] = $this->status;
        $data['title'] = $this->request->param('title');
        if(empty($data['title'])){
          $this->result([],400,'响亮口号不能为空','json');
        }
        $data['company_name'] = $this->request->param('company_name');
        if(empty($data['company_name'])){
          $this->result([],400,'商家全称不能为空','json');
        }
        $data['sort_name'] = $this->request->param('sort_name');
        if(empty($data['sort_name'])){
          $this->result([],400,'商家简称不能为空','json');
        }
        $data['logo'] = $this->request->param('logo');
        if(empty($data['logo'])){
          $this->result([],400,'LOGO不能为空','json');
        }
        $data['type'] = (int) $this->request->param('type');
        if(empty($data['type'])){
          $this->result([],400,'类型不能为空','json');
        }
        $data['cat_id'] = (int) $this->request->param('cat_id');
        if(empty($data['cat_id'])){
          $this->result([],400,'分类不能为空','json');
        }
        $data['area_id'] = (int) $this->request->param('area_id');
        if(empty($data['area_id'])){
          $this->result([],400,'区域不能为空','json');
        }
        $data['main_business'] = $this->request->param('main_business');
        if(empty($data['main_business'])){
          $this->result([],400,'主营业务不能为空','json');
        }
        $data['bg_year'] = $this->request->param('bg_year');
        if(empty($data['bg_year'])){
          $this->result([],400,'成立时间不能为空','json');
        }
        $data['name'] = $this->request->param('name');
        if(empty($data['name'])){
          $this->result([],400,'联系人不能为空','json');
        }
        $data['tel'] = $this->request->param('tel');
        if(empty($data['tel'])){
          $this->result([],400,'电话不能为空','json');
        }
        $data['zhiwu'] = $this->request->param('zhiwu');
        if(empty($data['zhiwu'])){
          $this->result([],400,'职务不能为空','json');
        }
        $data['addr'] = $this->request->param('addr');
        $data['lng'] = $this->request->param('lng');
        $data['lat'] = $this->request->param('lat');
        $data['detail'] = $this->request->param('detail');
        $data['audit_photo'] = $this->request->param('audit_photo');
        $data['audit'] = 0 ;
        $CompanyModel = new CompanyModel();
        $CompanyModel->save($data);
        $photo = file_get_contents("php://input");
        $photo = json_decode($photo, true);
        $photos = $photo['photos'];
        $_photos = [];
        foreach ($photos as $key => $val) {
            $_photos[] = [
                'company_id' => $CompanyModel->company_id,
                'member_miniapp_id' => $this->appid,
                'pic' => getImg($val),
            ];
        }
        $PicsModel = new PicsModel();
        $PicsModel->saveAll($_photos);
        $this->result([],'200','操作成功','json');
    }

    public function getDetail(){
        if($this->status == 0){
            $this->result([],400,'未提交','json');
        }
        if(empty($this->cpmpany)){
            $this->result([],400,'未提交任何资料','jso');
        }
        $PicsModel = new PicsModel();
        $where['company_id'] = $this->cpmpany->company_id;
        $pics = $PicsModel->where($where)->select();
        $photos = $likes =  [];
        foreach ($pics as $val){
            $photos[] = IMG_URL . getImg($val->pic);
            $likes[] = getImg($val->pic);
        }
        $AreaModel = new AreaModel();
        $arae = $AreaModel->find($this->cpmpany->area_id);
        $data = [
            'company_id'  => $this->cpmpany->company_id,
            'title'  => $this->cpmpany->title,
            'company_name'  => $this->cpmpany->company_name,
            'sort_name'  => $this->cpmpany->sort_name,
            'logo'  => IMG_URL . getImg($this->cpmpany->logo),
            'type'  => $this->cpmpany->type,
            'cat_id'  => $this->cpmpany->cat_id,
            'area_id'  => $this->cpmpany->area_id,
            'area_name' => empty($arae) ? '' : $arae->area_name,
            'main_business'  => $this->cpmpany->main_business,
            'bg_year'  => $this->cpmpany->bg_year,
            'name'  => $this->cpmpany->name,
            'tel'  => $this->cpmpany->tel,
            'zhiwu'  => $this->cpmpany->zhiwu,
            'addr'  => $this->cpmpany->addr,
            'lng'  => $this->cpmpany->lng,
            'lat'  => $this->cpmpany->lat,
            'vip'  => $this->cpmpany->vip,
            'view_num'  => $this->cpmpany->view_num,
            'tel_num'  => $this->cpmpany->tel_num,
            'yuyue_num'  => $this->cpmpany->yuyue_num,
            'zan_num'  => $this->cpmpany->zan_num,
            'share_num'  => $this->cpmpany->share_num,
            'detail'  => $this->cpmpany->detail,
            'audit'  => $this->cpmpany->audit,
            'photos' => $photos,
            'links' => $likes,
        ];
        $this->result($data,200,'数据初始化成功','json');
    }


    public function edit(){
        $company_id = (int) $this->request->param('company_id');
         if($company_id != $this->cpmpany->company_id){
             $this->result([],200,'不存在商家','json');
         }
        $data['member_miniapp_id'] = $this->appid;
        $data['user_id'] = $this->user->user_id;
       // $data['status'] = $this->status;
        $data['title'] = $this->request->param('title');
        if(empty($data['title'])){
            $this->result([],400,'响亮口号不能为空','json');
        }
        $data['cat_id'] = (int) $this->request->param('cat_id');
        if(empty($data['cat_id'])){
            $this->result([],400,'分类不能为空','json');
        }
        $data['bg_year'] = (int) $this->request->param('bg_year');
        if(empty($data['bg_year'])){
            $this->result([],400,'分类不能为空','json');
        }
        $data['area_id'] = (int) $this->request->param('area_id');
        if(empty($data['area_id'])){
            $this->result([],400,'区域不能为空','json');
        }
        $data['main_business'] = $this->request->param('main_business');
        if(empty($data['main_business'])){
            $this->result([],400,'主营业务不能为空','json');
        }
        $data['name'] = $this->request->param('name');
        if(empty($data['name'])){
            $this->result([],400,'联系人不能为空','json');
        }
        $data['tel'] = $this->request->param('tel');
        if(empty($data['tel'])){
            $this->result([],400,'电话不能为空','json');
        }
        $data['zhiwu'] = $this->request->param('zhiwu');
        if(empty($data['zhiwu'])){
            $this->result([],400,'职务不能为空','json');
        }
        $data['addr'] = $this->request->param('addr');
        $data['lng'] = $this->request->param('lng');
        $data['lat'] = $this->request->param('lat');
        $data['detail'] = $this->request->param('detail');
        $CompanyModel = new CompanyModel();
        $CompanyModel->save($data,['company_id'=>$company_id]);
        $photo = file_get_contents("php://input");
        $photo = json_decode($photo, true);
        $photos = $photo['photos'];
        $_photos = [];
        foreach ($photos as $key => $val) {
            $_photos[] = [
                'company_id' => $company_id,
                'member_miniapp_id' => $this->appid,
                'pic' => getImg($val),
            ];
        }
        $PicsModel = new PicsModel();
        $PicsModel->where(['company_id'=>$company_id])->delete();
        $PicsModel->saveAll($_photos);
        $this->result([],'200','操作成功','json');
    }


    public function bespeak(){
        $keyword  = $this->request->param('keywords');
        if(!empty($keyword)){
           $where['name|mobile'] = array('LIKE', '%' . $keyword. '%');
        }
        $YuyueModel = new YuyueModel();
        $where['company_id'] = $this->cpmpany->company_id;
        $data['num'] = $YuyueModel->where($where)->count();
        $list = $YuyueModel->where($where)->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'name' => $val->name,
                'mobile' => $val->mobile,
                'add_time' => date("Y-m-d H:i:s",$val->add_time),
                'content' => $val->content,
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,'200','数据初始化成功','json');
    }

}