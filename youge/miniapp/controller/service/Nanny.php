<?php
namespace app\miniapp\controller\service;
use app\common\model\miniapp\MiniappModel;
use app\common\model\service\CategoryModel;
use app\common\model\service\NannyphotoModel;
use app\common\model\service\SkillModel;
use app\miniapp\controller\Common;
use app\common\model\service\NannyModel;
use think\Model;

class Nanny extends Common {
    
    public function index() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = NannyModel::where($where)->count();
        $list = NannyModel::where($where)->order(['nanny_id'=>'desc'])->paginate(10, $count);
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
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('名称不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('头像不能为空',null,101);
            }
            $data['category_id'] = (int) $this->request->param('category_id');
            if(empty($data['category_id'])){
                $this->error('分类不能为空',null,101);
            }
            $data['prie'] = (int) $this->request->param('prie');
            if(empty($data['prie'])){
                $this->error('价格不能为空',null,101);
            }
            $data['day'] = (int) $this->request->param('day');
            if(empty($data['day'])){
                $this->error('该价格 服务多少天不能为空',null,101);
            }
            $data['yv_price'] = ((int) $this->request->param('yv_price')) * 100;
            if(empty($data['yv_price'])){
                $this->error('预约价格不能为空',null,101);
            }
            $data['age'] = (int) $this->request->param('age');
            $data['place'] = $this->request->param('place');  
            $data['work'] = (int) $this->request->param('work');
            $data['home'] = $this->request->param('home');  
            $data['type'] = (int) $this->request->param('type');
            $data['education'] = $this->request->param('education');  
            $data['nation'] = $this->request->param('nation');  
            $data['certificates'] = $this->request->param('certificates');  
            $data['evaluate'] = $this->request->param('evaluate');
            $data['orderby'] = (int) $this->request->param('orderby');
            $data['yvyue_num'] = (int) $this->request->param('yvyue_num');
            $skill = empty($_POST['skill']) ? '' : $_POST['skill'];
            if(empty($skill)){
                $this->error('至少选择一个技能',null,101);
            }
            $data['skill'] = implode(',',$skill);
            $imgs = empty($_POST['imgs']) ? [] :  $_POST['imgs'] ;
            if(empty($imgs)){
                $this->error('请上传案例图片',null,101);
            }
            $NannyModel = new NannyModel();
            $NannyModel->save($data);
            $data2 = [];
            $nanny_id = $NannyModel->nanny_id;
            foreach ($imgs as $val){
                $data2[] = [
                    'member_miniapp_id' => $this->miniapp_id,
                    'nanny_id' => $nanny_id,
                    'photo'  => $val,
                ];
            }
            $SjsalphotoModel = new NannyphotoModel();
            $SjsalphotoModel->saveAll($data2);
            $this->success('操作成功',null);
        } else {
            $CategoryModel = new CategoryModel();
            $category = $CategoryModel->where(['member_miniapp_id'=>$this->miniapp_id,'type'=>2])->select();
            $this->assign('category',$category);
            $SkillModel = new SkillModel();
            $skill = $SkillModel->where(['member_miniapp_id'=>$this->miniapp_id])->limit(0,100)->select();
            $skilarray = [];
            $skilpid = [];
            foreach ($skill as $val){
                    if($val->pid == 0){
                        $skilpid[] = $val;
                    }else{
                        $skilarray[$val->pid][] = $val;
                    }

            }
            $this->assign('skilarray',$skilarray);
            $this->assign('skilpid',$skilpid);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $nanny_id = (int)$this->request->param('nanny_id');
         $NannyModel = new NannyModel();
         if(!$detail = $NannyModel->get($nanny_id)){
             $this->error('请选择要编辑的阿姨管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在阿姨管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('名称不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('头像不能为空',null,101);
            }
            $data['category_id'] = (int) $this->request->param('category_id');
            if(empty($data['category_id'])){
                $this->error('分类不能为空',null,101);
            }
            $data['prie'] = (int) $this->request->param('prie');
            if(empty($data['prie'])){
                $this->error('价格不能为空',null,101);
            }
            $data['day'] = (int) $this->request->param('day');
            if(empty($data['day'])){
                $this->error('该价格 服务多少天不能为空',null,101);
            }
            $data['yv_price'] = ((int) $this->request->param('yv_price')) * 100;
            $data['age'] = (int) $this->request->param('age');
            $data['place'] = $this->request->param('place');  
            $data['work'] = (int) $this->request->param('work');
            $data['home'] = $this->request->param('home');  
            $data['type'] = (int) $this->request->param('type');
            $data['education'] = $this->request->param('education');  
            $data['nation'] = $this->request->param('nation');  
            $data['certificates'] = $this->request->param('certificates');  
            $data['evaluate'] = $this->request->param('evaluate');
            $data['orderby'] = (int) $this->request->param('orderby');
             $data['yvyue_num'] = (int) $this->request->param('yvyue_num');
             $skill = empty($_POST['skill']) ? '' : $_POST['skill'];
             if(empty($skill)){
                 $this->error('至少选择一个技能',null,101);
             }
             $data['skill'] = implode(',',$skill);
            $NannyModel = new NannyModel();
             $imgs = empty($_POST['imgs']) ? [] :  $_POST['imgs'] ;
             if(empty($imgs)){
                 $this->error('请上传案例图片',null,101);
             }
             $data2 = [];
             foreach ($imgs as $val){
                 $data2[] = [
                     'member_miniapp_id' => $this->miniapp_id,
                     'nanny_id' => $nanny_id,
                     'photo'  => $val,
                 ];
             }
             $SjsalphotoModel = new NannyphotoModel();
             $SjsalphotoModel->where(['nanny_id'=>$nanny_id])->delete();
             $SjsalphotoModel->saveAll($data2);
            $NannyModel->save($data,['nanny_id'=>$nanny_id]);
            $this->success('操作成功',null);
         }else{
             $CategoryModel = new CategoryModel();
             $category = $CategoryModel->where(['member_miniapp_id'=>$this->miniapp_id,'type'=>2])->select();
             $this->assign('category',$category);
             $SkillModel = new SkillModel();
             $skill = $SkillModel->where(['member_miniapp_id'=>$this->miniapp_id])->limit(0,100)->select();
             $skilarray = [];
             $skilpid = [];
             foreach ($skill as $val){
                 if($val->pid == 0){
                     $skilpid[] = $val;
                 }else{
                     $skilarray[$val->pid][] = $val;
                 }

             }
             $NannyphotoModel = new NannyphotoModel();
             $photo = $NannyphotoModel->where(['member_miniapp_id'=>$this->miniapp_id,'nanny_id'=>$nanny_id])->limit(0,50)->select();
             $skillarray = explode(',',$detail->skill);
             $skills= [];
             foreach ($skillarray as $val){
                $skills[$val] = $val;
             }
             $this->assign('skills',$skills);
             $this->assign('photo',$photo);
             $this->assign('skilarray',$skilarray);
             $this->assign('skilpid',$skilpid);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $nanny_id = (int)$this->request->param('nanny_id');
         $NannyModel = new NannyModel();
       
        if(!$detail = $NannyModel->find($nanny_id)){
            $this->error("不存在该阿姨管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该阿姨管理', null, 101);
        }
        $NannyModel->where(['nanny_id'=>$nanny_id])->delete();
        $this->success('操作成功');
    }
   
}