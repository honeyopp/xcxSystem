<?php
namespace app\api\controller\hair;
use app\api\controller\Common;
use app\common\model\hair\CategoryModel;
use app\common\model\hair\CommentModel;
use app\common\model\hair\DesignerModel;
use app\common\model\hair\EnrollModel;

class Member extends Common{
    protected $checklogin = true;
    protected $status = [
        0 => '等待商家接单',
        1 => '已接单',
        2 => '拒绝此预约',
        3 => '取消此预约',
    ];
    /*
     *  预约
     */
    public function enroll(){
        $data['member_miniapp_id'] = $this->appid;
        $data['designer_id'] = (int) $this->request->param('designer_id');
        if(empty($data['designer_id'])){
            $this->result('',400,'设计师不能为空','json');
        }
        $data['user_id'] = $this->user->user_id;
        $data['category_id'] = (int) $this->request->param('category_id');
        if(empty($data['category_id'])){
            $this->result('',400,'预约类型不能为空','json');
        }
        $data['num'] = (int) $this->request->param('num');
        if(empty($data['num'])){
            $this->result('',400,'预约人数不能为空','json');
        }
        $data['time'] = (int) strtotime($this->request->param('time'));
        if(empty($data['time'])){
            $this->result('',400,'预约时间不能为空','json');
        }
        $data['name'] = $this->request->param('name');
        if(empty($data['name'])){
            $this->result('',400,'姓名不能为空','json');
        }
        $data['mobile'] = $this->request->param('mobile');
        if(empty($data['mobile'])){
            $this->result('',400,'手机号不能为空','json');
        }
        $DesignerModel = new DesignerModel();
        $DesignerModel->where(['designer_id'=>$data['designer_id']])->setInc('enroll_num');
        $EnrollModel = new EnrollModel();
        $EnrollModel->save($data);
        $this->result($data,200,'操作成功','json');

    }
    /*
     * 我的预约
     */
    public function enrollList(){
        $where['user_id'] = $this->user->user_id;
        $EnrollModel = new EnrollModel();
        $list = $EnrollModel->where($where)->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        $designerIds =  $categoryIds = [];
        foreach ($list as $val){
            $designerIds[$val->designer_id] = $val->designer_id;
            $categoryIds[$val->category_id] = $val->category_id;
        }
        $CategoryModel = new CategoryModel();
         $cate =  $CategoryModel->itemsByIds($categoryIds);
        $DesignerModel = new DesignerModel();
        $designer = $DesignerModel->itemsByIds($designerIds);
        foreach ($list as $val){
            $data['list'][]= [
                'enrol_id' => $val->enrol_id,
                'num' => $val->num,
                'category_name' => empty($cate[$val->category_id]) ? '' : $cate[$val->category_id]->name,
                'name' => $val->name,
                'mobile' => $val->mobile,
                'status' => $val->status,
                'status_mean' => empty($this->status[$val->status]) ? '' : $this->status[$val->status],
                'time' => date("Y-m-d H:i:s",$val->time),
                'designer' =>  empty($designer[$val->designer_id]) ? '' : $designer[$val->designer_id]->name,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 取消预约
     *
     */
    public function cancel(){
        $enrol_id = (int) $this->request->param('enrol_id');
        $EnrollModel = new EnrollModel();
        if(!$detail = $EnrollModel->find($enrol_id)){
            $this->result('',400,'参数错误','json');
        }
        if($detail->user_id != $this->user->user_id){
            $this->result('',400,'参数错误','json');
        }
        if($detail->status >1){
            $this->result('',400,'不可取消','json');
        }
        $data['status'] = 4;
        $EnrollModel->save($data,['enrol_id'=>$enrol_id]);
        $this->result('',200,'操作成功','json');
    }

    /*
     * 评论设计师
     */
    public function comment(){
        $designer_id = (int) $this->request->param('designer_id');
        $DesignerModel = new DesignerModel();
        if(!$detail = $DesignerModel->find($designer_id)){
            $this->result('',400,'不存在设计师','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'不存在设计师','json');
        }
        $data['user_id'] = $this->user->user_id;
        $data['designer_id'] = $designer_id;
        $data['member_miniapp_id'] = $this->appid;
        $data['content'] = (string) $this->request->param('content');
        if(empty($data['content'])){
            $this->result('',400,'请评论内容','json');
        }
        $CommentModel = new CommentModel();
        $CommentModel->save($data);
        $this->result('',200,'操作成功','json');
    }

}