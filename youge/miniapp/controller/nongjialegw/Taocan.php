<?php
namespace app\miniapp\controller\nongjialegw;
use app\common\model\nongjiale\PackageModel;
use app\common\model\nongjiale\TaocanphotoModel;
use app\common\model\nongjiale\TaocanpriceModel;
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
            $data['is_online'] = (int) $this->request->param('is_online');
            $data['orderby'] = (int) $this->request->param('orderby');
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
             $data['is_online'] = (int) $this->request->param('is_online');
             $data['orderby'] = (int) $this->request->param('orderby');
            $TaocanModel = new TaocanModel();
            $TaocanModel->save($data,['taocan_id'=>$taocan_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
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
        $data = [];
        foreach($orderby as $k=>$v){
            $data[] = ['photo_id'=>$k,'orderby'=>$v];
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
        $TaocanpriceModel = new TaocanpriceModel();
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
                $this->error('有不存在的房间1',null,101);
                die();
            }
            $taocan[$val->taocan_id] = 1 ;
            $taocan_id = $val->taocan_id;
        }
        if(sizeof($taocan) > 1 || sizeof($packages) !== sizeof($data)){
            $this->error('有不存在的房间2',null,101);
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

        $TaocanpriceModel = new TaocanpriceModel();
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