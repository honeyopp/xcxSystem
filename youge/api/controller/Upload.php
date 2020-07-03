<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/29 0029
 * Time: 17:06
 */
namespace app\api\controller;
use think\Controller;
use think\Image;
class Upload extends Controller {
    public function upload(){
        $mdl = $this->request->param('mdl');
        $setting =  config('setting.attachs');
        $file = request()->file('file');
         if(empty($file)){
             $this->result([],400,'请选择上传文件','json');
         }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $dir = ROOT_PATH . 'attachs' . DS . 'uploads';
        $info = $file->move($dir);
        if($info){
            $img = $info->getSaveName();
            if(isset($setting[$mdl])){
                foreach( $setting[$mdl] as $k=>$v){
                    if(!empty($v)){
                        $img2 = getImg($img,$k);
                        $image = Image::open($dir.'/'.$img);
                        $wh = explode('X',$v);
                        $image->thumb($wh[0],$wh[1],\think\Image::THUMB_CENTER)->save($dir.'/'.$img2);
                    }
                }
            }
            $data['img'] = getImg($img);
            $data['img_url'] = IMG_URL . getImg($img);
            $this->result($data,200,'图片长传成功','json');
        }else{
            // 上传失败获取错误信息
            $this->result([],400,$file->getError(),'json');
        }
    }
}