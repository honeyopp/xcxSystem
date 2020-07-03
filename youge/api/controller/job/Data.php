<?php

namespace app\api\controller\job;

use app\api\controller\Common;
use app\common\model\job\AreaModel;
use app\common\model\job\CompanyModel;
use app\common\model\job\IndustryModel;
use app\common\model\job\JobModel;
use app\common\model\job\PicsModel;
use app\common\model\job\PriceModel;
use app\common\model\job\PrivilegeModel;

class  Data extends Common
{

    public function getSearch()
    {
        $data['area'] = [];
        $AreaModel = new AreaModel();
        $area = $AreaModel->where(['member_miniapp_id' => $this->appid])->limit(0, 50)->select();
        foreach ($area as $val) {
            $data['area'][] = [
                'area_name' => $val->area_name,
                'area_id' => $val->area_id,
            ];
        }

        $data['salary'] = [];
        $salary = config('jobsetting.salary');
        foreach ($salary as $key => $val) {
            $data['salary'][] = [
                'salary_id' => $key,
                'salary_name' => $val,
            ];
        }
        $data['experience'] = [];
        $salary = config('jobsetting.experience');
        foreach ($salary as $key => $val) {
            $data['experience'][] = [
                'experience_id' => $key,
                'experience_name' => $val,
            ];
        }
        $data['education'] = [];
        $salary = config('jobsetting.education');
        foreach ($salary as $key => $val) {
            $data['education'][] = [
                'education_id' => $key,
                'education_name' => $val,
            ];
        }

        $data['type'] = [];
        $salary = config('jobsetting.type');
        foreach ($salary as $key => $val) {
            $data['type'][] = [
                'type_id' => $key,
                'type_name' => $val,
            ];
        }
        $data['scale'] = [];
        $salary = config('jobsetting.scale');
        foreach ($salary as $key => $val) {
            $data['scale'][] = [
                'scale_id' => $key,
                'scale_name' => $val,
            ];
        }

        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function getIndex()
    {
        $where = [];
        $region = (int)$this->request->param('area_id');
        if (!empty($region)) {
            $where['region'] = $region;
        }
        $salary_id = (int)$this->request->param('salary_id');
        if (!empty($salary_id)) {
            $where['salary_id'] = $salary_id;
        }
        $experience_id = (int)$this->request->param('experience_id');
        if (!empty($experience_id)) {
            $where['experience_id'] = $experience_id;
        }
        $education_id = (int)$this->request->param('education_id');
        if (!empty($education_id)) {
            $where['education_id'] = $education_id;
        }
        $type_id = (int)$this->request->param('type_id');
        if (!empty($type_id)) {
            $where['type'] = $type_id;
        }
        $scale_id = (int)$this->request->param('scale_id');
        if (!empty($scale_id)) {
            $where['region'] = $scale_id;
        }
        $keyword = $this->request->param('keyword');
        if (!empty($keyword)) {
            $where['title'] = array('LIKE', '%' . $keyword . '%');
        }
        $where['member_miniapp_id'] = $this->appid;
        $where['is_online'] = 1;
        $where['is_delete'] = 0;
        $JobModel = new JobModel();
        $lsit = $JobModel->where($where)->order(['update' => 'desc'])->limit($this->limit_bg, $this->limit_num)->select();
        $data['list'] = $CompanyIds = $areaIds = [];
        foreach ($lsit as $val) {
            $areaIds[$val->region] = $val->region;
            $CompanyIds[$val->company_id] = $val->company_id;
        }
        $AreaModel = new AreaModel();
        $CompanyModel = new CompanyModel();
        $area = $AreaModel->itemsByIds($areaIds);
        $company = $CompanyModel->itemsByIds($CompanyIds);
        foreach ($lsit as $val) {
            $data['list'][] = [
                'job_id' => $val->job_id,
                'title' => $val->title,
                'company_name' => empty($company[$val->company_id]) ? '' : $company[$val->company_id]->company_name,
                'salary' => empty(config('jobsetting.salary')[$val->salary_id]) ? '' : config('jobsetting.salary')[$val->salary_id],
                'experience' => empty(config('jobsetting.experience')[$val->experience_id]) ? '' : config('jobsetting.experience')[$val->experience_id],
                'education' => empty(config('jobsetting.education')[$val->education_id]) ? '' : config('jobsetting.education')[$val->education_id],
                'region' => empty($area[$val->region]) ? '' : $area[$val->region]->area_name,
                'is_ji' => $val->is_ji,
                'audit' => empty($company[$val->company_id]) ? '' : $company[$val->company_id],
                'name' => $val->name,
                'tel' => $val->tel,
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function detail()
    {
        $job_id = (int) $this->request->param('job_id');
        $JobModel = new JobModel();
        if (!$job = $JobModel->find($job_id)) {
            $this->result('', 400, '不存在招聘信息', 'json');
        }
        if ($job->member_miniapp_id != $this->appid) {
            $this->result('', 400, '不存在招聘信息', 'json');
        }
        if ($job->is_delete == 1) {
            $this->error('', 400, '不存在招聘信息', 'json');
        }
        $CompanyModel = new CompanyModel();
        $company = $CompanyModel->find($job->company_id);
        $industry = [];
        $scale = '';
        if ($company) {
            $IndustryModel = new IndustryModel();
            $industry = $IndustryModel->find($company->industry_id);
            $scale = empty(config('jobsetting.scale')[$company->scale]) ? '' : config('jobsetting.scale')[$company->scale];
        }
        $AreaModel = new AreaModel();
        $area = $AreaModel->find($job->region);
        $data = [
            'title' => $job->title,
            'salary' => empty(config('jobsetting.salary')[$job->salary_id]) ? '' : config('jobsetting.salary')[$job->salary_id],
            'area' => empty($area) ? '' : $area->area_name,
            'job_id' => $job->job_id,
            'experience' => empty(config('jobsetting.experience')[$job->experience_id]) ? '' : config('jobsetting.experience')[$job->experience_id],
            'education' => empty(config('jobsetting.education')[$job->education_id]) ? '' : config('jobsetting.education')[$job->education_id],
            'is_ji' => $job->is_ji,
            'audit' => empty($company[$job->company_id]) ? '' : $company[$job->company_id],
            'name' => $job->name,
            'tel' => $job->tel,
            'apply_num' => $job->apply_num,
            'describe' => $job->describe,
            'is_eat' => $job->is_eat,
            'is_live' => $job->is_live,
            'is_weekend' => $job->is_weekend,
            'is_overtime' => $job->is_overtime,
            'is_vehicle' => $job->is_vehicle,
            'is_bus' => $job->is_bus,
            'is_meal' => $job->is_meal,
            'is_phone' => $job->is_phone,
            'is_room' => $job->is_room,
            'is_festival' => $job->is_festival,
            'is_wuxian' => $job->is_wuxian,
            'is_mpf' => $job->is_mpf,
            'is_bonus' => $job->is_bonus,
            'is_newyear' => $job->is_newyear,
            'is_healthy' => $job->is_healthy,
            'is_tourism' => $job->is_tourism,
            'is_train' => $job->is_train,
            'is_shares' => $job->is_shares,
            'lat' =>  empty($company) ? 0 : (float) $company->lat,
            'lng' =>  empty($company) ? 0 : (float) $company->lng,
            'address' => empty($company) ? '' : $company->address,
            'company_id' => empty($company) ? '' : $company->company_id,
            'company_name' => empty($company) ? '' : $company->company_name,
            'company_logo' => empty($company) ? '' : IMG_URL . getImg($company->logo),
            'company_is_gopublic' => empty($company) ? '' : $company->is_gopublic,
            'company_industry' => empty($industry) ? '未知' : $industry->industry_name,
            'company_scale' => $scale,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }


    public function getCompanyData()
    {
        $data['area'] = [];
        $AreaModel = new AreaModel();
        $area = $AreaModel->where(['member_miniapp_id' => $this->appid])->limit(0, 50)->select();
        foreach ($area as $val) {
            $data['area'][] = [
                'area_name' => $val->area_name,
                'area_id' => $val->area_id,
            ];
        }
        $IndustryModel = new IndustryModel();
        $industry = $IndustryModel->where(['member_miniapp_id' => $this->appid, 'pid' => 0])->limit(0, 50)->select();
        $data['industry'] = [];
        foreach ($industry as $val) {
            $data['industry'][] = [
                'industry_id' => $val->industry_id,
                'industry_name' => $val->industry_name,
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function company()
    {
        $keyword = $this->request->param('keywords');
        if (!empty($keyword)) {
            $where['title|company_name|sort_name'] = array('LIKE', '%' . $keyword . '%');
        }
        $lat = floatval($this->request->param('lat'));
        $lng = floatval($this->request->param('lng'));
        $industry_id = $this->request->param('industry_id');
        $area_id = $this->request->param('area_id');
        $order = (int)$this->request->param('orderby');
        if (!empty($cat_id)) {
            $where['industry_id'] = $industry_id;
        }
        if (!empty($area_id)) {
            $where['region'] = $area_id;
        }
        $orderby = ' vip_expire desc,vip desc,orderby desc';
        switch ($order) {
            case 1:
                $orderby = 'vip_expire desc,vip desc,orderby  desc ';
                break;
            case 2:
                $orderby = 'vip_expire desc,vip desc,view_num asc ';
                break;
            case 3:
                $orderby = 'vip_expire desc,vip desc,tel_num desc ';
                break;
            case 5:
                $orderby = 'vip_expire desc,vip desc,share_num desc ';
                break;
            case 6:
                $orderby = 'vip_expire desc,vip desc,zan_num desc ';
                break;
            case 7:
                $orderby = " ABS(lng-'{$lng}' + lat-'{$lat}')  ASC ";
                break;
        }
        $where['member_miniapp_id'] = $this->appid;
        $where['audit'] = 1;
        $CompanyModel = new CompanyModel();
        $data['num'] = $CompanyModel->where($where)->count();
        $list = $CompanyModel->where($where)->order($orderby)->limit($this->limit_bg, $this->limit_num)->select();
        $data['list'] = [];
        $areaIds = [];
        foreach ($list as $val) {
            $areaIds[$val->region] = $val->region;
        }
        $AreaModel = new AreaModel();
        $areas = $AreaModel->itemsByIds($areaIds);
        foreach ($list as $val) {
            $data['list'][] = [
                'company_id' => $val->company_id,
                'logo' => IMG_URL . getImg($val->logo),
                'title' => $val->title,
                'audit' => $val->audit,
                'sort_name' => $val->sort_name,
                'area_name' => empty($areas[$val->region]) ? '' : $areas[$val->region]->area_name,
                'industry' => empty(config('jobsetting.industry')[$val->industry_id]) ? '' : config('jobsetting.industry')[$val->industry_id],
                'scale' => empty(config('jobsetting.scale')[$val->scale]) ? '' : config('jobsetting.scale')[$val->scale],
                'job_num' => $val->job_num,
                'vip' => $val->vip,
                'is_vip' => $val->vip_expire < time() ? 0 : 1,
                'tel' => $val->tel,
                'job_num' => $val->job_num,
                'juli' => empty($lat) ? '（定位中）' : getDistance($lat, $lng, $val->lat, $val->lng),
            ];
        }
        $this->result($data, '200', '数据初始化成功', 'json');
    }

    public function companyDetail()
    {
        $company_id = (int)$this->request->param('company_id');
        $CompanyModel = new CompanyModel();
        if (!$company = $CompanyModel->find($company_id)) {
            $this->result([], '400', '不存在商家', 'json');
        }
        if ($company->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在商家', 'json');
        }
        $PicsModel = new PicsModel();
        $where['company_id'] = $company->company_id;
        $pics = $PicsModel->where($where)->select();
        $photos = [];
        foreach ($pics as $val) {
            $photos[] = IMG_URL . getImg($val->pic);
        }
        $AreaModel = new AreaModel();
        $arae = $AreaModel->find($company->region);
        $CompanyModel->where(['company_id' => $company_id])->setInc('view_num');
        $data['company'] = [
            'company_id' => $company->company_id,
            'title' => $company->title,
            'company_name' => $company->company_name,
            'sort_name' => $company->sort_name,
            'logo' => IMG_URL . getImg($company->logo),
            'type' => $company->type,
            'industry_id' => $company->industry_id,
            'area_id' => $company->region,
            'area_name' => empty($arae) ? '' : $arae->area_name,
            'main_business' => $company->main_business,
            'bg_year' => $company->bg_year,
            'name' => $company->name,
            'tel' => $company->tel,
            'zhiwu' => $company->zhiwu,
            'address' => $company->address,
            'lng' => $company->lng,
            'lat' => $company->lat,
            'vip' => $company->vip,
            'view_num' => $company->view_num,
            'tel_num' => $company->tel_num,
            'yuyue_num' => $company->yuyue_num,
            'zan_num' => $company->zan_num,
            'share_num' => $company->share_num,
            'detail' => $company->detail,
            'audit' => $company->audit,
            'photos' => $photos,
        ];
        $data['job'] = [];
        $JobModel = new JobModel();
        $_where['member_miniapp_id'] = $this->appid;
        $_where['is_online'] = 1;
        $_where['is_delete'] = 0;
        $_where['company_id'] = $company_id;
        $list = $JobModel->where($where)->order('add_time desc')->limit($this->limit_bg, $this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val) {
            $data['list'][] = [
                'job_id' => $val->job_id,
                'title' => $val->title,
                'salary' => empty(config('jobsetting.salary')[$val->salary_id]) ? '' : config('jobsetting.salary')[$val->salary_id],
                'people_num' => $val->people_num,
                'education' => empty(config('jobsetting.educatio')[$val->education_id]) ? '' : config('jobsetting.educatio')[$val->education_id],
                'experience' => empty(config('jobsetting.experience')[$val->experience_id]) ? '' : config('jobsetting.experience')[$val->experience_id]
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, 200, '数据初始化成功', 'json');
    }


    public function qrcord()
    {

        $id = (int)$this->request->param('id');
        $CompanytelModel = new CompanyModel();
        if (!$store = $CompanytelModel->find($id)) {
            $this->result([], '400', '不存在商家', 'json');
        };
        if ($store->member_miniapp_id != $this->appid) {
            $this->result([], '400', '不存在商家', 'json');
        }
        $path = (string)$this->request->param('path');
        $path = empty($path) ? '/pages/index' : $path;
        if (empty($store->qrcode)) {
            $path = (string)$this->request->param('path');
            $path = empty($path) ? '/pages/index' : $path;
            $MiniApp = new \app\common\library\MiniApp($this->appid);
            $data = $MiniApp->getcode($path);
            $type = $this->check_image_type($data);
            $file_name = date("Ymd") . DS . md5(true) . '.' . $type;
            $dir = 'attachs' . DS . 'uploads' . DS . $file_name;
            $datas['qrcode'] = $file_name;
            $return = IMG_URL . getImg($file_name);
//            if(!is_dir($dir)){
//                mkdir($dir,'0777');
//            }
            file_put_contents($dir, $data);
            $CompanytelModel->save($datas, ['company_id' => $id]);
        } else {
            $return = IMG_URL . getImg($store->qrcode);
        }
        $this->result($return, 200, '数据初始化成功', 'json');
        //目录+文件
    }

    public function check_image_type($image)
    {
        $bits = array('jpg' => "\xFF\xD8\xFF", 'gif' => "gif", 'png' => "\x89\x50\x4e\x47\x0d\x0a\x1a\x0a", 'BMP' => 'BM',);
        foreach ($bits as $type => $bit) {
            if (substr($image, 0, strlen($bit)) === $bit) {
                return $type;
            }
        }
        return 'png';
    }


//    获得全部行业；
    public function getIndustry()
    {
        $IndustryModel = new IndustryModel();
        $industry = $IndustryModel->where(['member_miniapp_id' => $this->appid, 'pid' => 0])->limit(0, 50)->select();
        $data = [];
        foreach ($industry as $val) {
            $data[] = [
                'industry_id' => $val->industry_id,
                'industry_name' => $val->industry_name,
                'check' => false,
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function getDatas()
    {
        $conifg = config('jobsetting');
        $data['politicalstatus'] = [];
        foreach ($conifg['politicalstatus'] as $key => $val) {
            $data['politicalstatus'][] = [
                'id' => $key,
                'name' => $val,
            ];
        }
        $data['marriage'] = [];
        foreach ($conifg['marriage'] as $key => $val) {
            $data['marriage'][] = [
                'id' => $key,
                'name' => $val,
            ];
        }
        $data['nature'] = [];
        foreach ($conifg['nature'] as $key => $val) {
            $data['nature'][] = [
                'id' => $key,
                'name' => $val,
            ];
        }
        $data['salary'] = [];
        foreach ($conifg['salary'] as $key => $val) {
            $data['salary'][] = [
                'id' => $key,
                'name' => $val,
            ];
        }
        $data['status'] = [];
        foreach ($conifg['status'] as $key => $val) {
            $data['status'][] = [
                'id' => $key,
                'name' => $val,
            ];
        }
        $data['experience'] = [];
        foreach ($conifg['experience'] as $key => $val) {
            $data['experience'][] = [
                'id' => $key,
                'name' => $val,
            ];
        }
        $data['education'] = [];
        foreach ($conifg['education'] as $key => $val) {
            if ($key != 0) {
                $data['education'][] = [
                    'scale_id' => $key,
                    'scale_name' => $val,
                ];
            }
        }
        $data['type'] = [];
        foreach ($conifg['type'] as $key => $val) {
            if ($key != 0) {
                $data['type'][] = [
                    'scale_id' => $key,
                    'scale_name' => $val,
                ];
            }
        }
        $data['english'] = [];
        foreach ($conifg['english'] as $key => $val) {
            if ($key != 0) {
                $data['english'][] = [
                    'scale_id' => $key,
                    'scale_name' => $val,
                ];
            }
        }
        $data['language'] = [];
        foreach ($conifg['language'] as $key => $val) {
            if ($key != 0) {
                $data['language'][] = $val;
            }
        }
        $data['scale'] = [];
        foreach ($conifg['scale'] as $key => $val) {
            if ($key != 0) {
                $data['scale'][] = [
                    'scale_id' => $key,
                    'scale_name' => $val,
                ];
            }
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }

// 获得行业与职能
    public function getProfession()
    {
        $IndustryModel = new IndustryModel();
        $industry = $IndustryModel->where(['member_miniapp_id' => $this->appid])->select();
        $tree = [];
        foreach ($industry as $category) {
            $tree[$category->industry_id] = [
                'pid' => $category->pid,
                'industry_id' => $category->industry_id,
                'industry_name' => $category->industry_name,
                'check' => false,
                'is_show' => 0,
            ];
            $tree[$category->industry_id]['children'] = [];
        }
        foreach ($tree as $k => $item) {
            if ($item['pid'] != 0) {
                $tree[$item['pid']]['children'][] = &$tree[$k];
                unset($tree[$k]);
            }
        }
        //去掉key；
        $data = [];
        foreach ($tree as $key => $val) {
            $data[] = $val;
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    /*
     * 获取购买信息；
     */

    public function getBuy(){
        $PriceModel = new PriceModel();
        $where['member_miniapp_id'] = $this->appid;
        $PrivilegeModel = new PrivilegeModel();
        $price = $PriceModel->where($where)->order("day_num desc")->limit(0,5)->select();
        $data['price'] = [];
        foreach ($price as $val){
            $data['price'][] = [
                'price_id' => $val->price_id,
                'price' => sprintf("%.2f",$val->price/100),
                'day_price' => sprintf("%.2f",($val->price/$val->day_num)/100),
                'day_num' => $val->day_num,
                'vip_level' => $val->vip_level,
            ];
        }
        $privilege = $PrivilegeModel->find($this->appid);
        $data['privilege'] = '';
        $data['explain'] = '';
        if(!empty($privilege)){
            $data['privilege'] = $privilege->privilege;
            $data['explain'] = $privilege->explain;
        }
        $this->result($data,200,'数据初始化成功','json');
    }

}