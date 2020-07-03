<?php
namespace  app\api\controller\love;
use app\api\controller\Common;
use app\common\model\love\SpouseModel;
use app\common\model\love\UserModel;
use app\common\model\love\UserphotoModel;
use app\miniapp\controller\love\Im;

class  Member extends  Common{
    protected $checklogin = true;
    protected  $info = []; //用户基本信息；
    protected  $is_vip = false ; //是否是会员；
    protected  $is_user = false; //还没填写任何信息；


//登录后的首页列表;

 public function index(){
     $where['sex'] = $this->info->sex;
     $where['member_miniapp_id'] = $this->appid;
     $UserModel = new UserModel();
     $list = $UserModel->where($where)->order("orderby desc")->limit($this->limit_bg,$this->limit_num)->select();
     $data['list'] = [];
     foreach ($list as  $val){
         $data['list'][] = [
             'photo' => IMG_URL . getImg($val->photo),
             'nickname' => $val->nickname,
             'work'    => $val->work,
             'user_id'  => $val->user_id,
         ];
     }
 }

//    用户详情：（）；
  public function detail(){
        $user_id  =  (int) $this->request->param('user_id');
        $UserModel = new UserModel();
        if(!$user = $UserModel->find($user_id)){
            $this->result('',400,'不存在会员','json');
        }
        if($user->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在会员','json');
        }
        $SpouseModel = new SpouseModel();
        $_spouse = $SpouseModel->find($user_id);
        $spouse = [];
        if(!empty($_spouse)){
            $spouse = [
                'age' => $_spouse->age,
                'height' => $_spouse->height,
                'salary' => $_spouse->salary,
                'posture' => $_spouse->posture,
                'weight' => $_spouse->weight,
                'city' => $_spouse->city,
                'education' => $_spouse->education,
                'marriage' => $_spouse->marriage,
                'child' => $_spouse->child,
                'want_child' => $_spouse->want_child,
                'work' => $_spouse->work,
                'is_car' => $_spouse->is_car,
                'is_smoke' => $_spouse->is_smoke,
                'is_alcohol' => $_spouse->is_alcohol,
            ];
        }
        $data = [
            'user_id' => $user->user_id,
            'nickname' => $user->nickname,
            'photo' => IMG_URL . getImg($user->photo),
            'sex' =>  empty(config('jobsetting.sex')[$user->sex])  ?  '' : config('jobsetting.sex')[$user->sex],
            'height' => $user->height,
            'salary' => empty(config('jobsetting.salary_love')[$user->sex])  ?  '' : config('jobsetting.salary_love')[$user->sex],
            'city' => $user->city,
            'education' => empty(config('jobsetting.education')[$user->sex])  ?  '' : config('jobsetting.education')[$user->sex],
            'marriage' => empty(config('jobsetting.')[$user->sex])  ?  '' : config('jobsetting.')[$user->sex],
            'posture' => empty(config('jobsetting.')[$user->sex])  ?  '' : config('jobsetting.')[$user->sex],
            'weight' => $user->weight,
            'child' => empty(config('jobsetting.')[$user->sex])  ?  '' : config('jobsetting.')[$user->sex],
            'want_child' => empty(config('jobsetting.')[$user->sex])  ?  '' : config('jobsetting.')[$user->sex],
            'work' => $user->work,
            'is_car' => $user->is_car,
            'is_smoke' => $user->is_smoke,
            'is_alcohol' => $user->is_alcohol,
            'constellation' => $user->constellation,
            'race' => $user->race,
            'marry' => empty(config('jobsetting.')[$user->sex])  ?  '' : config('jobsetting.')[$user->sex],
            'introduce' => $user->introduce,
            'spouse' => $spouse,
        ];
  }




