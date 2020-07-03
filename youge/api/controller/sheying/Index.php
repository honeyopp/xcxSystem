<?php
namespace app\api\controller\sheying;
use app\api\controller\Common;
use app\common\model\sheying\BannerModel;
use app\common\model\sheying\CategoryModel;
use app\common\model\sheying\PhotoModel;
use app\common\model\sheying\SheyingModel;
use app\common\model\sheying\WorksModel;

class Index extends Common{

    /*
     * 获取首页
     */
    public function getIndex(){
        //获取banner；
        $BannerModel = new BannerModel();
        $banner = $BannerModel->where(['member_miniapp_id'=>$this->appid])->limit(0,10)->select();
        $data['banner'] = [];
        foreach ($banner as $val){
            $data['banner'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        //获取客片 6个 排序最高
        $WorksModel = new WorksModel();
        $works = $WorksModel->where(['member_miniapp_id'=>$this->appid])->order("orderby desc")->limit(0,6)->select();
        $data['works'] = [];
        foreach ($works as $val){
            $data['works'][] = [
                'works_id' => $val->works_id,
                'title' => $val->title,
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        //获取分类下图片；
        $cats = $WorksModel->where(['member_miniapp_id'=>$this->appid])
                            ->group('category_id')->select();

        $categoryIds = [];
        foreach ($cats as $val){
            $categoryIds[$val->category_id] = $val->category_id;
        }
        $CategoryModel = new CategoryModel();
        $cateforys = $CategoryModel->itemsByIds($categoryIds);
        $data['cates'] = [];
        foreach ($cats as $val){
            $data['cates'][] = [
                 'works_id' => $val->works_id,
                'photo' => IMG_URL . getImg($val->photo),
                'title' => empty($cateforys[$val->category_id]) ? '' : $cateforys[$val->category_id]->name,
            ];
        }

        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 关于我们
     */
    public function about(){
        $SheyingModel = new SheyingModel();
        $detail = $SheyingModel->find($this->appid);
        if(empty($detail)){
            $this->result('',200,'数据初始化成功','json');
        }
        $data = [
            'lat' => $detail->lat,
            'lng' => $detail->lng,
            'address' => $detail->address,
            'introduce' => $detail->introduce,
            'mobile' => $detail->mobile,
            'trade' => $detail->trade,
        ];
        $BannerModel = new BannerModel();
        $banner = $BannerModel->where(['member_miniapp_id'=>$this->appid])->limit(0,10)->select();
        $data['banner'] = [];
        foreach ($banner as $val){
            $data['banner'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 客片
     */
    public function getWorks(){
        $where['member_miniapp_id'] = $this->appid;
        $WorksModel = new WorksModel();
        $list = $WorksModel->where($where)->order("orderby desc")->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'works_id' => $val->works_id,
                'title' => $val->title,
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 客片详情
     *
     */
    public function worksDetail(){
        $works_id = (int) $this->request->param('works_id');
        $WorksModel = new WorksModel();
        if(!$detail = $WorksModel->find($works_id)){
            $this->result('',400,'参数错误','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        $PhotoModel = new PhotoModel();
        $photos = $PhotoModel->where(['member_miniapp_id'=>$this->appid,'works_id'=>$works_id])->limit(0,50)->select();
        $data['list'] = [];
        foreach ($photos as $val){
            $data['list'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    public function getCate(){
        $CategoryModel = new CategoryModel();
        $list = $CategoryModel->where(['member_miniapp_id'=>$this->appid])->limit(0,20)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'category_id' => $val->category_id,
                'name' => $val->name,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

}