<?php
namespace app\miniapp\controller\hotel;
use app\miniapp\controller\Common;
use app\common\model\hotel\HotelspecialModel;
class Hotelspecial extends Common {
    
    public function index() {
        $where = $search = [];
        $search['special_title1'] = $this->request->param('special_title1');
        if (!empty($search['special_title1'])) {
            $where['special_title1'] = array('LIKE', '%' . $search['special_title1'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = HotelspecialModel::where($where)->count();
        $list = HotelspecialModel::where($where)->order(['special_id'=>'desc'])->paginate(10, $count);
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
            $data['special_title1'] = $this->request->param('special_title1');  
            if(empty($data['special_title1'])){
                $this->error('标题不能为空',null,101);
            }
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $HotelspecialModel = new HotelspecialModel();
            $HotelspecialModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    public function edit(){
         $special_id = (int)$this->request->param('special_id');
         $HotelspecialModel = new HotelspecialModel();
         if(!$detail = $HotelspecialModel->get($special_id)){
             $this->error('不存在标题',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
             $this->error('不存在专题',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['special_title1'] = $this->request->param('special_title1');  
            if(empty($data['special_title1'])){
                $this->error('标题不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $HotelspecialModel = new HotelspecialModel();
            $HotelspecialModel->save($data,['special_id'=>$special_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    public function delete() {
        if($this->request->method() == 'POST'){
             $special_id = $_POST['special_id'];
        }else{
            $special_id = $this->request->param('special_id');
        }
        $data = [];
        if (is_array($special_id)) {
            foreach ($special_id as $k => $val) {
                $special_id[$k] = (int) $val;
            }
            $data = $special_id;
        } else {
            $data[] = $special_id;
        }
        if (!empty($data)) {
            $HotelspecialModel = new HotelspecialModel();
            $HotelspecialModel->where(array('special_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}