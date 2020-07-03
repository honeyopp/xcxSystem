<?php
namespace app\miniapp\controller\nongjiale;
use app\miniapp\controller\Common;
use app\common\model\nongjiale\TaocanModel;
class Taocan extends Common {
    
    public function index() {
        $where = $search = [];
        $search['city_id'] = (int)$this->request->param('city_id');
        if (!empty($search['city_id'])) {
            $where['city_id'] = $search['city_id'];
        }
                $search['nav_id'] = (int)$this->request->param('nav_id');
        if (!empty($search['nav_id'])) {
            $where['nav_id'] = $search['nav_id'];
        }
                $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = TaocanModel::where($where)->count();
        $list = TaocanModel::where($where)->order(['taocan_id'=>'desc'])->paginate(10, $count);
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
            $data['city_id'] = (int) $this->request->param('city_id');
            if(empty($data['city_id'])){
                $this->error('所在城市不能为空',null,101);
            }
            $data['nav_id'] = (int) $this->request->param('nav_id');
            if(empty($data['nav_id'])){
                $this->error('游玩类型不能为空',null,101);
            }
            $data['type_id'] = (int) $this->request->param('type_id');
            if(empty($data['type_id'])){
                $this->error('产品类型不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['banner'] = $this->request->param('banner');  
            if(empty($data['banner'])){
                $this->error('Banner不能为空',null,101);
            }
            $data['score'] = (int) $this->request->param('score');
            if(empty($data['score'])){
                $this->error('评分不能为空',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            if(empty($data['price'])){
                $this->error('起价不能为空',null,101);
            }
            $data['lat'] = $this->request->param('lat');  
            if(empty($data['lat'])){
                $this->error('经度不能为空',null,101);
            }
            $data['lng'] = $this->request->param('lng');  
            if(empty($data['lng'])){
                $this->error('纬度不能为空',null,101);
            }
            $data['address'] = $this->request->param('address');  
            if(empty($data['address'])){
                $this->error('地址不能为空',null,101);
            }
            $data['is_online'] = $this->request->param('is_online');  
            if(empty($data['is_online'])){
                $this->error('是否上架不能为空',null,101);
            }
            $data['restrict'] = $this->request->param('restrict');  
            if(empty($data['restrict'])){
                $this->error('预定限制不能为空',null,101);
            }
            $data['usetime'] = $this->request->param('usetime');  
            if(empty($data['usetime'])){
                $this->error('使用时间不能为空',null,101);
            }
            $data['service'] = $this->request->param('service');  
            if(empty($data['service'])){
                $this->error('服务不能为空',null,101);
            }
            $data['method'] = $this->request->param('method');  
            if(empty($data['method'])){
                $this->error('使用方式不能为空',null,101);
            }
            $data['other'] = $this->request->param('other');  
            if(empty($data['other'])){
                $this->error('其他不能为空',null,101);
            }
            $data['plus'] = $this->request->param('plus');  
            if(empty($data['plus'])){
                $this->error('加购不能为空',null,101);
            }
            $data['is_hot'] = $this->request->param('is_hot');  
            if(empty($data['is_hot'])){
                $this->error('是否热门不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            
            
            $TaocanModel = new TaocanModel();
            $TaocanModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $taocan_id = (int)$this->request->param('taocan_id');
         $TaocanModel = new TaocanModel();
         if(!$detail = $TaocanModel->get($taocan_id)){
             $this->error('请选择要编辑的产品管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在产品管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['city_id'] = (int) $this->request->param('city_id');
            if(empty($data['city_id'])){
                $this->error('所在城市不能为空',null,101);
            }
            $data['nav_id'] = (int) $this->request->param('nav_id');
            if(empty($data['nav_id'])){
                $this->error('游玩类型不能为空',null,101);
            }
            $data['type_id'] = (int) $this->request->param('type_id');
            if(empty($data['type_id'])){
                $this->error('产品类型不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['banner'] = $this->request->param('banner');  
            if(empty($data['banner'])){
                $this->error('Banner不能为空',null,101);
            }
            $data['score'] = (int) $this->request->param('score');
            if(empty($data['score'])){
                $this->error('评分不能为空',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            if(empty($data['price'])){
                $this->error('起价不能为空',null,101);
            }
            $data['lat'] = $this->request->param('lat');  
            if(empty($data['lat'])){
                $this->error('经度不能为空',null,101);
            }
            $data['lng'] = $this->request->param('lng');  
            if(empty($data['lng'])){
                $this->error('纬度不能为空',null,101);
            }
            $data['address'] = $this->request->param('address');  
            if(empty($data['address'])){
                $this->error('地址不能为空',null,101);
            }
            $data['is_online'] = $this->request->param('is_online');  
            if(empty($data['is_online'])){
                $this->error('是否上架不能为空',null,101);
            }
            $data['restrict'] = $this->request->param('restrict');  
            if(empty($data['restrict'])){
                $this->error('预定限制不能为空',null,101);
            }
            $data['usetime'] = $this->request->param('usetime');  
            if(empty($data['usetime'])){
                $this->error('使用时间不能为空',null,101);
            }
            $data['service'] = $this->request->param('service');  
            if(empty($data['service'])){
                $this->error('服务不能为空',null,101);
            }
            $data['method'] = $this->request->param('method');  
            if(empty($data['method'])){
                $this->error('使用方式不能为空',null,101);
            }
            $data['other'] = $this->request->param('other');  
            if(empty($data['other'])){
                $this->error('其他不能为空',null,101);
            }
            $data['plus'] = $this->request->param('plus');  
            if(empty($data['plus'])){
                $this->error('加购不能为空',null,101);
            }
            $data['is_hot'] = $this->request->param('is_hot');  
            if(empty($data['is_hot'])){
                $this->error('是否热门不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }

            
            $TaocanModel = new TaocanModel();
            $TaocanModel->save($data,['taocan_id'=>$taocan_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $taocan_id = (int)$this->request->param('taocan_id');
         $TaocanModel = new TaocanModel();
       
        if(!$detail = $TaocanModel->find($taocan_id)){
            $this->error("不存在该产品管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该产品管理', null, 101);
        }
        if($detail->is_delete == 1){
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $TaocanModel->save($data,['taocan_id'=>$taocan_id]);
        $this->success('操作成功');
    }
   
}