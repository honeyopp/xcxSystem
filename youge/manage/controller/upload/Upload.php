<?php

namespace app\manage\controller\upload;
use app\manage\controller\Common;
use app\common\model\setting\SettingModel;
use think\Image;
class Upload extends Common{
    
    public function index(){
        $type = $this->request->param('type');
        $mdl = $this->request->param('mdl');
        $setting =  config('setting.attachs');

        $file = request()->file('file');
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
            if($type == 'ajaxupload'){
                echo json_encode(['img'=>$img]);
            }else{
                echo $img;
            }
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }
    
    
}
