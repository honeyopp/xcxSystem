<?php
namespace app\home\controller;
use app\common\model\news\NewsModel;

class News extends Common{
    protected $footer = 3;
    public function index(){
        $NewsModel = new NewsModel();
        $list = $NewsModel->field("news_id,title,add_time")->order("news_id desc")->paginate(10);
        $page = $list->render();
        $this->assign('page',$page);
        $this->assign('list', $list);
        return $this->fetch();
    }
    public function detail(){
        $news_id = (int) $this->request->param('news_id');
        $NewsModel = new NewsModel();
        if(!$news = $NewsModel->find($news_id)){
            $this->error('不存在该新闻',null,101);
        }
        $this->assign('news',$news);
        return $this->fetch();
    }
}