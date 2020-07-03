<?php
namespace app\miniapp\controller\job;
use app\miniapp\controller\Common;
use app\common\model\job\IndustryModel;
class Industry extends Common {
    
    public function index() {
        $IndustryModel = new IndustryModel();
        $where = $search = [];
        $search['industry_name'] = $this->request->param('industry_name');
        if (!empty($search['industry_name'])) {
            $where['industry_name'] = array('LIKE', '%' . $search['industry_name'] . '%');
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = IndustryModel::where($where)->count();
        $list = IndustryModel::where($where)->order(['industry_id'=>'desc'])->paginate(10, $count);
        $page = $list->render();
        $pIds = [];
        foreach ($list as $val){
            $pIds[$val->pid] = $val->pid;
        }
        $this->assign('pid',$IndustryModel->itemsByIds($pIds));
        $this->assign('search', $search);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('totalNum', (int) $count);
        return $this->fetch();
    }
    
    public function create() {
        $IndustryModel = new IndustryModel();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['industry_name'] = $this->request->param('industry_name');  
            if(empty($data['industry_name'])){
                $this->error('行业名称不能为空',null,101);
            }
            $data['pid'] = (int) $this->request->param('pid');
            $IndustryModel->save($data);
            $this->success('操作成功',null);
        } else {
              $where['pid'] = 0;
              $industry = $IndustryModel->where($where)->select();
              $this->assign('industry',$industry);
            return $this->fetch();
        }
    }
    
    public function edit(){
         $industry_id = (int)$this->request->param('industry_id');
         $IndustryModel = new IndustryModel();
         if(!$detail = $IndustryModel->get($industry_id)){
             $this->error('请选择要编辑的行业设置',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在行业设置");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['industry_name'] = $this->request->param('industry_name');  
            if(empty($data['industry_name'])){
                $this->error('行业名称不能为空',null,101);
            }
            $data['pid'] = (int) $this->request->param('pid');
            if(empty($data['pid'])){
                $this->error('父级分类不能为空',null,101);
            }

            
            $IndustryModel = new IndustryModel();
            $IndustryModel->save($data,['industry_id'=>$industry_id]);
            $this->success('操作成功',null);
         }else{
             $where['pid'] = 0;
             $industry = $IndustryModel->where($where)->select();
             $this->assign('industry',$industry);
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $industry_id = (int)$this->request->param('industry_id');
         $IndustryModel = new IndustryModel();
       
        if(!$detail = $IndustryModel->find($industry_id)){
            $this->error("不存在该行业设置",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该行业设置', null, 101);
        }
        $IndustryModel->where(['industry_id'=>$industry_id])->delete();
        $this->success('操作成功');
    }
   
}