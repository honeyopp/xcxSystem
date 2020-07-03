<?php

namespace app\api\controller\companygw;

use app\api\controller\Common;
use app\common\model\companygw\BannerModel;
use app\common\model\companygw\CompanyModel;
use app\common\model\companygw\ConsultModel;
use app\common\model\companygw\ContentModel;
use app\common\model\companygw\NewsModel;
use app\common\model\companygw\ProductModel;
use think\Request;

class  Index extends Common
{

    public function index()
    {
        //  获取banner；
        $BannerModel = new BannerModel();
        $where['member_miniapp_id'] = $this->appid;
        $BannerList = $BannerModel->where($where)->order('orderby desc')->limit(0, 5)->select();
        $data['banner'] = [];
        foreach ($BannerList as $val) {
            $data['banner'][] = [
                'photo' => IMG_URL . getImg($val->photo),
            ];
        }
        //公司介绍；
        $CompanyModel = new CompanyModel();
        $companyList = $CompanyModel->find($this->appid);
        $data['describe'] = empty($companyList) ? '' : $companyList->describe;
        //做多3个解决方案
        $_where['member_miniapp_id'] = $this->appid;
        $_where['type'] = 1;
        $NewsModel = new NewsModel();
        $planList = $NewsModel->where($_where)->limit(0, 6)->select();
        $data['news'] = [];
        foreach ($planList as $val) {
            $data['news'][] = [
                'id' => $val->toutiao_id,
                'title' => $val->title,
                'add_time' => date("Y-m-d", $val->add_time),
            ];
        }
        $_where['type'] = 2;
        $planList = $NewsModel->where($_where)->limit(0, 3)->select();
        $contentIds = [];
        foreach ($planList as $val) {
            $contentIds[$val->toutiao_id] = $val->toutiao_id;
        }
        $contentIds = empty($contentIds) ? 0 : $contentIds;
        $ContentModel = new ContentModel();
        $plan_where['toutiao_id'] = ['IN', $contentIds];
        $plan_where['type'] = 2;
        $list = $ContentModel->where($plan_where)->group('toutiao_id')->select();
        $contents = [];
        foreach ($list as $val) {
            $contents[$val->toutiao_id] = $val;
        }

        foreach ($planList as $val) {
            $data['plan'][] = [
                'id' => $val->toutiao_id,
                'title' => $val->title,
                'add_time' => date("Y-m-d", $val->add_time),
                'photo' => IMG_URL . getImg($val->photo),
                'content' => empty($contents[$val->toutiao_id]) ? '' : $contents[$val->toutiao_id]->content,

            ];
        }
        // 最多6条动态；
        //最多8个产品；
        $ProductModel = new ProductModel();
        $productList = $ProductModel->where($where)->limit(0, 8)->select();
        $data['product'] = [];
        foreach ($productList as $val) {
            $data['product'][] = [
                'id' => $val->product_id,
                'photo' => IMG_URL . getImg($val->photo),
                'product_name' => $val->product_name,
            ];
        }
        $this->result($data, 200, '数据初始化成功', 'json');
    }

//    获取产品列表
    public function product(Request $request)
    {
        $keywords = (string)$this->request->param('ketword');
        if (!empty($keywords)) {
            $where['product_name'] = array('LIKE', '%' . $keywords . '%');
        }
        $where['member_miniapp_id'] = $this->appid;
        $ProductModel = new ProductModel();
        $productList = $ProductModel->where($where)->limit($this->limit_bg, $this->limit_num)->select();

        $data['list'] = [];
        foreach ($productList as $val) {
            $data['list'][] = [
                'id' => $val->product_id,
                'photo' => IMG_URL . getImg($val->photo),
                'price' => $val->price,
                'version' => $val->version,
                'product_name' => $val->product_name,
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, '200', '数据初始化成功', 'json');
    }

    public function plan(){
        $where['type'] = 2;
        $where['member_miniapp_id'] = $this->appid;
        $NewsModel = new NewsModel();
        $newsList = $NewsModel->where($where)->limit($this->limit_bg, $this->limit_num)->select();
        $contentIds = [];
        foreach ($newsList as $val) {
            $contentIds[$val->toutiao_id] = $val->toutiao_id;
        }
        $contentIds = empty($contentIds) ? 0 : $contentIds;
        $ContentModel = new ContentModel();
        $where['toutiao_id'] = ['IN', $contentIds];
        $where['type'] = 2;
        $list = $ContentModel->where($where)->group('toutiao_id')->select();
        $contents = [];
        foreach ($list as $val) {
            $contents[$val->toutiao_id] = $val;
        }
        $data['list'] = [];
        foreach ($newsList as $val) {
            $data['list'][] = [
                'id' => $val->toutiao_id,
                'title' => $val->title,
                'add_time' => date("Y-m-d", $val->add_time),
                'photo' => IMG_URL . getImg($val->photo),
                'content' => empty($contents[$val->toutiao_id]) ? '' : $contents[$val->toutiao_id]->content,
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, '200', '数据初始化成功', 'json');
    }

    public function news(){
        $where['type'] = 1;
        $where['member_miniapp_id'] = $this->appid;
        $NewsModel = new NewsModel();
        $newsList = $NewsModel->where($where)->limit($this->limit_bg, $this->limit_num)->select();
        $data['list'] = [];
        foreach ($newsList as $val) {
            $data['list'][] = [
                'id' => $val->toutiao_id,
                'title' => $val->title,
                'add_time' => date("Y-m-d", $val->add_time),
            ];
        }
        $data['more'] = count($data['list']) == $this->limit_num ? 1 : 0;
        $this->result($data, '200', '数据初始化成功', 'json');
    }


    //普通信息
    public function detail(){
        $id = (int)$this->request->param('id');

        $ToutiaoModel = new NewsModel();
        if (!$detail = $ToutiaoModel->get($id)) {
            $this->result('', 400, '没有要查看的信息', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有要查看的信息', 'json');
        }

        $contents =ContentModel::where(['member_miniapp_id'=>  $this->appid,'toutiao_id'=>$id,'type'=>['neq',3]])->order(['orderby'=>'asc'])->select();
        $contentArr = [];
        foreach($contents as $val){
            $contentArr[]=[
                'content' => $val->content,
                'photo'   => empty($val->photo) ? '' : IMG_URL.getImg($val->photo)
            ];
        }
        $data = [
            'id' => $id,
            'title' => $detail->title,
            'contents'=>$contentArr,
            'add_time'=>date('Y-m-d H:i:s',$detail->add_time),
        ];
        $this->result($data, 200, '获取数据成功', 'json');
    }


    //产品信息
    public function detail2(){
        $id = (int)$this->request->param('id');
        $ToutiaoModel = new ProductModel();
        if (!$detail = $ToutiaoModel->get($id)) {
            $this->result('', 400, '没有要查看的信息', 'json');
        }
        if ($detail->member_miniapp_id != $this->appid) {
            $this->result('', 400, '没有要查看的信息', 'json');
        }

        $contents =ContentModel::where(['member_miniapp_id'=>  $this->appid,'toutiao_id'=>$id,'type'=>3])->order(['orderby'=>'asc'])->select();
        $contentArr = [];
        $product = [
            'product_name' => $detail->product_name,
            'photo'       =>  IMG_URL . getImg($detail->photo),
            'price'     =>   $detail->price,
            'version'   =>  $detail->version,
            'add_time'=>date('Y-m-d H:i:s',$detail->add_time),
        ];

        foreach($contents as $val){
            $contentArr[]=[
                'content' => $val->content,
                'photo'   => empty($val->photo) ? '' : IMG_URL.getImg($val->photo)
            ];
        }

        $data = [
            'contents'=>$contentArr,
            'product' => $product,
        ];
        $this->result($data, 200, '获取数据成功', 'json');
    }


    public function getCompany(){
        $CompanyModel  = new CompanyModel();
        $data = [];
        if($detail = $CompanyModel->find($this->appid)){
            $data = [
                'company_name' => $detail->company_name,
                'lat' => $detail->lat,
                'lng' => $detail->lng,
                'address' => $detail->address,
                'traffic' => $detail->traffic,
                'name' => $detail->name,
                'mobile' => $detail->mobile,
                'describe' => $detail->describe,
            ];
        }
      $this->result($data,200,'数据初始换成功','json');
    }
   public function  consult (){
       $ConsultModel = new ConsultModel();
       $data['product_name'] = (string) $this->request->param('product_name');
       $data['name'] = (string) $this->request->param('name');
       if(empty($data['name'])){
           $this->result([],400,'联系人不能为空','json');
       }
       $data['member_miniapp_id'] = $this->appid;
       $data['remarks'] = (string) $this->request->param('remarks');
       $data['tel'] = (string) $this->request->param('tel');
       if(empty($data['tel'])){
           $this->result([],400,'联系方式不能为空','json');
       }
       $ConsultModel->save($data);
       $this->result([],200,'操作成功','json');
    }









}