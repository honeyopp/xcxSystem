<?php
namespace app\miniapp\controller\zhuangxiu;
use app\miniapp\controller\Common;
use app\common\model\zhuangxiu\OfferModel;
class Offer extends Common {

    public function create() {
        $OfferModel = new OfferModel();
        $detail = $OfferModel->where(['member_miniapp_id'=>$this->miniapp_id])->find();
        if ($this->request->method() == 'POST') {
            $data = [];
            $data['member_miniapp_id'] = $this->miniapp_id;
            $data['bedroom'] = ((int) $this->request->param('bedroom')) * 100;
            if(empty($data['bedroom'])){
                $this->error('卧室每间估价不能为空',null,101);
            }
            $data['livingroom'] = ((int) $this->request->param('livingroom')) * 100;
            if(empty($data['livingroom'])){
                $this->error('客厅每间估价不能为空',null,101);
            }
            $data['kitchen'] = ((int) $this->request->param('kitchen')) * 100;
            if(empty($data['kitchen'])){
                $this->error('厨房每间估价不能为空',null,101);
            }
            $data['toilet'] = ((int) $this->request->param('toilet')) * 100;
            if(empty($data['toilet'])){
                $this->error('卫生间每间估价不能为空',null,101);
            }
            $data['balcony'] = ((int) $this->request->param('balcony')) * 100;
            if(empty($data['balcony'])){
                $this->error('阳台没每间格不能为空',null,101);
            }
            $data['artificial'] = (int) $this->request->param('artificial');
            if(empty($data['artificial'])){
                $this->error('人工费总价百分比不能为空',null,101);
            }
            $data['material'] = (int) $this->request->param('material');
            if(empty($data['material'])){
                $this->error('材料费百分比不能为空',null,101);
            }
            $data['design'] = (int) $this->request->param('design');
            if(empty($data['design'])){
                $this->error('设计费不能为空',null,101);
            }
            $data['inspect'] = (int) $this->request->param('inspect');
            if(empty($data['inspect'])){
                $this->error('质检费百分比不能为空',null,101);
            }
            if(empty($detail)){
                $OfferModel->save($data);
            }else{
                $OfferModel->save($data,['offer_id'=>$detail->offer_id]);
            }
            $this->success('操作成功',null);
        } else {
            $this->assign('detail',$detail);
            return $this->fetch();
        }
    }

}