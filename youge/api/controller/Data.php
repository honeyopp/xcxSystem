<?php
namespace app\api\controller;
use app\common\model\miniapp\MiniappsettingModel;
use app\common\model\setting\SettingModel;
use app\common\model\setting\SkinModel;

class Data extends Common{

    /*
     * 获取小程序配置信息
     **/
    public  function  getSetting(){
        $where['member_miniapp_id'] = $this->appid;
        $MiniappsettingModel = new MiniappsettingModel();
        $setting = $MiniappsettingModel->where($where)->find();
        $data = [
           'service_tel' => empty($setting->service_tel) ? '' : $setting->service_tel,
           'complaint_tel' => empty($setting->service_tel) ? '' : $setting->complaint_tel,
           'about' => empty($setting->service_tel) ? '' : $setting->about,
           'app_name' => empty($setting->service_tel) ? '' : $setting->app_name,
           'logo' => empty($setting->logo) ? '' : IMG_URL . getImg($setting->logo),
        ];
        $this->result($data,200,'获取成功','json');
    }


    public  function  getSkinSetting(){
        $where['member_miniapp_id'] = $this->appid;
        $MiniappsettingModel = new SkinModel();
        $setting = $MiniappsettingModel->where($where)->find();
        $data = [
            'service_tel' => empty($setting->service_tel) ? '' : $setting->service_tel,
            'complaint_tel' => empty($setting->service_tel) ? '' : $setting->complaint_tel,
            'about' => empty($setting->service_tel) ? '' : $setting->about,
            'app_name' => empty($setting->service_tel) ? '' : $setting->app_name,
            'skin' => empty($setting->service_tel) ? '' : $setting->skin,
            'logo' => empty($setting->logo) ? '' : IMG_URL . getImg($setting->logo),
            'last_time' => time() + 600,
        ];
        $this->result($data,200,'获取成功','json');
    }
}