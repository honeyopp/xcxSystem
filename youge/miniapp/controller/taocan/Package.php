<?php
namespace app\miniapp\controller\taocan;
use app\common\model\taocan\StoreModel;
use app\common\model\taocan\TaocanModel;
use app\miniapp\controller\Common;
use app\common\model\taocan\PackageModel;
class Package extends Common {
    
    public function index() {
        $where = $search = [];
        $search['taocanname'] = $this->request->param('taocanname');
        $search['taocan_id'] = (int)$this->request->param('taocan_id');
        if (!empty($search['taocan_id'])) {
            $where['taocan_id'] = $search['taocan_id'];
        }
                $search['store_id'] = (int)$this->request->param('store_id');
        if (!empty($search['store_id'])) {
            $where['store_id'] = $search['store_id'];
        }
                $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        
                $search['is_online'] = $this->request->param('is_online');
        if (!empty($search['is_online'])) {
            $where['is_online'] = array('LIKE', '%' . $search['is_online'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['is_delete'] = 0;
        $count = PackageModel::where($where)->count();
        $list = PackageModel::where($where)->order(['package_id'=>'desc'])->paginate(10, $count);
        $taocanIds = $storeIds = [];
        foreach ($list as $val){
            $taocanIds[$val->taocan_id] = $val->taocan_id;
            $storeIds[$val->store_id] = $val->store_id;
        }
        $TaocanModel  = new TaocanModel();
        $StoreModel = new StoreModel();
        $this->assign('taocans',$TaocanModel->itemsByIds($taocanIds));
        $this->assign('stores',$StoreModel->itemsByIds($storeIds));
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
            $data['taocan_id'] = (int) $this->request->param('taocan_id');
            if(empty($data['taocan_id'])){
                $this->error('所属套餐不能为空',null,101);
            }
            $PackageModel = new PackageModel();
            $TaocanModel = new TaocanModel();
            if(!$taocan = $TaocanModel->find($data['taocan_id'])){
                $this->error('不存在套餐',null,101);
            }
            if($taocan->member_miniapp_id != $this->miniapp_id){
                $this->error('不存在套餐',null,101);
            }
            $data['store_id'] = $taocan->store_id;
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('列表缩略图不能为空',null,101);
            }
            $data['price'] = ((int) $this->request->param('price')) * 100;
            if(empty($data['price'])){
                $this->error('日常价格不能为空',null,101);
            }
            $taocanpruice = $TaocanModel->where(['member_miniapp_id'=>$this->miniapp_id,'taocan_id'=>$data['taocan_id']])->order("price asc")->find();
//            添加房屋是修改最民宿最低起始价如果新房源的价格是最小的则修改民宿其实价格；
            if($data['price'] <= $taocanpruice->price || empty($taocanpruice->price)){
                $package_data['price'] = $data['price'];
                $TaocanModel->save($package_data,['taocan_id'=>$data['taocan_id']]);
//                如果大于民宿最小价格则判断当前其实价格是不是最小价格如果不是则修改防止设最小价格后修改
            }else if($taocan->price  < $taocanpruice->price && $taocanpruice->price < $data['price'] ){
                $package_data['price'] = $taocanpruice->price;
                $TaocanModel->save($package_data,['taocan_id'=>$data['taocan_id']]);
            }
            $data['is_cancel'] = (int) $this->request->param('is_cancel');
            $data['is_changes'] = (int) $this->request->param('is_changes');
            $data['details'] = $this->request->param('details');
            $data['especially'] = $this->request->param('especially');  
            if(empty($data['especially'])){
                $this->error('特别说明不能为空',null,101);
            }
            $data['cancel'] = $this->request->param('cancel');  
            if(empty($data['cancel'])){
                $this->error('退订规则不能为空',null,101);
            }
            $data['changes'] = $this->request->param('changes');  
            if(empty($data['changes'])){
                $this->error('改签政策不能为空',null,101);
            }
            $data['day_num'] = $this->request->param('day_num');  
            if(empty($data['day_num'])){
                $this->error('单日最大预定数不能为空',null,101);
            }
            $data['is_online'] = (int) $this->request->param('is_online');
            $PackageModel->save($data);
            $this->success('操作成功',null);
        } else {
            $taocan_id = (int) $this->request->param('taocan_id');
            $taocan = TaocanModel::find($taocan_id);
            $this->assign('taocan',$taocan);
            return $this->fetch();
        }
    }

    public function edit(){
         $package_id = (int)$this->request->param('package_id');
         $PackageModel = new PackageModel();
         if(!$detail = $PackageModel->get($package_id)){
             $this->error('请选择要编辑的套餐内容',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在套餐内容");
         }

         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;

            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('列表缩略图不能为空',null,101);
            }
            $data['price'] = ((int) $this->request->param('price'))*100;
            if(empty($data['price'])){
                $this->error('日常价格不能为空',null,101);
            }
            $TaocanModel = new TaocanModel();
            if(!$taocan = $TaocanModel->find($detail->taocan_id)){
                $this->error('套餐已丢失不存在请联系客服',null,101);
            }
             $taocanpruice = $TaocanModel->where(['member_miniapp_id'=>$this->miniapp_id,'taocan_id'=>$detail->taocan_id])->order("price asc")->find();
//            添加房屋是修改最民宿最低起始价如果新房源的价格是最小的则修改民宿其实价格；
             if($data['price'] <= $taocanpruice->price || empty($taocanpruice->price)){
                 $package_data['price'] = $data['price'];
                 $TaocanModel->save($package_data,['taocan_id'=>$detail->taocan_id]);
//                如果大于民宿最小价格则判断当前其实价格是不是最小价格如果不是则修改防止设最小价格后修改
             }else if($taocan->price  <= $taocanpruice->price && $taocanpruice->price < $data['price'] ){
                 $package_data['price'] = $data['price'] ;
                 $TaocanModel->save($package_data,['taocan_id'=>$detail->taocan_id]);
             }
            $data['is_cancel'] =(int)$this->request->param('is_cancel');
            $data['is_changes'] = (int)$this->request->param('is_changes');
            $data['details'] = $this->request->param('details');  
            if(empty($data['details'])){
                $this->error('详情说明不能为空',null,101);
            }

            $data['especially'] = $this->request->param('especially');  
            if(empty($data['especially'])){
                $this->error('特别说明不能为空',null,101);
            }
            $data['cancel'] = $this->request->param('cancel');  
            if(empty($data['cancel'])){
                $this->error('退订规则不能为空',null,101);
            }
            $data['changes'] = $this->request->param('changes');  
            if(empty($data['changes'])){
                $this->error('改签政策不能为空',null,101);
            }
            $data['day_num'] = $this->request->param('day_num');  
            if(empty($data['day_num'])){
                $this->error('单日最大预定数不能为空',null,101);
            }
            $data['is_online'] = (int) $this->request->param('is_online');
            $PackageModel = new PackageModel();
            $PackageModel->save($data,['package_id'=>$package_id]);
            $this->success('操作成功',null);
         }else{
            $taocan = TaocanModel::find($detail->taocan_id);
            $this->assign('detail',$detail);
            $this->assign('taocan',$taocan);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $package = (int)$this->request->param('package');
         $PackageModel = new PackageModel();
       
        if(!$detail = $PackageModel->find($package)){
            $this->error("不存在该套餐内容",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该套餐内容', null, 101);
        }
        if($detail->is_delete == 1){
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $PackageModel->save($data,['package'=>$package]);
        $this->success('操作成功');
    }
   
}