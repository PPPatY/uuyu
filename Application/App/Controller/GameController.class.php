<?php
namespace App\Controller;
use Common\Model\GameModel;
use Common\Model\GiftbagModel;
use Common\Model\UserPlayModel;
use Common\Model\DocumentModel;
use Common\Model\ServerModel;
use Think\Controller;
use Common\Api\GameApi;
use Org\UcenterSDK\Ucservice;
class GameController extends BaseController{
    //首页游戏列表
    public function gameRecList($rec_status,$p=1,$limit=10,$order="g.sort desc,g.id desc"){
        $map['recommend_status'] = array('like','%'.$rec_status.'%');
        $model = new GameModel();
        $data = $model->getGameLists($map,$order="g.sort desc,g.id desc",$p,$limit);
        if (empty($data)){
            $data = [];
        }
        $this->set_message(200,'success',$data);
    }
    //游戏分类
    public function gameGroup(){
        $model = new GameModel();
        $data = $model->getGroupLists();
        if (empty($data)){
            $data = [];
        }
        $this->set_message(200,'success',$data);
    }
    //分类游戏列表
    public function gameGroupList($type_id='',$p=1,$limit=10){
        if (empty($type_id)){
            $this->set_message(200,'success',[]);
        }
        if($type_id!=-1){
            $map['game_type_id'] = $type_id;
        }
        $model = new GameModel();
        $data = $model->getGameLists($map,'g.play_count desc,g.sort desc,g.id desc',$p,$limit);
        if (empty($data)){
            $data = [];
        }

        $this->set_message(200,'success',$data);
    }

    function sortArrByField(&$array, $field, $desc = false){
        $fieldArr = array();
        foreach ($array as $k => $v) {
            $fieldArr[$k] = $v[$field];
        }
        $sort = $desc == false ? SORT_ASC : SORT_DESC;
        array_multisort($fieldArr, $sort, $array);
    }

    //用户最近在玩
    public function userLatelyPlay($token){
        $this->auth($token);
        $model = new UserPlayModel();
        $map['user_id'] = USER_ID;
        $data = $model->getUserPlay($map,"u.play_time desc",1,10);
        if (empty($data)){
            $data = [];
        }
        $this->set_message(200,'success',$data);
    }

