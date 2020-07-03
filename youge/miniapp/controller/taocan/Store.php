<?php
namespace app\miniapp\controller\taocan;

use app\common\model\city\CityModel;
use app\miniapp\controller\Common;
use app\common\model\taocan\StoreModel;

class Store extends Common
{

    public function index()
    {
        $where = $search = [];
        $search['store_tel'] = $this->request->param('store_tel');
        if (!empty($search['store_tel'])) {
            $where['store_tel'] = array('LIKE', '%' . $search['store_tel'] . '%');
        }

        $search['store_name'] = $this->request->param('store_name');
        if (!empty($search['store_name'])) {
            $where['store_name'] = array('LIKE', '%' . $search['store_name'] . '%');
        }


        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['is_delete'] = 0;
        $count = StoreModel::where($where)->count();
        $list = StoreModel::where($where)->order(['store_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }


    public function select()
    {
        $where = $search = [];
        $search['store_tel'] = $this->request->param('store_tel');
        if (!empty($search['store_tel'])) {
            $where['store_tel'] = array('LIKE', '%' . $search['store_tel'] . '%');
        }
        $search['store_name'] = $this->request->param('store_name');
        if (!empty($search['store_name'])) {
            $where['store_name'] = array('LIKE', '%' . $search['store_name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['is_delete'] = 0;
        $count = StoreModel::where($where)->count();
        $list = StoreModel::where($where)->order(['store_id' => 'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int)$count);
        return $this->fetch();
    }

    public function create()
    {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['store_name'] = $this->request->param('store_name');
            if (empty($data['store_name'])) {
                $this->error('商家名称不能为空', null, 101);
            }
            $data['city_id'] = (int) $this->request->param('city_id');
            $CityModel = new CityModel();
            if(!$city = $CityModel->find($data['city_id']) ){
                $this->error('不存在城市',null,101);
            }

            if($city->member_miniapp_id != $this->miniapp_id){
                $this->error('不存在城市',null,101);
            }
            $data['stroe_detail'] = $this->request->param('stroe_detail');
            if (empty($data['stroe_detail'])) {
                $this->error('商家详情不能为空', null, 101);
            }
            $data['store_tel'] = $this->request->param('store_tel');
            if (empty($data['store_tel'])) {
                $this->error('负责人手机号不能为空', null, 101);
            }
            $data['stroe_address'] = $this->request->param('stroe_address');
            if (empty($data['stroe_address'])) {
                $this->error('负责人地址不能为空', null, 101);
            }
            $data['store_idcard'] = $this->request->param('store_idcard');
            if (empty($data['store_idcard'])) {
                $this->error('负责人身份证不能为空', null, 101);
            }
            $data['lat'] = $this->request->param('lat');
            if (empty($data['lat'])) {
                $this->error('经度不能为空', null, 101);
            }
            $data['lng'] = $this->request->param('lng');
            if (empty($data['lng'])) {
                $this->error('纬度不能为空', null, 101);
            }


            $StoreModel = new StoreModel();
            $StoreModel->save($data);
            $this->success('操作成功', null);
        } else {
            return $this->fetch();
        }
    }

    public function edit()
    {
        $store_id = (int)$this->request->param('store_id');
        $StoreModel = new StoreModel();
        if (!$detail = $StoreModel->get($store_id)) {
            $this->error('请选择要编辑的套餐管理', null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error("不存在套餐管理");
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['city_id'] = (int) $this->request->param('city_id');
            $CityModel = new CityModel();
            if(!$city = $CityModel->find($data['city_id']) ){
                $this->error('不存在城市',null,101);
            }

            if($city->member_miniapp_id != $this->miniapp_id){
                $this->error('不存在城市',null,101);
            }
            $data['store_name'] = $this->request->param('store_name');
            if (empty($data['store_name'])) {
                $this->error('商家名称不能为空', null, 101);
            }
            $data['stroe_detail'] = $this->request->param('stroe_detail');
            if (empty($data['stroe_detail'])) {
                $this->error('商家详情不能为空', null, 101);
            }
            $data['store_tel'] = $this->request->param('store_tel');
            if (empty($data['store_tel'])) {
                $this->error('负责人手机号不能为空', null, 101);
            }
            $data['stroe_address'] = $this->request->param('stroe_address');
            if (empty($data['stroe_address'])) {
                $this->error('负责人地址不能为空', null, 101);
            }
            $data['store_idcard'] = $this->request->param('store_idcard');
            if (empty($data['store_idcard'])) {
                $this->error('负责人身份证不能为空', null, 101);
            }
            $data['lat'] = $this->request->param('lat');
            if (empty($data['lat'])) {
                $this->error('经度不能为空', null, 101);
            }
            $data['lng'] = $this->request->param('lng');
            if (empty($data['lng'])) {
                $this->error('纬度不能为空', null, 101);
            }
            $StoreModel = new StoreModel();
            $StoreModel->save($data, ['store_id' => $store_id]);
            $this->success('操作成功', null);
        } else {
            $city = CityModel::find($detail->city_id);
            $this->assign('city',$city);
            $this->assign('detail', $detail);
            return $this->fetch();
        }
    }

    public function delete()
    {

        $store_id = (int)$this->request->param('store_id');
        $StoreModel = new StoreModel();

        if (!$detail = $StoreModel->find($store_id)) {
            $this->error("不存在该套餐管理", null, 101);
        }
        if ($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该套餐管理', null, 101);
        }
        if ($detail->is_delete == 1) {
            $this->success('操作成功');
        }
        $data['is_delete'] = 1;
        $StoreModel->save($data, ['store_id' => $store_id]);
        $this->success('操作成功');
    }

}