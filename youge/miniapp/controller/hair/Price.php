<?php
namespace app\miniapp\controller\hair;
use app\miniapp\controller\Common;
use app\common\model\hair\PriceModel;
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
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('项目名称不能为空',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            if(empty($data['price'])){
                $this->error('价格不能为空',null,101);
            }
            $data['vip_price'] = (int) $this->request->param('vip_price');
            if(empty($data['vip_price'])){
                $this->error('VIP价格不能为空',null,101);
            }
            
            

            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $price_id = (int)$this->request->param('price_id');
         $PriceModel = new PriceModel();
         if(!$detail = $PriceModel->get($price_id)){
             $this->error('请选择要编辑的项目价格',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在项目价格");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['name'] = $this->request->param('name');  
            if(empty($data['name'])){
                $this->error('项目名称不能为空',null,101);
            }
            $data['price'] = (int) $this->request->param('price');
            if(empty($data['price'])){
                $this->error('价格不能为空',null,101);
            }
            $data['vip_price'] = (int) $this->request->param('vip_price');
            if(empty($data['vip_price'])){
                $this->error('VIP价格不能为空',null,101);
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
            $this->error("不存在该项目价格",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该项目价格', null, 101);
        }
        $PriceModel->where(['price_id'=>$price_id])->delete();
        $this->success('操作成功');
    }
   
}