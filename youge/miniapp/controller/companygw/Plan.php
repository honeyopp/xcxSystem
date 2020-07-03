<?php

namespace app\miniapp\controller\companygw;

use app\common\model\companygw\NewsModel;
use app\miniapp\controller\Common;
use app\common\model\toutiao\NavModel;
use app\common\model\companygw\ContentModel;

class Plan extends Common
{
    public function index()
    {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['type'] = 2;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = NewsModel::where($where)->count();
        $list = NewsModel::where($where)->order(['toutiao_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }

    public function create2()
    {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['type'] = 2; //文章
            $data['title'] = $this->request->param('title');
            if (empty($data['title'])) {
                $this->error('头条标题不能为空', null, 101);
            }
            $data['photo'] = $this->request->param('photo');
            if (empty($data['photo'])) {
                $this->error('标题图片不能为空', null, 101);
            }
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
                        'type' => 2,
                    ];
                }
            }
            $ToutiaoModel = new NewsModel();
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

    public function edit2()
    {
        $toutiao_id = (int)$this->request->param('toutiao_id');
        $ToutiaoModel = new NewsModel();
        if (!$detail = $ToutiaoModel->get($toutiao_id)) {
            $this->error('请选择要编辑的头条', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在头条");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['title'] = $this->request->param('title');
            if (empty($data['title'])) {
                $this->error('头条标题不能为空', null, 101);
            }
            $data['photo'] = $this->request->param('photo');
            if (empty($data['photo'])) {
                $this->error('方案标题图片不能为空', null, 101);
            }
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
                        'type' => 2,
                    ];
                }
            }
            $ToutiaoModel->save($data, ['toutiao_id' => $toutiao_id]);
            $ContentModel = new ContentModel();
            $ContentModel->where(['toutiao_id' =>$toutiao_id, 'type' => 2])->delete();// 先删除内容
            foreach ($dlarr as $k => $val) {
                $dlarr[$k]['toutiao_id'] = $toutiao_id;
            }
            $ContentModel = new ContentModel();
            $ContentModel->saveAll($dlarr);
            $this->success('编辑文章成功！');
        } else {
            $ContentModel = new ContentModel();
            $content =  $ContentModel->where(['toutiao_id'=>$toutiao_id,'type'=>2])->order(['orderby'=>'asc'])->select();
            $this->assign('toutiao',$detail);
            $this->assign('contents',$content);
            return $this->fetch();
        }
}

    public function delete(){
    $toutiao_id = (int)$this->request->param('toutiao_id');
    $ToutiaoModel = new NewsModel();
    if (!$detail = $ToutiaoModel->get($toutiao_id)) {
        $this->error('请选择要编辑的头条', null, 101);
    }
    if ($detail->member_miniapp_id != $this->miniapp_id) {
        $this->error("不存在头条");
    }
    $ToutiaoModel->where(['toutiao_id' => $toutiao_id])->delete();
    $ContentModel = new ContentModel();
    $ContentModel->where(['toutiao_id' => $toutiao_id])->delete();
    $this->success('操作成功！');
}
}
