<?php
namespace app\miniapp\controller\job;
use app\common\model\job\CompanyModel;
use app\miniapp\controller\Common;
use app\common\model\job\JobModel;
class Job extends Common {
    
    public function index() {
        $where = $search = [];
        $search['company_id'] = (int) $this->request->param('company_id');
        if(!empty($search['company_id'])){
            $where['company_id'] = $search['company_id'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = JobModel::where($where)->count();
        $list = JobModel::where($where)->order(['job_id'=>'desc'])->paginate(10, $count);
        $companyIds = [];
        foreach ($list as $val){
            $companyIds[$val->company_id] = $val->company_id;
        }
        $CompanyModel = new CompanyModel();
        $company = $CompanyModel->itemsByIds($companyIds);
        $page = $list->render();
        $this->assign('company',$company);
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    
    public function create() {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['company_id'] = (int) $this->request->param('company_id');
            if(empty($data['company_id'])){
                $this->error('所属公司不能为空',null,101);
            }
            $CompanyModel = new CompanyModel();
            if(!$company = $CompanyModel->find($data['company_id'])){
                $this->error('不存在公司',null,101);
            }
            if($company->member_miniapp_id != $this->miniapp_id){
                $this->error('不存在公司',null,101);
            }
            $data['lat'] = $company->lat;
            $data['lng'] = $company->lng;
            $data['address'] = $company->address;
            $data['region'] = $company->region;
            $data['type'] = $company->type;
            $data['scale'] = $company->scale;
            $data['industry_id'] = $company->industry_id;

            $data['title'] = $this->request->param('title');
            if(empty($data['title'])){
                $this->error('职位标题不能为空',null,101);
            }
            $data['people_num'] = (int) $this->request->param('people_num');
            if(empty($data['people_num'])){
                $this->error('招聘人数不能为空',null,101);
            }
            $data['experience_id'] = (int) $this->request->param('experience_id');

            $data['education_id'] = (int) $this->request->param('education_id');

            $data['salary_id'] = (int) $this->request->param('salary_id');

            $data['describe'] = $this->request->param('describe');
            if(empty($data['describe'])){
                $this->error('描述不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('联系人不能为空',null,101);
            }
            $data['tel'] = $this->request->param('tel');  
            if(empty($data['tel'])){
                $this->error('联系方式不能为空',null,101);
            }
            $data['is_online'] = (int) $this->request->param('is_online');
            $data['is_delete'] = (int) $this->request->param('is_delete');
            $data['apply_num'] = (int) $this->request->param('apply_num');
            $data['is_eat'] =  (int)$this->request->param('is_eat');
            $data['is_live'] =  (int)$this->request->param('is_live');
            $data['is_weekend'] = (int) $this->request->param('is_weekend');
            $data['is_overtime']  = (int)$this->request->param('is_overtime');
            $data['is_vehicle'] = (int) $this->request->param('is_vehicle');
            $data['is_bus'] =  (int)$this->request->param('is_bus');
            $data['is_meal'] =  (int)$this->request->param('is_meal');
            $data['is_phone'] = (int) $this->request->param('is_phone');
            $data['is_room'] =  (int)$this->request->param('is_room');
            $data['is_festival'] =  (int)$this->request->param('is_festival');
            $data['is_wuxian'] =  (int)$this->request->param('is_wuxian');
            $data['is_mpf'] =  (int)$this->request->param('is_mpf');
            $data['is_bonus'] = (int) $this->request->param('is_bonus');
            $data['is_newyear'] = (int) $this->request->param('is_newyear');
            $data['is_healthy'] = (int) $this->request->param('is_healthy');
            $data['is_tourism'] = (int) $this->request->param('is_tourism');
            $data['is_train'] =  (int)$this->request->param('is_train');
            $data['is_shares'] = (int) $this->request->param('is_shares');
            $data['orderby'] = (int) $this->request->param('orderby');
            $data['update_time'] = (int) strtotime($this->request->param('update_time'));
            $JobModel = new JobModel();
            $JobModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $job_id = (int)$this->request->param('job_id');
         $JobModel = new JobModel();
         if(!$detail = $JobModel->get($job_id)){
             $this->error('请选择要编辑的招聘信息',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在招聘信息");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('职位标题不能为空',null,101);
            }
            $data['people_num'] = (int) $this->request->param('people_num');
            if(empty($data['people_num'])){
                $this->error('招聘人数不能为空',null,101);
            }
            $data['experience_id'] = (int) $this->request->param('experience_id');
            $data['education_id'] = (int) $this->request->param('education_id');

            $data['salary_id'] = (int) $this->request->param('salary_id');

            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('联系人不能为空',null,101);
            }
            $data['tel'] = $this->request->param('tel');  
            if(empty($data['tel'])){
                $this->error('联系方式不能为空',null,101);
            }
            $data['is_online'] = $this->request->param('is_online');  

            $data['is_delete'] = (int) $this->request->param('is_delete');
            $data['apply_num'] = (int) $this->request->param('apply_num');
            $data['is_eat'] = $this->request->param('is_eat');  
            $data['is_live'] = $this->request->param('is_live');  
            $data['is_weekend'] = $this->request->param('is_weekend');  
            $data['is_overtime'] = $this->request->param('is_overtime');  
            $data['is_vehicle'] = $this->request->param('is_vehicle');  
            $data['is_bus'] = $this->request->param('is_bus');  
            $data['is_meal'] = $this->request->param('is_meal');  
            $data['is_phone'] = $this->request->param('is_phone');  
            $data['is_room'] = $this->request->param('is_room');  
            $data['is_festival'] = $this->request->param('is_festival');  
            $data['is_wuxian'] = $this->request->param('is_wuxian');  
            $data['is_mpf'] = $this->request->param('is_mpf');  
            $data['is_bonus'] = $this->request->param('is_bonus');  
            $data['is_newyear'] = $this->request->param('is_newyear');  
            $data['is_healthy'] = $this->request->param('is_healthy');  
            $data['is_tourism'] = $this->request->param('is_tourism');  
            $data['is_train'] = $this->request->param('is_train');  
            $data['is_shares'] = $this->request->param('is_shares');  
            $data['orderby'] = (int) $this->request->param('orderby');
            $data['update'] = (int) strtotime($this->request->param('update'));
            $JobModel = new JobModel();
            $JobModel->save($data,['job_id'=>$job_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $job_id = (int)$this->request->param('job_id');
        $JobModel = new JobModel();
        if(!$detail = $JobModel->find($job_id)){
            $this->error("不存在该招聘信息",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该招聘信息', null, 101);
        }
        $JobModel->where(['job_id'=>$job_id])->delete();
        $this->success('操作成功');
    }
   
}