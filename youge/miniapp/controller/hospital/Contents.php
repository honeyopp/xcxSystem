<?php

namespace app\miniapp\controller\hospital;

use app\miniapp\controller\Common;
use app\common\model\hospital\ContentsModel;

class Contents extends Common
{

    public function index()
    {
        $where = $search = [];

        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ContentsModel::where($where)->count();
        $list = ContentsModel::where($where)->order(['contents_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }

    public function create()
    {
        $ContentsModel = new ContentsModel();
        $contents =  $ContentsModel->where(['member_miniapp_id'=>$this->miniapp_id])->order(['orderby' => 'asc'])->select();

        if ($this->request->method() == 'POST') {
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
            $ContentsModel->where(['member_miniapp_id'=>$this->miniapp_id])->delete();
            $ContentsModel->saveAll($dlarr);
            $this->success('操作成功', null);
        } else {
            $this->assign('contents', $contents);
            return $this->fetch();
        }
}

public
function edit()
{
    $contents_id = (int)$this->request->param('contents_id');
    $ContentsModel = new ContentsModel();
    if (!$detail = $ContentsModel->get($contents_id)) {
        $this->error('请选择要编辑的医院简介', null, 101);
    }
    if ($detail->member_miniapp_id != $this->miniapp_id) {
        $this->error("不存在医院简介");
    }
    if ($this->request->method() == 'POST') {
        $data = [];
        $data['member_miniapp_id'] = $this->miniapp_id;
        $data['member_miniapp_id'] = (int)$this->request->param('member_miniapp_id');
        if (empty($data['member_miniapp_id'])) {
            $this->error('不能为空', null, 101);
        }
        $data['photo'] = (int)$this->request->param('photo');
        if (empty($data['photo'])) {
            $this->error('不能为空', null, 101);
        }
        $data['content'] = (int)$this->request->param('content');
        if (empty($data['content'])) {
            $this->error('不能为空', null, 101);
        }
        $data['orderby'] = (int)$this->request->param('orderby');
        if (empty($data['orderby'])) {
            $this->error('不能为空', null, 101);
        }
        $data['add_time'] = (int)$this->request->param('add_time');
        if (empty($data['add_time'])) {
            $this->error('不能为空', null, 101);
        }
        $data['add_ip'] = (int)$this->request->param('add_ip');
        if (empty($data['add_ip'])) {
            $this->error('不能为空', null, 101);
        }


        $ContentsModel = new ContentsModel();
        $ContentsModel->save($data, ['contents_id' => $contents_id]);
        $this->success('操作成功', null);
    } else {
        $this->assign('detail', $detail);
        return $this->fetch();
    }
}

public
function delete()
{

    $contents_id = (int)$this->request->param('contents_id');
    $ContentsModel = new ContentsModel();

    if (!$detail = $ContentsModel->find($contents_id)) {
        $this->error("不存在该医院简介", null, 101);
    }
    if ($detail->member_miniapp_id != $this->miniapp_id) {
        $this->error('不存在该医院简介', null, 101);
    }
    $ContentsModel->where(['contents_id' => $contents_id])->delete();
    $this->success('操作成功');
}

}