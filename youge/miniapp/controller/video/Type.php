<?php
namespace app\miniapp\controller\video;
use app\common\model\video\TagModel;
use app\common\model\video\VideojoinModel;
use app\common\model\video\VideoModel;
use app\miniapp\controller\Common;
use app\common\model\video\TypeModel;
class Type extends Common {
    
    public function index() {
        $where = $search = [];
        $search['type_name'] = $this->request->param('type_name');
        if (!empty($search['type_name'])) {
            $where['type_name'] = array('LIKE', '%' . $search['type_name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = TypeModel::where($where)->count();
        $list = TypeModel::where($where)->order(['type_id'=>'desc'])->paginate(10, $count);
        $typeIds = [];
        foreach ($list as $val){
            $typeIds[$val->type_id] = $val->type_id;
        }
        $TagModel = new TagModel();
        $typeIds = empty($typeIds) ? 0 : $typeIds;
        $t_where['type_id'] = ["IN",$typeIds];
        $_tages = $TagModel->field('GROUP_CONCAT(tag_name) as names,type_id')->where($t_where)->group('type_id')->select();
        $tags = [];
        foreach ($_tages as $val){
            $tags[$val->type_id] = $val;
        }
        $this->assign('tags',$tags);
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
            $data['type_name'] = $this->request->param('type_name');
            if(empty($data['type_name'])){
                $this->error('分类名称不能为空',null,101);
            }
            $data['photo'] = $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('图片不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
            if(empty($data['orderby'])){
                $this->error('排序不能为空',null,101);
            }
            $tages = $this->request->param('tages');
            if(empty($tages)){
                $this->error('标签不能为空',null,101);
            }
            $TypeModel = new TypeModel();
            $TypeModel->save($data);

            $array = explode(',',$tages);
            if(empty($array)){
                $this->error('标签格式不正确',null,101);
            }
            $tags_array = [];
            foreach ($array as $val){
                $tags_array[] = [
                    'member_miniapp_id'  => $this->miniapp_id,
                    'tag_name'  => $val,
                    'type_id'  => $TypeModel->type_id,
                ];
            }
            $TagModel = new TagModel();
            $TagModel->saveAll($tags_array);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    public function edit(){
         $type_id = (int)$this->request->param('type_id');
         $TypeModel = new TypeModel();
        $TagModel = new TagModel();
         if(!$detail = $TypeModel->get($type_id)){
             $this->error('请选择要编辑的分类设置',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在分类设置");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
             $data['member_miniapp_id'] = $this->miniapp_id;
             $data['type_name'] = $this->request->param('type_name');
             if(empty($data['type_name'])){
                 $this->error('分类名称不能为空',null,101);
             }
             $data['photo'] = $this->request->param('photo');
             if(empty($data['photo'])){
                 $this->error('图片不能为空',null,101);
             }
             $data['orderby'] = (int) $this->request->param('orderby');
             if(empty($data['orderby'])){
                 $this->error('排序不能为空',null,101);
             }
             $tages = $this->request->param('tages');
             if(empty($tages)){
                 $this->error('标签不能为空',null,101);
             }
             $TypeModel = new TypeModel();
             $TypeModel->save($data,['type_id'=>$type_id]);

             $array = explode(',',$tages);
             if(empty($array)){
                 $this->error('标签格式不正确',null,101);
             }
             $tags_array = [];
             foreach ($array as $val){
                 $tags_array[] = [
                     'member_miniapp_id'  => $this->miniapp_id,
                     'tag_name'  => $val,
                     'type_id'  => $type_id,
                 ];
             }
             $TagModel->where(['type_id'=>$type_id])->delete();
             $TagModel->saveAll($tags_array);
             $this->success('操作成功',null,101);
         }else{
             $t_where['type_id'] = $type_id;
             $_tages = $TagModel->field('GROUP_CONCAT(tag_name) as names,type_id')->where($t_where)->group('type_id')->select();
             $tags = [];
             foreach ($_tages as $val){
                 $tags[$val->type_id] = $val;
             }
             $this->assign('tags',$tags);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }


    public function ajaxtag(){
        $type_id = (int) $this->request->param('type_id');
        $TypeModel = new TypeModel();
        if(!$type = $TypeModel->find($type_id)){
            $this->result([],400,'不存在分类','json');
        }
        if($type->member_miniapp_id != $this->miniapp_id){
            $this->result([],400,'不存在分类','json');
        }
        $video_id = (int) $this->request->param('video_id');
        $VideoModel = new VideoModel();
        $video = $VideoModel->find($video_id);

        $select = [];
        if($video && $video->member_miniapp_id == $this->miniapp_id){
            $VideojoinModel = new VideojoinModel();
            $join = $VideojoinModel->where(['video_id'=>$video_id])->select();
            foreach ($join as  $val){
                    $select[$val->tag_id] = $val->tag_id;
            }
        }
        $TagModel = new TagModel();
        $where['type_id'] = $type_id;
        $tages = $TagModel->where($where)->select();
        $data = [];
        foreach ($tages as $val){
            $data[] = [
                'tag_id'  => $val->tag_id,
                'is_select' => empty($select[$val->tag_id]) ? '' : 'checked',
                'tag_name'  => $val->tag_name,
                'type_id'   => $val->type_id,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    public function delete(){
        $type_id = (int) $this->request->param('type_id');
        $TypeModel = new TypeModel();
        if(!$type = $TypeModel->find($type_id)){
            $this->error('不存在分类',null,101);
        }
        if($type->member_miniapp_id != $this->miniapp_id){
            $this->error('不存在分类',null,101);
        }
        $TypeModel->where(['type_id'=>$type_id])->delete();
        $this->success('操作成功');
    }
   
}