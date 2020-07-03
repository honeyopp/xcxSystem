<?php
namespace app\admin\controller\news;
use app\admin\controller\Common;
use app\common\model\news\NewsModel;
class News extends Common {
    
    public function index() {
        $where = $search = [];
        $search['title'] = $this->request->param('title');
        if (!empty($search['title'])) {
            $where['title|title2'] = array('LIKE', '%' . $search['title'] . '%');
        }
        $NewsModel = new NewsModel();
        $count = $NewsModel->where($where)->count();
        $list = $NewsModel->where($where)->order(['news_id'=>'desc'])->paginate(10, $count);
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
            $data['title'] = $this->request->param('title');  
            $data['title2'] = $this->request->param('title2');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['content'] =  $this->request->param('content','','SecurityEditorHtml');
            if(empty($data['content'])){
                $this->error('内容不能为空',null,101);
            }
            $NewsModel = new NewsModel();
            $NewsModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    public function edit(){
         $news_id = (int)$this->request->param('news_id');
         $NewsModel = new NewsModel();
         if(!$detail = $NewsModel->get($news_id)){
             $this->error('请选择要编辑的动态管理',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['title'] = $this->request->param('title');  
             $data['title2'] = $this->request->param('title2');  
            if(empty($data['title'])){
                $this->error('标题不能为空',null,101);
            }
            $data['content'] =  $this->request->param('content','','SecurityEditorHtml');
            if(empty($data['content'])){
                $this->error('内容不能为空',null,101);
            }
            $NewsModel = new NewsModel();
            $NewsModel->save($data,['news_id'=>$news_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        if($this->request->method() == 'POST'){
             $news_id = $_POST['news_id'];
        }else{
            $news_id = $this->request->param('news_id');
        }
        $data = [];
        if (is_array($news_id)) {
            foreach ($news_id as $k => $val) {
                $news_id[$k] = (int) $val;
            }
            $data = $news_id;
        } else {
            $data[] = $news_id;
        }
        if (!empty($data)) {
            $NewsModel = new NewsModel();
            $NewsModel->where(array('news_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}