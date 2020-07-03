<?php
namespace app\admin\controller\miniapp;
use app\admin\controller\Common;
use app\common\model\miniapp\MiniappModel;
use app\common\model\miniapp\PhotoModel;
use think\Image;

class Miniapp extends Common {
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $search['price'] = (int)$this->request->param('price');
        if (!empty($search['price'])) {
            $where['price'] = $search['price'];
        }
        $search['is_online'] = $this->request->param('is_online');
        if(!empty($search['is_online'])){
            $where['is_online'] = $search['is_online'] == 1 ? : 0;
        }
        $count = MiniappModel::where($where)->count();
        $list = MiniappModel::where($where)->order(['orderby'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    /**
     * 示例图片 设置
     */
    public function photo(){
        $miniapp_id = $this->request->param('miniapp_id');
        $MiniModel = new MiniappModel();
        if(!$detail = $MiniModel->get($miniapp_id)){
            $this->error('请选择要编辑的积分商品',null,101);
        }
        $photos  = PhotoModel::where(['miniapp_id'=>$miniapp_id])->order(['orderby'=>'desc'])->select();
        $this->assign('photos',$photos);
        $this->assign('miniapp_id',$miniapp_id);
        $this->assign('detail',$detail);

        return $this->fetch();
    }
    public function photoupdate(){
        $orderby = empty($_POST['orderby']) ? [] : $_POST['orderby'];
        $PhotoModel = new PhotoModel();
        foreach($orderby as $k=>$v){
            $PhotoModel->save(['orderby'=>$v],['photo_id'=>$k]);
        }
        $this->success('操作成功！',null);
    }
    /*
     * 选择小程序
     */
    public function select(){
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $search['price'] = (int)$this->request->param('price');
        if (!empty($search['price'])) {
            $where['price'] = $search['price'];
        }
        $search['is_online'] = $this->request->param('is_online');
        if(!empty($search['is_online'])){
            $where['is_online'] = $search['is_online'] == 1 ? : 0;
        }
        $count = MiniappModel::where($where)->count();
        $list = MiniappModel::where($where)->order(['orderby'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function photodelete(){
        $photo_id = (int)$this->request->param('photo_id');
        if(empty($photo_id)){
            $this->error('参数错误',null,101);
        }
        //echo $photo_id;
        $PhotoModel = new PhotoModel();
        // var_dump($GoodsphotoModel->get($photo_id));
        if(!$PhotoModel->get($photo_id)){
            $this->error('参数错误',null,101);
        }
        $PhotoModel->where(['photo_id'=>$photo_id])->delete();
        $this->success('删除成功！',null);
    }

    public function photosave(){
        $miniapp_id = $this->request->param('miniapp_id');
        $GoodsModel = new MiniappModel();
        if(!$detail = $GoodsModel->get($miniapp_id)){
            $this->error('请选择要编辑的积分商品',null,101);
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
            $GoodsphotoModel = new PhotoModel();
            $GoodsphotoModel ->save([
                'miniapp_id' => $miniapp_id,
                'photo'    => $img,
            ]);
            echo $img;
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }

    public function create() {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('小程序标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('展示图片不能为空',null,101);
            }
            $data['version'] = $this->request->param('version');  
            if(empty($data['version'])){
                $this->error('版本号不能为空',null,101);
            }
   
           
            $data['price'] = ((int) $this->request->param('price'))*100;
            $data['activity_price'] = ((int) $this->request->param('activity_price'))*100;
            $data['expire_day'] = (int) $this->request->param('expire_day');

            if(empty($data['expire_day']) && empty($data['expire_day'])){
                $this->error('体验天数不能为空和价格',null,101);
            }
             
            $data['template_id'] = (int) $this->request->param('template_id');
            if(empty($data['template_id'])){
                $this->error('模板不能为空',null,101);
            }
            $data['miniapp_dir'] = $this->request->param('miniapp_dir');  
            if(empty($data['miniapp_dir'])){
                $this->error('小程序目录不能为空',null,101);
            }
            $data['describe'] = $this->request->param('describe');
            if(empty($data['describe'])){
                $this->error('小程序描述不能为空',null,101);
            }
            $data['qrcode'] = (string) $this->request->param('qrcode');
            $data['is_online'] = $this->request->param('is_online');  
            $data['orderby'] = (int) $this->request->param('orderby');
            $MiniappModel = new MiniappModel();
            $MiniappModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $miniapp_id = (int)$this->request->param('miniapp_id');
         $MiniappModel = new MiniappModel();
         if(!$detail = $MiniappModel->get($miniapp_id)){
             $this->error('请选择要编辑的小程序管理',null,101);
         }
         if ($this->request->method() == 'POST') {
             $data['title'] = $this->request->param('title');
             if(empty($data['title'])){
                 $this->error('小程序标题不能为空',null,101);
             }
             $data['photo'] = $this->request->param('photo');
             if(empty($data['photo'])){
                 $this->error('展示图片不能为空',null,101);
             }
             $data['version'] = $this->request->param('version');
             if(empty($data['version'])){
                 $this->error('版本号不能为空',null,101);
             }

            $data['price'] = ((int) $this->request->param('price'))*100;
            $data['expire_day'] = (int) $this->request->param('expire_day');
             $data['activity_price'] = ((int) $this->request->param('activity_price'))*100;
          
             $data['template_id'] = (int) $this->request->param('template_id');
             if(empty($data['template_id'])){
                 $this->error('模板不能为空',null,101);
             }
             $data['describe'] = $this->request->param('describe');
             if(empty($data['describe'])){
                 $this->error('小程序描述不能为空',null,101);
             }
             $data['miniapp_dir'] = $this->request->param('miniapp_dir');
             if(empty($data['miniapp_dir'])){
                 $this->error('小程序目录不能为空',null,101);
             }
             $data['qrcode'] = (string) $this->request->param('qrcode');
             $data['is_online'] = $this->request->param('is_online');
             $data['orderby'] = (int) $this->request->param('orderby');
             $MiniappModel = new MiniappModel();
             $MiniappModel->save($data,['miniapp_id'=>$miniapp_id]);
             $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        if($this->request->method() == 'POST'){
             $miniapp_id = $_POST['miniapp_id'];
        }else{
            $miniapp_id = $this->request->param('miniapp_id');
        }
        $data = [];
        if (is_array($miniapp_id)) {
            foreach ($miniapp_id as $k => $val) {
                $miniapp_id[$k] = (int) $val;
            }
            $data = $miniapp_id;
        } else {
            $data[] = $miniapp_id;
        }
        if (!empty($data)) {
            $MiniappModel = new MiniappModel();
            $MiniappModel->where(array('miniapp_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
}