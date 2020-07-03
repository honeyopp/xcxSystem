<?php
namespace app\miniapp\controller\minsu;
use app\miniapp\controller\Common;
use app\common\model\minsu\MinsuspecialModel;
class Minsuspecial extends Common {
    
    public function index() {
        $where = $search = [];
        $search['special_title1'] = $this->request->param('special_title1');
        if (!empty($search['special_title1'])) {
            $where['special_title1'] = array('LIKE', '%' . $search['special_title1'] . '%');
        }
        $search['special_title2'] = $this->request->param('special_title2');
        if (!empty($search['special_title2'])) {
            $where['special_title2'] = array('LIKE', '%' . $search['special_title2'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = MinsuspecialModel::where($where)->count();
        $list = MinsuspecialModel::where($where)->order(['special_id'=>'desc'])->paginate(10, $count);
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
            $data['special_title1'] = $this->request->param('special_title1');  
            if(empty($data['special_title1'])){
                $this->error('标题（显示在首页）不能为空',null,101);
            }
            $data['special_title2'] = $this->request->param('special_title2');  
            if(empty($data['special_title2'])){
                $this->error('副标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['banner'] = $this->request->param('banner');
            if(empty($data['banner'])){
                $this->error('专题banner不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $MinsuspecialModel = new MinsuspecialModel();
            $MinsuspecialModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }

    public function edit(){
         $special_id = (int)$this->request->param('special_id');
         $MinsuspecialModel = new MinsuspecialModel();
         if(!$detail = $MinsuspecialModel->get($special_id)){
             $this->error('不存在专题',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
             $this->error('不存在专题',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['special_title1'] = $this->request->param('special_title1');  
            if(empty($data['special_title1'])){
                $this->error('标题（显示在首页）不能为空',null,101);
            }
            $data['special_title2'] = $this->request->param('special_title2');  
            if(empty($data['special_title2'])){
                $this->error('副标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['banner'] = $this->request->param('banner');
             if(empty($data['banner'])){
                 $this->error('专题banner不能为空',null,101);
             }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $MinsuspecialModel = new MinsuspecialModel();
            $MinsuspecialModel->save($data,['special_id'=>$special_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }

    }
    public function delete() {
            $special_id = (int) $this->request->param('special_id');
            $MinsuspecialModel = new MinsuspecialModel();
            if(!$special = $MinsuspecialModel->find($special_id)){
                $this->error('不存在专题',null,101);
            }
            if($special->member_miniapp_id != $this->miniapp_id){
                $this->error('不存在专题',null,101);
            }
            $MinsuspecialModel = new MinsuspecialModel();
            $MinsuspecialModel->where(array('special_id'=>$special_id))->delete();
        $this->success('操作成功');
    }
   
}