    public function server($token='',$type=0,$p=1,$row=10){
        $model = new ServerModel();
        if(!empty($token)){
            $this->auth($token);
            $user_id = USER_ID;
        }else{
            $user_id = 0;
        }
        $data = $model->server($type,$p,$row,$user_id);
        if (empty($data)){
            $data = [];
        }
        if($data['code']==-1){
            $this->set_message(1090,$data['msg']);
        }else{
            $this->set_message(200,'success',$data);
        }
        
    }
    //设置开服通知
    public function setServerNotice($token,$server_id,$type){
        $this->auth($token,'');
        $model = new ServerModel();
        $result = $model->set_server_notice(USER_ID,$server_id,$type);
        if($result!==false){
            $this->set_message(200,'操作成功','');
        }else{
            $this->set_message(1043,'操作失败','');
        }
    }
    //游戏详情 不包含礼包、活动、都在玩
    public function gameDetail($game_id='',$token=''){
        empty($game_id)&&$this->set_message(-1,'缺少game_id');
        if(!empty($token)){
            $this->auth($token);
            $user_id = USER_ID;
        }else{
            $user_id = 0;
        }
        $model = new GameModel();
        $data = $model->gameDetail($game_id,$user_id);
        if($data===false){
            $this->set_message(1057,'游戏不存在');
        }else{
            $data['introduction'] = str_replace('~~',"\n",$data['introduction']);
            $this->set_message(200,'success',$data);
        }
    }
    //游戏详情礼包
    public function gameGift($game_id,$token=''){
        $model = new GiftbagModel();
        $data = $model->getGiftLists($game_id);
        if($data===false){
            $this->set_message(1015,'暂无礼包');
        }else{
            if($token){
                $this->auth($token);
                foreach ($data as $key => $value) {
                    $map = array();
                    $map['game_id'] = $value['game_id'];
                    $map['gift_id'] = $value['gift_id'];
                    $map['user_id'] = USER_ID;
                    $isget = M('gift_record','tab_')->where($map)->getField('id');
                    if(!empty($isget)){
                        $data[$key]['received'] = 1;
                    }else{
                        $data[$key]['received'] = 0;
                    }
                }
            }
            $data = array_values($data);
            $this->set_message(200,'success',$data);
        }
    }
    //游戏文章
    public function gameActiveDoc($game_id){
        $model = new DocumentModel();
        $data = $model->getArticleListsByCategory($game_id,array('app_huodong','app_gg'),1,3,2);
        if($data===false){
            $this->set_message(1033,'暂无数据');
        }else{
            $this->set_message(200,'success',$data);
        }
    }
    /**
     * 猜你喜欢和大家都在玩（随机取出四条数据）
     * @param  无
     * @return 四条游戏数据
     * @author lyf
     */
    public function gsULike($getnum=4){
        $game_list=get_game_list();
        $count = count($game_list);
        if($count>=$getnum){
            $getnum = $getnum;
        }else{
            $getnum = $count;
        }

        $game_keys=array_rand($game_list,$getnum);
        foreach ($game_keys as $val) {
            $game_like['game_id']=$game_list[$val]['id'];
            $game_like['icon']=icon_url($game_list[$val]['icon']);
            $game_like['game_name']=$game_list[$val]['game_name'];
            $game_like['game_type_id']=$game_list[$val]['game_type_id'];
            $game_like['play_url']='http://' . $_SERVER['HTTP_HOST'] .'/mobile.php/?s=/Game/open_game/game_id/'.$game_list[$val]['id'];
            $res[]=$game_like;
        }
        $this->set_message(1,'success',$res);
    }

    //游戏礼包列表
    public function gameGiftLists($rec_status=false,$token='',$p=1){
        $model = new GiftbagModel();
        $data = $model->getGameGiftLists($rec_status,0,[],$p);
        if($data===false){
            $this->set_message(1015,'暂无礼包');
        }else{
            if($token){
                $this->auth($token);
                foreach ($data as $key => $value) {
                    foreach ($value['gblist'] as $k => $v) {
                        $map = array();
                        $map['game_id'] = $v['game_id'];
                        $map['gift_id'] = $v['gift_id'];
                        $map['user_id'] = USER_ID;
                        $isget = M('gift_record','tab_')->where($map)->getField('id');
                        if(!empty($isget)){
                            $data[$key]['gblist'][$k]['received'] = 1;
                        }else{
                            $data[$key]['gblist'][$k]['received'] = 0;
                        }
                    }
                }
            }
            $this->set_message(200,'success',array_values($data));
        }
    }
    //礼包详情
    public function giftDetail($gift_id,$token=''){
        $model = new GiftbagModel();
        $data = $model->getDetail($gift_id);
        $data['desribe'] = $this->ttt($data['desribe']);
        if($data===false){
            $this->set_message(1015,'暂无礼包');
        }else{
            if($token){
                $this->auth($token);
                $map = array();
                $map['game_id'] = $data['game_id'];
                $map['gift_id'] = $data['gift_id'];
                $map['user_id'] = USER_ID;
                $isget = M('gift_record','tab_')->where($map)->getField('id');
                if(!empty($isget)){
                    $data['received'] = 1;
                }else{
                    $data['received'] = 0;
                }
            }
            if(!check_gift_server($data['server_id'])){
                $data['server_name'] = '适用区服已关闭';
            }
            $this->set_message(1,'success',$data);
        }
    }

