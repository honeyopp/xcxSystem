<?php
namespace app\home\controller;
use app\common\model\miniapp\DescribeModel;
use app\common\model\miniapp\MiniappModel;
use app\common\model\miniapp\PhotoModel;

class  Shop extends Common {
    protected $footer = 2;
    /*
     *应用大厅
     * */
    public function index(){

        $where = ['is_online'=>1];
        $list = MiniappModel::where($where)->order(['orderby'=>'desc'])->select();
        $this->assign('list', $list);
        return  $this->fetch();
    }
    /*
     *模板吗详情；
     */
    public function detail(){
        $miniapp_id = (int) $this->request->param('miniapp_id');
        $MiniModel = new MiniappModel();
        if(!$detail = $MiniModel->find($miniapp_id)){
            $this->error("请选择模板");
        }
        $PhotoModel = new PhotoModel();
        $photos = $PhotoModel->where(['miniapp_id'=>$miniapp_id])->order("orderby desc")->select();
        $DescribeModel = new DescribeModel();
        $describes = $DescribeModel->where(['miniapp_id'=>$miniapp_id])->order("orderby desc")->select();
        $describes_text = [];
        $this->assign('describes',$describes);

        $this->assign('photos',$photos);
        $this->assign('detail',$detail);
        $this->assign('seo_title',$detail->title);
        return $this->fetch();
    }
}