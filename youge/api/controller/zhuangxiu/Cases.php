<?php
namespace app\api\controller\zhuangxiu;
use app\api\controller\Common;
use app\common\model\zhuangxiu\CasecatModel;
use app\common\model\zhuangxiu\CasephotoModel;
use app\common\model\zhuangxiu\CasesModel;
use app\common\model\zhuangxiu\ColorModel;
use app\common\model\zhuangxiu\SpaceModel;

class  Cases extends Common{
    //获取分类；
    public function getCats(){
            $where['member_miniapp_id'] = $this->appid;
        $CasecatModel = new CasecatModel();
        $cats = $CasecatModel->where($where)->limit(0,20)->select();
        $spaces = SpaceModel::where($where)->limit(0,20)->select();
        $colors = ColorModel::where($where)->limit(0,20)->select();
        $data['cats'] = $data['spaces'] =  $data['colors'] =  [];
        foreach ($cats as $val){
            $data['cats'][] = [
                'cat_id' => $val->cat_id,
                'cat_name' => $val->cat_name,
             ];
        }

        foreach ($spaces as $val){
            $data['spaces'][] = [
                'space_id' => $val->space_id,
                'space_name' => $val->space_name,
            ];
        }
        foreach ($colors as $val){
            $data['colors'][] = [
                'color_id' => $val->color_id,
                'color_name' => $val->color_name,
            ];
        }
        $this->result($data,200,'数据初始化成功','json');
    }

    //获取列表；默认全部；
    public function  getList(){
        $cat_id = (int) $this->request->param('cat_id');
        if(!empty($cat_id)){
            $where['cat_id'] = $cat_id;
        }
        $space_id = (int) $this->request->param('space_id');
        if(!empty($space_id)){
            $where['space_id'] = $space_id;
        }
        $color_id = (int) $this->request->param('color_id');
        if(!empty($color_id)){
            $where['color_id'] = $color_id;
        }
        $where['member_miniapp_id'] = $this->appid;
        $CasesModel = new CasesModel();
        $data['num'] = $CasesModel->where($where)->count();
        $list = $CasesModel->where($where)->order("orderby desc")->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                'case_id' => $val->case_id,
                'photo'   => IMG_URL . getImg($val->photo),
                'title'   => $val->title,
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data,'200','数据初始化成功','json');
    }

    //获取相册
    public function photos(){
        $case_id = (int) $this->request->param('case_id');
        $CasesModel = new CasesModel();
        if(!$case = $CasesModel->find($case_id)){
            $this->result([],'400','不存在效果图','json');
        }
        if($case->member_miniapp_id != $this->appid){
            $this->result([],'400','不存在效果图','json');
        }
        $where['case_id'] = $case_id;
        $CasephotoModel= new CasephotoModel();
        $photos = $CasephotoModel->where($where)->order("orderby desc")->limit(0,50)->select();
        $data = [];
        foreach ($photos as $val){
            $data[] = IMG_URL . getImg($val->photo);
        }
        $this->result($data,'200','数据初始化成功','json');
    }

}