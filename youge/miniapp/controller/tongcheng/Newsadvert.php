<?php
namespace app\miniapp\controller\tongcheng;
use app\miniapp\controller\Common;
use app\common\model\tongcheng\NewsadvertModel;
class Newsadvert extends Common {
    
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
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = NewsadvertModel::where($where)->count();
        $list = NewsadvertModel::where($where)->order(['advert_id'=>'desc'])->paginate(10, $count);
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
                $this->error('广告图片不能为空',null,101);
            }
            $data['info_id'] = (int) $this->request->param('info_id');
            if(empty($data['info_id'])){
                $this->error('指向信息不能为空',null,101);
            }
            $data['info'] = $this->request->param('info');  
            if(empty($data['info'])){
                $this->error('备注信息不能为空',null,101);
            }
            $data['view_num'] = (int) $this->request->param('view_num');
            if(empty($data['view_num'])){
                $this->error('查看次数不能为空',null,101);
            }
            $data['bg_data'] = $this->request->param('bg_data');  
            if(empty($data['bg_data'])){
                $this->error('广告开始时间不能为空',null,101);
            }
            $data['end_data'] = $this->request->param('end_data');  
            if(empty($data['end_data'])){
                $this->error('广告结束时间不能为空',null,101);
            }
            $data['is_end'] = $this->request->param('is_end');  
            if(empty($data['is_end'])){
                $this->error('1强制不显示不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            
            
            $NewsadvertModel = new NewsadvertModel();
            $NewsadvertModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $advert_id = (int)$this->request->param('advert_id');
         $NewsadvertModel = new NewsadvertModel();
         if(!$detail = $NewsadvertModel->get($advert_id)){
             $this->error('请选择要编辑的发现页广告',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在发现页广告");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('广告图片不能为空',null,101);
            }
            $data['info_id'] = (int) $this->request->param('info_id');
            if(empty($data['info_id'])){
                $this->error('指向信息不能为空',null,101);
            }
            $data['info'] = $this->request->param('info');  
            if(empty($data['info'])){
                $this->error('备注信息不能为空',null,101);
            }
            $data['view_num'] = (int) $this->request->param('view_num');
            if(empty($data['view_num'])){
                $this->error('查看次数不能为空',null,101);
            }
            $data['bg_data'] = $this->request->param('bg_data');  
            if(empty($data['bg_data'])){
                $this->error('广告开始时间不能为空',null,101);
            }
            $data['end_data'] = $this->request->param('end_data');  
            if(empty($data['end_data'])){
                $this->error('广告结束时间不能为空',null,101);
            }
            $data['is_end'] = $this->request->param('is_end');  
            if(empty($data['is_end'])){
                $this->error('1强制不显示不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }

            
            $NewsadvertModel = new NewsadvertModel();
            $NewsadvertModel->save($data,['advert_id'=>$advert_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $advert_id = (int)$this->request->param('advert_id');
         $NewsadvertModel = new NewsadvertModel();
       
        if(!$detail = $NewsadvertModel->find($advert_id)){
            $this->error("不存在该发现页广告",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该发现页广告', null, 101);
        }
        $NewsadvertModel->where(['advert_id'=>$advert_id])->delete();
        $this->success('操作成功');
    }
   
}