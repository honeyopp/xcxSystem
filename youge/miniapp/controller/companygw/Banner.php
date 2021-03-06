<?php
namespace app\miniapp\controller\companygw;
use app\miniapp\controller\Common;
use app\common\model\companygw\BannerModel;
class Banner extends Common {
    
    public function index() {
        $where = $search = [];

        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = BannerModel::where($where)->count();
        $list = BannerModel::where($where)->order(['banner_id'=>'desc'])->paginate(10, $count);
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
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('Banner不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            
            
            $BannerModel = new BannerModel();
            $BannerModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $banner_id = (int)$this->request->param('banner_id');
         $BannerModel = new BannerModel();
         if(!$detail = $BannerModel->get($banner_id)){
             $this->error('请选择要编辑的Banner设置',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在Banner设置");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('Banner不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }

            
            $BannerModel = new BannerModel();
            $BannerModel->save($data,['banner_id'=>$banner_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $banner_id = (int)$this->request->param('banner_id');
         $BannerModel = new BannerModel();
       
        if(!$detail = $BannerModel->find($banner_id)){
            $this->error("不存在该Banner设置",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该Banner设置', null, 101);
        }
        $BannerModel->where(['banner_id'=>$banner_id])->delete();
        $this->success('操作成功');
    }
   
}