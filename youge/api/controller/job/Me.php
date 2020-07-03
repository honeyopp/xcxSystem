<?php
namespace app\api\controller\job;
use app\api\controller\Common;
use app\common\model\job\ApplyModel;
use app\common\model\job\CompanyModel;
use app\common\model\job\IndustryModel;
use app\common\model\job\JobModel;
use app\common\model\job\OrderModel;
use app\common\model\job\PicsModel;
use app\common\model\job\ResumeModel;
use app\common\model\job\ResumecertificateModel;
use app\common\model\job\ResumecompanyModel;
use app\common\model\job\ResumehonorModel;
use app\common\model\job\ResumelanguageModel;
use app\common\model\job\ResumepositionModel;
use app\common\model\job\ResumepracticeModel;
use app\common\model\job\ResumeprojectModel;
use app\common\model\job\ResumeschoolModel;
use app\common\model\job\ResumetrainModel;

class Me extends  Common{
    protected $checklogin = true;

    /*
     * 预览简历
     * */
    public function getResume(){
        //获取基本信息
        $resume = ResumeModel::find($this->user->user_id);
        $data['resume'] = [];
        $sex_mean = [0=>'未填写',1=>'男',2=>'女'];
        if(!empty($resume)){
            $data['resume'] = [
                'name' => $resume->name,
                'sex' => empty($sex_mean[$resume->sex]) ? '' : $sex_mean[$resume->sex] ,
                'birthday' => $resume->birthday,
                'census_register' => $resume->census_register,
                'residence' => $resume->residence,
                'workingyears' => $resume->workingyears,
                'mobile' => $resume->mobile,
                'emal' => $resume->emal,
                'qq' => $resume->qq,
                'relative_tel' => $resume->relative_tel,
                'politicalstatus' => empty(config('jobsetting.politicalstatus')[$resume->politicalstatus_id]) ? '' : config('jobsetting.politicalstatus')[$resume->politicalstatus_id] ,
                'marriage' =>  empty(config('jobsetting.marriage')[$resume->marriage_id]) ? '' : config('jobsetting.marriage')[$resume->marriage_id] ,
                'height' => $resume->height,
            ];
        }

        // 获取求职意向
        $data['intention'] = [];
        if(!empty($resume)){
            $IndustryModel = new IndustryModel();
            $industryIds = $skillIds = [];
            $industryIds = explode(',', $resume->industry_ids);
            $skillIds = explode(',', $resume->skill_ids);
            $industry = $IndustryModel->itemsByIds($industryIds);
            $skill = $IndustryModel->itemsByIds($skillIds);
            $industry_names = '';
            foreach ($industry as $val) {
                $industry_names .= $val->industry_name . ',';
            }
            $skill_names = '';
            foreach ($skill as $val) {
                $skill_names .= $val->industry_name . ',';
            }
            $data['intention'] = [
                'nature_id' => empty(config('jobsetting.nature')[$resume->nature_id]) ? '' :  config('jobsetting.nature')[$resume->nature_id],
                'salary' => empty(config('jobsetting.salary')[$resume->salary]) ? '' : config('jobsetting.salary')[$resume->salary]  ,
                'status' =>empty(config('jobsetting.status')[$resume->status]) ? '' : config('jobsetting.status')[$resume->status],
                'work_address' => $resume->work_address,
                'industrys' => $industry_names,
                'skills' => $skill_names,
                'industry_ids' => $resume->industry_ids,
                'skill_ids' => $resume->skill_ids,
            ];
        }


        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        //教育经历
        $data['school'] = [];
        $ResumeschoolModel = new ResumeschoolModel();
        $list = $ResumeschoolModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        foreach ($list as $val) {
            $data['school'][] = [
                'school_id' => $val->school_id,
                'school_name' => $val->school_name,
                'bgdate' => $val->bgdate,
                'enddate' => $val->enddate,
                'education_id' => empty(config('jobsetting.education')[$val->education_id]) ? '' :  config('jobsetting.education')[$val->education_id],
                'is_tz' => $val->is_tz,
                'major' => $val->major,

            ];
        }

        //工作经历
        $data['company'] = [];
        $ResumecompanyModel = new ResumecompanyModel();
        $list = $ResumecompanyModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        $_industryIds = [];
        foreach ($list as $val) {
            $_industryIds[$val->company_industry] = $val->company_industry;
        }
        $_industryIds =   $IndustryModel->itemsByIds($_industryIds);
        foreach ($list as $val) {
            $data['company'][] = [
                'company_id' => $val->company_id,
                'bgdate' => $val->bgdate,
                'enddate' => $val->enddate,
                'company_name' => $val->company_name,
                'company_industry' => empty($_industryIds[$val->company_industry]) ? '' : $_industryIds[$val->company_industry]->industry_name,
                'company_salary' =>  $val->company_salary,
                'company_position' => $val->company_position,
                'company_division' => $val->company_division,
                'company_type' => empty(config('jobsetting.type')[$val->company_type]) ? '' : config('jobsetting.type')[$val->company_type],
                'company_detail' => $val->company_detail,
            ];
        }
        //项目经验
        $data['project'] = [];
        $ResumeprojectModel = new ResumeprojectModel();
        $list = $ResumeprojectModel->where($where)->order('add_time desc')->limit('0', '10')->select();

        foreach ($list as $val) {
            $data['project'][] = [
                'project_id' => $val->project_id,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
                'project_name' => $val->project_name,
                'company_name' => $val->company_name,
                'project_describe' => $val->project_describe,
                'duty_describe' => $val->duty_describe,
            ];
        }
        //培训经历
        $data['train'] = [];
        $ResumetrainModel= new ResumetrainModel();
        $list = $ResumetrainModel->where($where)->order('add_time desc')->limit('0', '10')->select();

        foreach ($list as $val) {
            $data['train'][] = [
                'train_id' => $val->train_id,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
                'train_name' => $val->train_name,
                'train_calss' => $val->train_calss,
                'train_describe' => $val->train_describe,
            ];
        }
        //语言能力
        $data['language'] = [];
        $ResumelanguageModel = new ResumelanguageModel();
        $list = $ResumelanguageModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        foreach ($list as $val) {
            $data['language'] [] = [
                'language_id' => $val->language_id,
                'language' => $val->language,
                'level' => empty(config('jobsetting.english')[$val->level]) ? '' : config('jobsetting.english')[$val->level],
            ];
        }
        //证书
        $data['certificate'] = [];
        $ResumecertificateModel = new ResumecertificateModel();
        $list = $ResumecertificateModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        foreach ($list as $val) {
            $data['certificate'][] = [
                'certificate_id' => $val->certificate_id,
                'date' => $val->date,
                'certificate_name' => $val->certificate_name,
                'score' => $val->score,
            ];
        }
        //荣誉奖励
        $data['honor'] = [];
        $ResumehonorModel = new ResumehonorModel();
        $list = $ResumehonorModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        foreach ($list as $val) {
            $data['honor'][] = [
                'honor_id' => $val->honor_id,
                'date' => $val->date,
                'honor_name' => $val->honor_name,
            ];
        }
        //校内职务
        $data['position']  = [];
        $ResumepositionModel = new ResumepositionModel();
        $list = $ResumepositionModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        foreach ($list as $val) {
            $data['position'][] = [
                'position_id' => $val->position_id,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
                'position_name' => $val->position_name,
                'position_describe' => $val->position_describe,
            ];
        }
        //社会实践
        $data['practice'] = [];
        $ResumepracticeModel = new ResumepracticeModel();
        $list = $ResumepracticeModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        foreach ($list as $val) {
            $data['practice'][] = [
                'practice_id' => $val->practice_id,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
                'practice_name' => $val->practice_name,
                'practice_describe' => $val->practice_describe,
            ];
        }
        $data['introduction'] = empty($resume) ? '' : $resume->introduction;
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 公开程度
     * */
    public function setOpenStatus(){
        $resume = ResumeModel::find($this->user->user_id);
        if(empty($resume)){
            $this->result('',400,'请您先添加简历','json');
        }
        $data['open_status']  =  $resume->open_status == 0 ? 1 : 0;
        $ResumeModel = new ResumeModel();
        $ResumeModel->save($data,['user_id'=>$this->user->user_id]);
        $this->result('',200,'数据添加成功','json');
    }
    public function  getOpenStatus(){
        $resume = ResumeModel::find($this->user->user_id);
        $data['open_status'] = 0;
        if(!empty($resume)){
            $data['open_status'] = $resume->open_status;
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 企业查看记录
     * */

    public function getLook(){
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $where['user_delete'] = 0;
        $where['status'] = ['neq',0];
        $ApplyModel = new ApplyModel();
        $list = $ApplyModel->where($where)->order('add_time desc')->limit($this->limit_bg,$this->limit_num)->select();
        $companyIds = [];
        foreach ($list as $val){
            $companyIds[$val->company_id] = $val->company_id;
        }
        $CompanyModel = new CompanyModel();
        $company = $CompanyModel->itemsByIds($companyIds);
        $data['list'] = [];
        foreach ($list as $val){
               $data['list'][] = [
                   'company_name' => empty($company[$val->company_id]) ? '' : $company[$val->company_id]->company_name,
                   'look_num'  => $val->look_num,
                   'add_time'  => date("Y-m-d",$val->add_time),
                   'company_id' => $val->company_id,
               ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     *  职位申请记录
     *  0代查看默认 1已查看 2对我有意 3暂不合适
     */

    public function getApply(){
        $type = (int) $this->request->param('type');
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $where['user_delete'] = 0;
        $where['status'] = 0;
        switch ($type){
            case 1 :
                $where['status'] = 1;
                break;
            case 2 :
                $where['status'] = 2;
                break;
            case 3 :
                $where['status'] = 3;
                break;
        }
      $ApplyModel =  new ApplyModel();
        $list = $ApplyModel->where($where)->order('add_time desc')->limit($this->limit_bg,$this->limit_num)->select();
        $companyIds = $jobIds =  [];
        foreach ($list as $val){
            $companyIds[$val->company_id] = $val->company_id;
            $jobIds[$val->job_id] = $val->job_id;
        }
        $CompanyModel = new CompanyModel();
        $company = $CompanyModel->itemsByIds($companyIds);
        $JobModel = new JobModel();
        $job  = $JobModel->itemsByIds($jobIds);
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'apply_id' => $val->apply_id,
                'job_title' => empty($job[$val->job_id]) ? '已失效' : $job[$val->job_id]->title,
                'apply_num' => empty($job[$val->job_id]) ? '' : $job[$val->job_id]->apply_num,
                'company_name' => empty($company[$val->company_id]) ? '' : $company[$val->company_id]->company_name,
                'add_time'  => date("Y-m-d",$val->add_time),
                'company_id' => $val->company_id,
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data,200,'数据初始化成功','json');

    }

/*
 *  删除申请简历；
 * */
    public function deleteApply(){
         $apply_id = (int) $this->request->param('apply_id');
         $ApplyModel = new ApplyModel();
         if(!$apply = $ApplyModel->find($apply_id)){
              $this->result('',400,'不存在','json');
         }
         if($apply->user_id != $this->user->user_id){
             $this->result('',400,'不存在','json');
         }
         $data['user_delete'] = 1;
         $ApplyModel->save($data,['apply_id'=>$apply_id]);
         $this->result('',200,'操作成功','json');
    }



    /*
     * 用户状态 0:不是企业用户  1:申请成功的企业用户  2:审核中用户  3:审核失败用户
     * */
    public function getStatus(){
        $CompanyModel = new CompanyModel();
        if($company = $CompanyModel->where(['member_miniapp_id'=>$this->appid,'user_id'=>$this->user->user_id])->find()){
            $data['status'] = 1;
            $this->result($data,200,'数据初始化成功','json');
        }
        $data['status'] = 0;
        $this->result($data,200,'数据初始化成功','json');
    }

    /*
     *申请企业用户
     * */
    public function setCompany(){
        $CompanyModel = new CompanyModel();
        if($company = $CompanyModel->where(['member_miniapp_id'=>$this->appid,'user_id'=>$this->user->user_id])->find()){
            $this->result('',400,'您已经是企业用户','json');
        }
        $data['user_id'] = $this->user->user_id;
        $data['member_miniapp_id'] = $this->appid;
        $data['title'] = $this->request->param('title');
        if(empty($data['title'])){
            $this->result('',400,'响亮的口号不能为空','json');
        }
        $data['company_name'] = $this->request->param('company_name');
        if(empty($data['company_name'])){
            $this->result('',400,'公司名称不能为空','json');
        }
        $data['sort_name'] = $this->request->param('sort_name');
        if(empty($data['sort_name'])){
            $this->result('',400,'公司简称不能为空','json');
        }
        $data['logo'] = $this->request->param('logo');
        if(empty($data['logo'])){
            $this->result('',400,'公司LOGO不能为空','json');
        }
        $data['lat'] =  $this->request->param('lat');
        if(empty($data['lat'])){
            $this->result('',400,'经度不能为空','json');
        }
        $data['lng'] = $this->request->param('lng');
        if(empty($data['lng'])){
            $this->result('',400,'纬度不能为空','json');
        }
        $data['address'] = $this->request->param('address');
        if(empty($data['address'])){
            $this->result('',400,'地址不能为空','json');
        }
        $data['region'] = (int) $this->request->param('region');
        if(empty($data['region'])){
            $this->result('',400,'所在区域不能为空','json');
        }
        $data['main_business'] = $this->request->param('main_business');
        if(empty($data['main_business'])){
            $this->result('',400,'主营业务不能为空','json');
        }
        $data['type'] = (int) $this->request->param('type');
        if(empty($data['type'])){
            $this->result('',400,'公司性质不能为空','json');
        }
        $data['scale'] = $this->request->param('scale');
        if(empty($data['scale'])){
            $this->result('',400,'公司规模不能为空','json');
        }
        $data['industry_id'] = (int) $this->request->param('industry_id');
        if(empty($data['industry_id'])){
            $this->result('',400,'所属行业不能为空','json');
        }
        $data['bg_year'] = $this->request->param('bg_year');
        if(empty($data['bg_year'])){
            $this->result('',400,'年份不能为空','json');
        }
        $data['name'] = $this->request->param('name');
        if(empty($data['name'])){
            $this->result('',400,'公司负责人不能为空','json');
        }
        $data['tel'] = $this->request->param('tel');
        if(empty($data['tel'])){
            $this->result('',400,'负责人联系方式不能为空','json');
        }
        $data['zhiwu'] = $this->request->param('zhiwu');
        if(empty($data['zhiwu'])){
            $this->result('',400,'负责人职位不能为空','json');
        }
        $data['detail'] =  $this->request->param('detail');
        if(empty($data['detail'])){
            $this->result('',400,'商家介绍不能为空','json');
        }
        $data['audit_photo'] = $this->request->param('audit_photo');
        if(empty($data['audit_photo'])){
            $this->result('',400,'审核证件图片不能为空','json');
        }
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
        $this->result('',200,'操作成功','json');
    }


    public function testby(){
        $OrderModel = new OrderModel();
        $order_id = 23;
        $order  = $OrderModel->find($order_id);
        $CompanyModel = new \app\common\model\job\CompanyModel();
        if (!$company = $CompanyModel->find($order->company_id)) {
            $status = 2;
            $pay_info = '商家已经支付成功但是未找到正确的公司信息';
        }
        //  如果已过期 则当前时间 + 购买时长  否则  过期时间 + 购买时长
        $vip_expire = $company->vip_expire < time() ? time() +  $order->by_time : $company->vip_expire + $order->by_time;
        //   赠送时长与yip；等级 高级覆盖低级
        $level = $company->vip < $order->vip_level ? $order->vip_level : $company->vip;

        $CompanyModel->save([
            'vip_expire' => $vip_expire,  //$company->vip_expire +
            'vip' => $level,
        ], ['company_id' => $order->company_id]);

        if ($order->status == 0) {
            $OrderModel->save([
                'status' => $status,
                'pay_money' => $result['cash_fee'],
                'pay_info' => $pay_info,
                'pay_time' => time(),
            ], ['order_id' => $order_id]);
        }
    }

    /*
     *  分享回调接口；
     */
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


    /*
       *  赞回调接口
       *
       * */
    public function zan(){
        $company_id = (int) $this->request->param('compay_id');
        $CompanyModel = new CompanyModel();
        if(!$company =  $CompanyModel->find($company_id)){
            $this->result([],'400','不存在商家','json');
        }
        if($company->member_miniapp_id != $this->appid){
            $this->result([],'400','不存在商家','json');
        }
        $CompanyModel->where(['company_id'=>$company_id])->setInc('zan_num');
        $this->result([],'200','操作成功','json');
    }

    /*
     * 打电话毁掉接口
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
        $this->result([],'200','操作成功','json');
    }














}