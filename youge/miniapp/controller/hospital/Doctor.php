<?php
namespace app\miniapp\controller\hospital;
use app\common\model\publicuse\CategoryModel;
use app\miniapp\controller\Common;
use app\common\model\hospital\DoctorModel;
class Doctor extends Common {
    
    public function index() {
        $where = $search = [];
        $search['doctor_name'] = $this->request->param('doctor_name');
        if (!empty($search['doctor_name'])) {
            $where['doctor_name'] = array('LIKE', '%' . $search['doctor_name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = DoctorModel::where($where)->count();
        $list = DoctorModel::where($where)->order(['doctor_id'=>'desc'])->paginate(10, $count);
        $categoryIds = [];
        foreach ($list as $val){
            $categoryIds[$val->category_id] = $val->category_id;
        }
        $CategoryModel = new CategoryModel();
        $category = $CategoryModel->itemsByIds($categoryIds);
        $this->assign('category',$category);
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
            $data['category_id'] = (int) $this->request->param('category_id');
            if(empty($data['category_id'])){
                $this->error('分类不能为空',null,101);
            }
            $data['doctor_name'] = $this->request->param('doctor_name');  
            if(empty($data['doctor_name'])){
                $this->error('职工名称不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('列表图片不能为空',null,101);
            }
            $data['experience'] = (int) $this->request->param('experience');
            $data['introduce'] = $this->request->param('introduce');
            $data['major'] = $this->request->param('major');
            $data['learning'] = $this->request->param('learning');
            $data['thank_num'] = (int) $this->request->param('thank_num');
            $data['consult_num'] = (int) $this->request->param('consult_num');
            $data['enroll_num'] = (int) $this->request->param('enroll_num');
            $data['orderby'] = (int) $this->request->param('orderby');
            $DoctorModel = new DoctorModel();
            $DoctorModel->save($data);
            $this->success('操作成功',null);
        } else {
            $category = CategoryModel::where(['member_miniapp_id'=>$this->miniapp_id])->select();
            $this->assign('category',$category);
            return $this->fetch();
        }
    }
    public function edit(){
         $doctor_id = (int)$this->request->param('doctor_id');
         $DoctorModel = new DoctorModel();
         if(!$detail = $DoctorModel->get($doctor_id)){
             $this->error('请选择要编辑的职工管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在职工管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['category_id'] = (int) $this->request->param('category_id');
            if(empty($data['category_id'])){
                $this->error('分类不能为空',null,101);
            }
            $data['doctor_name'] = $this->request->param('doctor_name');  
            if(empty($data['doctor_name'])){
                $this->error('职工名称不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('列表图片不能为空',null,101);
            }
            $data['experience'] = (int) $this->request->param('experience');
            if(empty($data['experience'])){
                $this->error('临床经验不能为空',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('介绍不能为空',null,101);
            }
            $data['major'] = $this->request->param('major');  
            if(empty($data['major'])){
                $this->error('专业擅长不能为空',null,101);
            }
            $data['learning'] = $this->request->param('learning');  
            if(empty($data['learning'])){
                $this->error('学术荣誉不能为空',null,101);
            }
            $data['thank_num'] = (int) $this->request->param('thank_num');
            if(empty($data['thank_num'])){
                $this->error('感谢信不能为空',null,101);
            }
            $data['consult_num'] = (int) $this->request->param('consult_num');
            if(empty($data['consult_num'])){
                $this->error('咨询量不能为空',null,101);
            }
            $data['enroll_num'] = (int) $this->request->param('enroll_num');
            if(empty($data['enroll_num'])){
                $this->error('预约数不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $DoctorModel = new DoctorModel();
            $DoctorModel->save($data,['doctor_id'=>$doctor_id]);
            $this->success('操作成功',null);
         }else{
             $category = CategoryModel::where(['member_miniapp_id'=>$this->miniapp_id])->select();
             $this->assign('category',$category);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    public function delete() {
        $doctor_id = (int)$this->request->param('doctor_id');
        $DoctorModel = new DoctorModel();
        if(!$detail = $DoctorModel->find($doctor_id)){
            $this->error("不存在该职工管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该职工管理', null, 101);
        }
        $DoctorModel->where(['doctor_id'=>$doctor_id])->delete();
        $this->success('操作成功');
    }
   
}