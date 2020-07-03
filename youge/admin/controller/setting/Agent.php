<?php
namespace app\admin\controller\setting;
use app\admin\controller\Common;
use app\common\model\setting\SettingModel;

class Agent extends  Common{


    /*
     * 代理商管理
    */
    public function agent() {
        $SettingModel  = new SettingModel();
        if ($this->request->method() == 'POST') {
            $datas = empty($_POST['data']) ? [] : $_POST['data'];
            if(empty($datas)){
                $this->error('最少添加一个',null,101);
            }
            $data  = [];
            $i=1;
            foreach ($datas['price'] as $key=>$val){
                 if(empty($datas['price'][$key]) || empty($datas['agent_name'][$key])){
                       $this->error("第{$i}行请输入代理商名称",null,101);
                 }
                $i++;
                 $data[$key+1] = [
                     'name' => $datas['agent_name'][$key],
                     'discount' => $datas['price'][$key],
                 ];
            }
            $agents = serialize($data);
            $SettingModel->save(['v'=>$agents],['k'=>'agent']);
            $this->success('操作成功');
        }else{
             $agent = $SettingModel->fetchAll(true);
            $this->assign('agent',$agent);
            return $this->fetch();
        }
    }
}