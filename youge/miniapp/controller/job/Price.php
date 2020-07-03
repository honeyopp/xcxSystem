<?php
namespace app\miniapp\controller\job;
use app\miniapp\controller\Common;
use app\common\model\job\PriceModel;
class Price extends Common {
    
    public function index() {
        $where = $search = [];

        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = PriceModel::where($where)->count();
        $list = PriceModel::where($where)->order(['price_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    
    public function create() {
        if ($this->request->method() == 'POST') {
            $data = [];
            $where['member_miniapp_id'] = $this->miniapp_id;
            $count = PriceModel::where($where)->count();
            if($count >= 5){
                $this->error('您做多只能添加5条',null,101);
            }
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['day_num'] = (int) $this->request->param('day_num');
            if(empty($data['day_num'])){
                $this->error('服务天数不能为空',null,101);
            }
            $data['vip_level'] = (int) $this->request->param('vip_level');
            $data['price'] = ((int) $this->request->param('price')) * 100;
            if(empty($data['price'])){
                $this->error('价格不能为空',null,101);
            }
            $PriceModel = new PriceModel();
            $PriceModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $price_id = (int)$this->request->param('price_id');
         $PriceModel = new PriceModel();
         if(!$detail = $PriceModel->get($price_id)){
             $this->error('请选择要编辑的服务价格',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在服务价格");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['day_num'] = (int) $this->request->param('day_num');
            if(empty($data['day_num'])){
                $this->error('服务天数不能为空',null,101);
            }
            $data['vip_level'] = (int) $this->request->param('vip_level');
            $data['price'] = ((int) $this->request->param('price')) * 100;
            if(empty($data['price'])){
                $this->error('价格不能为空',null,101);
            }

            
            $PriceModel = new PriceModel();
            $PriceModel->save($data,['price_id'=>$price_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $price_id = (int)$this->request->param('price_id');
         $PriceModel = new PriceModel();
       
        if(!$detail = $PriceModel->find($price_id)){
            $this->error("不存在该服务价格",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该服务价格', null, 101);
        }
        $PriceModel->where(['price_id'=>$price_id])->delete();
        $this->success('操作成功');
    }
   
}