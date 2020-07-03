<?php
/**
 * @fileName    city.php
 * @author      tudou<1114998996@qq.com>
 * @date        2017/7/20 0020
 */
namespace app\miniapp\controller\taocan;
use app\common\model\setting\CityModel;
use app\miniapp\controller\Common;
class City extends  Common{
    /**
     * 城市列表；
     */
    public function index() {
        $where = $search = [];
        $search['province_id'] = (int)$this->request->param('province_id');
        if (!empty($search['province_id'])) {
            $where['province_id'] = $search['province_id'];
        }
        $search['city_name'] = $this->request->param('city_name');
        if (!empty($search['city_name'])) {
            $where['city_name'] = $search['city_name'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['is_delete'] = 0;
        $CityModel  = new CityModel();
        $count = $CityModel->where($where)->count();
        $list = $CityModel->where($where)->order(['city_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $provinces = config('province');
        $province = [];
        foreach ($provinces as $val){
            $province[$val['initial']][] = $val;
        }
        ksort($province);
        $this->assign('province',$province);
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function create() {
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;

            $data['province_id'] = (int) $this->request->param('province_id');
            if(empty($data['province_id'])){
                $this->error('请选择一个省份',null,101);
            }
            $data['city_name'] =  $this->request->param('city_name');
            if(empty($data['city_name'])){
                $this->error('请填写您要开通的城市',null,101);
            }
            $data['lat'] =  $this->request->param('lat');
            if(empty($data['lat'])){
                $this->error('请选择地址（单击选择地址按钮）',null,101);
            }
            $data['lng'] =  $this->request->param('lng');
            if(empty($data['lng'])){
                $this->error('请选择地址（单击选择地址按钮）',null,101);
            }
            $data['pinyin'] = $this->request->param('pinyin');
            if(empty($data['pinyin'])){
                $this->error('请填写拼音（全拼）',null,101);
            }
            $data['initial'] =  $this->request->param('initial');
            if(empty($data['initial'])){
                $this->error('请填写首字母（大写）',null,101);
            }
            $data['is_open'] = (int) $this->request->param('is_open');
            $CityModel = new CityModel();
            $CityModel->save($data);
            $this->success('操作成功',null);
        } else {
            $province = [];
            foreach (config('province') as $val){
                $province[$val['initial']] [] = $val;
            }
            ksort($province);
            $this->assign('province',$province);
            return $this->fetch();
        }
    }
    public function edit(){
        $city_id = (int)$this->request->param('city_id');
        $CityModel = new CityModel();
        if(!$detail = $CityModel->get($city_id)){
            $this->error('您未增加该城市',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("您未增加该城市",null,101);
        }
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['province_id'] = (int) $this->request->param('province_id');
            if(empty($data['province_id'])){
                $this->error('请选择一个省份',null,101);
            }
            $data['city_name'] =  $this->request->param('city_name');
            if(empty($data['city_name'])){
                $this->error('请填写您要开通的城市',null,101);
            }
            $data['lat'] =  $this->request->param('lat');
            if(empty($data['lat'])){
                $this->error('请选择地址（单击选择地址按钮）',null,101);
            }
            $data['lng'] =  $this->request->param('lng');
            if(empty($data['lng'])){
                $this->error('请选择地址（单击选择地址按钮）',null,101);
            }
            $data['pinyin'] =  $this->request->param('pinyin');
            if(empty($data['pinyin'])){
                $this->error('请填写拼音（全拼）',null,101);
            }
            $data['initial'] = $this->request->param('initial');
            if(empty($data['initial'])){
                $this->error('请填写拼音（首字母）',null,101);
            }
            $data['is_open'] = (int) $this->request->param('is_open');
            $CityModel = new CityModel();
            $CityModel->save($data,['city_id'=>$city_id]);
            $this->success('操作成功',null);
        }else{
            $province = [];
            foreach (config('province') as $val){
                $province[$val['initial']] [] = $val;
            }
            ksort($province);
            $this->assign('province',$province);
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }


    /**
     * 城市选择；
     */
    public function  select(){
        $where = $search = [];
        $search['province_id'] = (int)$this->request->param('province_id');
        if (!empty($search['province_id'])) {
            $where['province_id'] = $search['province_id'];
        }
        $search['city_name'] = $this->request->param('city_name');
        if (!empty($search['city_name'])) {
            $where['city_name'] = $search['city_name'];
        }
        $where['is_delete'] = 0;
        $where['member_miniapp_id'] = $this->miniapp_id;
        $CityModel  = new CityModel();
        $count = $CityModel->where($where)->count();
        $list = $CityModel->where($where)->order(['city_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $provinces = config('province');
        $province = [];
        foreach ($provinces as $val){
            $province[$val['initial']][] = $val;
        }
        ksort($province);
        $this->assign('province',$province);
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }

    public function delete() {
        $city_id = (int)$this->request->param('city_id');
        $CityModel = new CityModel();
        if(!$detail = $CityModel->get($city_id)){
            $this->error('您未添加该城市',null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("您未添加该城市",null,101);
        }
        $data['is_delete'] = 1;
       $CityModel->save($data,['city_id'=>$city_id]);
        $this->success('操作成功');
    }


}