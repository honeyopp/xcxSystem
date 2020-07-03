<?php
namespace app\miniapp\controller\job;
use app\common\model\job\IndustryModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
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
use think\Request;

class Resume extends Common {
    
    public function index() {
        $where = $search = [];
        $search['user_id'] = $this->request->param('user_id');
        if(!empty($search['user_id'])){
            $where['user_id'] = $search['user_id'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ResumeModel::where($where)->count();
        $list = ResumeModel::where($where)->order(['user_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function show(){

        $user_id = (int) $this->request->param('user_id');
        $UserModel = new UserModel();
        if(!$user = $UserModel->find($user_id)){
            $this->error('不存在用户',null,101);

        }
        if($user->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在用户',null,101);
        }
        //获取基本信息
        $resume = ResumeModel::find($user_id);
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


        $where['member_miniapp_id'] = $this->miniapp_id;
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
        $this->assign('data',$data);
        return $this->fetch();
    }

   
}