<?php

namespace app\miniapp\controller\nongjialegw;

use app\common\model\nongjiale\NewscontentModel;
use app\common\model\nongjiale\NewsModel;
use app\common\model\nongjiale\StoreModel;
use app\miniapp\controller\Common;


class News extends Common {

    public function index() {
        $where = $search = [];
        $search['type'] = (int) $this->request->param('type');
        if (!empty($search['type'])) {
            $where['type'] = $search['type'];
        }
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = NewsModel::where($where)->count();
        $list = NewsModel::where($where)->order(['news_id' => 'desc'])->paginate(10, $count);
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
                $this->error('动态标题不能为空', null, 101);
            }
            $data['author'] = $this->request->param('author');
            $data['photo1'] = $this->request->param('photo1');
            $data['photo2'] = $this->request->param('photo2');
            $data['photo3'] = $this->request->param('photo3');
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
            $NewsModel = new NewsModel();
            if ($NewsModel->save($data)) {
                foreach ($dlarr as $k => $val) {
                    $dlarr[$k]['news_id'] = $NewsModel->news_id;
                }
                $NewscontentModel = new NewscontentModel();
                $NewscontentModel->saveAll($dlarr);
            }
            $this->success('发布文章成功！', url('nongjialegw.news/index'));
        } else {
            return $this->fetch();
        }
    }
    
    public function edit2(){

        $news_id = (int) $this->request->param('news_id');
        $NewsModel = new NewsModel();
        if (!$detail = $NewsModel->get($news_id)) {
            $this->error('不存在动态', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在动态");
        }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['type'] = 1; //文章
            $data['title'] = $this->request->param('title');
            if (empty($data['title'])) {
                $this->error('动态标题不能为空', null, 101);
            }
            $data['author'] = $this->request->param('author');
            $data['photo1'] = $this->request->param('photo1');
            $data['photo2'] = $this->request->param('photo2');
            $data['photo3'] = $this->request->param('photo3');
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
            $NewsModel = new NewsModel();
           $NewsModel->save($data,['news_id'=>$news_id]);
                $NewscontentModel = new NewscontentModel();
                $NewscontentModel->where(['news_id'=>$news_id])->delete();// 先删除内容
                foreach ($dlarr as $k => $val) {
                    $dlarr[$k]['news_id'] = $news_id;
                }
                $NewscontentModel = new NewscontentModel();
                $NewscontentModel->saveAll($dlarr);
                $this->success('编辑文章成功！');
         } else {
             $StoreModel= new StoreModel();
             $store = $StoreModel->get($detail->store_id);
            $NewscontentModel = new NewscontentModel();
            $this->assign('store',$store);
            $this->assign('toutiao',$detail);
            $this->assign('contents',$NewscontentModel->where(['news_id'=>$news_id])->order(['orderby'=>'asc'])->select());
            return $this->fetch();
        }
    }
    
    
    public function create() {

        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['type'] = 2; //视频
            $data['title'] = $this->request->param('title');
            if (empty($data['title'])) {
                $this->error('动态标题不能为空', null, 101);
            }
            $data['author'] =  $this->request->param('author');
            $data['photo1'] = $this->request->param('photo1');
            $data['video_url'] = $this->request->param('video_url');
            if (empty($data['video_url'])) {
                $this->error('视频链接不能为空');
            }
            $NewsModel = new NewsModel();
            $NewsModel->save($data);
            $this->success('操作成功', null);
        } else {
            return $this->fetch();
        }
    }
    
    public function delete(){
        $news_id = (int) $this->request->param('news_id');
        $NewsModel = new NewsModel();
        if (!$detail = $NewsModel->get($news_id)) {
            $this->error('请选择要编辑的动态', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在动态");
        }
        $NewsModel->where(['news_id'=>$news_id])->delete();
        $NewscontentModel = new NewscontentModel();
        $NewscontentModel->where(['news_id'=>$news_id])->delete();
        $this->success('操作成功！');
    }

    public function edit() {
        $news_id = (int) $this->request->param('news_id');
        $NewsModel = new NewsModel();
        if (!$detail = $NewsModel->get($news_id)) {
            $this->error('请选择要编辑的动态', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在动态");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['title'] = $this->request->param('title');
            if (empty($data['title'])) {
                $this->error('动态标题不能为空', null, 101);
            }
            $data['author'] =  $this->request->param('author');
            $data['photo1'] = $this->request->param('photo1');
            $data['video_url'] = $this->request->param('video_url');

            if (empty($data['video_url'])) {
                $this->error('视频链接不能为空');
            }
            $NewsModel = new NewsModel();
            $NewsModel->save($data, ['news_id' => $news_id]);
            $this->success('操作成功', null);
        } else {
            $this->assign('detail', $detail);
            return $this->fetch();
        }
    }

}
