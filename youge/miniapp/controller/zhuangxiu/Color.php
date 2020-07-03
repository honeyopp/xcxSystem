<?php
namespace app\miniapp\controller\zhuangxiu;
use app\miniapp\controller\Common;
use app\common\model\zhuangxiu\ColorModel;
class Color extends Common {
    
    public function index() {
        $where = $search = [];
        $search['color_name'] = $this->request->param('color_name');
        if (!empty($search['color_name'])) {
            $where['color_name'] = array('LIKE', '%' . $search['color_name'] . '%');
        }
        
        
        $where['member_miniapp_id'] = $this->miniapp_id;
        $count = ColorModel::where($where)->count();
        $list = ColorModel::where($where)->order(['color_id'=>'desc'])->paginate(10, $count);
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
            $data['color_name'] = $this->request->param('color_name');  
            if(empty($data['color_name'])){
                $this->error('名称不能为空',null,101);
            }
            
            
            $ColorModel = new ColorModel();
            $ColorModel->save($data);
            $this->success('操作成功',null);
        } else {
            return $this->fetch();
        }
    }
    
    public function edit(){
         $color_id = (int)$this->request->param('color_id');
         $ColorModel = new ColorModel();
         if(!$detail = $ColorModel->get($color_id)){
             $this->error('请选择要编辑的效果图色系分类',null,101);
         }
         if($detail->member_miniapp_id != $this->miniapp_id){
            $this->error("不存在效果图色系分类");
         }
         if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['color_name'] = $this->request->param('color_name');  
            if(empty($data['color_name'])){
                $this->error('名称不能为空',null,101);
            }

            
            $ColorModel = new ColorModel();
            $ColorModel->save($data,['color_id'=>$color_id]);
            $this->success('操作成功',null);
         }else{
            $this->assign('detail',$detail);
            return $this->fetch();  
         }
    }
    
    public function delete() {
   
        $color_id = (int)$this->request->param('color_id');
         $ColorModel = new ColorModel();
       
        if(!$detail = $ColorModel->find($color_id)){
            $this->error("不存在该效果图色系分类",null,101);
        }
        if($detail->member_miniapp_id != $this->miniapp_id) {
            $this->error('不存在该效果图色系分类', null, 101);
        }
        $ColorModel->where(['color_id'=>$color_id])->delete();
        $this->success('操作成功');
    }
   
}