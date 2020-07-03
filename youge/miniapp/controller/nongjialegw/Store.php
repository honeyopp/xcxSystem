<?php
namespace app\miniapp\controller\nongjialegw;
use app\common\model\nongjiale\StorephotoModel;
use app\miniapp\controller\Common;
use app\common\model\nongjiale\StoreModel;
use think\Image;

class Store extends Common {
    
    public function index() {
        $where = $search = [];
        $search['store_name'] = $this->request->param('store_name');
        if (!empty($search['store_name'])) {
            $where['store_name'] = array('LIKE', '%' . $search['store_name'] . '%');
        }
        
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
         $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['type'] = 2;
        $where['is_delete'] = 0;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = StoreModel::where($where)->count();
        $list = StoreModel::where($where)->order(['store_id'=>'desc'])->paginate(10, $count);
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

            $data['store_name'] = $this->request->param('store_name');  
            if(empty($data['store_name'])){
                $this->error('负责人名称不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('农家乐标题不能为空',null,101);
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
            $data['store_tel'] = $this->request->param('store_tel');  
            if(empty($data['store_tel'])){
                $this->error('负责人电话不能为空',null,101);
            }
            $data['store_weixin'] = $this->request->param('store_weixin');  
            if(empty($data['store_weixin'])){
                $this->error('负责人微信不能为空',null,101);
            }
            $data['store_company'] = $this->request->param('store_company');  
            if(empty($data['store_company'])){
                $this->error('所属公司不能为空',null,101);
            }
            $data['traffic'] = $this->request->param('traffic');  
            if(empty($data['traffic'])){
                $this->error('交通方式不能为空',null,101);
            }
            $data['score'] = ((int) $this->request->param('score')) * 10;
            if(empty($data['score'])) {
                $this->error('评分不能为空', null, 101);
            }
            if($data['score'] > 50 || $data['score'] < 10){
                $this->error('评分不得超过5分',null,101);
            }
            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('介绍不能为空',null,101);
            }
            $data['banner'] = $this->request->param('banner');
            if(empty($data['banner'])){
                $this->error('banner不能为空',null,101);
            }
            $StoreModel = new StoreModel();
            $StoreModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $StoreModel = new StoreModel();
         $detail = $StoreModel->where(['member_miniapp_id'=>$this->miniapp_id])->find();
         if ($this->request->method() == 'POST') {
            $data = [];
             $data['type'] = 2;
            $data['store_name'] = $this->request->param('store_name');  
            if(empty($data['store_name'])){
                $this->error('负责人名称不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('农家乐标题不能为空',null,101);
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
            $data['store_tel'] = $this->request->param('store_tel');  
            if(empty($data['store_tel'])){
                $this->error('负责人电话不能为空',null,101);
            }
            $data['store_weixin'] = $this->request->param('store_weixin');  
            if(empty($data['store_weixin'])){
                $this->error('负责人微信不能为空',null,101);
            }
            $data['store_company'] = $this->request->param('store_company');  
            if(empty($data['store_company'])){
                $this->error('所属公司不能为空',null,101);
            }
            $data['traffic'] = $this->request->param('traffic');  
            if(empty($data['traffic'])){
                $this->error('交通方式不能为空',null,101);
            }

            $data['introduce'] = $this->request->param('introduce');  
            if(empty($data['introduce'])){
                $this->error('介绍不能为空',null,101);
            }
             $data['banner'] = $this->request->param('banner');
             if(empty($data['banner'])){
                 $this->error('banner不能为空',null,101);
             }
              $StoreModel = new StoreModel();
             if(empty($detail)){
                 $data['member_miniapp_id'] = $this->miniapp_id;
                 $StoreModel->save($data);
             }else{
                 $StoreModel->save($data,['store_id'=>$detail->store_id]);
             }

            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }

    public function  online(){
        $store_id = (int) $this->request->param('store_id');
        $StoreModel = new StoreModel();
        if(!$store = $StoreModel->find($store_id)){
            $this->error('不存在农家乐',null,101);
        }
        if($store->member_miniapp_id != $this->miniapp_id){
            $this->error('不要存在农家乐',null,101);
        }
        $data['is_online'] = $store->is_online == 1 ? 0 : 1;
        $StoreModel->save($data,['store_id'=>$store_id]);
        $this->success('操作成功');
    }



    public function photo(){
        $store_id = $this->request->param('store_id');
        $StoreModel= new StoreModel();
        if(!$detail = $StoreModel->get($store_id)){
            $this->error('不存在农家乐',null,101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在农家乐',null,101);
        }
        $photos  = StorephotoModel::where(['store_id'=>$store_id])->order(['orderby'=>'desc'])->select();
        $this->assign('photos',$photos);
        $this->assign('store_id',$store_id);
        $this->assign('detail',$detail);
        return $this->fetch();
    }
    public function photoupdate(){
        $orderby = empty($_POST['orderby']) ? [] : $_POST['orderby'];
        $PhotoModel = new StorephotoModel();
        foreach($orderby as $k=>$v){
            $PhotoModel->save(['orderby'=>$v],['photo_id'=>$k]);
        }
        $this->success('操作成功！',null);
    }

    public function photodelete(){
        $photo_id = (int)$this->request->param('photo_id');
        if(empty($photo_id)){
            $this->error('参数错误',null,101);
        }
        //echo $photo_id;
        $PhotoModel = new StorephotoModel();
        // var_dump($GoodsphotoModel->get($photo_id));
        if(!$photo  = $PhotoModel->get($photo_id)){
            $this->error('参数错误1',null,101);
        }
        if($photo->member_miniapp_id != $this->miniapp_id){
            $this->error('参数错误2',null,101);
        }
        $PhotoModel->where(['photo_id'=>$photo_id])->delete();
        $this->success('删除成功！',null);
    }

    public function photosave(){
        $store_id = $this->request->param('store_id');
        $StoreModel= new StoreModel();
        if(!$detail = $StoreModel->get($store_id)){
            $this->error('不存在农家乐',null,101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在农家乐',null,101);
        }
        //$mdl = $this->request->param('mdl');  //后期配缩略图
        $mdl = $this->request->param('mdl');
        $setting =  config('setting.attachs');
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $dir = ROOT_PATH . 'attachs' . DS . 'uploads';
        $info = $file->move($dir);
        if($info){
            $img = $info->getSaveName();
            if(isset($setting[$mdl])){
                foreach( $setting[$mdl] as $k=>$v){
                    if(!empty($v)){
                        $img2 = getImg($img,$k);
                        $image = Image::open($dir.'/'.$img);
                        $wh = explode('X',$v);
                        $image->thumb($wh[0],$wh[1],\think\Image::THUMB_CENTER)->save($dir.'/'.$img2);
                    }
                }
            }
            $StorephotoModel = new StorephotoModel();
            $StorephotoModel ->save([
                'member_miniapp_id' => $this->miniapp_id,
                'store_id' => $store_id,
                'photo'    => $img,
            ]);
            echo $img;
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }






    public function delete() {
        $store_id = (int)$this->request->param('store_id');
         $StoreModel = new StoreModel();
       
        if(!$detail = $StoreModel->find($store_id)){
            $this->error("不存在该农家乐商家",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该农家乐商家', null, 101);
        }
        if($detail->is_delete == 1){
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $StoreModel->save($data,['store_id'=>$store_id]);
        $this->success('操作成功');
    }
   
}