<?php

namespace app\miniapp\controller\tongcheng;

use app\miniapp\controller\Common;
use app\common\model\toutiao\ToutiaoModel;
use app\common\model\toutiao\NavModel;
use app\common\model\toutiao\ContentModel;

class Toutiao extends Common {
    private $nav = [];
    public function _initialize() {
        parent::_initialize();
        $NavModel = new NavModel();
        $nav = $NavModel->where(['member_miniapp_id' => $this->miniapp_id])->order(['orderby' => 'desc'])->select();

        foreach ($nav as $val) {
            $this->nav[$val['nav_id']] = $val;
        }
        $this->assign('nav', $this->nav);
    }
    public function index() {
        $where = $search = [];
        $search['type'] = (int) $this->request->param('type');
        if (!empty($search['type'])) {
            $where['type'] = $search['type'];
        }
        $search['nav_id'] = (int) $this->request->param('nav_id');
        if (!empty($search['nav_id'])) {
            $where['nav_id'] = $search['nav_id'];
        }
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ToutiaoModel::where($where)->count();
        $list = ToutiaoModel::where($where)->order(['toutiao_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function create2() {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['type'] = 1; //文章
            $data['title'] = $this->request->param('title');
            if (empty($data['title'])) {
                $this->error('头条标题不能为空', null, 101);
            }
            $data['author'] = $this->request->param('author');
            $data['nav_id'] = (int) $this->request->param('nav_id');
            if (empty($data['nav_id'])) {
                $this->error('分类ID不能为空', null, 101);
            }
            $data['photo1'] = $this->request->param('photo1');
            if(empty($data['photo1'])){
                $this->error('请上传标题图片',null,101);
            }
            $dl = empty($_POST['dl']) ? [] : $_POST['dl'];
            if (empty($dl)) {
                $this->error('文章段落内容不能为空');
            }
            $dlarr = [];
            $i = 0;
            foreach ($dl as $val) {
                $i ++;
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
            $ToutiaoModel = new ToutiaoModel();
            if ($ToutiaoModel->save($data)) {
                foreach ($dlarr as $k => $val) {
                    $dlarr[$k]['toutiao_id'] = $ToutiaoModel->toutiao_id;
                }
                $ContentModel = new ContentModel();
                $ContentModel->saveAll($dlarr);
            }
            $this->success('发布文章成功！', url('toutiao.toutiao/index'));
        } else {
            return $this->fetch();
        }
    }

    public function edit2(){
        $toutiao_id = (int) $this->request->param('toutiao_id');
        $ToutiaoModel = new ToutiaoModel();
        if (!$detail = $ToutiaoModel->get($toutiao_id)) {
            $this->error('请选择要编辑的头条', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在头条");
        }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['type'] = 1; //文章
            $data['title'] = $this->request->param('title');
            if (empty($data['title'])) {
                $this->error('头条标题不能为空', null, 101);
            }
            $data['author'] = $this->request->param('author');
            $data['nav_id'] = (int) $this->request->param('nav_id');
            if (empty($data['nav_id'])) {
                $this->error('分类ID不能为空', null, 101);
            }
            $data['photo1'] = $this->request->param('photo1');
             $data['photo1'] = $this->request->param('photo1');
             if(empty($data['photo1'])){
                 $this->error('请上传标题图片',null,101);
             }
            $dl = empty($_POST['dl']) ? [] : $_POST['dl'];
            if (empty($dl)) {
                $this->error('文章段落内容不能为空');
            }
            $dlarr = [];
            $i = 0;
            foreach ($dl as $val) {
                $i ++;
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
            $ToutiaoModel = new ToutiaoModel();
            if ($ToutiaoModel->save($data,['toutiao_id'=>$toutiao_id])) {
                $ContentModel = new ContentModel();
                $ContentModel->where(['toutiao_id'=>$toutiao_id])->delete();// 先删除内容
                foreach ($dlarr as $k => $val) {
                    $dlarr[$k]['toutiao_id'] = $toutiao_id;
                }
                $ContentModel = new ContentModel();
                $ContentModel->saveAll($dlarr);
            }
            $this->success('编辑文章成功！');
        } else {
            $ContentModel = new ContentModel();
            $this->assign('toutiao',$detail);
            $this->assign('contents',$ContentModel->where(['toutiao_id'=>$toutiao_id])->order(['orderby'=>'asc'])->select());
            return $this->fetch();
        }
    }

    public function delete(){
        $toutiao_id = (int) $this->request->param('toutiao_id');
        $ToutiaoModel = new ToutiaoModel();
        if (!$detail = $ToutiaoModel->get($toutiao_id)) {
            $this->error('请选择要编辑的头条', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在头条");
        }
        
        $ToutiaoModel->where(['toutiao_id'=>$toutiao_id])->delete();
        $ContentModel = new ContentModel();
        $ContentModel->where(['toutiao_id'=>$toutiao_id])->delete();
        $this->success('操作成功！');
    }


}
