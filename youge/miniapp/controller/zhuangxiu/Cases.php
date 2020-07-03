<?php
namespace app\miniapp\controller\zhuangxiu;
use app\common\model\zhuangxiu\CasecatModel;
use app\common\model\zhuangxiu\CasephotoModel;
use app\common\model\zhuangxiu\ColorModel;
use app\common\model\zhuangxiu\SpaceModel;
use app\miniapp\controller\Common;
use app\common\model\zhuangxiu\CasesModel;
class Cases extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = CasesModel::where($where)->count();
        $list = CasesModel::where($where)->order(['case_id'=>'desc'])->paginate(10, $count);
       $spaceIds = $colorIds = $catIds =  [];
        foreach ($list as $val){
                $spaceIds[$val->space_id] = $val->space_id;
                $colorIds[$val->color_id] = $val->color_id;
                $catIds[$val->cat_id] = $val->cat_id;
        }
        $SpaceModel= new SpaceModel();
        $ColorModel = new ColorModel();
        $CasecatModel = new CasecatModel();
        $page = $list->render();
        $this->assign('spaces',$SpaceModel->itemsByIds($spaceIds));
        $this->assign('colors',$ColorModel->itemsByIds($colorIds));
        $this->assign('cats',$CasecatModel->itemsByIds($catIds));
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
            $data['cat_id'] = (int) $this->request->param('cat_id');
            if(empty($data['cat_id'])){
                $this->error('风格分类不能为空',null,101);
            }
            $data['space_id'] = (int) $this->request->param('space_id');
            if(empty($data['space_id'])){
                $this->error('空间分类不能为空',null,101);
            }
            $data['color_id'] = (int) $this->request->param('color_id');
            if(empty($data['color_id'])){
                $this->error('色系主题分类不能为空',null,101);
            }
            $data['title'] =  $this->request->param('title');
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] =  $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('封面不能为空',null,101);
            }
            $CasesModel = new CasesModel();
            $CasesModel->save($data);
            $this->success('操作成功',null);
        } else {
            $where['member_miniapp_id'] = $this->miniapp_id;
            $CasecatModel = new CasecatModel();
            $cats = $CasecatModel->where($where)->limit(0,20)->select();
            $spaces = SpaceModel::where($where)->limit(0,20)->select();
            $colors = ColorModel::where($where)->limit(0,20)->select();
            $this->assign('cats',$cats);
            $this->assign('spaces',$spaces);
            $this->assign('colors',$colors);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $case_id = (int)$this->request->param('case_id');
         $CasesModel = new CasesModel();
         if(!$detail = $CasesModel->get($case_id)){
             $this->error('请选择要编辑的效果图设置',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在效果图设置");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['cat_id'] = (int) $this->request->param('cat_id');
            if(empty($data['cat_id'])){
                $this->error('风格分类不能为空',null,101);
            }
            $data['space_id'] = (int) $this->request->param('space_id');
            if(empty($data['space_id'])){
                $this->error('空间分类不能为空',null,101);
            }
            $data['color_id'] = (int) $this->request->param('color_id');
            if(empty($data['color_id'])){
                $this->error('色系主题分类不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('封面不能为空',null,101);
            }

            
            $CasesModel = new CasesModel();
            $CasesModel->save($data,['case_id'=>$case_id]);
            $this->success('操作成功',null);
         }else{
             $where['member_miniapp_id'] = $this->miniapp_id;
             $CasecatModel = new CasecatModel();
             $cats = $CasecatModel->where($where)->limit(0,20)->select();
             $spaces = SpaceModel::where($where)->limit(0,20)->select();
             $colors = ColorModel::where($where)->limit(0,20)->select();
             $this->assign('cats',$cats);
             $this->assign('spaces',$spaces);
             $this->assign('colors',$colors);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    public function photo(){
        $case_id= (int) $this->request->param('case_id');
        $HotelModel = new CasesModel();
        if(!$detail = $HotelModel->find($case_id)){
            $this->error('请选择酒店',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择酒店',null,101);
        }
        $HotelphotoModel = new CasephotoModel();
        $photos  = $HotelphotoModel->where(['case_id'=>$case_id,'member_miniapp_id'=>$this->miniapp_id])->order(['orderby'=>'desc'])->select();
        $this->assign('photos',$photos);
        $this->assign('case_id',$case_id);
        $this->assign('detail',$detail);

        return $this->fetch();
    }
    public function photoupdate(){
        $orderby = empty($_POST['orderby']) ? [] : $_POST['orderby'];
        $HotelphotoModel = new CasephotoModel();
        $HotelIds = [];
        $data = [];
        foreach($orderby as $k=>$v){
            $data[] = ['photo_id'=>$k,'orderby'=>$v];
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
        $photo_id = (int)$this->request->param('photo_id');
        if(empty($photo_id)){
            $this->error('参数错误',null,101);
        }
        //echo $photo_id;
        $HotelphotoModel = new CasephotoModel();
        // var_dump($GoodsphotoModel->get($photo_id));
        if(!$photo = $HotelphotoModel->get($photo_id)){
            $this->error('参数错误',null,101);
        }
        if($photo->member_miniapp_id != $this->miniapp_id){
            $this->error('参数错误',null,101);
        }
        $HotelphotoModel->where(['photo_id'=>$photo_id])->delete();
        $this->success('删除成功！',null);
    }

    public function photosave(){
        $case_id = (int) $this->request->param('case_id');
        $HotelModel = new CasesModel();
        if(!$detail = $HotelModel->find($case_id)){
            $this->error('请选择酒店',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error('请选择酒店',null,101);
        }
        //$mdl = $this->request->param('mdl');  //后期配缩略图
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $dir = ROOT_PATH . 'attachs' . DS . 'uploads';
        $info = $file->move($dir);
        if($info){
            $img = $info->getSaveName();
            $HotelphotoModel = new CasephotoModel();
            $HotelphotoModel ->save([
                'case_id' => $case_id,
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
    public function delete() {
        $case_id = (int)$this->request->param('case_id');
        $CasesModel = new CasesModel();
        if(!$detail = $CasesModel->find($case_id)){
            $this->error("不存在该效果图设置",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该效果图设置', null, 101);
        }
        $CasesModel->where(['case_id'=>$case_id])->delete();
        $this->success('操作成功');
    }



}