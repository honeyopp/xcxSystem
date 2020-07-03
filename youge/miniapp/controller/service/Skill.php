<?php
namespace app\miniapp\controller\service;
use app\miniapp\controller\Common;
use app\common\model\service\SkillModel;
class Skill extends Common {

    public function index() {
        $where = $search = [];
        $search['name'] = $this->request->param('name');
        if (!empty($search['name'])) {
            $where['name'] = array('LIKE', '%' . $search['name'] . '%');
        }
        $where['pid'] = 0;

        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = SkillModel::where($where)->count();
        $list = SkillModel::where($where)->order(['skill_id'=>'desc'])->paginate(10, $count);
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
                $this->error('名称不能为空',null,101);
            }
            $_tages = (string) $this->request->param('tages');
            if(empty($_tages)){
                $this->error('请填写具体技能',null,101);
            }
            $ARRAY = explode(',',$_tages);
            $SkillModel = new SkillModel();
            $SkillModel->save($data);
            $datas = [];
            foreach ($ARRAY  as  $key=>$val){
                    $datas[] = [
                        'pid' => $SkillModel->skill_id,
                        'name'     => $val,
                        'member_miniapp_id' => $this->miniapp_id,
                    ];
              }
            $SkillModel->saveAll($datas);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }

    public function edit(){
         $skill_id = (int)$this->request->param('skill_id');
         $SkillModel = new SkillModel();
         if(!$detail = $SkillModel->get($skill_id)){
             $this->error('请选择要编辑的阿姨技能',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在阿姨技能");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['name'] = $this->request->param('name');
            if(empty($data['name'])){
                $this->error('名称不能为空',null,101);
            }

             $_tages = (string) $this->request->param('tages');
             if(empty($_tages)){
                 $this->error('请填写具体技能',null,101);
             }
             $ARRAY = explode(',',$_tages);
             $SkillModel = new SkillModel();
             $datas = [];
             foreach ($ARRAY  as  $key=>$val){
                 $datas[] = [
                     'pid' => $skill_id,
                     'name'     => $val,
                     'member_miniapp_id' => $this->miniapp_id,
                 ];
             }
            $SkillModel->save($data,['skill_id'=>$skill_id]);
             $SkillModel->where(['pid'=>$skill_id])->delete();
             $SkillModel->saveAll($datas);
            $this->success('操作成功',null);
         }else{
             $pids =  $SkillModel->where(['pid'=>$skill_id])->select();
             $pids_array = [];
             foreach ($pids as $val){
                $pids_array[] = $val->name;
             }
             $pids_str = implode(',',$pids_array);
             $this->assign('pids',$pids_str);
            $this->assign('detail',$detail);
            return $this->fetch();
         }
    }

    public function delete() {

        $skill_id = (int)$this->request->param('skill_id');
         $SkillModel = new SkillModel();

        if(!$detail = $SkillModel->find($skill_id)){
            $this->error("不存在该阿姨技能",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该阿姨技能', null, 101);
        }
        $SkillModel->where(['skill_id'=>$skill_id])->delete();
        $SkillModel->where(['pid'=>$skill_id])->delete();
        $this->success('操作成功');
    }

}