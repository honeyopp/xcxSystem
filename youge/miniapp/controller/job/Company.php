<?php
namespace app\miniapp\controller\job;
use app\common\model\job\AreaModel;
use app\common\model\job\IndustryModel;
use app\common\model\job\PicsModel;
use app\common\model\love\UserModel;
use app\miniapp\controller\Common;
use app\common\model\job\CompanyModel;
class Company extends Common {
    
    public function index() {
        $where = $search = [];
        $search['company_name'] = $this->request->param('company_name');
        if (!empty($search['company_name'])) {
            $where['company_name'] = array('LIKE', '%' . $search['company_name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CompanyModel::where($where)->count();
        $list = CompanyModel::where($where)->order(['company_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function select() {
        $where = $search = [];
        $search['company_name'] = $this->request->param('company_name');
        if (!empty($search['company_name'])) {
            $where['company_name'] = array('LIKE', '%' . $search['company_name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CompanyModel::where($where)->count();
        $list = CompanyModel::where($where)->order(['company_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    
    public function create() {
        $CompanyModel = new CompanyModel();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('响亮的口号不能为空',null,101);
            }
            $data['user_id'] = $this->request->param('user_id');
            if($CompanyModel->where(['user_id'=>$data['user_id'],'member_miniapp_id'=>$this->miniapp_id])->find()){
                $this->error('该用户已经是企业用户了不不能再添加了',NULL,101);
            }
            $data['company_name'] = $this->request->param('company_name');  
            if(empty($data['company_name'])){
                $this->error('公司名称不能为空',null,101);
            }
            $data['sort_name'] = $this->request->param('sort_name');  
            if(empty($data['sort_name'])){
                $this->error('公司简称不能为空',null,101);
            }
            $data['logo'] = $this->request->param('logo');  
            if(empty($data['logo'])){
                $this->error('公司LOGO不能为空',null,101);
            }
            $data['lat'] =  $this->request->param('lat');
            if(empty($data['lat'])){
                $this->error('经度不能为空',null,101);
            }
            $data['lng'] = $this->request->param('lng');
            if(empty($data['lng'])){
                $this->error('纬度不能为空',null,101);
            }
            $data['address'] = $this->request->param('address');  
            if(empty($data['address'])){
                $this->error('地址不能为空',null,101);
            }
            $data['region'] = (int) $this->request->param('region');
            if(empty($data['region'])){
                $this->error('所在区域不能为空',null,101);
            }
            $data['main_business'] = $this->request->param('main_business');  
            if(empty($data['main_business'])){
                $this->error('主营业务不能为空',null,101);
            }
            $data['type'] = (int) $this->request->param('type');
            if(empty($data['type'])){
                $this->error('公司性质不能为空',null,101);
            }
            $data['scale'] = $this->request->param('scale');  
            if(empty($data['scale'])){
                $this->error('公司规模不能为空',null,101);
            }
            $data['industry_id'] = (int) $this->request->param('industry_id');
            if(empty($data['industry_id'])){
                $this->error('所属行业不能为空',null,101);
            }
            $data['bg_year'] = $this->request->param('bg_year');  
            if(empty($data['bg_year'])){
                $this->error('年份不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('公司负责人不能为空',null,101);
            }
            $data['tel'] = $this->request->param('tel');  
            if(empty($data['tel'])){
                $this->error('负责人联系方式不能为空',null,101);
            }
            $data['zhiwu'] = $this->request->param('zhiwu');  
            if(empty($data['zhiwu'])){
                $this->error('负责人职位不能为空',null,101);
            }
            $data['vip'] = (int) $this->request->param('vip');

            $data['detail'] =  $this->request->param('detail');
            if(empty($data['detail'])){
                $this->error('商家介绍不能为空',null,101);
            }
            $data['audit_photo'] = $this->request->param('audit_photo');  
            if(empty($data['audit_photo'])){
                $this->error('审核证件图片不能为空',null,101);
            }
            $data['audit'] = $this->request->param('audit');  
            if(empty($data['audit'])){
                $this->error('审核状态不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');

            $data['vip_expire'] = strtotime($this->request->param('vip_expire'));
            $data['is_gopublic'] = (int) $this->request->param('is_gopublic');

            $CompanyModel->save($data);
            $this->success('操作成功',null);
        } else {
            $AreaModel = new AreaModel();
            $area = $AreaModel->where(['member_miniapp_id'=>$this->miniapp_id])->select();
            $IndustryModel = new IndustryModel();
            $industry = $IndustryModel->where(['member_miniapp_id'=>$this->miniapp_id,'pid'=>0])->select();
            $this->assign('industry',$industry);
            $this->assign('area',$area);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $company_id = (int)$this->request->param('company_id');
         $CompanyModel = new CompanyModel();
         if(!$detail = $CompanyModel->get($company_id)){
             $this->error('请选择要编辑的公司管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在公司管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('响亮的口号不能为空',null,101);
            }
            $data['company_name'] = $this->request->param('company_name');  
            if(empty($data['company_name'])){
                $this->error('公司名称不能为空',null,101);
            }
            $data['sort_name'] = $this->request->param('sort_name');  
            if(empty($data['sort_name'])){
                $this->error('公司简称不能为空',null,101);
            }
            $data['logo'] = $this->request->param('logo');  
            if(empty($data['logo'])){
                $this->error('公司LOGO不能为空',null,101);
            }
            $data['lat'] = $this->request->param('lat');
            if(empty($data['lat'])){
                $this->error('经度不能为空',null,101);
            }
            $data['lng'] =  $this->request->param('lng');
            if(empty($data['lng'])){
                $this->error('纬度不能为空',null,101);
            }
            $data['address'] = $this->request->param('address');  
            if(empty($data['address'])){
                $this->error('地址不能为空',null,101);
            }
            $data['region'] = (int) $this->request->param('region');
            if(empty($data['region'])){
                $this->error('所在区域不能为空',null,101);
            }
            $data['main_business'] = $this->request->param('main_business');  
            if(empty($data['main_business'])){
                $this->error('主营业务不能为空',null,101);
            }
            $data['type'] = (int) $this->request->param('type');
            if(empty($data['type'])){
                $this->error('公司性质不能为空',null,101);
            }
            $data['scale'] = $this->request->param('scale');  
            if(empty($data['scale'])){
                $this->error('公司规模不能为空',null,101);
            }
            $data['industry_id'] = (int) $this->request->param('industry_id');
            if(empty($data['industry_id'])){
                $this->error('所属行业不能为空',null,101);
            }
            $data['bg_year'] = $this->request->param('bg_year');  
            if(empty($data['bg_year'])){
                $this->error('年份不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('公司负责人不能为空',null,101);
            }
            $data['tel'] = $this->request->param('tel');  
            if(empty($data['tel'])){
                $this->error('负责人联系方式不能为空',null,101);
            }
            $data['zhiwu'] = $this->request->param('zhiwu');  
            if(empty($data['zhiwu'])){
                $this->error('负责人职位不能为空',null,101);
            }
            $data['vip'] = (int) $this->request->param('vip');
            $data['detail'] =  $this->request->param('detail');
            if(empty($data['detail'])){
                $this->error('商家介绍不能为空',null,101);
            }
            $data['audit_photo'] = $this->request->param('audit_photo');  
            if(empty($data['audit_photo'])){
                $this->error('审核证件图片不能为空',null,101);
            }
            $data['audit'] = $this->request->param('audit');  
            if(empty($data['audit'])){
                $this->error('审核状态不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            $data['vip_expire'] = strtotime($this->request->param('vip_expire'));
            $data['is_gopublic'] = (int) $this->request->param('is_gopublic');
            $CompanyModel = new CompanyModel();
            $CompanyModel->save($data,['company_id'=>$company_id]);
            $this->success('操作成功',null);
         }else{
             $AreaModel = new AreaModel();
             $area = $AreaModel->where(['member_miniapp_id'=>$this->miniapp_id])->select();
             $IndustryModel = new IndustryModel();
             $industry = $IndustryModel->where(['member_miniapp_id'=>$this->miniapp_id,'pid'=>0])->select();
             $this->assign('industry',$industry);
             $this->assign('area',$area);
             $this->assign('detail',$detail);
             return $this->fetch();
         }
    }

    public function photo(){
        $company_id = (int) $this->request->param('company_id');
        $CompanyModel = new CompanyModel();
        if(!$detail = $CompanyModel->find($company_id)){
            $this->error('请选择酒店',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择酒店',null,101);
        }
        $PicsModel = new PicsModel();
        $photos  = $PicsModel->where(['company_id'=>$company_id,'member_miniapp_id'=>$this->miniapp_id])->order(['orderby'=>'desc'])->select();
        $this->assign('photos',$photos);
        $this->assign('company_id',$company_id);
        $this->assign('detail',$detail);
        return $this->fetch();
    }

    public function photoupdate(){
        $orderby = empty($_POST['orderby']) ? [] : $_POST['orderby'];
        $HotelphotoModel = new PicsModel();
        $HotelIds = [];
        $data = [];
        foreach($orderby as $k=>$v){
            $data[] = ['pic_id'=>$k,'orderby'=>$v];
            $HotelIds[$k] = $k;
        }
        $hotel  = $HotelphotoModel->itemsByIds($HotelIds);
        foreach ($hotel as $val){
            if($val->member_miniapp_id != $this->miniapp_id){
                $this->error('有不存在的图片',null,101);
                break;
            }
        }
        $HotelphotoModel->saveAll($data);
        $this->success('操作成功！',null);
    }

    public function photodelete(){
        $photo_id = (int)$this->request->param('pic_id');
        if(empty($photo_id)){
            $this->error('参数错误1',null,101);
        }
        //echo $photo_id;
        $HotelphotoModel = new PicsModel();
        // var_dump($GoodsphotoModel->get($photo_id));
        if(!$photo = $HotelphotoModel->get($photo_id)){
            $this->error('参数错误2',null,101);
        }
        if($photo->member_miniapp_id != $this->miniapp_id){
            $this->error('参数错误3',null,101);
        }
        $HotelphotoModel->where(['pic_id'=>$photo_id])->delete();
        $this->success('删除成功！',null);
    }

    public function photosave(){
        $company_id = (int) $this->request->param('company_id');
        $CompanyModel = new CompanyModel();
        if(!$detail = $CompanyModel->find($company_id)){
            $this->error('请选择公司',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择公司',null,101);
        }
        //$mdl = $this->request->param('mdl');  //后期配缩略图
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $dir = ROOT_PATH . 'attachs' . DS . 'uploads';
        $info = $file->move($dir);
        if($info){
            $img = $info->getSaveName();
            $HotelphotoModel = new PicsModel();
            $HotelphotoModel ->save([
                'company_id' => $company_id,
                'pic'    => $img,
                'member_miniapp_id' => $this->miniapp_id,
                'add_time'  => $this->request->time(),
            ]);
            echo $img;
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }

    public function delete() {
        $company_id = (int)$this->request->param('company_id');
        $CompanyModel = new CompanyModel();
        if(!$detail = $CompanyModel->find($company_id)){
            $this->error("不存在该公司管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该公司管理', null, 101);
        }
        $CompanyModel->where(['company_id'=>$company_id])->delete();
        $this->success('操作成功');
    }




}