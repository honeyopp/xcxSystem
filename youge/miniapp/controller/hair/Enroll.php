<?php
namespace app\miniapp\controller\hair;
use app\common\model\hair\CategoryModel;
use app\common\model\hair\DesignerModel;
use app\miniapp\controller\Common;
use app\common\model\hair\EnrollModel;
class Enroll extends Common {
    protected $status = [
        0 => '等待商家接单',
        1 => '已接单',
        2 => '拒绝此预约',
        3 => '取消此预约',
        4 => '取消此预约',
    ];
    public function index() {
        $where = $search = [];
        $search['time'] = $this->request->param('time');
        if (!empty($search['time'])) {
            $where['time'] = array('LIKE', '%' . $search['time'] . '%');
        }
        
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        
        $search['mobile'] = $this->request->param('mobile');
        if (!empty($search['mobile'])) {
            $where['mobile'] = array('LIKE', '%' . $search['mobile'] . '%');
        }
        
        $search['status'] = $this->request->param('status');
        if (!empty($search['status'])) {
            $where['status'] = array('LIKE', '%' . $search['status'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = EnrollModel::where($where)->count();
        $list = EnrollModel::where($where)->order(['enrol_id'=>'desc'])->paginate(10, $count);
        $categoryIds = $designerIds = [];
        foreach ($list as $val){
            $categoryIds[$val->category_id] = $val->category_id;
            $designerIds[$val->designer_id]  =  $val->designer_id;
        }
        $CategotyModel = new CategoryModel();
        $DesignerModel= new DesignerModel();
        $page = $list->render();
        $this->assign('category',$CategotyModel->itemsByIds($categoryIds));
        $this->assign('descigner',$DesignerModel->itemsByIds($designerIds));
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('status',$this->status);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

   public function no(){
       $enrol_id = (int)$this->request->param('enrol_id');
       $EnrollModel = new EnrollModel();

       if(!$detail = $EnrollModel->find($enrol_id)){
           $this->error("不存在该客户预约",null,101);
       }
       if($detail->member_miniapp_id != $this->miniapp_id) {
           $this->error('不存在该客户预约', null, 101);
       }
       if($detail->status != 0){
           $this->error('已处理订单', null, 101);
       }
       $data['status'] = 2;
       $EnrollModel->save($data,['enrol_id'=>$enrol_id]);
       $this->success('操作成功');
   }

   public function yes(){
       $enrol_id = (int)$this->request->param('enrol_id');
       $EnrollModel = new EnrollModel();

       if(!$detail = $EnrollModel->find($enrol_id)){
           $this->error("不存在该客户预约",null,101);
       }
       if($detail->member_miniapp_id != $this->miniapp_id) {
           $this->error('不存在该客户预约', null, 101);
       }
       if($detail->status != 0){
           $this->error('已处理订单', null, 101);
       }
       $data['status'] = 1;
       $EnrollModel->save($data,['enrol_id'=>$enrol_id]);
       $this->success('操作成功');
   }
    public function delete() {
   
        $enrol_id = (int)$this->request->param('enrol_id');
         $EnrollModel = new EnrollModel();
       
        if(!$detail = $EnrollModel->find($enrol_id)){
            $this->error("不存在该客户预约",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该客户预约', null, 101);
        }
        $EnrollModel->where(['enrol_id'=>$enrol_id])->delete();
        $this->success('操作成功');
    }
   
}