    /**
     * 去除换行符
     */
    function ttt($str){
        $order = array("\r\n", "\n", "\r");
        $replace = '';
        $str = str_replace($order, $replace, $str);
        return $str;
    }
    /**
     * 领取激活码
     * @param $token
     * @param $gift_id
     * author: xmy 280564871@qq.com
     */
    public function get_novice($token,$gift_id){
        $this->auth($token,"");
        $model = new GiftbagModel();
        $exist = $model->checkAccountGiftExist(USER_ID,$gift_id);
        if($exist){
            $this->set_message(1014,"您已经领取过该礼包","","");
        }
        $novice = $model->getNovice(USER_ID,$gift_id);
        if(empty($novice)){
            $this->set_message(1116,"来晚一步，激活码被领光了~","");
        }
        $this->set_message(200,'success',$novice);
    }



    /**
     * 热门礼包
     */
    public function hot_gift($token='',$limit=3){
        if ($token){
            $this->auth($token);
            $user_id = USER_ID;
        }
        $map['giftbag_type'] = 2;
        $giftbgmodel = new GiftbagModel();
        $gamegift = $giftbgmodel->getGiftLists(array('gt',0),1,$limit,$map,$user_id,"g.id desc");
        $this->set_message(200,'success',$gamegift);
    }

    /**
     * 热门游戏
     */
    public function hot_game($token='',$p=0,$type='0'){
        if (!empty($token)){
            $this->auth($token);
            $user_id = USER_ID;
        }
        $shunxu = $p;
        if(!$type){
            $shuzu = M('Game','tab_')->where(array('game_status'=>1,'recommend_status'=>2))->field('id')->group('id')->select();
        }else{
            $shuzu = M('Game','tab_')->where(array('game_status'=>1))->field('id')->select();
        }
        $count = count($shuzu);

        $shunxu1 = $shunxu*4;
        $shunxu2 = $shunxu*4+4;


        if (floor($shunxu1/$count) == floor($shunxu2/$count) && floor($shunxu1/$count) == 0){
            $shunxu_x = $shunxu1.",".($shunxu2-$shunxu1);
        }elseif(floor($shunxu1/$count) == floor($shunxu2/$count) && floor($shunxu1/$count) != 0){
            $floor = floor($shunxu1/$count);
            $shunxu_x = ($shunxu1-$floor*$count).",".(($shunxu2-$floor*$count)-($shunxu1-$floor*$count));
        }elseif (floor($shunxu1/$count) == 0 && floor($shunxu2/$count)!=0){
            $floor = floor($shunxu2/$count);
            $shunxu_x1 = $shunxu1.",".($count-$shunxu1);
            $shunxu_x2 = '0'.",".($shunxu2-$floor*$count);
        }elseif(floor($shunxu1/$count) != 0 && floor($shunxu2/$count)!=0 && floor($shunxu1/$count) != floor($shunxu2/$count)){
            $floor1 = floor($shunxu1/$count);
            $floor2 = floor($shunxu2/$count);

            $shunxu_x1 = ($shunxu1-$floor1*$count).",".($count-($shunxu1-$floor1*$count));
            $shunxu_x2 = '0'.",".($shunxu2-$floor2*$count);
        }

        $model = new GameModel();
        if(!$type){
            $map['recommend_status'] = 2;
        }
        if ($shunxu_x){
            $reco = $model->getHotGame($map,'g.sort desc,g.id desc',$shunxu_x,$user_id);
        }else{
            $reco1 = $model->getHotGame($map,'g.sort desc,g.id desc',$shunxu_x1,$user_id);

            if ($shunxu_x2 != '0,0'){
                $reco2 = $model->getHotGame($map,'g.sort desc,g.id desc',$shunxu_x2,$user_id);
                $reco = array_merge($reco1,$reco2);
            }else{
                $reco = $reco1;
            }
        }

        $this->set_message(200,'success',$reco);
    }


    public function game_recommend_hot($token='',$limit=3,$rec_status=1){
        if (!empty($token)){
            $this->auth($token);
            $user_id = USER_ID;
        }
        $map['recommend_status'] = $rec_status;
        $model = new GameModel();
        $data = $model->getGameLists($map,'g.sort desc',$p=1,$limit,$modul='Mobile',$user_id);
        if (empty($data)){
            $this->set_message(1082,'暂无数据',[]);
        }else{
            $this->set_message(200,'success',$data);
        }
    }
}
