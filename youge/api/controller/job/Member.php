<?php

namespace app\api\controller\job;

use app\api\controller\Common;
use app\common\model\job\ApplyModel;
use app\common\model\job\AreaModel;
use app\common\model\job\IndustryModel;
use app\common\model\job\JobModel;
use app\common\model\job\ResumecertificateModel;
use app\common\model\job\ResumecompanyModel;
use app\common\model\job\ResumehonorModel;
use app\common\model\job\ResumelanguageModel;
use app\common\model\job\ResumeModel;
use app\common\model\job\ResumepositionModel;
use app\common\model\job\ResumepracticeModel;
use app\common\model\job\ResumeprojectModel;
use app\common\model\job\ResumeschoolModel;
use app\common\model\job\ResumetrainModel;

class Member extends Common
{
    protected $checklogin = true;

    public function apply()
    {
        $job_id = (int)$this->request->param('job_id');
        $JobModel = new JobModel();
        if (!$job = $JobModel->find($job_id)) {
            $this->result([], 400, '不存在职位', 'json');
        }
        if ($job->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在职位', 'json');
        }
        if ($job->is_online == 0) {
            $this->result([], 400, '该职位暂时不招了', 'json');
        }

        $ApplyModel = new ApplyModel();
        $apply = $ApplyModel->where(['user_id' => $this->user->user_id,'job_id'=>$job_id])->order('add_time desc')->find();
        if (!empty($apply) && $apply->add_time + 86400 >= $this->request->time()) {
            $this->result([], 400, '24小时之内您只能申请一次', 'json');
        }
        if (!ResumeModel::find($this->user->user_id)) {
            $this->result(['status' => 0], 200, '没有创建任何简历', 'json');
        }
        $ApplyModel->save([
            'user_id' => $this->user->user_id,
            'job_id' => $job_id,
            'member_miniapp_id' => $this->appid,
            'company_id' => $job->company_id,
            'status' => 0,
        ]);
        $JobModel->where(['job_id' => $job_id])->setInc('apply_num');
        $this->result(['status' => 1], 200, '申请成功', 'json');
    }


    public function getStatus()
    {
//        $data['tasic'] = ResumeModel::find($this->user_user_id) ? 1 : 0 ;
//        $data['school'] = ResumeschoolModel::find($this->user_user_id) ? 1 : 0 ;
//        $data['company'] = ResumecompanyModel::find($this->user_user_id) ? 1 : 0 ;
//        $data['project'] = ResumeprojectModel::find($this->user_user_id) ? 1 : 0 ;
//        $data['train'] = ResumetrainModel::find($this->user_user_id) ? 1 : 0 ;
//        $data['language'] = ResumelanguageModel::find($this->user_user_id) ? 1 : 0 ;
//        $data['honor'] = ResumehonorModel::find($this->user_user_id) ? 1 : 0 ;
//        $data['certificate'] = ResumecertificateModel::find($this->user_user_id) ? 1 : 0 ;
//        $data['wxb_job_resume_practice'] = ResumepracticeModel::find($this->user_user_id) ? 1 : 0 ;
    }

