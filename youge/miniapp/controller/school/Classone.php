<?php
namespace app\miniapp\controller\school;
use app\common\model\school\ContentModel;
use app\miniapp\controller\Common;
use app\common\model\school\ClassoneModel;
class Classone extends Common {
    
    public function index() {
        $where = $search = [];

        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['type'] = 1;
        $count = ClassoneModel::where($where)->count();
        $list = ClassoneModel::where($where)->order(['class_id'=>'desc'])->paginate(10, $count);
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

            $data['title'] =   $this->request->param('title');
            if(empty($data['title'])){
                $this->error('请输入标题',null,101);
            }
            $data['photo'] = (string) $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('请添加一张标题图片',null,101);
            }
            $data['price'] =   (string)   $this->request->param('price');
            $data['type'] = 1;
            $dl = empty($_POST['dl']) ? [] : $_POST['dl'];
            if (empty($dl)) {
                $this->error('文章段落内容不能为空');
            }
            $dlarr = [];
            $i = 0;
            foreach ($dl as $val) {
                $i++;
                if (empty($val['photo']) && empty($val['content'])) {
                    $this->error('第' . $i . '段落内容不能为空！');
                } else {
                    $dlarr[] = [
                        'member_miniapp_id' => $this->miniapp_id,
                        'photo' => $val['photo'],
                        'content' => $val['content'],
                        'orderby' => $i,
                    ];
                }
            }
            $ClassoneModel = new ClassoneModel();
            if ($ClassoneModel->save($data)) {
                foreach ($dlarr as $k => $val) {
                    $dlarr[$k]['class_id'] = $ClassoneModel->class_id;
                }
                $ContentModel = new ContentModel();
                $ContentModel->saveAll($dlarr);
            }
            $this->success('发布文章成功！', url('school.classone/index'));
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
        $class_id= (int)$this->request->param('class_id');
        $ClassoneModel = new ClassoneModel();
        if (!$detail = $ClassoneModel->get($class_id)) {
            $this->error('请选择要编辑的头条', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在头条");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] =   $this->request->param('title');
            if(empty($data['title'])){
                $this->error('请输入标题',null,101);
            }
            $data['photo'] = (string) $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('请添加一张标题图片',null,101);
            }
            $data['price'] =   (string)   $this->request->param('price');
            $dl = empty($_POST['dl']) ? [] : $_POST['dl'];
            if (empty($dl)) {
                $this->error('文章段落内容不能为空');
            }
            $dlarr = [];
            $i = 0;
            foreach ($dl as $val) {
                $i++;
                if (empty($val['photo']) && empty($val['content'])) {
                    $this->error('第' . $i . '段落内容不能为空！');
                } else {
                    $dlarr[] = [
                        'member_miniapp_id' => $this->miniapp_id,
                        'photo' => $val['photo'],
                        'content' => $val['content'],
                        'orderby' => $i,
                    ];
                }
            }
           $ClassoneModel->save($data, ['class_id' => $class_id]);
            $ContentModel = new ContentModel();
            $ContentModel->where(['class_id' => $class_id])->delete();// 先删除内容
            foreach ($dlarr as $k => $val) {
                $dlarr[$k]['class_id'] = $class_id;
            }
            $ContentModel->saveAll($dlarr);
            $this->success('编辑文章成功！');
        } else {

            $ContentModel = new ContentModel();
            $this->assign('toutiao',$detail);
            $this->assign('contents',$ContentModel->where(['class_id'=>$class_id])->order(['orderby'=>'asc'])->select());
            return $this->fetch();
        }
    }
    
    public function delete() {
   
        $class_id = (int)$this->request->param('class_id');
         $ClassoneModel = new ClassoneModel();
       
        if(!$detail = $ClassoneModel->find($class_id)){
            $this->error("不存在该课程管理",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该课程管理', null, 101);
        }
        $ClassoneModel->where(['class_id'=>$class_id])->delete();
        $this->success('操作成功');
    }
   
}