<?php
namespace app\admin\controller\miniapp;
use app\admin\controller\Common;
use app\common\model\miniapp\MiniappsettingModel;
class Miniappsetting extends Common {
    
    public function index() {
        $where = $search = [];

        $count = MiniappsettingModel::where($where)->count();
        $list = MiniappsettingModel::where($where)->order(['setting_id'=>'desc'])->paginate(10, $count);
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
            $data['member_miniapp_id'] = (int) $this->request->param('member_miniapp_id');
            if(empty($data['member_miniapp_id'])){
                $this->error('授权小程序id不能为空',null,101);
            }
            $data['service_tel'] = $this->request->param('service_tel');  
            if(empty($data['service_tel'])){
                $this->error('客服电话不能为空',null,101);
            }
            $data['complaint_tel'] = $this->request->param('complaint_tel');  
            if(empty($data['complaint_tel'])){
                $this->error('投诉电话不能为空',null,101);
            }
            $data['about'] = $this->request->param('about');  
            if(empty($data['about'])){
                $this->error('关于我们不能为空',null,101);
            }
            $data['copyright'] = $this->request->param('copyright');  
            $data['apiclient_cert'] = $this->request->param('apiclient_cert');  
            if(empty($data['apiclient_cert'])){
                $this->error('证书pem格式不能为空',null,101);
            }
            $data['apiclient_cert_key'] = $this->request->param('apiclient_cert_key');  
            if(empty($data['apiclient_cert_key'])){
                $this->error('证书秘钥pem格式不能为空',null,101);
            }
            $data['rootca'] = $this->request->param('rootca');  
            if(empty($data['rootca'])){
                $this->error('ci证书文件不能为空',null,101);
            }
            
            
            $MiniappsettingModel = new MiniappsettingModel();
            $MiniappsettingModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $setting_id = (int)$this->request->param('setting_id');
         $MiniappsettingModel = new MiniappsettingModel();
         if(!$detail = $MiniappsettingModel->get($setting_id)){
             $this->error('请选择要编辑的授权小程序设置',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = (int) $this->request->param('member_miniapp_id');
            if(empty($data['member_miniapp_id'])){
                $this->error('授权小程序id不能为空',null,101);
            }
            $data['service_tel'] = $this->request->param('service_tel');  
            if(empty($data['service_tel'])){
                $this->error('客服电话不能为空',null,101);
            }
            $data['complaint_tel'] = $this->request->param('complaint_tel');  
            if(empty($data['complaint_tel'])){
                $this->error('投诉电话不能为空',null,101);
            }
            $data['about'] = $this->request->param('about');  
            if(empty($data['about'])){
                $this->error('关于我们不能为空',null,101);
            }
            $data['copyright'] = $this->request->param('copyright');  
            $data['apiclient_cert'] = $this->request->param('apiclient_cert');  
            if(empty($data['apiclient_cert'])){
                $this->error('证书pem格式不能为空',null,101);
            }
            $data['apiclient_cert_key'] = $this->request->param('apiclient_cert_key');  
            if(empty($data['apiclient_cert_key'])){
                $this->error('证书秘钥pem格式不能为空',null,101);
            }
            $data['rootca'] = $this->request->param('rootca');  
            if(empty($data['rootca'])){
                $this->error('ci证书文件不能为空',null,101);
            }

            
            $MiniappsettingModel = new MiniappsettingModel();
            $MiniappsettingModel->save($data,['setting_id'=>$setting_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        if($this->request->method() == 'POST'){
             $setting_id = $_POST['setting_id'];
        }else{
            $setting_id = $this->request->param('setting_id');
        }
        $data = [];
        if (is_array($setting_id)) {
            foreach ($setting_id as $k => $val) {
                $setting_id[$k] = (int) $val;
            }
            $data = $setting_id;
        } else {
            $data[] = $setting_id;
        }
        if (!empty($data)) {
            $MiniappsettingModel = new MiniappsettingModel();
            $MiniappsettingModel->where(array('setting_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}