    public function getBasic()
    {
        $ResumeModel = new ResumeModel();
        if (!$datas = $ResumeModel->find($this->user->user_id)) {
            $this->result([], 200, '数据初始化成功', 'json');
        };
        $data = [
            'name' => $datas->name,
            'sex' => $datas->sex,
            'birthday' => $datas->birthday,
            'census_register' => $datas->census_register,
            'residence' => $datas->residence,
            'workingyears' => $datas->workingyears,
            'this_salary' => $datas->this_salary,
            'mobile' => $datas->mobile,
            'emal' => $datas->emal,
            'id_card' => $datas->id_card,
            'qq' => $datas->qq,
            'relative_tel' => $datas->relative_tel,
            'politicalstatus_id' => $datas->politicalstatus_id,
            'marriage_id' => $datas->marriage_id,
            'height' => $datas->height,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function setBasic()
    {
        $data['name'] =  $this->request->param('name');
        if (empty($data['name'])) {
            $this->result([], 400, '姓名不能为空','json');
        }
        $data['sex'] =  $this->request->param('sex');
        if (empty($data['sex'])) {
            $this->result([], 400, '性别不能为空','json');
        }

        $data['birthday'] = (string)$this->request->param('birthday');
        $data['census_register'] = (string)$this->request->param('census_register');
        $data['workingyears'] = (string)$this->request->param('workingyears');
        $data['this_salary'] = (string)$this->request->param('this_salary');
        $data['emal'] = (string)$this->request->param('emal');
        $data['qq'] = (string)$this->request->param('qq');
        $data['id_card'] = (string)$this->request->param('id_card');
        $data['relative_tel'] = (string)$this->request->param('relative_tel');
        $data['politicalstatus_id'] = (int)$this->request->param('politicalstatus_id');
        $data['marriage_id'] = (int)$this->request->param('marriage_id');
        $data['nature_id'] = (int)$this->request->param('nature_id');
        $data['mobile'] = (string)$this->request->param('mobile');
        $data['height'] = (string)$this->request->param('height');
        $data['this_salary'] = (int) $this->request->param('salary');
        $data['residence'] = $this->request->param('residence');

        if (empty($data['mobile'])) {
            $this->result([], 400, '手机号不能为空','json');
        }
        $ResumeModel = new ResumeModel();
        $detail = $ResumeModel->find($this->user->user_id);
        if (!empty($detail)) {
            $ResumeModel->save($data, ['user_id' => $this->user->user_id]);
        } else {
            $data['member_miniapp_id'] = $this->appid;
            $data['user_id'] = $this->user->user_id;
            $ResumeModel->save($data);
        }
        $this->result([],200,'操作成功','json');
    }

//  求职意向；回去求职意向
    public function getIntention()
    {
        $ResumeModel = new ResumeModel();
        if (!$detail = $ResumeModel->find($this->user->user_id)) {
            $this->result([], 400, '数据初始化成功', 'json');
        };
        $IndustryModel = new IndustryModel();
        $industryIds = $skillIds = [];
        $industryIds = explode(',', $detail->skill_ids);
        $skillIds = explode(',', $detail->skill_ids);
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
        $data = [
            'nature_id' => $detail->nature_id,
            'salary_id' => $detail->salary,
            'status_id' => $detail->status,
            'work_address' => $detail->work_address,
            'industrys' => $industry_names,
            'skills' => $skill_names,
            'industry_ids' => $detail->industry_ids,
            'skill_ids' => $detail->skill_ids,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    //设置修改求职意向；
    public function setIntention()
    {
        $data['nature_id'] = (int)$this->request->param('nature_id');
        if (empty($data['nature_id'])) {
            $this->result('', 400, '求职性质不能为空', 'json');
        }
        $data['salary'] = (int)$this->request->param('salary');
        if (empty($data['salary'])) {
            $this->result('', 400, '月薪要求不能为空', 'json');
        }
        $data['status'] = (int)$this->request->param('status');
        if (empty($data['status'])) {
            $this->result('', 400, '您的求职状态不能为空', 'json');
        }
        $data['work_address'] = (string)$this->request->param('work_address');
        $data['industry_ids'] = (string)$this->request->param('industryids');
        if (!empty($data['industry_ids'])) {
            if (count(explode(',', $data['industry_ids'])) > 5) {
                $this->result('', 400, '您最多只能选择5个行业', 'json');
            }
        }
        $data['skill_ids'] = (string)$this->request->param('skillids');
        if (!empty($data['skill_ids'])) {
            if (count(explode(',', $data['skill_ids'])) > 5) {
                $this->result('', 400, '您最多只能选择5个职能意向', 'json');
            }
        }
        $ResumeModel = new ResumeModel();
        $detail = $ResumeModel->find($this->user->user_id);
        if (!empty($detail)) {
            $ResumeModel->save($data, ['user_id' => $this->user->user_id]);
        } else {
            $data['member_miniapp_id'] = $this->appid;
            $data['user_id'] = $this->user->user_id;
            $ResumeModel->save($data);
        }
        $this->result('',200,'操作成功','json');
    }

    /*
     *教育经历；
     * */
    public function schoolList()
    {
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $ResumeschoolModel = new ResumeschoolModel();
        $list = $ResumeschoolModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        $data = [];
        foreach ($list as $val) {
            $data[] = [
                'school_id' => $val->school_id,
                'school_name' => $val->school_name,
                'bgdate' => $val->bgdate,
                'enddate' => $val->enddate,
                'education' => empty(config('jobsetting.education')[$val->education_id]) ? '' :  config('jobsetting.education')[$val->education_id],

            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    public function schoolDetail()
    {
        $school_id = (int)$this->request->param('school_id');
        $ResumeschoolModel = new ResumeschoolModel();
        if (!$detail = $ResumeschoolModel->find($school_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $data = [
            'school_id' => $detail->school_id,
            'bgdate' => $detail->bgdate,
            'enddate' => $detail->enddate,
            'school_name' => $detail->school_name,
            'education_id' => $detail->education_id,
            'is_tz' => $detail->is_tz,
            'major' => $detail->major,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function setSchool()
    {
        $school_id = (int)$this->request->param('school_id');
        $ResumeschoolModel = new ResumeschoolModel();
        $detail = $ResumeschoolModel->find($school_id);
        $data['bgdate'] = $this->request->param('bgdate');
        if(empty($data['bgdate'])){
            $this->result('',400,'开始时间不能为空','json');
        }
        $data['enddate'] = $this->request->param('bgdate');
        if(empty($data['enddate'])){
            $this->result('',400,'结束时间不能为空','json');
        }
        $data['school_name'] = $this->request->param('school_name');
        if(empty($data['school_name'])){
            $this->result('',400,'学校名称不能为空','json');
        }
        $data['education_id'] = (int)$this->request->param('education_id');
        if(empty($data['education_id'])){
            $this->result('',400,'学历不能为空','json');
        }
        $data['is_tz'] = (int) $this->request->param('is_tz');
        $data['major'] = (string)$this->request->param('major');
        if ($detail && $detail->member_miniapp_id == $this->appid) {
            $ResumeschoolModel->save($data, ['school_id' => $school_id]);
        } else {
            $num = $ResumeschoolModel->where(['member_miniapp_id' => $this->appid, 'user_id' => $this->user->user_id])->count();
            if ($num >= 10) {
                $this->result([], 400, '您做多添加10条数据', 'json');
            }
            $data['user_id'] = $this->user->user_id;
            $data['member_miniapp_id'] = $this->appid;
            $ResumeschoolModel->save($data);
        }
        $this->result([], '200', '操作成功', 'json');
    }

    public function delteSchool()
    {
        $school_id = (int) $this->request->param('school_id');
        $ResumeschoolModel = new ResumeschoolModel();
        if (!$detail = $ResumeschoolModel->find($school_id)) {

            $this->result([], 400, '不存在教育经历#1', 'json');
        }
        if ($detail->user_id != $this->user->user_id) {
            $this->result([], 400, '不存在教育经历#2', 'json');
        }
        $ResumeschoolModel->where(['school_id' => $school_id])->delete();
        $this->result('', 200, '操作成功', 'json');
    }

    /*
     *工作经历
     * */
    public function companyList()
    {
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $ResumecompanyModel = new ResumecompanyModel();
        $list = $ResumecompanyModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        $data = [];
        foreach ($list as $val) {
            $data[] = [
                'company_id' => $val->company_id,
                'bgdate' => $val->bgdate,
                'enddate' => $val->enddate,
                'company_name' => $val->company_name,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    public function companyDetail(){
        $company_id = (int)$this->request->param('company_id');
        $ResumecompanyModel = new ResumecompanyModel();
        if (!$detail = $ResumecompanyModel->find($company_id)) {
            $this->result([], 400, '不存在工作经历', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在工作经历', 'json');
        }
        $data = [
            'company_id' => $detail->company_id,
            'bgdate' => $detail->bgdate,
            'enddate' => $detail->enddate,
            'company_name' => $detail->company_name,
            'company_type' => $detail->company_type,
            'company_division' => $detail->company_division,
            'company_industry' => $detail->company_industry,
            'company_position' => $detail->company_position,
            'company_salary' => $detail->company_salary,
            'company_detail' => $detail->company_detail,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function setCompany(){
        $company_id = (int)$this->request->param('company_id');
        $ResumecompanyModel = new ResumecompanyModel();
        $detail = $ResumecompanyModel->find($company_id);
        $data['bgdate'] = $this->request->param('bgdate');
        $data['enddate'] = $this->request->param('bgdate');
        $data['company_name'] = $this->request->param('company_name');
        $data['company_industry'] = (int)$this->request->param('company_industry');
        $data['company_type'] = (int)$this->request->param('company_type');
        $data['company_division'] = (string)$this->request->param('company_division');
        $data['company_position'] = (string)$this->request->param('company_position');
        $data['company_salary'] = (string)$this->request->param('company_salary');
        $data['company_detail'] = (string)$this->request->param('company_detail');
        if ($detail && $detail->member_miniapp_id == $this->appid) {
            $ResumecompanyModel->save($data, ['company_id' => $company_id]);
        } else {
            $num = $ResumecompanyModel->where(['member_miniapp_id' => $this->appid, 'user_id' => $this->user->user_id])->count();
            if ($num >= 10) {
                $this->result([], 400, '您做多添加10条数据', 'json');
            }
            $data['user_id'] = $this->user->user_id;
            $data['member_miniapp_id'] = $this->appid;
            $ResumecompanyModel->save($data);
        }
        $this->result([], '200', '操作成功', 'json');
    }

    public function delteCompany(){
        $company_id = (int)$this->request->param('company_id');
        $ResumecompanyModel = new ResumecompanyModel();
        if (!$detail = $ResumecompanyModel->find($company_id)) {
            $this->result([], 400, '不存在工作经历', 'json');
        }
        if ($detail->user_id != $this->user->user_id) {
            $this->result([], 400, '不存在工作经历', 'json');
        }
        $ResumecompanyModel->where(['company_id' => $company_id])->delete();
        $this->result('', 200, '数据操作成功', 'json');
    }
    /*
     *项目经验
     */
    public function projectList()
    {
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $ResumeprojectModel = new ResumeprojectModel();
        $list = $ResumeprojectModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        $data = [];
        foreach ($list as $val) {
            $data[] = [
                'project_id' => $val->project_id,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
                'project_name' => $val->project_name,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    public function projectDetail()
    {
        $project_id = (int)$this->request->param('project_id');
        $ResumeprojectModel = new ResumeprojectModel();
        if (!$detail = $ResumeprojectModel->find($project_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $data = [
            'project_id' => $detail->project_id,
            'bg_date' => $detail->bg_date,
            'end_date' => $detail->end_date,
            'project_name' => $detail->project_name,
            'company_name' => $detail->company_name,
            'project_describe' => $detail->project_describe,
            'duty_describe' => $detail->duty_describe,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function setProject()
    {
        $project_id = (int)$this->request->param('project_id');
        $ResumeprojectModel = new ResumeprojectModel();
        $detail = $ResumeprojectModel->find($project_id);
        $data['bg_date'] = $this->request->param('bg_date');
        if(empty($data['bg_date'])){
            $this->result('',400,'开始时间不能为空','json');
        }
        $data['end_date'] = $this->request->param('end_date');
        if(empty($data['end_date'])){
            $this->result('',400,'结束时间不能为空','json');
        }
        $data['project_name'] = $this->request->param('project_name');
        $data['project_describe'] = $this->request->param('project_describe');
        $data['duty_describe'] = $this->request->param('duty_describe');

        if ($detail && $detail->member_miniapp_id == $this->appid) {
            $ResumeprojectModel->save($data, ['project_id' => $project_id]);
        } else {
            $num = $ResumeprojectModel->where(['member_miniapp_id' => $this->appid, 'user_id' => $this->user->user_id])->count();
            if ($num >= 10) {
                $this->result([], 400, '您做多添加10条数据', 'json');
            }
            $data['user_id'] = $this->user->user_id;
            $data['member_miniapp_id'] = $this->appid;
            $ResumeprojectModel->save($data);
        }
        $this->result([], '200', '操作成功', 'json');
    }

    public function delteProject()
    {
        $project_id = (int)$this->request->param('project_id');
        $ResumeprojectModel = new ResumeprojectModel();
        if (!$detail = $ResumeprojectModel->find($project_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->user_id != $this->user->user_id) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $ResumeprojectModel->where(['project_id' => $project_id])->delete();
        $this->result('', 200, '数据操作成功', 'json');
    }

    /*
     *培训经历
     * */
    public function trainList()
    {
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $ResumetrainModel= new ResumetrainModel();
        $list = $ResumetrainModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        $data = [];
        foreach ($list as $val) {
            $data[] = [
                'train_id' => $val->train_id,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
                'train_name' => $val->train_name,
                'train_calss' => $val->train_calss,
                'train_describe' => $val->train_describe,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    public function trainDetail()
    {
        $train_id = (int)$this->request->param('train_id');
        $ResumetrainModel = new ResumetrainModel();
        if (!$detail = $ResumetrainModel->find($train_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $data = [
            'train_id' => $detail->train_id,
            'bg_date' => $detail->bg_date,
            'end_date' => $detail->end_date,
            'train_name' => $detail->train_name,
            'train_calss' => $detail->train_calss,
            'train_describe' => $detail->train_describe,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function setTrain()
    {
        $train_id = (int)$this->request->param('train_id');
        $ResumetrainModel = new ResumetrainModel();
        $detail = $ResumetrainModel->find($train_id);
        $data['bg_date'] = $this->request->param('bg_date');
        $data['end_date'] = $this->request->param('end_date');
        $data['train_name'] = $this->request->param('train_name');
        $data['train_calss'] = $this->request->param('train_calss');
        $data['train_describe'] = $this->request->param('train_describe');
        if ($detail && $detail->member_miniapp_id == $this->appid) {
            $ResumetrainModel->save($data, ['train_id' => $train_id]);
        } else {
            $num = $ResumetrainModel->where(['member_miniapp_id' => $this->appid, 'user_id' => $this->user->user_id])->count();
            if ($num >= 10) {
                $this->result([], 400, '您做多添加10条数据', 'json');
            }
            $data['user_id'] = $this->user->user_id;
            $data['member_miniapp_id'] = $this->appid;
            $ResumetrainModel->save($data);
        }
        $this->result([], '200', '操作成功', 'json');
    }

    public function delteTrain()
    {
        $train_id = (int)$this->request->param('train_id');
        $ResumetrainModel = new ResumetrainModel();
        if (!$detail = $ResumetrainModel->find($train_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->user_id != $this->user->user_id) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $ResumetrainModel->where(['train_id' => $train_id])->delete();
        $this->result('', 200, '数据操作成功', 'json');
    }
    /*
    *语言能力language
    * */
    public function languageList()
    {
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $ResumelanguageModel = new ResumelanguageModel();
        $list = $ResumelanguageModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        $data = [];
        foreach ($list as $val) {
            $data[] = [
                'language_id' => $val->language_id,
                'language' => $val->language,
                'level' => empty(config('jobsetting.english')[$val->level]) ? '' : config('jobsetting.english')[$val->level],
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    public function languageDetail()
    {
        $language_id = (int) $this->request->param('language_id');
        $ResumelanguageModel = new ResumelanguageModel();
        if (!$detail = $ResumelanguageModel->find($language_id)) {
            $this->result([], 400, '不存在语言能力', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在语言能力', 'json');
        }
        $data = [
            'language_id' => $detail->language_id,
            'language' => $detail->language,
            'level' => $detail->level,

        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function setLanguage()
    {
        $language_id = (int)$this->request->param('language_id');
        $ResumelanguageModel = new ResumelanguageModel ();
        $detail = $ResumelanguageModel->find($language_id);
        $data['language'] = (string) $this->request->param('language');
        if (empty($data['language'])){
            $this->result('',400,'请选择语言','json');
        }
        $data['level'] = (string) $this->request->param('level');
        if ($detail && $detail->member_miniapp_id == $this->appid) {
            $ResumelanguageModel->save($data, ['language_id' => $language_id]);
        } else {
            $num = $ResumelanguageModel->where(['member_miniapp_id' => $this->appid, 'user_id' => $this->user->user_id])->count();
            if ($num >= 10) {
                $this->result([], 400, '您做多添加10条数据', 'json');
            }
            $data['user_id'] = $this->user->user_id;
            $data['member_miniapp_id'] = $this->appid;
            $ResumelanguageModel->save($data);
        }
        $this->result([], '200', '操作成功', 'json');
    }

    public function delteLanguage()
    {
        $language_id= (int)$this->request->param('language_id');
        $ResumelanguageModel = new ResumelanguageModel();
        if (!$detail = $ResumelanguageModel->find($language_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->user_id != $this->user->user_id) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $ResumelanguageModel->where(['language_id' => $language_id])->delete();
        $this->result('', 200, '数据操作成功', 'json');
    }
    /*
     * 证书certificate
     */
    public function certificateList()
    {
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $ResumecertificateModel = new ResumecertificateModel();
        $list = $ResumecertificateModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        $data = [];
        foreach ($list as $val) {
            $data[] = [
                'certificate_id' => $val->certificate_id,
                'date' => $val->date,
                'certificate_name' => $val->certificate_name,
                'score' => $val->score,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    public function certificateDetail(){
        $certificate_id = (int)$this->request->param('certificate_id');
        $ResumecertificateModel = new ResumecertificateModel();
        if (!$detail = $ResumecertificateModel->find($certificate_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $data = [
            'certificate_id' => $detail->certificate_id,
            'date' => $detail->date,
            'certificate_name' => $detail->certificate_name,
            'score' => $detail->score,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }
    public function setCertificate()
    {
        $certificate_id = (int)$this->request->param('certificate_id');
        $ResumecertificateModel = new ResumecertificateModel();
        $detail = $ResumecertificateModel->find($certificate_id);
        $data['date'] =  $this->request->param('date');
        $data['certificate_name'] = $this->request->param('certificate_name');
        if(empty($data['certificate_name'])){
            $this->result('',400,'证书名称不能为空','json');
        }
        $data['score'] = (int) $this->request->param('score');
        if ($detail && $detail->member_miniapp_id == $this->appid) {
            $ResumecertificateModel->save($data, ['certificate_id' => $certificate_id]);
        } else {
            $num = $ResumecertificateModel->where(['member_miniapp_id' => $this->appid, 'user_id' => $this->user->user_id])->count();
            if ($num >= 10) {
                $this->result([], 400, '您做多添加10条数据', 'json');
            }
            $data['user_id'] = $this->user->user_id;
            $data['member_miniapp_id'] = $this->appid;
            $ResumecertificateModel->save($data);
        }
        $this->result([], '200', '操作成功', 'json');
    }
    public function delteCertificate()
    {
        $certificate_id = (int)$this->request->param('certificate_id');
        $ResumecertificateModel= new ResumecertificateModel();
        if (!$detail = $ResumecertificateModel->find($certificate_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->user_id != $this->user->user_id) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $ResumecertificateModel->where(['certificate_id' => $certificate_id])->delete();
        $this->result('', 200, '数据操作成功', 'json');
    }


    /*
     * 荣誉奖励honor
     */
    public function honorList()
    {
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $ResumehonorModel = new ResumehonorModel();
        $list = $ResumehonorModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        $data = [];
        foreach ($list as $val) {
            $data[] = [
                'honor_id' => $val->honor_id,
                'date' => $val->date,
                'honor_name' => $val->honor_name,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    public function honorDetail(){
        $honor_id = (int)$this->request->param('honor_id');
        $ResumehonorModel = new ResumehonorModel();
        if (!$detail = $ResumehonorModel->find($honor_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $data = [
            'honor_id' => $detail->honor_id,
            'date' => $detail->date,
            'honor_name' => $detail->honor_name,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function setHonor()
    {
        $honor_id = (int)$this->request->param('honor_id');
        $ResumehonorModel = new ResumehonorModel();
        $detail = $ResumehonorModel->find($honor_id);
        $data['date'] = $this->request->param('date');
        $data['honor_name'] = $this->request->param('honor_name');
        if(empty($data['honor_name'])){
            $this->result([],400,'请输入名称','json');
        }
        if ($detail && $detail->member_miniapp_id == $this->appid) {
            $ResumehonorModel->save($data, ['honor_id' => $honor_id]);
        } else {
            $num = $ResumehonorModel->where(['member_miniapp_id' => $this->appid, 'user_id' => $this->user->user_id])->count();
            if ($num >= 10) {
                $this->result([], 400, '您做多添加10条数据', 'json');
            }
            $data['user_id'] = $this->user->user_id;
            $data['member_miniapp_id'] = $this->appid;
            $ResumehonorModel->save($data);
        }
        $this->result([], '200', '操作成功', 'json');
    }

    public function delteHonor(){
        $honor_id = (int)$this->request->param('honor_id');
        $ResumeschoolModel = new ResumehonorModel();
        if (!$detail = $ResumeschoolModel->find($honor_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->user_id != $this->user->user_id) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $ResumeschoolModel->where(['honor_id' => $honor_id])->delete();
        $this->result('', 200, '数据操作成功', 'json');
    }
    /*
     * 校内职务position
     */
    public function positionList()
    {
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $ResumepositionModel = new ResumepositionModel();
        $list = $ResumepositionModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        $data = [];
        foreach ($list as $val) {
            $data[] = [
                'position_id' => $val->position_id,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
                'position_name' => $val->position_name,
                'position_describe' => $val->position_describe,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    public function positionDetail()
    {
        $position_id = (int)$this->request->param('position_id');
        $ResumepositionModel = new ResumepositionModel();
        if (!$detail = $ResumepositionModel->find($position_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $data = [
            'position_id' => $detail->position_id,
            'bg_date' => $detail->bg_date,
            'end_date' => $detail->end_date,
            'position_name' => $detail->position_name,
            'position_describe' => $detail->position_describe,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function setPosition()
    {
        $position_id = (int)$this->request->param('position_id');
        $ResumepositionModel = new ResumepositionModel();
        $detail = $ResumepositionModel->find($position_id);
        $data['bg_date'] = $this->request->param('bg_date');
        $data['end_date'] = $this->request->param('end_date');
        $data['position_name'] = $this->request->param('position_name');
        $data['position_describe'] = $this->request->param('position_describe');
        if ($detail && $detail->member_miniapp_id == $this->appid) {
            $ResumepositionModel->save($data, ['position_id' => $position_id]);
        } else {
            $num = $ResumepositionModel->where(['member_miniapp_id' => $this->appid, 'user_id' => $this->user->user_id])->count();
            if ($num >= 10) {
                $this->result([], 400, '您做多添加10条数据', 'json');
            }
            $data['user_id'] = $this->user->user_id;
            $data['member_miniapp_id'] = $this->appid;
            $ResumepositionModel->save($data);
        }
        $this->result([], '200', '操作成功', 'json');
    }

    public function deltePosition()
    {
        $position_id = (int)$this->request->param('position_id');
        $ResumepositionModel = new ResumepositionModel();
        if (!$detail = $ResumepositionModel->find($position_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->user_id != $this->user->user_id) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $ResumepositionModel->where(['position_id' => $position_id])->delete();
        $this->result('', 200, '数据操作成功', 'json');
    }

    /*
     * 社会实践 practice
     */
    public function practiceList(){
        $where['member_miniapp_id'] = $this->appid;
        $where['user_id'] = $this->user->user_id;
        $ResumepracticeModel = new ResumepracticeModel();
        $list = $ResumepracticeModel->where($where)->order('add_time desc')->limit('0', '10')->select();
        $data = [];
        foreach ($list as $val) {
            $data[] = [
                'practice_id' => $val->practice_id,
                'bg_date' => $val->bg_date,
                'end_date' => $val->end_date,
                'practice_name' => $val->practice_name,
                'practice_describe' => $val->practice_describe,
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function practiceDetail(){
        $practice_id = (int)$this->request->param('practice_id');
        $ResumepracticeModel = new ResumepracticeModel();
        if (!$detail = $ResumepracticeModel->find($practice_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $data = [
            'practice_id' => $detail->practice_id,
            'bg_date' => $detail->bg_date,
            'end_date' => $detail->end_date,
            'practice_name' => $detail->practice_name,
            'practice_describe' => $detail->practice_describe,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function setpractice()
    {
        $practice_id = (int)$this->request->param('practice_id');
        $ResumepracticeModel = new ResumepracticeModel();
        $detail = $ResumepracticeModel->find($practice_id);
        $data['bg_date'] = $this->request->param('bg_date');
        $data['end_date'] = $this->request->param('end_date');
        $data['practice_name'] = $this->request->param('practice_name');
        $data['practice_describe'] = $this->request->param('practice_describe');
        if ($detail && $detail->member_miniapp_id == $this->appid) {
            $ResumepracticeModel->save($data, ['practice_id' => $practice_id]);
        } else {
            $num = $ResumepracticeModel->where(['member_miniapp_id' => $this->appid, 'user_id' => $this->user->user_id])->count();
            if ($num >= 10) {
                $this->result([], 400, '您做多添加10条数据', 'json');
            }
            $data['user_id'] = $this->user->user_id;
            $data['member_miniapp_id'] = $this->appid;
            $ResumepracticeModel->save($data);
        }
        $this->result([], '200', '操作成功', 'json');
    }

    public function deltepractice()
    {
        $practice_id = (int)$this->request->param('practice_id');
        $ResumepracticeModel = new ResumepracticeModel();
        if (!$detail = $ResumepracticeModel->find($practice_id)) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        if ($detail->user_id != $this->user->user_id) {
            $this->result([], 400, '不存在教育经历', 'json');
        }
        $ResumepracticeModel->where(['practice_id' => $practice_id])->delete();
        $this->result('', 200, '数据操作成功', 'json');
    }

    /*自我介绍*/
    public function  getIntroduction(){
        $ResumeModel = new ResumeModel();
        if (!$detail = $ResumeModel->find($this->user->user_id)) {
            $this->result([], 400, '数据初始化成功', 'json');
        };
        $data = [
            'introduction' => $detail->introduction,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }
    public function  setIntroduction(){
        $data['introduction'] = (string) $this->request->param('introduction');
        $ResumeModel = new ResumeModel();
        $detail = $ResumeModel->find($this->user->user_id);
        if (!empty($detail)) {
            $ResumeModel->save($data, ['user_id' => $this->user->user_id]);
        } else {
            $data['member_miniapp_id'] = $this->appid;
            $data['user_id'] = $this->user->user_id;
            $ResumeModel->save($data);
        }
        $this->result([],200,'操作成功','json');
    }
}