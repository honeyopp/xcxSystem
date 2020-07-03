<?php
namespace app\miniapp\controller\minsu;
use app\common\model\count\CountModel;
use app\common\model\minsu\MinsuModel;
use app\common\model\minsu\MinsuorderModel;
use app\common\model\minsu\RoomModel;
use app\miniapp\controller\Common;

class Ranking extends  Common{
    /*
     * 商家排名；
     * */
    public function minsu (){
        $countModel = new CountModel();
        $date =  $countModel->getDate();
        $search['end_date'] = $date['EndDate'];
        $search['bg_date'] = $date['BingDate'];
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['add_time'] = array('between',array(strtotime($date['BingDate']),strtotime($date['EndDate']) + 86400));
        $MinsuorderModel = new MinsuorderModel();
        $list = $MinsuorderModel->field("minsu_id, count(*) as num,add_time  ")->where($where)->group('minsu_id')->order(["num"=>'desc','add_time'=>'asc'])->paginate(10);
        $hoteIds = [];
        $page = (int) $this->request->param('page');
        if($page <= 0){$page = 1;};
        foreach ($list as $val){
            $hoteIds[$val->minsu_id] = $val->minsu_id;
        }
        $MinsuModel = new MinsuModel();
        $minsu = $MinsuModel->itemsByIds($hoteIds);
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
                    'minsu_name' => empty($minsu[$val->minsu_id])  ? '' : $minsu[$val->minsu_id]->minsu_name,
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
        $search['minsu_id'] = (int) $this->request->param('minsu_id');
        if(!empty($search['minsu_id'])){
            $where['minsu_id'] = $search['minsu_id'];
        }
        $where['member_miniapp_id'] = $this->miniapp_id;
        $where['add_time'] = array('between',array(strtotime($date['BingDate']),strtotime($date['EndDate']) + 86400));
        $MinsuorderModel = new MinsuorderModel();
        $list = $MinsuorderModel->field("minsu_id, room_id,count(*) as num,add_time  ")->where($where)->group('room_id')->order(["num"=>'desc','add_time'=>'asc'])->paginate(10);
        $hoteIds = $roomIds =  [];
        $page = (int) $this->request->param('page');
        if($page <= 0){$page = 1;};
        foreach ($list as $val){
            $hoteIds[$val->minsu_id] = $val->minsu_id;
            $roomIds[$val->room_id] = $val->room_id;
        }
        $RoomModel = new RoomModel();
        $rooms = $RoomModel->itemsByIds($roomIds);
        $MinsuModel = new MinsuModel();
        $minsu = $MinsuModel->itemsByIds($hoteIds);
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
                'minsu_name' => empty($minsu[$val->minsu_id])  ? '' : $minsu[$val->minsu_id]->minsu_name,
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