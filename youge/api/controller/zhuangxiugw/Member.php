<?php
namespace app\api\controller\zhuangxiugw;
use app\api\controller\Common;
use app\common\model\fitment\ActiviModel;
use app\common\model\fitment\DesignerModel;
use app\common\model\fitment\EncrollModel;
use app\common\model\fitment\WorkModel;
use app\common\model\fitment\YvyueModel;
use app\common\model\fitment\GroupModel;

class Member extends  Common{
  //protected $checklogin;


  /*
   * 我要装修
   */
  public function yvyue(){
      $data['member_miniapp_id'] = $this->appid;
      //$data['user_id'] = $this->user->user_id;
      $data['name'] = $this->request->param('name');
      if(empty($data['name'])){
          $this->result('',400,'联系人不能为空','json');
      }
      $data['mobile'] = $this->request->param('mobile');
      if(empty($data['mobile'])){
          $this->result('',400,'联系方式不能为空','json');
      }
      $data['area'] = (int) $this->request->param('area');
      $data['price'] = (int) $this->request->param('price');
      $data['address'] = (string) $this->request->param('address');
      $data['info'] = (string) $this->request->param('info');
      $YvyueModel = new YvyueModel();
      $YvyueModel->save($data);
      $this->result('',200,'操作成功','json');
  }


    /*
   * 团装报名
   */
    public function group(){
        $group_id = (int) $this->request->param('group_id');
        $GroupModel = new GroupModel();
        if(!$detail =  $GroupModel->find($group_id)){
            $this->result('',400,'不存在团购','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在团购','json');
        }
        $now = date('Y-m-d',time());
        if($detail->bg_date > $now){
            $this->result('',400,'活动未开始','json');
        }
        if($detail->end_date < $now || $detail->is_end == 1){
            $this->result('',400,'活动以结束','json');
        }
        $data['member_miniapp_id'] = $this->appid;
        $data['type'] = 3;
        $data['type_id'] = $group_id;
        $data['name']  = (string) $this->request->param('name');
        if(empty($data['name'])){
            $this->result('',400,'请输入联系人','json');
        }
        $data['mobile']  = (string) $this->request->param('mobile');
        if(empty($data['mobile'])){
            $this->result('',400,'请输入联系方式','json');
        }
        $EncrollModel = new EncrollModel();
        $EncrollModel->save($data);
        $this->result('',200,'操作成功','json');
    }

    /*
     * 工地预约
     */
    public function gonfdi(){
        $work_id= (int) $this->request->param('work_id');
        $WorkModel = new WorkModel();
        if(!$detail =  $WorkModel->find($work_id)){
            $this->result('',400,'不存在团购','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在团购','json');
        }
        $data['member_miniapp_id'] = $this->appid;
        $data['type'] = 4;
        $data['type_id'] = $work_id;
        $data['name']  = (string) $this->request->param('name');
        if(empty($data['name'])){
            $this->result('',400,'请输入联系人','json');
        }
        $data['mobile']  = (string) $this->request->param('mobile');
        if(empty($data['mobile'])){
            $this->result('',400,'请输入联系方式','json');
        }
        $EncrollModel = new EncrollModel();
        $EncrollModel->save($data);
        $this->result('',200,'操作成功','json');
    }

    /*
     * 设计师预约
     */

    public function shjishi(){
        $designer_id = (int) $this->request->param('designer_id');
        $DesignerModel = new DesignerModel();
        if(!$detail =  $DesignerModel->find($designer_id)){
            $this->result('',400,'不存在团购','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在团购','json');
        }
        $data['member_miniapp_id'] = $this->appid;
        $data['type'] = 2;
        $data['type_id'] = $designer_id;
        $data['name']  = (string) $this->request->param('name');
        if(empty($data['name'])){
            $this->result('',400,'请输入联系人','json');
        }
        $data['mobile']  = (string) $this->request->param('mobile');
        if(empty($data['mobile'])){
            $this->result('',400,'请输入联系方式','json');
        }
        $EncrollModel = new EncrollModel();
        $EncrollModel->save($data);
        $this->result('',200,'操作成功','json');
    }

    /*
     * 活动报名
     */
    public function hodong(){
        $activity_id = (int) $this->request->param('activity_id');
        $ActiviModel = new ActiviModel();
        if(!$detail =  $ActiviModel->find($activity_id)){
            $this->result('',400,'参数错误','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        $now = date('Y-m-d',time());
        if($detail->bg_date > $now){
            $this->result('',400,'活动未开始','json');
        }
        if($detail->end_date < $now || $detail->is_end == 1){
            $this->result('',400,'活动以结束','json');
        }
        $data['member_miniapp_id'] = $this->appid;
        $data['type'] = 1;
        $data['type_id'] = $activity_id;
        $data['name']  = (string) $this->request->param('name');
        if(empty($data['name'])){
            $this->result('',400,'请输入联系人','json');
        }
        $data['mobile']  = (string) $this->request->param('mobile');
        if(empty($data['mobile'])){
            $this->result('',400,'请输入联系方式','json');
        }
        $EncrollModel = new EncrollModel();
        $EncrollModel->save($data);
        $this->result('',200,'操作成功','json');
    }

}