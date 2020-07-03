<?php

namespace app\api\controller\job;

use app\api\controller\Common;
use app\common\model\job\ApplyModel;
use app\common\model\job\CompanyModel;
use app\common\model\job\IndustryModel;
use app\common\model\job\JobModel;
use app\common\model\job\OrderModel;
use app\common\model\job\PriceModel;
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
use app\common\model\setting\SkinModel;


class Manage extends Common
{
    protected $checklogin = true;
    protected $is_vip = false;
    protected $company = [];

    public function _initialize()
    {
        parent::_initialize();
        $CompanyModel = new CompanyModel();
        if (!$detail = $CompanyModel->where(['member_miniapp_id' => $this->appid, 'user_id' => $this->user->user_id])->find()) {
            $this->result([], 400, '不是企业用户请申请企业用户', 'json');
        }
        if ($detail->vip_expire > $this->request->time()) {
            $this->is_vip = true;
        }
        $this->company = $detail;
    }

    /*
     * 修改公司数据
     */
    public function editCompany()
    {
        if ($this->company->audit != 2) {
            $this->result('', 400, '正在审核中或审核通过不可修改修改请联系客服', 'json');
        }
        $data['name'] = (string)$this->request->param('name');
        $data['tel'] = (string)$this->request->param('tel');
        $data['zhiwu'] = (string)$this->request->param('zhiwu');
        $data['title'] = (string)$this->request->param('title');
        $CompanyModel = new CompanyModel();
        $CompanyModel->save($data, ['company_id' => $this->company->company_id]);
    }

