<?php
namespace app\miniapp\controller\taocan;
use app\common\model\city\CityModel;
use app\common\model\miniapp\DescribeModel;
use app\common\model\taocan\DestinationjoinModel;
use app\common\model\taocan\DestinationModel;
use app\common\model\taocan\NavModel;
use app\common\model\taocan\PackageModel;
use app\common\model\taocan\StoreModel;
use app\common\model\taocan\TaocanDetailModel;
use app\common\model\taocan\TaocanpackagepriceModel;
use app\common\model\taocan\TaocanphotoModel;
use app\miniapp\controller\Common;
use app\common\model\taocan\TaocanModel;
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
                $search['store_id'] = (int)$this->request->param('store_id');
        if (!empty($search['store_id'])) {
            $where['store_id'] = $search['store_id'];
        }
             $search['taocan_name'] = $this->request->param('taocan_name');
        if (!empty($search['taocan_name'])) {
            $where['taocan_name'] = array('LIKE', '%' . $search['taocan_name'] . '%');
        }
        
        $where['is_delete'] = 0;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = TaocanModel::where($where)->count();
        $list = TaocanModel::where($where)->order(['taocan_id'=>'desc'])->paginate(10, $count);
        $cityIds = $navIds = $storeIds = [];
        foreach ($list as $val){
             $cityIds[$val->city_id] = $val->city_id;
             $navIds[$val->nav_id] = $val->nav_id;
             $storeIds[$val->store_id] = $val->store_id;
        }
        $CityModel = new CityModel();
        $NavModel = new NavModel();
        $StoreModel = new StoreModel();
        $this->assign('citys',$CityModel->itemsByIds($cityIds));
        $this->assign('navs',$NavModel->itemsByIds($navIds));
        $this->assign('stores',$StoreModel->itemsByIds($storeIds));
        $NavModel = new NavModel();
        $navs = $NavModel->where(['member_miniapp_id'=>$this->miniapp_id])->select();
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('navs',$navs);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function select() {
        $where = $search = [];
        $search['city_id'] = (int)$this->request->param('city_id');
        if (!empty($search['city_id'])) {
            $where['city_id'] = $search['city_id'];
        }
        $search['nav_id'] = (int)$this->request->param('nav_id');
        if (!empty($search['nav_id'])) {
            $where['nav_id'] = $search['nav_id'];
        }
        $search['stroe_id'] = (int)$this->request->param('stroe_id');
        if (!empty($search['stroe_id'])) {
            $where['stroe_id'] = $search['stroe_id'];
        }
        $search['taocan_name'] = $this->request->param('taocan_name');
        if (!empty($search['taocan_name'])) {
            $where['taocan_name'] = array('LIKE', '%' . $search['taocan_name'] . '%');
        }

        $where['is_delete'] = 0;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = TaocanModel::where($where)->count();
        $list = TaocanModel::where($where)->order(['taocan_id'=>'desc'])->paginate(10, $count);
        $cityIds = $navIds = $storeIds = [];
        foreach ($list as $val){
            $cityIds[$val->city_id] = $val->city_id;
            $navIds[$val->nav_id] = $val->nav_id;
            $storeIds[$val->store_id] = $val->store_id;
        }
        $CityModel = new CityModel();
        $NavModel = new NavModel();
        $StoreModel = new StoreModel();
        $this->assign('citys',$CityModel->itemsByIds($cityIds));
        $this->assign('navs',$NavModel->itemsByIds($navIds));
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
            $data['city_id'] = (int) $this->request->param('city_id');
            if(empty($data['city_id'])){
                $this->error('所在城市不能为空',null,101);
            }
            $data['nav_id'] = (int) $this->request->param('nav_id');
            if(empty($data['nav_id'])){
                $this->error('导航(分类)不能为空',null,101);
            }
            $data['type'] = (int) $this->request->param('type');
            if(empty($data['type'])){
                $this->error('请选择套餐类型',null,101);
            }
            $data['store_id'] = (int) $this->request->param('store_id');
            if(empty($data['store_id'])){
                $this->error('所属商家不能为空',null,101);
            }
            $data['taocan_name'] = $this->request->param('taocan_name');  
            if(empty($data['taocan_name'])){
                $this->error('套餐名称不能为空',null,101);
            }
            $data['taocan_tel'] = $this->request->param('taocan_tel');  
            if(empty($data['taocan_tel'])){
                $this->error('负责人电话不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['banner'] = $this->request->param('banner');
            if(empty($data['banner'])){
                $this->error('banner不能为空',null,101);
            }
            $data['score'] = ((int) $this->request->param('score')) * 10;
            if(empty($data['score'])){
                $this->error('评分不能为空',null,101);
            }
            if($data['score'] < 0 || $data['score'] > 50){
                $this->error('您最高只能打5份哦',null,101);
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
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $data['is_hot'] = (int) $this->request->param('is_hot');
            $data2['restrict'] = $this->request->param('restrict');
            if(empty($data2['restrict'])){
                $this->error('预定限制不能为空',null,101);
            }
            $data2['restrict'] = $this->request->param('restrict');
            if(empty($data2['restrict'])){
                $this->error('预定限制不能为空',null,101);
            }
            $data2['usetime'] = $this->request->param('usetime');
            if(empty($data2['usetime'])){
                $this->error('使用时间不能为空',null,101);
            }
            $data2['service'] = $this->request->param('service');
            if(empty($data2['service'])){
                $this->error('服务提示不能为空',null,101);
            }
            $data2['method'] = $this->request->param('method');
            if(empty($data2['method'])){
                $this->error('使用方法不能为空',null,101);
            }
            $data2['other'] = $this->request->param('other');
            if(empty($data2['other'])){
                $this->error('其他提示不能为空',null,101);
            }
            $data2['plus'] = $this->request->param('plus');
            if(empty($data2['plus'])){
                $this->error('加购不能为空',null,101);
            }
            $data['destination_id'] = (int)  $this->request->param('destination_id');
            if(!empty($data['destination_id'])){
                $DestinationModel = new DestinationModel();
                if(!$mudidi = $DestinationModel->find($data['destination_id'])){
                    $this->error('不存在目的地',null,101);
                }
                if($mudidi->member_miniapp_id != $this->miniapp_id){
                    $this->error('不存在目的地',null,101);
                }
            }
            $TaocanModel = new TaocanModel();
            $TaocanModel->save($data);
            $TaocanDetailModel = new TaocanDetailModel();
            $data2['taocan_id'] = $TaocanModel->taocan_id;
            $TaocanDetailModel->save($data2);
            $this->success('操作成功',null);
        } else {
            $store_id = (int) $this->request->param('store_id');
            $store = StoreModel::find($store_id);
            $this->assign('store',$store);
            $navs = NavModel::where(['member_miniapp_id'=>$this->miniapp_id])->limit(0,20)->select();
            $this->assign('navs',$navs);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $taocan_id = (int)$this->request->param('taocan_id');
         $TaocanModel = new TaocanModel();
         if(!$detail = $TaocanModel->get($taocan_id)){
             $this->error('请选择要编辑的套餐管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在套餐管理");
         }
         if ($this->request->method() == 'POST') {
             $data = [];
             $data['member_miniapp_id'] = $this->miniapp_id;
             $data['nav_id'] = (int) $this->request->param('nav_id');
             if(empty($data['nav_id'])){
                 $this->error('导航(分类)不能为空',null,101);
             }
             $data['type'] = (int) $this->request->param('type');
             if(empty($data['type'])){
                 $this->error('请选择套餐类型',null,101);
             }
             $data['is_hot'] = (int) $this->request->param('is_hot');
             $data['taocan_name'] = $this->request->param('taocan_name');
             if(empty($data['taocan_name'])){
                 $this->error('套餐名称不能为空',null,101);
             }
             $data['taocan_tel'] = $this->request->param('taocan_tel');
             if(empty($data['taocan_tel'])){
                 $this->error('负责人电话不能为空',null,101);
             }
             $data['photo'] = $this->request->param('photo');
             if(empty($data['photo'])){
                 $this->error('图片不能为空',null,101);
             }
             $data['banner'] = $this->request->param('banner');
             if(empty($data['banner'])){
                 $this->error('banner不能为空',null,101);
             }
             $data['score'] = ((int) $this->request->param('score')) * 10;
             if(empty($data['score'])){
                 $this->error('评分不能为空',null,101);
             }
             if($data['score'] < 0 || $data['score'] > 50){
                 $this->error('您最高只能打5份哦',null,101);
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
             $data['orderby'] = (int) $this->request->param('orderby');
             if(empty($data['orderby'])){
                 $this->error('排序不能为空',null,101);
             }
             $data2['restrict'] = $this->request->param('restrict');
             if(empty($data2['restrict'])){
                 $this->error('预定限制不能为空',null,101);
             }
             $data2['restrict'] = $this->request->param('restrict');
             if(empty($data2['restrict'])){
                 $this->error('预定限制不能为空',null,101);
             }
             $data2['usetime'] = $this->request->param('usetime');
             if(empty($data2['usetime'])){
                 $this->error('使用时间不能为空',null,101);
             }
             $data2['service'] = $this->request->param('service');
             if(empty($data2['service'])){
                 $this->error('服务提示不能为空',null,101);
             }
             $data2['method'] = $this->request->param('method');
             if(empty($data2['method'])){
                 $this->error('使用方法不能为空',null,101);
             }
             $data2['other'] = $this->request->param('other');
             if(empty($data2['other'])){
                 $this->error('其他提示不能为空',null,101);
             }
             $data2['plus'] = $this->request->param('plus');
             if(empty($data2['plus'])){
                 $this->error('加购不能为空',null,101);
             }
             $data['destination_id'] = (int)  $this->request->param('destination_id');
             if(!empty($data['destination_id'])){
                $DestinationModel = new DestinationModel();
                 if(!$mudidi = $DestinationModel->find($data['destination_id'])){
                     $this->error('不存在目的地',null,101);
                 }
                 if($mudidi->member_miniapp_id != $this->miniapp_id){
                     $this->error('不存在目的地',null,101);
                 }
             }
             $TaocanModel = new TaocanModel();
             $TaocanModel->save($data,['taocan_id'=>$taocan_id]);
             $TaocanDetailModel = new TaocanDetailModel();
             if($TaocanDetailModel->find($taocan_id)){
                 $TaocanDetailModel->save($data2,['taocan_id'=>$taocan_id]);
             }else{
                 $data2['taocan_id'] = $taocan_id;
                 $TaocanDetailModel->save($data2);
             }
             $this->success('操作成功');
         }else{
             $DestinationModel = new DestinationModel();
             $destination = $DestinationModel->find($detail->destination_id);
             $this->assign('destination',$destination);
             $city = CityModel::find($detail->city_id);
             $store = StoreModel::find($detail->store_id);
             $navs = NavModel::where(['member_miniapp_id'=>$this->miniapp_id])->limit(0,20)->select();
             $this->assign('navs',$navs);
             $this->assign('city',$city);
             $this->assign('store',$store);
            $this->assign('detail',$detail);
            $detail2 = TaocanDetailModel::find($detail->taocan_id);
            $this->assign('detail2',$detail2);
            return $this->fetch();  
         }
    }

    public function photo(){
        $taocan_id = (int) $this->request->param('taocan_id');
        $TaocanModel = new TaocanModel();
        if(!$detail = $TaocanModel->find($taocan_id)){
            $this->error('请选择民宿',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择民宿',null,101);
        }
        $TaocanphotoModel = new TaocanphotoModel();
        $photos  = $TaocanphotoModel->where(['taocan_id'=>$taocan_id,'member_miniapp_id'=>$this->miniapp_id])->order(['orderby'=>'desc'])->select();
        $this->assign('photos',$photos);
        $this->assign('taocan_id',$taocan_id);
        $this->assign('detail',$detail);

        return $this->fetch();
    }
    public function photoupdate(){
        $orderby = empty($_POST['orderby']) ? [] : $_POST['orderby'];
        $TaocanphotoModel = new TaocanphotoModel();
        $taocanIds = [];
        $data = [];
        foreach($orderby as $k=>$v){
            $data[] = ['photo_id'=>$k,'orderby'=>$v];
            $taocanIds[$k] = $k;
        }
        $taocan  = $TaocanphotoModel->itemsByIds($taocanIds);
        foreach ($taocan as $val){
            if($val->member_miniapp_id != $this->miniapp_id){
                $this->error('有不存在的图片',null,101);
                break;
            }
        }
        $TaocanphotoModel->saveAll($data);
        $this->success('操作成功！',null);
    }

    public function photodelete(){
        $photo_id = (int)$this->request->param('photo_id');
        if(empty($photo_id)){
            $this->error('参数错误',null,101);
        }
        //echo $photo_id;
        $TaocanphotoModel = new TaocanphotoModel();
        // var_dump($GoodsphotoModel->get($photo_id));
        if(!$photo = $TaocanphotoModel->get($photo_id)){
            $this->error('参数错误',null,101);
        }
        if($photo->member_miniapp_id != $this->miniapp_id){
            $this->error('参数错误',null,101);
        }
        $TaocanphotoModel->where(['photo_id'=>$photo_id])->delete();
        $this->success('删除成功！',null);
    }

    public function photosave(){
        $taocan_id = (int) $this->request->param('taocan_id');
        $TaocanModel = new TaocanModel();
        if(!$detail = $TaocanModel->find($taocan_id)){
            $this->error('请选择民宿',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择民宿',null,101);
        }
        //$mdl = $this->request->param('mdl');  //后期配缩略图
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $dir = ROOT_PATH . 'attachs' . DS . 'uploads';
        $info = $file->move($dir);
        if($info){
            $img = $info->getSaveName();
            $TaocanphotoModel = new TaocanphotoModel();
            $TaocanphotoModel ->save([
                'taocan_id' => $taocan_id,
                'photo'    => $img,
                'member_miniapp_id' => $this->miniapp_id,
                'add_time'  => $this->request->time(),
            ]);
            echo $img;
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }

    public function price(){
        $taocan_id = (int) $this->request->param('taocan_id');
        $TaocanModel = new TaocanModel();
        if(!$taocan = $TaocanModel->find($taocan_id)){
            $this->error('不存在民宿',null,101);
        }
        if($taocan->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在民宿',null,101);
        }
        $TaocanpriceModel = new TaocanpackagepriceModel();
        $date   =   $this->request->param('date');
        $date   =   empty($date) ? date('Y-m-d',time()) : $date;
        $_date  =   strtotime($date) ?  date('Y-m-d',strtotime($date)) : date('Y-m-d',time());
        $package  =   $TaocanpriceModel->backPrice($taocan_id,$this->miniapp_id,$_date);
        $this->assign('taocan',$taocan);
        $this->assign('date',$date);
        $this->assign('packages',$package);
        return $this->fetch();
    }

    public function online (){
        $taocan_id = (int) $this->request->param('taocan_id');
        $TaocanModel = new TaocanModel();
        if(!$taocan = $TaocanModel->find($taocan_id)){
            $this->error("不存在该套餐",null,101);
        }
        if($taocan->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该套餐', null, 101);
        }
        $data['is_online'] = 1;
        if($taocan->is_online == 1){
            $data['is_online'] = 0;
        }
        $TaocanModel->save($data,['taocan_id'=>$taocan_id]);
        $this->success('操作成功');
    }
    
    public function setprice(){
        $data = $_POST['data'];
        if (empty($data)){
            $this->error('请不要更改数据',null,101);
        }
        $date   =   $this->request->param('date');
        $savedata = $packageIds =$taocan = $updatedata =  [];
        $taocan_id = 0;
        foreach ($data as $key=>$val){
            $packageIds[$key] =  (int) $key;
        }
        $packageModel = new PackageModel();
        $packages = $packageModel->itemsByIds($packageIds);
        foreach ($packages as $val){
            if($val->member_miniapp_id != $this->miniapp_id){
                $this->error('有不存在的房间',null,101);
                die();
            }
            $taocan[$val->taocan_id] = 1 ;
            $taocan_id = $val->taocan_id;
        }
        if(sizeof($taocan) > 1 || sizeof($packages) !== sizeof($data)){
            $this->error('有不存在的房间',null,101);
        }

        foreach ($data as $key=>$val){
            if(empty($val['taocanprice_id'])){
                $savedata[] = [
                    'price'     => ((float) $val['price']) * 100,
                    'taocan_id'  => $taocan_id,
                    'package_id'   => (int)$key,
                    'day'       => $date,
                    'is_online' => empty($val['is_online']) ? 0 : 1 ,
                    'member_miniapp_id' => $this->miniapp_id,

                ];
            }else{
                $updatedata[] = [
                    'price_id'  => $val['taocanprice_id'],
                    'price'     => ((float) $val['price']) * 100,
                    'taocan_id'  => $taocan_id,
                    'package_id'   => (int)$key,
                    'day'       => $date,
                    'is_online' => empty($val['is_online']) ? 0 : 1,
                ];
            }

        }

        $TaocanpriceModel = new TaocanpackagepriceModel();
        if(!empty($updatedata)){
            $TaocanpriceModel->saveAll($updatedata);
        }
        if(!empty($savedata)){
            $TaocanpriceModel->saveAll($savedata);
        }

        $this->success('操作成功');
    }

    public function delete() {
        $taocan_id = (int)$this->request->param('taocan_id');
         $TaocanModel = new TaocanModel();
       
        if(!$detail = $TaocanModel->find($taocan_id)){
            $this->error("不存在该套餐管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该套餐管理', null, 101);
        }
        if($detail->is_delete == 1){
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $TaocanModel->save($data,['taocan_id'=>$taocan_id]);
        $this->success('操作成功');
    }



    public function destination(){
        $taocan_id = (int) $this->request->param('taocan_id');
        $DestinationjoinModel = new DestinationjoinModel();
        $TaocanModel = new TaocanModel();
        if(!$taocan = $TaocanModel->find($taocan_id)){
            $this->error('不存在套餐',null,101);
        }
        if($taocan->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在套餐',null,101);
        }
        if($this->request->method() == "POST"){
            $data = empty($_POST['data']) ?  [] : $_POST['data'];
            $destinationIds = [];
            foreach ($data as $key=>$val){
                $destinationIds[$val] = $val;
            }
            $DestinationModel = new DestinationModel();
            $taocanspecials = $DestinationModel->itemsByIds($destinationIds);
            foreach ($taocanspecials as $val){
                if($val->member_miniapp_id != $this->miniapp_id){
                    $this->error('有不存在的',null,101);
                }
            }
            if(sizeof($destinationIds) != sizeof($taocanspecials)){
                $this->error('有不存在的',null,101);
            }
            $data = [];
            foreach ($destinationIds as $val){
                $data[] = [
                    'taocan_id'    => $taocan_id,
                    'destination_id' => $val
                ];
            }
            $DestinationjoinModel->where(['taocan_id'=>$taocan_id])->delete();
            $DestinationjoinModel->saveAll($data);
            $this->success('操作成功');
        }else{
            $taocanspecia1 =  $DestinationjoinModel->where(['taocan_id'=>$taocan_id])->select();

            $list = DestinationModel::where(['member_miniapp_id'=>$this->miniapp_id ,'city_id'=>$taocan->city_id,'is_delete'=>0])->select();
            $taocanspecia = [];
            foreach ($taocanspecia1 as $val){
                $taocanspecia[$val->destination_id] = $val->destination_id;
            }
            $this->assign('list',$list);
            $this->assign('taocanspecia',$taocanspecia);
            $this->assign('taocan',$taocan);
            return $this->fetch();
        }
    }
   
}