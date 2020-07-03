<?php
namespace app\miniapp\controller\group;
use app\common\model\group\GoodsModel;
use app\common\model\group\OrderModel;
use app\common\model\user\UserModel;
use app\miniapp\controller\Common;
use app\common\model\group\GroupModel;
class Group extends Common {
    public function index() {
        $where = $search =$whereor =  [];
        $search['user_id'] = (int)$this->request->param('user_id');
        if (!empty($search['user_id'])) {
            $where['user_id'] = $search['user_id'];
        }
        $search['date'] = $this->request->param('date');
        if (!empty($search['date'])) {
            $where['FROM_UNIXTIME(add_time,"%Y-%m-%d")'] = $search['date'];
        }
        //0请选择 1等待开团2已开团3未成团
        $search['status'] = (int) $this->request->param('status');
        if($search['status'] == 1){
            $where['status'] = 0;
        }elseif ($search['status'] == 2){
            $where['status'] = 1;
        }elseif ($search['status'] == 3){
            $where['status'] = 2;
            $whereor['expire_time'] = ['<',$this->request->time()];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = GroupModel::where($where)->whereOr($whereor)->count();
        $list = GroupModel::where($where)->whereOr($whereor)->order(['group_id'=>'desc'])->paginate(10, $count);
        $userIds = $goodsIds = [];
        foreach ($list as $val){
            $userIds[$val->user_id] = $val->user_id;
            $goodsIds[$val->goods_id] = $val->goods_id;
        }
        $GoodsModel = new GoodsModel();
        $UserModel = new UserModel();
        $this->assign('goods',$GoodsModel->itemsByIds($goodsIds));
        $this->assign('user',$UserModel->itemsByIds($userIds));
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
            $data['goods_id'] = (int) $this->request->param('goods_id');
            if(empty($data['goods_id'])){
                $this->error('商品不能为空',null,101);
            }
            $data['expire_time'] = (int) strtotime($this->request->param('expire_time'));
            if(empty($data['expire_time'])){
                $this->error('过期时间不能为空',null,101);
            }
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('团长不能为空',null,101);
            }
            $data['max_num'] = (int) $this->request->param('max_num');
            if(empty($data['max_num'])){
                $this->error('该团需要人数不能为空',null,101);
            }
            $data['this_num'] = (int) $this->request->param('this_num');
            if(empty($data['this_num'])){
                $this->error('当前参团人数不能为空',null,101);
            }
            $data['add_time'] = (int) strtotime($this->request->param('add_time'));
            if(empty($data['add_time'])){
                $this->error('开团时间不能为空',null,101);
            }
            $data['status'] = (int) $this->request->param('status');
            if(empty($data['status'])){
                $this->error('状态不能为空',null,101);
            }
            $GroupModel = new GroupModel();
            $GroupModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    public function edit(){
         $group_id = (int)$this->request->param('group_id');
         $GroupModel = new GroupModel();
         if(!$detail = $GroupModel->get($group_id)){
             $this->error('请选择要编辑的开团抢购',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在开团抢购");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['goods_id'] = (int) $this->request->param('goods_id');
            if(empty($data['goods_id'])){
                $this->error('商品不能为空',null,101);
            }
            $data['expire_time'] = (int) strtotime($this->request->param('expire_time'));
            if(empty($data['expire_time'])){
                $this->error('过期时间不能为空',null,101);
            }
            $data['user_id'] = (int) $this->request->param('user_id');
            if(empty($data['user_id'])){
                $this->error('团长不能为空',null,101);
            }
            $data['max_num'] = (int) $this->request->param('max_num');
            if(empty($data['max_num'])){
                $this->error('该团需要人数不能为空',null,101);
            }
            $data['this_num'] = (int) $this->request->param('this_num');
            if(empty($data['this_num'])){
                $this->error('当前参团人数不能为空',null,101);
            }
            $data['add_time'] = (int) strtotime($this->request->param('add_time'));
            if(empty($data['add_time'])){
                $this->error('开团时间不能为空',null,101);
            }
            $data['status'] = (int) $this->request->param('status');
            if(empty($data['status'])){
                $this->error('状态不能为空',null,101);
            }
            $GroupModel = new GroupModel();
            $GroupModel->save($data,['group_id'=>$group_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    public function delete() {
        $group_id = (int)$this->request->param('group_id');
        $GroupModel = new GroupModel();
        if(!$detail = $GroupModel->find($group_id)){
            $this->error("不存在该开团抢购",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该开团抢购', null, 101);
        }
        $GroupModel->where(['group_id'=>$group_id])->delete();
        $this->success('操作成功');
    }
    //一键成团；
    public function yjct(){
        $group_id = (int)$this->request->param('group_id');
        $GroupModel = new GroupModel();
        if(!$detail = $GroupModel->find($group_id)){
            $this->error("不存在该开团抢购",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该开团抢购', null, 101);
        }
        $GroupModel->save(['status'=>1],['group_id'=>$group_id]);
        $OrderModel = new OrderModel();
        $OrderModel->save(['status'=>2],['group_id'=>$group_id]);
        $this->success('操作成功');
    }
}