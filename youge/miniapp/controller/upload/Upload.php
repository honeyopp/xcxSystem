<?php

namespace app\miniapp\controller\upload;

use app\miniapp\controller\Common;
use think\Image;

class Upload extends Common
{
    public function index()
    {
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

    //多张图片上传；
    public function uploads(){
        $files = request()->file();
        //  $files = request()->file('image');
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                echo $info->getExtension();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
       // var_dump($file);die;
    }


    //上传文件
    public function file()
    {

     /*  pem文件 返回类型
        array(5) {
        ["name"]=>  "apiclient_key.pem"
        ["type"]=> "application/octet-stream"
        ["tmp_name"]=> "D:\UPUPW_AP5.4\temp\php5C37.tmp"
        ["error"]=> int(0)
         ["size"]=> int(1704)
       }*/
        $file = $this->request->file('file');
        $fileInfo = $file->getInfo();
         //判断memi类型为 字节流；
        $error = [];
         if($fileInfo['type'] !==  'application/octet-stream'){
             $error['error'] = 1;
             $error['info'] = '请上传正确的文件';
             $error = json_encode($error);
             return $error;
         }
        if( substr($fileInfo['name'] ,-3) !== 'pem'){
            $error['error'] = 1;
            $error['info'] = '请上传正确的文件';
            $error = json_encode($error);
            return $error;
        };
        // 动到框架应用根目录/public/uploads/ 目录下 这个文件夹 外部不可访问；
        $dir = ROOT_PATH . 'attachs' . DS . 'wxapiclient';
        $info = $file->move($dir);
        if($info){
            $img = $info->getSaveName();
            $error['error'] = 0;
            $error['info'] = $img;
        }else{
            // 上传失败获取错误信息
            $error['error'] = 0;
            $error['info']  =  $file->getError();
        }
        $error = json_encode($error);
        return $error;
    }
}
