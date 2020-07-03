<?php
namespace app\miniapp\controller\school;
use app\common\model\school\ActivitycontentModel;
use app\miniapp\controller\Common;
use app\common\model\school\ActivityModel;
class Activity extends Common {
    
    public function index() {
        $where = $search = [];
        $search['tite'] = $this->request->param('tite');
        if (!empty($search['tite'])) {
            $where['tite'] = array('LIKE', '%' . $search['tite'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ActivityModel::where($where)->count();
        $list = ActivityModel::where($where)->order(['activity_id'=>'desc'])->paginate(10, $count);
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
                $this->error('活动标题不能为空',null,101);
            }
            $data['date'] = $this->request->param('date');
            if(empty($data['date'])){
                $this->error('活动时间不能为空',null,101);
            }

            $data['photo'] = (string) $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('请添加一张活动的图片',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            $data['addr'] = $this->request->param('addr');
            if(empty($data['addr'])){
                $this->error('集合地点不能为空',null,101);
            }
            $data['num'] = (int) $this->request->param('num');
            if(empty($data['num'])){
                $this->error('最大报名人数不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
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
                 $ActivityModel = new ActivityModel();
                if ($ActivityModel->save($data)) {
                    foreach ($dlarr as $k => $val) {
                        $dlarr[$k]['activity_id'] = $ActivityModel->activity_id;
                    }
                    $ActivitycontentModel = new ActivitycontentModel();
                    $ActivitycontentModel->saveAll($dlarr);
                }
                $this->success('发布文章成功！', url('school.activity/index'));
            } else {
                return $this->fetch();
            }

    }
    
    public function edit(){
        $activity_id = (int) $this->request->param('activity_id');
        $ActivityModel = new ActivityModel();
        if (!$detail = $ActivityModel->get($activity_id)) {
            $this->error('请选择要编辑的头条', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在头条");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');
            if(empty($data['title'])){
                $this->error('活动标题不能为空',null,101);
            }
            $data['date'] = $this->request->param('date');
            if(empty($data['date'])){
                $this->error('活动时间不能为空',null,101);
            }

            $data['photo'] = (string) $this->request->param('photo');
            if(empty($data['photo'])){
                $this->error('请添加一张活动的图片',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            $data['addr'] = $this->request->param('addr');
            if(empty($data['addr'])){
                $this->error('集合地点不能为空',null,101);
            }
            $data['num'] = (int) $this->request->param('num');
            if(empty($data['num'])){
                $this->error('最大报名人数不能为空',null,101);
            }
            $data['orderby'] = (int) $this->request->param('orderby');
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
            $ActivityModel->save($data, ['activity_id' => $activity_id]);
            $ActivitycontentModel= new ActivitycontentModel();
            $ActivitycontentModel->where(['activity_id' => $activity_id])->delete();// 先删除内容
            foreach ($dlarr as $k => $val) {
                $dlarr[$k]['activity_id'] = $activity_id;
            }
            $ActivitycontentModel->saveAll($dlarr);
            $this->success('编辑文章成功！');
        } else {
            $ActivitycontentModel = new ActivitycontentModel();
            $this->assign('toutiao',$detail);
            $this->assign('contents',$ActivitycontentModel->where(['activity_id'=>$activity_id])->order(['orderby'=>'asc'])->select());
            return $this->fetch();
        }
    }
    
    public function delete() {
   
        $activity_id = (int)$this->request->param('activity_id');
         $ActivityModel = new ActivityModel();
       
        if(!$detail = $ActivityModel->find($activity_id)){
            $this->error("不存在该活动报名",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该活动报名', null, 101);
        }
        $ActivityModel->where(['activity_id'=>$activity_id])->delete();
        $this->success('操作成功');
    }
   
}