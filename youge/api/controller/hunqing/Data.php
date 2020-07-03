<?php

namespace app\api\controller\hunqing;

use app\api\controller\Common;
use app\common\model\company\AreaModel;
use app\common\model\company\CatModel;
use app\common\model\company\CompanyModel;
use app\common\model\company\CompanytelModel;
use app\common\model\company\PicsModel;
use app\manage\controller\Miniapp;

class  Data extends Common
{
    /*
     * 获取网站基本数据 区域 和分类；
     * type 0 获取 区域 和分类 type = 1 获取区域 2获取分类
     * */
    public function getData()
    {
        $type = (int)$this->request->param('type');
        $where['member_miniapp_id'] = $this->appid;
        $data = [];
        if ($type == 0 || $type == 1) {
            $AreaModel = new AreaModel();
            $areas = $AreaModel->where($where)->limit(0, 50)->select();
            $data['areas'] = [];
            foreach ($areas as $val) {
                $data['areas'][] = [
                    'area_name' => $val->area_name,
                    'area_id' => $val->area_id,
                ];
            }
        }

        if ($type == 0 || $type == 2) {
            $cats = config('dataattr.hunqingcat');
            foreach ($cats as $key=>$val) {
                $data['cats'][] = [
                    'cat_id' => $key,
                    'cat_name' => $val,
                ];
            }
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }

    public function getIndex()
    {
        $keyword = $this->request->param('keywords');
        if (!empty($keyword)) {
            $where['title|company_name|sort_name'] = array('LIKE', '%' . $keyword . '%');
        }
        $lat = floatval($this->request->param('lat'));
        $lng = floatval($this->request->param('lng'));
        if (empty($lat) || empty($lng)) {
            //$this->result('', 400, '获取地理位置失败！', 'json');
        }
        $cat_id = $this->request->param('cat_id');
        $area_id = $this->request->param('area_id');
        $order = (int)$this->request->param('orderby');
        if (!empty($cat_id)) {
            $where['cat_id'] = $cat_id;
        }
        if (!empty($area_id)) {
            $where['area_id'] = $area_id;
        }
        $orderby = 'vip desc,orderby desc';
        switch ($order) {
            case 1:
                $orderby = 'vip desc,orderby desc';
                break;
            case 2:
                $orderby = 'vip desc,view_num asc ';
                break;
            case 3:
                $orderby = 'vip desc,tel_num desc ';
                break;
            case 4:
                $orderby = 'vip desc,yuyue_num desc ';
                break;
            case 5:
                $orderby = 'vip desc,share_num desc ';
                break;
            case 6:
                $orderby = 'vip desc,zan_num desc ';
                break;
            case 7:
                $orderby = " ABS(lng-'{$lng}' + lat-'{$lat}')  ASC ";
                break;
        }
        $where['member_miniapp_id'] = $this->appid;
        $where['audit'] = 1;
        $CompanyModel = new CompanyModel();
        $data['num'] = $CompanyModel->where($where)->count();
        $list = $CompanyModel->where($where)->order($orderby)->limit($this->limit_bg, $this->limit_num)->select();
        $data['list'] = [];
        $areaIds = [];
        foreach ($list as $val) {
            $areaIds[$val->area_id] = $val->area_id;
        }
        $AreaModel = new AreaModel();
        $areas = $AreaModel->itemsByIds($areaIds);
        foreach ($list as $val) {
            $data['list'][] = [
                'company_id' => $val->company_id,
                'logo' => IMG_URL . getImg($val->logo),
                'title' => $val->title,
                'sort_name' => $val->sort_name,
                'area_name' => empty($areas[$val->area_id]) ? '' : $areas[$val->area_id]->area_name,
                'vip' => $val->vip,
                'tel' => $val->tel,
                'juli' => empty($lat) ? '（定位中）' : getDistance($lat, $lng, $val->lat, $val->lng),
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, '200', '数据初始化成功', 'json');
    }


    public function detail()
    {
        $company_id = (int)$this->request->param('company_id');
        $CompanyModel = new CompanyModel();
        if (!$company = $CompanyModel->find($company_id)) {
            $this->result([], '400', '不存在商家', 'json');
        }
        if ($company->member_miniapp_id != $this->appid) {
            $this->result([], 400, '不存在商家', 'json');
        }
        $PicsModel = new PicsModel();
        $where['company_id'] = $company->company_id;
        $pics = $PicsModel->where($where)->select();
        $photos = [];
        foreach ($pics as $val) {
            $photos[] = IMG_URL . getImg($val->pic);
        }
        $AreaModel = new AreaModel();
        $arae = $AreaModel->find($company->area_id);
        $CompanyModel->where(['company_id' => $company_id])->setInc('view_num');
        $data = [
            'company_id' => $company->company_id,
            'title' => $company->title,
            'company_name' => $company->company_name,
            'sort_name' => $company->sort_name,
            'logo' => IMG_URL . getImg($company->logo),
            'type' => $company->type,
            'cat_id' => $company->cat_id,
            'area_id' => $company->area_id,
            'area_name' => empty($arae) ? '' : $arae->area_name,
            'main_business' => $company->main_business,
            'bg_year' => $company->bg_year,
            'name' => $company->name,
            'tel' => $company->tel,
            'zhiwu' => $company->zhiwu,
            'addr' => $company->addr,
            'lng' => $company->lng,
            'lat' => $company->lat,
            'vip' => $company->vip,
            'view_num' => $company->view_num,
            'tel_num' => $company->tel_num,
            'yuyue_num' => $company->yuyue_num,
            'zan_num' => $company->zan_num,
            'share_num' => $company->share_num,
            'detail' => $company->detail,
            'audit' => $company->audit,
            'photos' => $photos,
        ];
        $this->result($data, 200, '数据初始化成功', 'json');
    }


    public function qrcord()
    {
        $id = (int) $this->request->param('id');
        $CompanytelModel = new CompanyModel();
        if(!$store = $CompanytelModel->find($id)){
            $this->result([],'400','不存在商家','json');
        };
        if($store->member_miniapp_id != $this->appid){
            $this->result([],'400','不存在商家','json');
        }
        if(empty($store->qrcode)){
            $path = (string) $this->request->param('path');
            $path = empty($path) ? '/pages/index' : $path;
            $MiniApp = new \app\common\library\MiniApp($this->appid);
            $data = $MiniApp->getcode($path);
            $type = $this->check_image_type($data);
            $file_name  = date("Ymd") . DS . md5(true) . '.' .  $type;
            $dir = ROOT_PATH . 'attachs' . DS . 'uploads' .DS .  $file_name;
            $datas['qrcode'] = $file_name;
            $return = IMG_URL . getImg($file_name);
            file_put_contents($dir,$data);
            $CompanytelModel->save($datas,['company_id'=>$id]);
        }else{
            $return = IMG_URL . getImg($store->qrcode);
        }
        $this->result($return,200,'数据初始化成功','json');
        //目录+文件
    }

    public function check_image_type($image)
    {
        $bits = array('jpg' => "\xFF\xD8\xFF", 'gif' => "gif", 'png' => "\x89\x50\x4e\x47\x0d\x0a\x1a\x0a", 'BMP' => 'BM',);
        foreach ($bits as $type => $bit) {
            if (substr($image, 0, strlen($bit)) === $bit) {
                return $type;
            }
        }
        return 'png';
    }

    public function getList(){
           $data = [];
            $where['member_miniapp_id'] = $this->appid;
            $where['audit'] = 1;
            $where['cat_id'] = 1;
            $CompanyModel = new CompanyModel();
            $list1 = $CompanyModel->where($where)->order("orderby desc")->limit(0,3)->select();
            if(!empty($list1)){
                $data[0]['cat_id'] = 1;
                $data[0]['cat_name'] = '婚纱摄影';
            }

            foreach ($list1 as $val){
                 $data[0]['cat_list'][] = [
                     'logo' => IMG_URL . getImg($val->logo),
                     'sort_name' => $val->sort_name,
                     'company_id' => $val->company_id,
                 ];
            }

        $where['cat_id'] = 2;
        $CompanyModel = new CompanyModel();
        $list2 = $CompanyModel->where($where)->order("orderby desc")->limit(0,3)->select();
        if(!empty($list2)){
            $data[1]['cat_id'] = 2;
            $data[1]['cat_name'] = '婚礼策划';
        }
        foreach ($list2 as $val){
            $data[1]['cat_list'][] = [
                'logo' => IMG_URL . getImg($val->logo),
                'sort_name' => $val->sort_name,
                'company_id' => $val->company_id,
            ];
        }


        $where['cat_id'] = 3;
        $CompanyModel = new CompanyModel();
        $list3 = $CompanyModel->where($where)->order("orderby desc")->limit(0,3)->select();
        if(!empty($list3)){
            $data[2]['cat_id'] = 3;
            $data[2]['cat_name'] = '婚纱礼服';
        }
        foreach ($list3 as $val){
            $data[2]['cat_list'][] = [
                'logo' => IMG_URL . getImg($val->logo),
                'sort_name' => $val->sort_name,
                'company_id' => $val->company_id,
            ];
        }


        $where['cat_id'] = 4;
        $CompanyModel = new CompanyModel();
        $list4 = $CompanyModel->where($where)->order("orderby desc")->limit(0,3)->select();
        if(!empty($list4)){
            $data[3]['cat_id'] = 4;
            $data[3]['cat_name'] = '婚礼跟拍';
        }
        foreach ($list4 as $val){
            $data[3]['cat_list'][] = [
                'logo' => IMG_URL . getImg($val->logo),
                'sort_name' => $val->sort_name,
                'company_id' => $val->company_id,
            ];
        }



        $where['cat_id'] = 5;
        $CompanyModel = new CompanyModel();
        $list5 = $CompanyModel->where($where)->order("orderby desc")->limit(0,3)->select();
        if(!empty($list5)){
            $data[4]['cat_id'] = 5;
            $data[4]['cat_name'] = '新娘跟妆';
        }
        foreach ($list5 as $val){
            $data[4]['cat_list'][] = [
                'logo' => IMG_URL . getImg($val->logo),
                'sort_name' => $val->sort_name,
                'company_id' => $val->company_id,
            ];
        }



        $where['cat_id'] = 6;
        $CompanyModel = new CompanyModel();
        $list6 = $CompanyModel->where($where)->order("orderby desc")->limit(0,3)->select();
        if(!empty($list6)){
            $data[5]['cat_id'] = 6;
            $data[5]['cat_name'] = '婚宴酒店';
        }
        foreach ($list6 as $val){
            $data[5]['cat_list'][] = [
                'logo' => IMG_URL . getImg($val->logo),
                'sort_name' => $val->sort_name,
                'company_id' => $val->company_id,
            ];
        }


        $where['cat_id'] = 7;
        $CompanyModel = new CompanyModel();
        $list7 = $CompanyModel->where($where)->order("orderby desc")->limit(0,3)->select();
        if(!empty($list7)){
            $data[6]['cat_id'] = 7;
            $data[6]['cat_name'] = '婚车租赁';
        }
        foreach ($list7 as $val){
            $data[6]['cat_list'][] = [
                'logo' => IMG_URL . getImg($val->logo),
                'sort_name' => $val->sort_name,
                'company_id' => $val->company_id,
            ];
        }


        $where['cat_id'] = 8;
        $CompanyModel = new CompanyModel();
        $list8 = $CompanyModel->where($where)->order("orderby desc")->limit(0,3)->select();
        if(!empty($list8)){
            $data[7]['cat_id'] = 8;
            $data[7]['cat_name'] = '婚礼司仪';
        }
        foreach ($list8 as $val){
            $data[7]['cat_list'][] = [
                'logo' => IMG_URL . getImg($val->logo),
                'sort_name' => $val->sort_name,
                'company_id' => $val->company_id,
            ];
        }

        $this->result($data,'200','数据初始化成功','json');

    }

}