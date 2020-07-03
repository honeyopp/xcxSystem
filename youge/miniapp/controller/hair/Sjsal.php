<?php

namespace app\miniapp\controller\hair;

use app\common\model\hair\DesignerModel;
use app\common\model\hair\SjsalphotoModel;
use app\miniapp\controller\Common;
use app\common\model\hair\SjsalModel;

class Sjsal extends Common
{

    public function index()
    {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $designer_id =   (int) $this->request->param('designer_id');
        $where['designer_id'] =$designer_id;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = SjsalModel::where($where)->count();
        $list = SjsalModel::where($where)->order(['works_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('designer_id',$designer_id);
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }

    public function create()
    {
        $designer_id = (int) $this->request->param('designer_id');
        $DesignerModel = new DesignerModel();
        if(!$detail = $DesignerModel->find($designer_id)){
          $this->error('不存在设计师',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
          $this->error('不存在设计师',null,101);
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');
            $data['designer_id'] = $designer_id;
            if (empty($data['title'])) {
                $this->error('标题不能为空', null, 101);
            }
            if (empty($_POST['imgs'])) {
                $this->error('请上传图片', null, 101);
            }
            $imgs = $_POST['imgs'];
            $data['photo'] = $imgs[0];
            $data['num'] = count($imgs);
            $SjsalModel = new SjsalModel();
            $SjsalModel->save($data);
            $data2 = [];
            $works_id = $SjsalModel->works_id;
            foreach ($imgs as $val) {
                $data2[] = [
                    'member_miniapp_id' => $this->miniapp_id,
                    'works_id' => $works_id,
                    'photo' => $val,
                ];
            }
            $PhotoModel = new SjsalphotoModel();
            $PhotoModel->saveAll($data2);
            $this->success('操作成功', null);
        } else {
            $this->assign('designer',$detail);
            return $this->fetch();
        }
    }

    public function edit()
    {
        $works_id = (int)$this->request->param('works_id');
        $SjsalModel = new SjsalModel();
        if (!$detail = $SjsalModel->get($works_id)) {
            $this->error('请选择要编辑的设计师作品', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在设计师作品");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');
            if (empty($data['title'])) {
                $this->error('标题不能为空', null, 101);
            }
            $imgs = $_POST['imgs'] ? $_POST['imgs'] : [];
            if(empty($imgs)){
                $this->error('请上传图片',null,101);
            }
            $data['photo'] = $imgs[0];
            $data['num'] = count($imgs);
            $PhotoModel = new SjsalphotoModel();
            $PhotoModel->where(['works_id'=>$works_id])->delete();
            $data2 = [];
            foreach ($imgs as $val){
                $data2[] = [
                    'member_miniapp_id' => $this->miniapp_id,
                    'works_id' => $works_id,
                    'photo' => $val,
                ];
            }
            $PhotoModel->saveAll($data2);
            $SjsalModel = new SjsalModel();
            $SjsalModel->save($data, ['works_id' => $works_id]);
            $this->success('操作成功', null);
        } else {
            $PhotoModel = new SjsalphotoModel();
            $where['member_miniapp_id'] = $this->miniapp_id;
            $where['works_id'] = $works_id;
            $photo = $PhotoModel->where($where)->limit(0,50)->select();
            $this->assign('photo',$photo);
            $this->assign('detail', $detail);
            return $this->fetch();
        }
    }

    public function delete()
    {

        $works_id = (int)$this->request->param('works_id');
        $SjsalModel = new SjsalModel();

        if (!$detail = $SjsalModel->find($works_id)) {
            $this->error("不存在该设计师作品", null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该设计师作品', null, 101);
        }
        $SjsalModel->where(['works_id' => $works_id])->delete();
        $this->success('操作成功');
    }

}