 public function addInfo(){
     $data['member_miniapp_id'] = $this->miniapp_id;
     $data['user_id'] = (int) $this->user->user_id;
     $MemberUserModel = new \app\common\model\user\UserModel();
     if (!$user = $MemberUserModel->find($data['user_id'])){
         $this->result('',400,'不存在会员','json');
     }
     if($user->member_miniapp_id != $this->miniapp_id){
         $this->result('',400,'不存在会员','json');
     }
     $data['nickname'] = $this->request->param('nickname');
     if(empty($data['nickname'])){
         $this->result('',400,'昵称不能为空','json');
     }
     $data['photo'] = $this->request->param('photo');
     if(empty($data['photo'])){
         $this->result('',400,'头像不能为空','json');
     }
     $data['sex'] = (int) $this->request->param('sex');
     if(empty($data['sex'])){
         $this->result('',400,'性别不能为空','json');
     }
     $data['height'] = (int) $this->request->param('height');
     if(empty($data['height'])){
         $this->result('',400,'身高不能为空','json');
     }
     $data['salary'] = (int) $this->request->param('salary');
     if(empty($data['salary'])){
         $this->result('',400,'工资不能为空','json');
     }
     $data['city'] = $this->request->param('city');
     if(empty($data['city'])){
         $this->result('',400,'所在地区不能为空','json');
     }
     $data['education'] = (int) $this->request->param('education');
     if(empty($data['education'])){
         $this->result('',400,'学历不能为空','json');
     }
     $data['marriage'] = (int) $this->request->param('marriage');
     if(empty($data['marriage'])){
         $this->result('',400,'婚姻状态不能为空','json');
     }
     $data['posture'] = (int) $this->request->param('posture');
     if(empty($data['posture'])){
         $this->result('',400,'体态不能为空','json');
     }
     $data['weight'] = (int) $this->request->param('weight');
     if(empty($data['weight'])){
         $this->result('',400,'体重不能为空','json');
     }
     $data['child'] = (int) $this->request->param('child');
     if(empty($data['child'])){
         $this->result('',400,'是否有孩子不能为空','json');
     }
     $data['want_child'] = (int) $this->request->param('want_child');
     if(empty($data['want_child'])){
         $this->result('',400,'是否想要孩子不能为空','json');
     }
     $data['work'] = $this->request->param('work');
     if(empty($data['work'])){
         $this->result('',400,'工作岗位不能为空','json');
     }
     $data['is_car'] = (int) $this->request->param('is_car');
     $data['is_smoke'] =  (int) $this->request->param('is_smoke');
     $data['is_alcohol'] = (int) $this->request->param('is_alcohol');
     $data['constellation'] = (int) $this->request->param('constellation');
     if(empty($data['constellation'])){
         $this->result('',400,'星座不能为空','json');
     }
     $data['race'] = $this->request->param('race');
     if(empty($data['race'])){
         $this->result('',400,'民族不能为空','json');
     }
     $data['marry'] = (int) $this->request->param('marry');
     if(empty($data['marry'])){
         $this->result('',400,'何时结婚不能为空','json');
     }
     $UserModel = new UserModel();
     $auth = $UserModel->find($this->user->user_id);
     if(empty($auth)){
         $UserModel->save($data);
     }else{
         unset($data['user_id']);
         $UserModel->save($data,['user_id'=>$this->user_user_id]);
     }
     $this->result('',200,'操作成功','json');
 }


// 打招呼
 public function  hi(){

 }

  public function checkUser(){
        $this->checkInfo();
        $data['is_user'] = $this->is_user;
        $data['is_vip'] = $this->is_vip;
        $this->result($data,200,'数据初始换成功','json');

   }

   public function editpototo(){
         $this->checkInfo();
         if(!$this->is_user){
             $this->result([],400,'您未填写任何资料','json');
         }
         $data['photo'] = $this->request->param('photo');
         if(empty($data['photo'])){
             $this->result('',400,'请上传图片');
         }
         $UserModel = new UserModel();
         $UserModel->save($data,['user_id'=>$this->user->user_id]);
   }
//    检查用户信息；
   public function checkInfo(){
     $UserModel = new UserModel();
     if(!$user = $UserModel->find($this->user->user_id)){
         $this->is_user = false;
         return true;
     }
     if($user->member_miniapp_id != $this->appid){
         $this->is_user = false;
         return true;
     }
     $this->info = $user;
     $this->is_user = true;
     if($user->vip_overdue > $this->request->time()){
         $this->is_vip = true;
     }
     return true;
   }

}