    /*
     * 获取已发布记录;
     */
    public function getJob()
    {
        $where['member_miniapp_id'] = $this->appid;
        $where['company_id'] = $this->company->company_id;
        $where['is_delete'] = 0;
        $JobModel = new JobModel();
        $list = $JobModel->where($where)->order("add_time desc")->limit($this->limit_num, $this->limit_bg)->select();
        $data['list'] = [];
        foreach ($list as $val) {
            $data['list'][] = [
                'job_id' => $val->job_id,
                'title' => $val->title,
                'salary_id' => empty(config('jobsetting.salary')[$val->salary_id]) ? '' : config('jobsetting.salary')[$val->salary_id],
                'is_online' => $val->is_online,
                'apply_num'  => $val->apply_num,
                'people_num' => $val->people_num,
                'name' => $val->name,
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function addJob()
    {

        $data['title'] = $this->request->param('title');
        if (empty($data['title'])) {
            $this->result('', 400, '职位标题不能为空', 'json');
        }
        $data['people_num'] = (int)$this->request->param('people_num');
        if (empty($data['people_num'])) {
            $this->result('', 400, '招聘人数不能为空', 'json');
        }
        $data['experience_id'] = (int)$this->request->param('experience_id');
        $data['education_id'] = (int)$this->request->param('education_id');
        $data['salary_id'] = (int)$this->request->param('salary_id');
        $data['describe'] = $this->request->param('describe');
        if (empty($data['describe'])) {
            $this->result('', 400, '描述不能为空', 'json');
        }
        $data['name'] = $this->request->param('name');
        if (empty($data['name'])) {
            $this->result('', 400, '联系人不能为空', 'json');
        }
        $data['tel'] = $this->request->param('tel');
        if (empty($data['tel'])) {
            $this->result('', 400, '联系方式不能为空', 'json');
        }
        $data['is_ji'] = (int)$this->request->param('is_ji');
        $data['apply_num'] = (int)$this->request->param('apply_num');
        $data['is_eat'] = (int)$this->request->param('is_eat');
        $data['is_live'] = (int)$this->request->param('is_live');
        $data['is_weekend'] = (int)$this->request->param('is_weekend');
        $data['is_overtime'] = (int)$this->request->param('is_overtime');
        $data['is_vehicle'] = (int)$this->request->param('is_vehicle');
        $data['is_bus'] = (int)$this->request->param('is_bus');
        $data['is_meal'] = (int)$this->request->param('is_meal');
        $data['is_phone'] = (int)$this->request->param('is_phone');
        $data['is_room'] = (int)$this->request->param('is_room');
        $data['is_festival'] = (int)$this->request->param('is_festival');
        $data['is_wuxian'] = (int)$this->request->param('is_wuxian');
        $data['is_mpf'] = (int)$this->request->param('is_mpf');
        $data['is_bonus'] = (int)$this->request->param('is_bonus');
        $data['is_newyear'] = (int)$this->request->param('is_newyear');
        $data['is_healthy'] = (int)$this->request->param('is_healthy');
        $data['is_tourism'] = (int)$this->request->param('is_tourism');
        $data['is_train'] = (int)$this->request->param('is_train');
        $data['is_shares'] = (int)$this->request->param('is_shares');
        $CompanyModel = new CompanyModel();

        $JobModel = new JobModel();
        $job_id = (int) $this->request->param('job_id');
        $job = $JobModel->find($job_id);
        if(!empty($job) && $job->member_miniapp_id == $this->appid){
            $JobModel->save($data,['job_id'=>$job_id]);
        }else{
            $data['member_miniapp_id'] = $this->appid;
            $data['company_id'] = $this->company->company_id;
            $data['lat'] = $this->company->lat;
            $data['lng'] = $this->company->lng;
            $data['address'] = $this->company->address;
            $data['region'] = $this->company->region;
            $data['type'] = $this->company->type;
            $data['scale'] = $this->company->scale;
            $data['industry_id'] = $this->company->industry_id;
            $CompanyModel->where(['company_id'=>$this->company->company_id])->setInc('job_num');
        }

        $this->result('', 200, '操作成功', 'json');
    }

    public function setOnline()
    {
        $job_id = (int)$this->request->param('job_id');
        $JobModel = new JobModel();
        if (!$job = $JobModel->find($job_id)) {
            $this->result('', 400, '不存在职业', 'json');
        }
        if ($job->company_id != $this->company->company_id) {
            $this->result('', 400, '不存在职业', 'json');
        }
        $data['is_online'] = 1;
        if ($job->is_online == 1) {
            $data['is_online'] = 0;
        }
        $JobModel->save($data, ['job_id' => $job_id]);
        $this->result($data, 200, '操作成功', 'json');
    }


    /*
     *  删除JOb
     *
     */
   public function deleteJob(){
       $job_id = (int)$this->request->param('job_id');
       $JobModel = new JobModel();
       if (!$job = $JobModel->find($job_id)) {
           $this->result('', 400, '不存在职业', 'json');
       }
       if ($job->company_id != $this->company->company_id) {
           $this->result('', 400, '不存在职业', 'json');
       }
       $data['is_delete'] = 1;
       if ($job->is_delete == 1) {
           $this->result($data, 200, '操作成功', 'json');
       }
       $JobModel->save($data, ['job_id' => $job_id]);
       $this->result('', 200, '操作成功', 'json');
   }

   /*
    * job 详情；
    *
    */

   public function jobDetail(){
       $job_id = (int)$this->request->param('job_id');
       $JobModel = new JobModel();
       if (!$job = $JobModel->find($job_id)) {
           $this->result('', 400, '不存在职业', 'json');
       }
       if ($job->company_id != $this->company->company_id) {
           $this->result('', 400, '不存在职业', 'json');
       }
       $data = [
           'job_id'  => $job->job_id,
           'title'  => $job->title,
           'people_num'  => $job->people_num,
           'experience_id'  => $job->experience_id,
           'education_id'  => $job->education_id,
           'salary_id'  => $job->salary_id,
           'describe'  => $job->describe,
           'name'  => $job->name,
           'tel'  => $job->tel,
           'is_eat'  => $job->is_eat,
           'is_live'  => $job->is_live,
           'is_weekend'  => $job->is_weekend,
           'is_overtime'  => $job->is_overtime,
           'is_vehicle'  => $job->is_vehicle,
           'is_bus'  => $job->is_bus,
           'is_meal'  => $job->is_meal,
           'is_phone'  => $job->is_phone,
           'is_room'  => $job->is_room,
           'is_festival'  => $job->is_festival,
           'is_wuxian'  => $job->is_wuxian,
           'is_mpf'  => $job->is_mpf,
           'is_bonus'  => $job->is_bonus,
           'is_newyear'  => $job->is_newyear,
           'is_healthy'  => $job->is_healthy,
           'is_tourism'  => $job->is_tourism,
           'is_train'  => $job->is_train,
           'is_shares'  => $job->is_shares,
           'is_ji'  => $job->is_ji,
       ];
       $this->result($data,200,'数据初始化成功','json');
   }
    /*
     * 收到的简历 0 代查看 默认 1已查看 2已沟通 3暂不合适
     */
    public function getResume()
    {
        $where['member_miniapp_id'] = $this->appid;
        $where['company_id'] = $this->company->company_id;
        $where['company_delete'] = 0;
        $where['status'] = 0;
        $type = (int) $this->request->param('type');
        switch ($type){
            case 1:
                $where['status'] = 1;
                break;
            case 2:
                $where['status'] = 2;
                break;
            case 3:
                $where['status'] = 3;
                break;
        }
        $ApplyModel = new ApplyModel();
        $list = $ApplyModel->where($where)->order('add_time desc')->limit($this->limit_bg, $this->limit_num)->select();
        $userIds = $jobIds = [];
        foreach ($list as $val) {
            $userIds[$val->user_id] = $val->user_id;
            $jobIds[$val->job_id] = $val->job_id;
        }
        $ResumeModel = new ResumeModel();
        $JobModel = new JobModel();
        $job = $JobModel->itemsByIds($jobIds);
        $resume = $ResumeModel->itemsByIds($userIds);
        $data['list'] = [];
        $sex = [0 => '保密', 1 => '男', 2 => '女'];
        foreach ($list as $val) {
            $data['list'][] = [
                'apply_id' => $val->apply_id,
                'name' => empty($resume[$val->user_id]) ? '' : $resume[$val->user_id]->name,
                'sex'  => empty($sex[$resume[$val->user_id]->sex]) ? '' : $sex[$resume[$val->user_id]->sex],
                'birthday' => empty($resume[$val->user_id]->birthday) ? '未填写' : date("Y-m-d") - $resume[$val->user_id]->birthday ,
                'job_title' => empty($job[$val->job_id]->title) ? '' : $job[$val->job_id]->title,
                'mobile'   => empty($resume[$val->user_id]->mobile) ? '' : $resume[$val->user_id]->mobile,
                'add_time' => date('m月d日',$val->add_time),
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data,200,'数据初始化成功','json');

    }
    /*
     *  简历详情；收到的简历以及搜索的简历列表
     */
    public function resumeDetail()
    {
        if ($this->is_vip == false) {
            $data['status'] = 0;
            $this->result($data, 200, '数据初始化成功', 'json');
        }

        $apply_id = (int)$this->request->param('apply_id');
        $ApplyModel = new ApplyModel();

        if (!$job = $ApplyModel->find($apply_id)) {
            $this->result('', 400, '不存在求职简历', 'json');
        }
        if ($job->company_id != $this->company->company_id) {
            $this->result('', 400, '不存在求职简历', 'json');
        }
          $status =   $job->status = 0 ? 1 : $job->status;
        $ApplyModel->save([
            'status' => $status,
            'look_num' => $job->look_num + 1,
        ], ['apply_id' => $apply_id]);
        $resume = ResumeModel::find($job->user_id);
        $data['resume'] = [];
        $sex_mean = [0 => '未填写', 1 => '男', 2 => '女'];
        if (!empty($resume)) {
            $data['resume'] = [
                'name' => $resume->name,
                'sex' => empty($sex_mean[$resume->sex]) ? '' : $sex_mean[$resume->sex],
                'birthday' => $resume->birthday,
                'census_register' => $resume->census_register,
                'residence' => $resume->residence,
                'workingyears' => $resume->workingyears,
                'mobile' => $resume->mobile,
                'emal' => $resume->emal,
                'qq' => $resume->qq,
                'relative_tel' => $resume->relative_tel,
                'politicalstatus_id' => empty(config('jobsetting.politicalstatus')[$resume->politicalstatus_id]) ? '' : config('jobsetting.politicalstatus')[$resume->politicalstatus_id],
                'marriage_id' => empty(config('jobsetting.marriage')[$resume->marriage_id]) ? '' : config('jobsetting.marriage')[$resume->marriage_id],
                'height' => $resume->height,
            ];
        }
        // 获取求职意向
        $data['intention'] = [];
        if (!empty($resume)) {
            $IndustryModel = new IndustryModel();
            $industryIds = $skillIds = [];
            $industryIds = explode(',', $resume->skill_ids);
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
                'nature_id' => empty(config('jobsetting.nature')[$resume->nature_id]) ? '' : config('jobsetting.nature')[$resume->nature_id],
                'salary' => empty(config('jobsetting.salary')[$resume->salary]) ? '' : config('jobsetting.salary')[$resume->salary],
                'status_id' => $resume->status,
                'work_address' => $resume->work_address,
                'industrys' => $industry_names,
                'skills' => $skill_names,
                'industry_ids' => $resume->industry_ids,
                'skill_ids' => $resume->skill_ids,
            ];
        }


        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $job->user_id;
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
                'education_id' => $val->education_id,
                'is_tz' => $val->is_tz,
                'major' => $val->major,

            ];
        }

        //工作经历
        $data['company'] = [];
        $ResumecompanyModel = new ResumecompanyModel();
        $list = $ResumecompanyModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        foreach ($list as $val) {
            $data['company'][] = [
                'company_id' => $val->company_id,
                'bgdate' => $val->bgdate,
                'enddate' => $val->enddate,
                'company_name' => $val->company_name,
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
            ];
        }
        //培训经历
        $data['train'] = [];
        $ResumetrainModel = new ResumetrainModel();
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
        $data['position'] = [];
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
        $this->result($data, 200, '数据初始化成功', 'json');

    }

    /*
     * 简历详情；收到的简历以及搜索的简历列表
     */
    public function searchDetail()
    {
        if ($this->is_vip == false) {
            $data['status'] = 0;
            $this->result($data, 200, '数据初始化成功', 'json');
        }
        $user_id = (int)$this->request->param('user_id');
        if (empty($user_id)) {
            $this->result('', 400, '请选择应聘者', 'json');
        }
        $data['status'] = 1;
        $resume = ResumeModel::find($user_id);
        $data['resume'] = [];
        $sex_mean = [0 => '未填写', 1 => '男', 2 => '女'];
        if (!empty($resume)) {
            $data['resume'] = [
                'name' => $resume->name,
                'sex' => empty($sex_mean[$resume->sex]) ? '' : $sex_mean[$resume->sex],
                'birthday' => $resume->birthday,
                'census_register' => $resume->census_register,
                'residence' => $resume->residence,
                'workingyears' => $resume->workingyears,
                'mobile' => $resume->mobile,
                'emal' => $resume->emal,
                'qq' => $resume->qq,
                'relative_tel' => $resume->relative_tel,
                'politicalstatus' => empty(config('jobsetting.politicalstatus')[$resume->politicalstatus_id]) ? '' : config('jobsetting.politicalstatus')[$resume->politicalstatus_id],
                'marriage' => empty(config('jobsetting.marriage')[$resume->marriage_id]) ? '' : config('jobsetting.marriage')[$resume->marriage_id],
                'height' => $resume->height,
            ];
        }

        // 获取求职意向
        $data['intention'] = [];
        if (!empty($resume)) {
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
                'nature_id' => empty(config('jobsetting.nature')[$resume->nature_id]) ? '' : config('jobsetting.nature')[$resume->nature_id],
                'salary' => empty(config('jobsetting.salary')[$resume->salary]) ? '' : config('jobsetting.salary')[$resume->salary],
                'status' => empty(config('jobsetting.status')[$resume->status]) ? '' : config('jobsetting.status')[$resume->status],
                'work_address' => $resume->work_address,
                'industrys' => $industry_names,
                'skills' => $skill_names,
                'industry_ids' => $resume->industry_ids,
                'skill_ids' => $resume->skill_ids,
            ];
        }


        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $user_id;
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
                'education_id' => empty(config('jobsetting.education')[$val->education_id]) ? '' : config('jobsetting.education')[$val->education_id],
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
        $IndustryModel = new IndustryModel();
        $_industryIds = $IndustryModel->itemsByIds($_industryIds);
        foreach ($list as $val) {
            $data['company'][] = [
                'company_id' => $val->company_id,
                'bgdate' => $val->bgdate,
                'enddate' => $val->enddate,
                'company_name' => $val->company_name,
                'company_industry' => empty($_industryIds[$val->company_industry]) ? '' : $_industryIds[$val->company_industry]->industry_name,
                'company_salary' => $val->company_salary,
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
        $ResumetrainModel = new ResumetrainModel();
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
        $data['position'] = [];
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
        $this->result($data, 200, '数据初始化成功', 'json');

    }
    /*
     * 获取公司数据；
     */
    public function getCompany()
    {
        $data = [
            'title' => $this->company->title,
            'company_name' => $this->company->company_name,
            'sort_name' => $this->company->sort_name,
            'logo' => IMG_URL . getImg($this->company->logo),
            'lat' => $this->company->lat,
            'lng' => $this->company->lng,
            'address' => $this->company->address,
            'region' => $this->company->region,
            'main_business' => $this->company->main_business,
            'type' => $this->company->type,
            'scale' => $this->company->scale,
            'industry_id' => $this->company->industry_id,
            'bg_year' => $this->company->bg_year,
            'name' => $this->company->name,
            'tel' => $this->company->tel,
            'zhiwu' => $this->company->zhiwu,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }
    /*
     * 搜索简历
     */
    public function getSearch()
    {
        $keyword = (string) $this->request->param('keyword');
        $IndustryModel = new IndustryModel();
        $where['member_miniapp_id'] = $this->appid;
        if (!empty($keyword)) {
            $_where['member_miniapp_id'] = $this->appid;
            $_where['industry_name'] = array('LIKE', '%' . $keyword . '%');
            $id = $IndustryModel->where($_where)->find();
            if(!empty($id)){
                $where['skill_ids|industry_ids'] = array('LIKE', '%' . $id->industry_id . '%');
            }else{
                $where['member_miniapp_id'] = 0;
            }

        }
        $where['open_status'] = 0;
        $ResumeModel = new ResumeModel();
        $list = $ResumeModel->where($where)->order('orderby desc')->limit($this->limit_bg, $this->limit_num)->select();
        $data['list'] = [];
        $sex = [0 => '保密', 1 => '男', 2 => '女'];
        foreach ($list as $val) {
            $data['list'][] = [
                'user_id' => $val->user_id,
                'name' => $val->name,
                'sex' => empty($sex[$val->sex]) ? '保密' : $sex[$val->sex],
                'birthday' => empty($val->birthday) ? '未填写' :  date("Y-m-d",time()) -  $val->birthday,
                'salary' => $val->salary,
                'residence' => $val->residence,
                'mobile'   =>  substr_replace($val->mobile,'****',3,4),
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }
    /*
     * 购买VIP
     */
    public function byVip()
    {
        $price_id = (int) $this->request->param('price_id');
        $PriceModel = new PriceModel();
        if (!$price = $PriceModel->find($price_id)) {
            $this->result([], 400, '购买失败', 'json');
        }
        if ($price->member_miniapp_id != $this->appid) {
            $this->result([], 400, '购买失败', 'json');
        }
        $SettingModel = new SkinModel();
        $setting = $SettingModel->get($this->appid);
//        if(empty($setting['apiclient_cert']) || $setting['apiclient_cert_key']){
//            $this->result([],400,'商家未上传证书请联系客服','json');
//        }
        $OrderModel = new OrderModel();
        $OrderModel->save([
            'user_id' => $this->user->user_id,
            'company_id' => $this->company->company_id,
            'price_id' => $price_id,
            'by_time' => $price->day_num * 86400,
            'vip_level' => $price->vip_level,
            'total_price' => $price->price,
            'member_miniapp_id' => $this->appid,
        ]);
        define('WX_APPID', $setting['mini_appid']);
        define('WX_MCHID', $setting['mini_mid']);
        define('WX_KEY', $setting['mini_apicode']);
        define('WX_APPSECRET', $setting['mini_appsecrept']);
        define('WX_SSLCERT_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert']);
        define('WX_SSLKEY_PATH', ROOT_PATH . '/attachs/wxapiclient/' . $setting['apiclient_cert_key']);
        define('WX_CURL_PROXY_HOST', '0.0.0.0');
        define('WX_CURL_PROXY_PORT', 0);
        define('WX_REPORT_LEVENL', 0);
        require_once ROOT_PATH . "/weixinpay/lib/WxPay.Api.php";
        require_once ROOT_PATH . "/weixinpay/example/WxPay.JsApiPay.php";
        $tools = new \JsApiPay();
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("VIP购买" . $OrderModel->order_id);
        $input->SetAttach($OrderModel->order_id);
        $input->SetOut_trade_no(WX_MCHID . rand(1000, 9999) . $OrderModel->order_id);
        $input->SetTotal_fee($price->price);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        // $input->SetGoods_tag();
        $input->SetNotify_url("https://" . $_SERVER['HTTP_HOST'] . "/api/weixin/notifjob/appid/" . $this->appid . '.html');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($this->request->param('openid'));
        $order = \WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $this->result(['order' => json_decode($jsApiParameters, true)], '200', '创建支付成功！', 'json');
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
        $data['company_delete'] = 1;
        $ApplyModel->save($data,['apply_id'=>$apply_id]);
        $this->result('',200,'操作成功','json');
    }


    /*
     * 查看状态
     *
     * */
    public function SetStyatus(){
        $apply_id = (int) $this->request->param('apply_id');
        $status = (int) $this->request->param('status');
        if($status < 1 || $status > 4){
            $this->result('',400,'请输入正确的状态','json');
        }
        $data['status'] = $status;
        $ApplyModel = new ApplyModel();
        if(!$apply = $ApplyModel->find($apply_id)){
            $this->result('',400,'不存在求职','json');
        }
        if($apply->company_id != $this->company->company_id){
            $this->result('',400,'不存在求职','json');
        }
        $data['status'] = $status;
        $ApplyModel->save($data,['apply_id'=>$apply_id]);
        $this->result('',200,'操作成功','json');
    }


}