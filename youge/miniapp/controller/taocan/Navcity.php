<?php
namespace app\miniapp\controller\taocan;
use app\common\model\city\CityModel;
use app\common\model\taocan\NavModel;
use app\miniapp\controller\Common;
use app\common\model\taocan\NavcityModel;
class Navcity extends Common {
    
    public function index() {
        return $this->fetch();
    }
    
    public function create() {
        $city_id = (int) $this->request->param('city_id');
        $CityModel = new CityModel();
        if(!$city = $CityModel->find($city_id)){
            $this->error('不存在城市',null,101);
        }
        if($city->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在城市',null,101);
        }
        $where['city_id'] = $city_id;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $nav = NavcityModel::where($where)->find();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['city_id'] = $city_id;
             $ids = $_POST['ids'];
             $NavModel = new NavModel();
             $navs = $NavModel->itemsByIds($ids);
             if(sizeof($ids) != sizeof($navs)){
                 $this->error( '有不存在的导航',null,101);
             }
              foreach ($navs as $val){
                  if($val->member_miniapp_id != $this->miniapp_id){
                      $this->error( '有不存在的导航',null,101);
                  }

              }
            $navIds = implode(',',$ids);
            $data['nav_ids'] =$navIds;
            $NavcityModel = new NavcityModel();
            if($nav){
                $NavcityModel->save($data,['navcity_id'=>$nav->navcity_id]);
            }else{
                $NavcityModel->save($data);
            }

            $this->success('操作成功',null);
        } else {
            $navids = $navs =  [];
            if(!empty($nav)){
                $navs =  explode(',',$nav->nav_ids);
            }

            foreach ($navs as $val){
                $navids[$val] = $val;
            }
            $list = NavModel::where(['member_miniapp_id'=>$this->miniapp_id])->select();
            $this->assign('list',$list);
            $this->assign('navids',$navids);
            $this->assign('city',$city);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $navcity_id = (int)$this->request->param('navcity_id');
         $NavcityModel = new NavcityModel();
         if(!$detail = $NavcityModel->get($navcity_id)){
             $this->error('请选择要编辑的设置导航',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在设置导航");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['city_id'] = (int) $this->request->param('city_id');
            if(empty($data['city_id'])){
                $this->error('城市不能为空',null,101);
            }
            $data['nav_ids'] = (int) $this->request->param('nav_ids');
            if(empty($data['nav_ids'])){
                $this->error('导航不能为空',null,101);
            }

            
            $NavcityModel = new NavcityModel();
            $NavcityModel->save($data,['navcity_id'=>$navcity_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $navcity_id = (int)$this->request->param('navcity_id');
         $NavcityModel = new NavcityModel();
       
        if(!$detail = $NavcityModel->find($navcity_id)){
            $this->error("不存在该设置导航",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该设置导航', null, 101);
        }
        if($detail->is_delete == 1){
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $NavcityModel->save($data,['navcity_id'=>$navcity_id]);
        $this->success('操作成功');
    }
   
}