<?php
namespace app\api\controller\yiliao;
use app\api\controller\Common;
use app\api\controller\Publicuse;
use app\common\model\hospital\ContentsModel;
use app\common\model\hospital\DoctorModel;
use app\common\model\hospital\HospitalModel;

class  Index extends Common{

    /*
     * 获取首页
     */
    public function getIndex(){
        $Publicuse = new Publicuse($this->appid);
        $data = [];
        $Publicuse->getBanner($data['banner']);
        $Publicuse->getCategoryColor($data['category']);
        $this->result($data,200,'数据初始化成功','json');
    }

    /*
     *  医院概况
     */
    public function  hospital(){
        //获取banner
        $Publicuse = new Publicuse($this->appid);
        $data = [];
        $Publicuse->getBanner($data['banner']);
        //获取详情；
        $contents =ContentsModel::where(['member_miniapp_id'=>  $this->appid])->order(['orderby'=>'asc'])->select();
        $data['contents'] = [];
        foreach($contents as $val){
            $data['contents'][]=[
                'content' => $val->content,
                'photo'   => empty($val->photo) ? '' : IMG_URL.getImg($val->photo)
            ];
        }
        $this->result($data,200,'数据初始化成功','json');

    }

    /*
     * 来院路线；
     */
    public function line(){
        $HospitalModel = new HospitalModel();
        $detail = $HospitalModel->find($this->appid);
        if(empty($detail)){
            $this->result('',400,'数据初始化成功','json');
        }
        $data = [
            'lat' => $detail->lat,
            'lng' => $detail->lng,
            'address' => $detail->address,
            'hospital_name' => $detail->hospital_name,
            'name' => $detail->name,
            'mobile' => $detail->mobile,
            'traffic' => $detail->traffic,
            'introduce' => $detail->introduce,
        ];
        $this->result($data,200,'数据初始化成功','json');
    }


    /*
     * 医生列表；
     */
    public function doctorList(){
        $category_id = (int) $this->request->param('category_id');
        $DoctorModel = new DoctorModel();
        $where['member_miniapp_id'] = $this->appid;
        $where['category_id'] = $category_id;
        $list = $DoctorModel->where($where)->order('orderby desc')->limit($this->limit_bg,$this->limit_num)->select();
        $data['list'] = [];
        foreach ($list as $val){
            $data['list'][] = [
                    'doctor_id' => $val->doctor_id,
                    'photo'  => IMG_URL . getImg($val->photo),
                    'doctor_name' => $val->doctor_name,
                    'thank_num'  => $val->thank_num,
                    'enroll_num'  => $val->enroll_num,
                    'consult_num'  => $val->consult_num,
                    'major'  => $val->major,
            ];
        }
        $data['more']  = count($data['list']) == $this->limit_num ? 1: 0;
        $this->result($data,200,'数据初始化成功','json');
    }
    /*
     * 医生详情
     */
    public function doctorDetail(){
        $doctor_id = (int) $this->request->param('doctor_id');
        $DoctorModel = new DoctorModel();
        if(!$detail = $DoctorModel->find($doctor_id)){
            $this->result('',400,'参数错误','json');
        }
        if($detail->member_miniapp_id != $this->appid){
            $this->result('',400,'参数错误','json');
        }
        $data = [
            'doctor_id' => $doctor_id,
            'doctor_name' => $detail->doctor_name,
            'photo' => IMG_URL . getImg($detail->photo),
            'experience' => $detail->experience,
            'introduce' => $detail->introduce,
            'major' => $detail->major,
            'learning' => $detail->learning,
            'thank_num' => $detail->thank_num,
            'consult_num' => $detail->consult_num,
            'enroll_num' => $detail->enroll_num,
        ];
        $this->result($data,200,'数据初始化成功','json');
    }
}