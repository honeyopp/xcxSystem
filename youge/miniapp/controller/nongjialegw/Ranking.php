<?php
namespace app\miniapp\controller\hotel;
use app\common\model\count\CountModel;
use app\common\model\hotel\HotelModel;
use app\common\model\hotel\HotelorderModel;
use app\common\model\hotel\RoomModel;
use app\miniapp\controller\Common;

class Ranking extends  Common{
    /*
     * 商家排名；
     * */
    public function hotel (){
        $countModel = new CountModel();
        $date =  $countModel->getDate();
        $search['end_date'] = $date['EndDate'];
        $search['bg_date'] = $date['BingDate'];
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['add_time'] = array('between',array(strtotime($date['BingDate']),strtotime($date['EndDate']) + 86400));
        $HotelOrderModel = new HotelorderModel();
        $list = $HotelOrderModel->field("hotel_id, count(*) as num,add_time  ")->where($where)->group('hotel_id')->order(["num"=>'desc','add_time'=>'asc'])->paginate(10);
        $hoteIds = [];
        $page = (int) $this->request->param('page');
        if($page <= 0){$page = 1;};
        foreach ($list as $val){
            $hoteIds[$val->hotel_id] = $val->hotel_id;
        }
        $HotelModel = new HotelModel();
        $hotel = $HotelModel->itemsByIds($hoteIds);
       $data = [];
       $i = 0;
        foreach ($list as $val){
            $i++;
          $corlor = '#CCCCCC';
            $level = ($page-1) * 10 + $i;
            switch ($level){
                case 1:
                    $corlor = '#fbd43b';
                    break;
                case 2 :
                    $corlor = '#C0C0C0';
                    break;
                case 3 :
                    $corlor = '#C2AD6F';
                    break;
            }
                $data[] = [
                    'hotel_name' => empty($hotel[$val->hotel_id])  ? '' : $hotel[$val->hotel_id]->hotel_name,
                    'num'        => $val->num,
                    'level'      =>  ($page-1) * 10 + $i,
                    'corlor'     => $corlor,
                ];
        }
        $pagesize = $list->render();
        $this->assign('search',$search);
        $this->assign('page',$pagesize);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function room(){
        $countModel = new CountModel();
        $date =  $countModel->getDate();
        $search['end_date'] = $date['EndDate'];
        $search['bg_date'] = $date['BingDate'];
        $search['hotel_id'] = (int) $this->request->param('hotel_id');
        if(!empty($search['hotel_id'])){
            $where['hotel_id'] = $search['hotel_id'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['add_time'] = array('between',array(strtotime($date['BingDate']),strtotime($date['EndDate']) + 86400));
        $HotelOrderModel = new HotelorderModel();
        $list = $HotelOrderModel->field("hotel_id, room_id,count(*) as num,add_time  ")->where($where)->group('room_id')->order(["num"=>'desc','add_time'=>'asc'])->paginate(10);
        $hoteIds = $roomIds =  [];
        $page = (int) $this->request->param('page');
        if($page <= 0){$page = 1;};
        foreach ($list as $val){
            $hoteIds[$val->hotel_id] = $val->hotel_id;
            $roomIds[$val->room_id] = $val->room_id;
        }
        $RoomModel = new RoomModel();
        $rooms = $RoomModel->itemsByIds($roomIds);
        $HotelModel = new HotelModel();
        $hotel = $HotelModel->itemsByIds($hoteIds);
        $data = [];
        $i = 0;
        foreach ($list as $val){
            $i++;
            $corlor = '#CCCCCC';
            $level = ($page-1) * 10 + $i;
            switch ($level){
                case 1:
                    $corlor = '#fbd43b';
                    break;
                case 2 :
                    $corlor = '#C0C0C0';
                    break;
                case 3 :
                    $corlor = '#C2AD6F';
                    break;
            }
            $data[] = [
                'hotel_name' => empty($hotel[$val->hotel_id])  ? '' : $hotel[$val->hotel_id]->hotel_name,
                 'room_name'      => empty($rooms[$val->room_id]) ? '': $rooms[$val->room_id]->title,
                'num'        => $val->num,
                'level'      =>  ($page-1) * 10 + $i,
                'corlor'     => $corlor,
            ];
        }
        $pagesize = $list->render();
        $this->assign('search',$search);
        $this->assign('page',$pagesize);
        $this->assign('data',$data);
        return $this->fetch();
    }
}