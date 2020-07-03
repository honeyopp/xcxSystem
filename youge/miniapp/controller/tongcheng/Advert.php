<?php
namespace app\miniapp\controller\tongcheng;
use app\miniapp\controller\Common;
use app\common\model\tongcheng\AdvertModel;
class Advert extends Common {
    
    public function index() {
        $where = $search = [];
        $search['info_id'] = (int)$this->request->param('info_id');
        if (!empty($search['info_id'])) {
            $where['info_id'] = $search['info_id'];
        }
        $search['info'] = $this->request->param('info');
        if (!empty($search['info'])) {
            $where['info'] = array('LIKE', '%' . $search['info'] . '%');
        }
        $search['bg_data'] = $this->request->param('bg_data');
        if (!empty($search['bg_data'])) {
            $where['bg_data'] = array('LIKE', '%' . $search['bg_data'] . '%');
        }
        $search['end_data'] = $this->request->param('end_data');
        if (!empty($search['end_data'])) {
            $where['end_data'] = array('LIKE', '%' . $search['end_data'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = AdvertModel::where($where)->count();
        $list = AdvertModel::where($where)->order(['advert_id'=>'desc'])->paginate(10, $count);
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
                $this->error('图片不能为空',null,101);
            }
            $data['info_id'] = (int) $this->request->param('info_id');
            if(empty($data['info_id'])){
                $this->error('公告指向文章不能为空',null,101);
            }
            $data['info'] = $this->request->param('info');  
            $data['bg_data'] = $this->request->param('bg_data');  
            if(empty($data['bg_data'])){
                $this->error('广告开始时间不能为空',null,101);
            }
            $data['end_data'] = $this->request->param('end_data');  
            if(empty($data['end_data'])){
                $this->error('广告结束时间不能为空',null,101);
            }
            $data['is_end'] = (int) $this->request->param('is_end');
            $data['orderby'] = (int) $this->request->param('orderby');
            $AdvertModel = new AdvertModel();
            $AdvertModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $advert_id = (int)$this->request->param('advert_id');
         $AdvertModel = new AdvertModel();
         if(!$detail = $AdvertModel->get($advert_id)){
             $this->error('请选择要编辑的首页广告',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在首页广告");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['info_id'] = (int) $this->request->param('info_id');
            if(empty($data['info_id'])){
                $this->error('公告指向文章不能为空',null,101);
            }
            $data['info'] = $this->request->param('info');  
            $data['bg_data'] = $this->request->param('bg_data');  
            if(empty($data['bg_data'])){
                $this->error('广告开始时间不能为空',null,101);
            }
            $data['end_data'] = $this->request->param('end_data');  
            if(empty($data['end_data'])){
                $this->error('广告结束时间不能为空',null,101);
            }
            $data['is_end'] = (int) $this->request->param('is_end');
            $data['orderby'] = (int) $this->request->param('orderby');
            $AdvertModel = new AdvertModel();
            $AdvertModel->save($data,['advert_id'=>$advert_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        $advert_id = (int)$this->request->param('advert_id');
         $AdvertModel = new AdvertModel();
        if(!$detail = $AdvertModel->find($advert_id)){
            $this->error("不存在该首页广告",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该首页广告', null, 101);
        }
        $AdvertModel->where(['advert_id'=>$advert_id])->delete();
        $this->success('操作成功');
    }
   
}