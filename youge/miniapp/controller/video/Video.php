<?php
namespace app\miniapp\controller\video;
use app\common\model\video\TypeModel;
use app\common\model\video\VideojoinModel;
use app\miniapp\controller\Common;
use app\common\model\video\VideoModel;
class Video extends Common {
    
    public function index() {
        $where = $search = [];
        $search['share_num'] = (int)$this->request->param('share_num');
        if (!empty($search['share_num'])) {
            $where['share_num'] = $search['share_num'];
        }
                $search['views'] = (int)$this->request->param('views');
        if (!empty($search['views'])) {
            $where['views'] = $search['views'];
        }
                $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = VideoModel::where($where)->count();
        $list = VideoModel::where($where)->order(['video_id'=>'desc'])->paginate(10, $count);
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
                $this->error('封面不能为空',null,101);
            }
            $data['link'] = $this->request->param('link');
            if(empty($data['link'])){
                $this->error('连接地址不能为空',null,101);
            }
            $data['type_id'] = (int) $this->request->param('type_id');
            if(empty($data['type_id'])){
                $this->error('分类不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $VideoModel = new VideoModel();
            $VideoModel->save($data);
               $tagIds  = $_POST['tag_ids'];
               $data2 = [];
               foreach ($tagIds as $val){
                     $data2[] = [
                         'tag_id' => $val,
                         'video_id' => $VideoModel->video_id,
                     ];
               }
              $VideojoinModel = new VideojoinModel();
              $VideojoinModel->saveAll($data2);
            $this->success('操作成功',null);
        } else {
            $TypeModel = new TypeModel();
            $types = $TypeModel->where(['member_miniapp_id'=>$this->miniapp_id])->order("orderby desc")->limit(0,20)->select();
            $this->assign('types',$types);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $video_id = (int)$this->request->param('video_id');
         $VideoModel = new VideoModel();
         if(!$detail = $VideoModel->get($video_id)){
             $this->error('请选择要编辑的视频管理',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在视频管理");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['share_num'] = (int) $this->request->param('share_num');
            $data['type_id'] = (int) $this->request->param('type_id');
            if(empty($data['type_id'])){
                $this->error('分类不能为空',null,101);
            }
            $data['title'] = $this->request->param('title');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');  
            if(empty($data['photo'])){
                $this->error('封面不能为空',null,101);
            }
            $data['link'] = $this->request->param('link');  
            if(empty($data['link'])){
                $this->error('连接地址不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
             $tagIds  = $_POST['tag_ids'];
             $data2 = [];
             foreach ($tagIds as $val){
                 $data2[] = [
                     'tag_id' => $val,
                     'video_id' => $video_id,
                 ];
             }
             $VideojoinModel = new VideojoinModel();
             $VideojoinModel->where(['video_id'=>$video_id])->delete();
             $VideojoinModel->saveAll($data2);
              $VideoModel->save($data,['video_id'=>$video_id]);
            $this->success('操作成功',null);
         }else{
             $TypeModel = new TypeModel();
             $types = $TypeModel->where(['member_miniapp_id'=>$this->miniapp_id])->order("orderby desc")->limit(0,20)->select();
             $this->assign('types',$types);
             $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $video_id = (int)$this->request->param('video_id');
         $VideoModel = new VideoModel();
       
        if(!$detail = $VideoModel->find($video_id)){
            $this->error("不存在该视频管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该视频管理', null, 101);
        }
        if($detail->is_delete == 1){
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $VideoModel->save($data,['video_id'=>$video_id]);
        $this->success('操作成功');
    }
   
}