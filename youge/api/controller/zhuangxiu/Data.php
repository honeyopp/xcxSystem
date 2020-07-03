<?php

namespace app\api\controller\zhuangxiu;

use app\api\controller\Common;
use app\common\model\company\AreaModel;
use app\common\model\company\CatModel;
use app\common\model\company\CompanyModel;
use app\common\model\company\CompanytelModel;
use app\common\model\company\PicsModel;
use app\common\model\zhuangxiu\OfferModel;
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
            //这里做分类的pid操作；
            $CatModel = new CatModel();
            $cats = $CatModel->where($where)->limit(0, 50)->select();
            $data['cats']['1']['pid'] = 1;
            $data['cats']['1']['pid_name'] = '装修公司';
            $data['cats']['2']['pid'] = 2;
            $data['cats']['2']['pid_name'] = '材料商';
            foreach ($cats as $val) {
                if($val->pid == 1){
                    $data['cats']['1']['list'][] = [
                        'cat_id' => $val->cat_id,
                        'cat_name' => $val->cat_name,
                    ];
                }elseif($val->pid == 2){
                    $data['cats']['2']['list'][] = [
                        'cat_id' => $val->cat_id,
                        'cat_name' => $val->cat_name,
                    ];
                }
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
          //  $this->result('', 400, '获取地理位置失败！', 'json');
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
        $orderby = '';
        switch ($order) {
            case 1:
                $orderby = 'vip desc,orderby  desc ';
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
                'juli' =>empty($lat) ? '（定位中）' : getDistance($lat, $lng, $val->lat, $val->lng),
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
        $CatModel = new CatModel();
        $cats = $CatModel->where(['member_miniapp_id'=>$this->appid])->limit(0, 50)->select();
        $cat1Ids = $cat2Ids = [];
        foreach ($cats as $val){
            if($val->pid == 1){
                $cat1Ids[$val->cat_id] = $val->cat_id;
            }elseif($val->pid == 2){
                $cat1Ids[$val->cat_id] = $val->cat_id;
            }
        }
        $cat1Ids = empty($cat1Ids) ? 0 : $cat1Ids;
        $where['member_miniapp_id'] = $this->appid;
        $where['audit'] = 1;
        $where['cat_id'] = ['IN',$cat1Ids];
        $CompanyModel  =  new CompanyModel();
        $list1 = $CompanyModel->where($where)->order('orderby desc')->limit(0,6)->select();
      if(!empty($list1)){
          $data[0]['pid_name'] = '装修公司';
          foreach ($list1 as $val){
              $data[0]['list'][] = [
                  'logo' => IMG_URL . getImg($val->logo),
                  'company_id' => $val->company_id,
                  'sort_name'  => $val->sort_name,
              ];
          }
      }
        $cat2Ids = empty($cat2Ids) ? 0 : $cat2Ids;
        $where['cat_id'] = ['IN',$cat2Ids];
        $CompanyModel  =  new CompanyModel();
        $list2 = $CompanyModel->where($where)->order('orderby desc')->limit(0,6)->select();
       if(!empty($list2)){
           $data[1]['pid_name'] = '材料商';
           foreach ($list2 as $val){
               $data[1]['list'][] = [
                   'logo' => IMG_URL . getImg($val->logo),
                   'company_id' => $val->company_id,
                   'sort_name'  => $val->sort_name,
               ];
           }
       }
        $this->result($data,'200','数据初始化成功','json');
    }


    public function getOffer(){
        $OfferModel = new OfferModel();
        $detail = $OfferModel->where(['member_miniapp_id'=>$this->appid])->find();
        if(empty($detail)){
            $this->result([],'200','数据初始化成功','json');
        }
        $data = [
             'bedroom' => $detail->bedroom,
             'livingroom' => $detail->livingroom,
             'kitchen' => $detail->kitchen,
             'toilet' => $detail->toilet,
             'balcony' => $detail->balcony,
             'artificial' => $detail->artificial,
             'material' => $detail->material,
             'design' => $detail->design,
             'inspect' => $detail->inspect,
         ];
        $this->result($data,200,'数据初始化成功','json');
    }

}