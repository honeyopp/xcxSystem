<?php
namespace app\admin\controller\miniapp;
use app\admin\controller\Common;
use app\common\model\member\MemberModel;
use app\common\model\miniapp\AuthorizerModel;
use app\common\model\miniapp\MiniappModel;

class Authorizer extends Common {
    
    public function index() {
        $where = $search = [];
        $search['member_id'] = (int)$this->request->param('member_id');
        if (!empty($search['member_id'])) {
            $where['member_id'] = $search['member_id'];
        }
        $search['miniapp_id'] = (int)$this->request->param('miniapp_id');
        if (!empty($search['miniapp_id'])) {
            $where['miniapp_id'] = $search['miniapp_id'];
        }
        $search['nick_name'] = $this->request->param('nick_name');
        if (!empty($search['nick_name'])) {
            $where['nick_name'] = array('LIKE', '%' . $search['nick_name'] . '%');
        }
        $search['expir'] =  (int) $this->request->param('expir');
        if (!empty($search['expir'])) {
            switch ($search['expir']){
                case 1:
                    $where['expir_time'] = ['<',$this->request->time()];
                    break;
                case 2:
                    $where['expir_time'] = ['<',$this->request->time() + 30 * 86400];
                    break;
                case 3:
                    $where['expir_time'] = ['>',$this->request->time()];
                    break;
            }
        }
        $count = AuthorizerModel::where($where)->count();
        $list = AuthorizerModel::where($where)->order(['member_miniapp_id'=>'desc'])->paginate(10, $count);
        $memberIds = $mininappIds = [];
        foreach ($list as $val){
            $memberIds[$val->member_id] = $val->member_id;
            $mininappIds[$val->miniapp_id] = $val->miniapp_id;
        }
        $MemberModel = new MemberModel();
        $MiniappModel = new MiniappModel();
        $this->assign('member',$MemberModel->itemsByIds($memberIds));
        $this->assign('miniapp',$MiniappModel->itemsByIds($mininappIds));
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
            $data['member_id'] = (int) $this->request->param('member_id');
            if(empty($data['member_id'])){
                $this->error('用户不能为空',null,101);
            }
            $data['miniapp_id'] = (int) $this->request->param('miniapp_id');
            if(empty($data['miniapp_id'])){
                $this->error('小程序不能为空',null,101);
            }
            $data['authorizer_appid'] = $this->request->param('authorizer_appid');
            if(empty($data['authorizer_appid'])){
                $this->error('授权用户APPID不能为空',null,101);
            }
            $data['authorizer_access_token'] = $this->request->param('authorizer_access_token');
            if(empty($data['authorizer_access_token'])){
                $this->error('授权用户ACCESS_TOKEN不能为空',null,101);
            }
            $data['authorizer_refresh_token'] = $this->request->param('authorizer_refresh_token');
            if(empty($data['authorizer_refresh_token'])){
                $this->error('授权用户REFRESH_TOKEN不能为空',null,101);
            }
            $data['authorizer_refresh_token_expir_time'] = (int) strtotime($this->request->param('authorizer_refresh_token_expir_time'));
            if(empty($data['authorizer_refresh_token_expir_time'])){
                $this->error('授权用户REFRESH_TOKEN过期时间不能为空',null,101);
            }
            $data['nick_name'] = $this->request->param('nick_name');
            if(empty($data['nick_name'])){
                $this->error('微信名称不能为空',null,101);
            }
            $data['head_img'] = $this->request->param('head_img');
            if(empty($data['head_img'])){
                $this->error('头像不能为空',null,101);
            }
            $data['user_name'] = $this->request->param('user_name');
            if(empty($data['user_name'])){
                $this->error('小程序名不能为空',null,101);
            }
            $data['qrcode_url'] = $this->request->param('qrcode_url');
            if(empty($data['qrcode_url'])){
                $this->error('二维码地址不能为空',null,101);
            }
            $data['principal_name'] = $this->request->param('principal_name');
            if(empty($data['principal_name'])){
                $this->error('公司名称不能为空',null,101);
            }
            $data['signature'] = $this->request->param('signature');
            if(empty($data['signature'])){
                $this->error('签名不能为空',null,101);
            }
            $data['expir_time'] = (int) strtotime($this->request->param('expir_time'));
            if(empty($data['expir_time'])){
                $this->error('过期时间不能为空',null,101);
            }
            $AuthorizerModel = new AuthorizerModel();
            $AuthorizerModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }

    public function edit(){
         $member_miniapp_id = (int)$this->request->param('member_miniapp_id');
         $AuthorizerModel = new AuthorizerModel();
         if(!$detail = $AuthorizerModel->get($member_miniapp_id)){
             $this->error('请选择要编辑的小程序授权管理',null,101);
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_id'] = (int) $this->request->param('member_id');
            if(empty($data['member_id'])){
                $this->error('用户不能为空',null,101);
            }
            $data['miniapp_id'] = (int) $this->request->param('miniapp_id');
            if(empty($data['miniapp_id'])){
                $this->error('小程序不能为空',null,101);
            }
            $data['authorizer_appid'] = $this->request->param('authorizer_appid');  
            if(empty($data['authorizer_appid'])){
                $this->error('授权用户APPID不能为空',null,101);
            }
            $data['authorizer_access_token'] = $this->request->param('authorizer_access_token');  
            if(empty($data['authorizer_access_token'])){
                $this->error('授权用户ACCESS_TOKEN不能为空',null,101);
            }
            $data['authorizer_refresh_token'] = $this->request->param('authorizer_refresh_token');  
            if(empty($data['authorizer_refresh_token'])){
                $this->error('授权用户REFRESH_TOKEN不能为空',null,101);
            }
            $data['authorizer_refresh_token_expir_time'] = (int) strtotime($this->request->param('authorizer_refresh_token_expir_time'));
            if(empty($data['authorizer_refresh_token_expir_time'])){
                $this->error('授权用户REFRESH_TOKEN过期时间不能为空',null,101);
            }
            $data['nick_name'] = $this->request->param('nick_name');  
            if(empty($data['nick_name'])){
                $this->error('微信名称不能为空',null,101);
            }
            $data['head_img'] = $this->request->param('head_img');
            $data['user_name'] = $this->request->param('user_name');  
            if(empty($data['user_name'])){
                $this->error('小程序名不能为空',null,101);
            }
            $data['qrcode_url'] = $this->request->param('qrcode_url');  
            if(empty($data['qrcode_url'])){
                $this->error('二维码地址不能为空',null,101);
            }
            $data['principal_name'] = $this->request->param('principal_name');  
            if(empty($data['principal_name'])){
                $this->error('公司名称不能为空',null,101);
            }
            $data['signature'] = $this->request->param('signature');  
            if(empty($data['signature'])){
                $this->error('签名不能为空',null,101);
            }
            $data['expir_time'] = (int) strtotime($this->request->param('expir_time'));
            if(empty($data['expir_time'])){
                $this->error('过期时间不能为空',null,101);
            }
            $data['is_case'] = (int) $this->request->param('is_case');
            $data['photo'] = (string) $this->request->param('photo');
             $AuthorizerModel = new AuthorizerModel();
            $AuthorizerModel->save($data,['member_miniapp_id'=>$member_miniapp_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
        if($this->request->method() == 'POST'){
             $member_miniapp_id = $_POST['member_miniapp_id'];
        }else{
            $member_miniapp_id = $this->request->param('member_miniapp_id');
        }
        $data = [];
        if (is_array($member_miniapp_id)) {
            foreach ($member_miniapp_id as $k => $val) {
                $member_miniapp_id[$k] = (int) $val;
            }
            $data = $member_miniapp_id;
        } else {
            $data[] = $member_miniapp_id;
        }
        if (!empty($data)) {
            $AuthorizerModel = new AuthorizerModel();
            $AuthorizerModel->where(array('member_miniapp_id'=>array('IN',$data)))->delete();
        }
        $this->success('操作成功');
    }
   
}