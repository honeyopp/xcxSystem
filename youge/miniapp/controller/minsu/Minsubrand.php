<?php
namespace app\miniapp\controller\minsu;
use app\common\model\minsu\MinsubrandModel;
use app\miniapp\controller\Common;
class Minsubrand extends Common {

    public function index() {
        $where = $search = [];
        $count = MinsubrandModel::where($where)->count();
        $where['member_miniapp_id'] = $this->miniapp_id;
        $list = MinsubrandModel::where($where)->order(['brand_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    public function select() {
        $where = $search = [];
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = MinsubrandModel::where($where)->count();
        $list = MinsubrandModel::where($where)->order(['brand_id'=>'desc'])->paginate(10, $count);
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
            $data['brand_name'] = $this->request->param('brand_name');
            if(empty($data['brand_name'])){
                $this->error('品牌名称不能为空',null,101);
            }
            $data['bloc'] = $this->request->param('bloc');
            if(empty($data['bloc'])){
                $this->error('所属集团不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $MinsubrandModel = new MinsubrandModel();
            $MinsubrandModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    public function edit(){
         $brand_id = (int)$this->request->param('brand_id');
         $MinsubrandModel = new MinsubrandModel();
         if(!$detail = $MinsubrandModel->get($brand_id)){
             $this->error('请选择要编辑的品牌管理1',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
             $this->error('请选择要编辑的品牌管理2',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['brand_name'] = $this->request->param('brand_name');
            if(empty($data['brand_name'])){
                $this->error('品牌名称不能为空',null,101);
            }
            $data['bloc'] = $this->request->param('bloc');
            if(empty($data['bloc'])){
                $this->error('所属集团不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
             $MinsubrandModel->save($data,['brand_id'=>$brand_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();
         }
    }
    public function delete() {
     $brand_id = (int) $this->request->param('brand_id');
     $MinsubrandModel = new MinsubrandModel();
     if(!$brand = $MinsubrandModel->find($brand_id)){
         $this->error('不存在品牌',null,101);
     }
     if($brand->member_miniapp_id != $this->miniapp_id){
         $this->error('不存在品牌',null,101);
     }
       $MinsubrandModel->where(['brand_id'=>$brand_id])->delete();
        $this->success('操作成功');
    }

}