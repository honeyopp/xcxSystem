<?php
namespace app\miniapp\controller\sheying;
use app\miniapp\controller\Common;
use app\common\model\sheying\BannerModel;
class Banner extends Common {

    public function photo(){
        $BannerModel = new BannerModel();
        $photos  = $BannerModel->where(['member_miniapp_id'=>$this->miniapp_id])->order(['orderby'=>'desc'])->select();
        $this->assign('photos',$photos);
        return $this->fetch();
    }

    public function photoupdate(){
        $orderby = empty($_POST['orderby']) ? [] : $_POST['orderby'];
        $HotelphotoModel = new BannerModel();
        $HotelIds = [];
        $data = [];
        foreach($orderby as $k=>$v){
            $data[] = ['banner_id'=>$k,'orderby'=>$v];
            $HotelIds[$k] = $k;
        }
        $hotel  = $HotelphotoModel->itemsByIds($HotelIds);
        foreach ($hotel as $val){
            if($val->member_miniapp_id != $this->miniapp_id){
                $this->error('有不存在的图片',null,101);
                break;
            }
        }
        $HotelphotoModel->saveAll($data);
        $this->success('操作成功！',null);
    }

    public function photodelete(){
        $banner_id = (int)$this->request->param('banner_id');
        if(empty($banner_id)){
            $this->error('参数错误',null,101);
        }
        //echo $photo_id;
        $BannerModel = new BannerModel();
        // var_dump($GoodsphotoModel->get($photo_id));
        if(!$photo = $BannerModel->get($banner_id)){
            $this->error('参数错误',null,101);
        }
        if($photo->member_miniapp_id != $this->miniapp_id){
            $this->error('参数错误',null,101);
        }
        $BannerModel->where(['banner_id'=>$banner_id])->delete();
        $this->success('删除成功！',null);
    }

    public function photosave(){
        //$mdl = $this->request->param('mdl');  //后期配缩略图
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $dir = ROOT_PATH . 'attachs' . DS . 'uploads';
        $info = $file->move($dir);
        if($info){
            $img = $info->getSaveName();
            $BannerModel = new BannerModel();
            $BannerModel ->save([
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
   
}