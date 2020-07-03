<?php
namespace app\api\controller;
use app\common\model\publicuse\BannerModel;
use app\common\model\publicuse\CategoryModel;

class Publicuse{

   protected $appid = 0;

    public function __construct($appid =0){
        $this->appid = $appid;
    }

    /*
     * 获取banner
     */

    public function getBanner(&$banners){
        $banner = BannerModel::where(['member_miniapp_id' => $this->appid])->order("orderby desc")->limit(0, 20)->select();
        foreach ($banner as $val) {
            $banners[] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        $banners = $banners ? $banners : [];
        return true;
    }
    /*
     * 获取带背景图片及颜色的分类
     */
    public function getCategoryColor(&$category){
        $CategoryModel = new CategoryModel();
        $categorys = $CategoryModel->where(['member_miniapp_id' => $this->appid])->select();
        foreach ($categorys as $val) {
            $category[] = [
                'category_id' => $val->category_id,
                'ico' => IMG_URL . getImg($val->photo),
                'name' => $val->category_name,
                'color' => $val->color,
            ];
        }
        $category = $category ? $category : [];
       return true;
    }
}