<?php
namespace app\miniapp\controller\company;
use app\common\model\company\AreaModel;
use app\common\model\company\CatModel;
use app\common\model\company\PicsModel;
use app\miniapp\controller\Common;
use app\common\model\company\CompanyModel;
class Company extends Common {
    
    public function index() {
        $where = $search = [];
        $search['company_name'] = $this->request->param('company_name');
        if (!empty($search['company_name'])) {
            $where['company_name'] = array('LIKE', '%' . $search['company_name'] . '%');
        }
        
                $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        
                $search['tel'] = $this->request->param('tel');
        if (!empty($search['tel'])) {
            $where['tel'] = array('LIKE', '%' . $search['tel'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CompanyModel::where($where)->count();
        $list = CompanyModel::where($where)->order(['company_id'=>'desc'])->paginate(10, $count);
        $catIds = $areaIds = [];
        foreach ($list as $val){
            $catIds[$val->cat_id] = $val->cat_id;
            $areaIds[$val->area_id] = $val->area_id;
        }
        $CatModel = new CatModel();
        $cats = $CatModel->itemsByIds($catIds);
        $AreaModel = New AreaModel();
        $areas = $AreaModel->itemsByIds($areaIds);
        $this->assign('cats',$cats);
        $this->assign('areas',$areas);
        $page = $list->render();
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
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('响亮口号不能为空',null,101);
            }
            $data['company_name'] = $this->request->param('company_name');  
            if(empty($data['company_name'])){
                $this->error('商家全称不能为空',null,101);
            }
            $data['sort_name'] = $this->request->param('sort_name');  
            if(empty($data['sort_name'])){
                $this->error('商家简称不能为空',null,101);
            }
            $data['logo'] = $this->request->param('logo');  
            if(empty($data['logo'])){
                $this->error('LOGO不能为空',null,101);
            }
            $data['type'] = (int) $this->request->param('type');
            if(empty($data['type'])){
                $this->error('类型不能为空',null,101);
            }
            $data['cat_id'] = (int) $this->request->param('cat_id');
            if(empty($data['cat_id'])){
                $this->error('分类不能为空',null,101);
            }
            $data['area_id'] = (int) $this->request->param('area_id');
            if(empty($data['area_id'])){
                $this->error('区域不能为空',null,101);
            }
            $data['main_business'] = $this->request->param('main_business');  
            if(empty($data['main_business'])){
                $this->error('主营业务不能为空',null,101);
            }
            $data['bg_year'] = $this->request->param('bg_year');  
            if(empty($data['bg_year'])){
                $this->error('成立时间不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('联系人不能为空',null,101);
            }
            $data['tel'] = $this->request->param('tel');  
            if(empty($data['tel'])){
                $this->error('电话不能为空',null,101);
            }
            $data['zhiwu'] = $this->request->param('zhiwu');  
            if(empty($data['zhiwu'])){
                $this->error('职务不能为空',null,101);
            }
            $data['addr'] = $this->request->param('addr');  
            $data['lng'] = $this->request->param('lng');  
            $data['lat'] = $this->request->param('lat');  
            $data['vip'] = (int) $this->request->param('vip');
            $data['view_num'] = (int) $this->request->param('view_num');
            $data['tel_num'] = (int) $this->request->param('tel_num');
            $data['yuyue_num'] = (int) $this->request->param('yuyue_num');
            $data['zan_num'] = (int) $this->request->param('zan_num');
            $data['share_num'] = (int) $this->request->param('share_num');
            $data['detail'] = $this->request->param('detail');  
            $data['audit_photo'] = $this->request->param('audit_photo');  
            $data['audit'] = $this->request->param('audit');
            $CompanyModel = new CompanyModel();
            $CompanyModel->save($data);
            $this->success('操作成功',null);
        } else {
            $AreaModel = new AreaModel();
            $areas = $AreaModel->where(['member_miniapp_id'=>$this->miniapp_id])->limit(0,50)->select();
            $cats = CatModel::where(['member_miniapp_id'=>$this->miniapp_id])->limit(0,50)->select();
            $this->assign('areas',$areas);
            $this->assign('cats',$cats);
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
            $this->error('请选择酒店',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择酒店',null,101);
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

    public function edit(){
         $company_id = (int)$this->request->param('company_id');
         $CompanyModel = new CompanyModel();
         if(!$detail = $CompanyModel->get($company_id)){
             $this->error('请选择要编辑的商家管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在商家管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('响亮口号不能为空',null,101);
            }
            $data['company_name'] = $this->request->param('company_name');  
            if(empty($data['company_name'])){
                $this->error('商家全称不能为空',null,101);
            }
            $data['sort_name'] = $this->request->param('sort_name');  
            if(empty($data['sort_name'])){
                $this->error('商家简称不能为空',null,101);
            }
            $data['logo'] = $this->request->param('logo');  
            if(empty($data['logo'])){
                $this->error('LOGO不能为空',null,101);
            }
            $data['type'] = (int) $this->request->param('type');
            if(empty($data['type'])){
                $this->error('类型不能为空',null,101);
            }
            $data['cat_id'] = (int) $this->request->param('cat_id');
            if(empty($data['cat_id'])){
                $this->error('分类不能为空',null,101);
            }
            $data['area_id'] = (int) $this->request->param('area_id');
            if(empty($data['area_id'])){
                $this->error('区域不能为空',null,101);
            }
            $data['main_business'] = $this->request->param('main_business');  
            if(empty($data['main_business'])){
                $this->error('主营业务不能为空',null,101);
            }
            $data['bg_year'] = $this->request->param('bg_year');  
            if(empty($data['bg_year'])){
                $this->error('成立时间不能为空',null,101);
            }
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('联系人不能为空',null,101);
            }
            $data['tel'] = $this->request->param('tel');  
            if(empty($data['tel'])){
                $this->error('电话不能为空',null,101);
            }
            $data['zhiwu'] = $this->request->param('zhiwu');  
            if(empty($data['zhiwu'])){
                $this->error('职务不能为空',null,101);
            }
            $data['addr'] = $this->request->param('addr');  
            $data['lng'] = $this->request->param('lng');  
            $data['lat'] = $this->request->param('lat');  
            $data['vip'] = (int) $this->request->param('vip');
            $data['view_num'] = (int) $this->request->param('view_num');
            $data['tel_num'] = (int) $this->request->param('tel_num');
            $data['yuyue_num'] = (int) $this->request->param('yuyue_num');
            $data['zan_num'] = (int) $this->request->param('zan_num');
            $data['share_num'] = (int) $this->request->param('share_num');
            $data['detail'] = $this->request->param('detail');  
            $data['audit_photo'] = $this->request->param('audit_photo');  
            $data['audit'] = $this->request->param('audit');  

            
            $CompanyModel = new CompanyModel();
            $CompanyModel->save($data,['company_id'=>$company_id]);
            $this->success('操作成功',null);
         }else{
             $AreaModel = new AreaModel();
             $areas = $AreaModel->where(['member_miniapp_id'=>$this->miniapp_id])->limit(0,50)->select();
             $cats = CatModel::where(['member_miniapp_id'=>$this->miniapp_id])->limit(0,50)->select();
             $this->assign('areas',$areas);
             $this->assign('cats',$cats);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $company_id = (int)$this->request->param('company_id');
         $CompanyModel = new CompanyModel();
       
        if(!$detail = $CompanyModel->find($company_id)){
            $this->error("不存在该删除管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该删除管理', null, 101);
        }
        $CompanyModel->where(['company_id'=>$company_id])->delete();
        $this->success('操作成功');
    }
   
}