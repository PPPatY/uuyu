<?php
namespace Sdk\Controller;
use Think\Controller;
use Common\Api\GameApi;
class GameController extends BaseController{
    //首页游戏（推荐、热门 用在首页 和游戏页面）
    public function recommend_game(){
        $data = json_decode(base64_decode(file_get_contents("php://input")),true);
        #判断数据是否为空
        if(empty($data)){$this->set_message(0,"fail","游戏数据不能为空");}
        $map['game_status']=1;
        $map['tab_game.recommend_status']=$data['recommend_status'];
        if($data['more']==''||$data['more']==null){
            if($data['recommend_status']==3){
                $limit=16;
            }else{
                $limit=8;
            }
            $game=M('game','tab_')
                ->field('tab_game.icon,tab_game.cover,tab_game.game_name,tab_game.id,tab_game.screen_type,tab_game.game_type_name,tab_game.features,tab_game.recommend_status')
                ->where($map)
                ->order('sort desc')
                ->limit($limit)
                ->select();
            foreach ($game as $key => $value) {
                $game[$key]['pic_link']='http://'.$_SERVER ['HTTP_HOST'].get_cover($value['icon'],'path');
                $game[$key]['cover_link']='http://'.$_SERVER ['HTTP_HOST'].get_cover($value['cover'],'path');
                $play_num=play_num($value['id']);
                $game[$key]['play_num']=$play_num;
            }
        }else if($data['more']==1){
            $data['limit']=$data['limit']==""?1:$data['limit'];
            $game=M('game','tab_')
                ->field('tab_game.icon,tab_game.game_name,tab_game.id,tab_game.screen_type,tab_game.game_type_name,tab_game.features,tab_game.recommend_status')
                ->where($map)
                ->order('sort desc')
                ->page($data['limit'],10)
                ->select();
            foreach ($game as $key => $value) {
                $game[$key]['pic_link']='http://'.$_SERVER ['HTTP_HOST'].get_cover($value['icon'],'path');
                $play_num=play_num($value['id']);
                $game[$key]['play_num']=$play_num;
            }
        }
        echo base64_encode(json_encode($game));
    }
    //打开游戏
    public function open_game(){
        $data = json_decode(base64_decode(file_get_contents("php://input")),true);
        #判断数据是否为空
        if(empty($data)){$this->set_message(0,"fail","游戏数据不能为空");}
        $GameApi = new GameApi();
        $uid = $data['user_id'];
        if($uid==''){
            {$this->set_message(0,"fail","用户数据不能为空");}
        }
        $game_id = $data['game_id'];
        $user=M('User','tab_')->where(array('id'=>$uid))->find();
        $game=M('Game','tab_')->where(array('id'=>$game_id))->find();
        if(!$user||!$game){
            echo base64_encode(json_encode(array("status"=>0,"return_code"=>"fail","return_msg"=>"参数错误！")));exit();
        }
        $login_url='http://'.$_SERVER['HTTP_HOST']. "/media.php?s=/Game/open_game/game_id/".$game_id.".html";//app也要悬浮球
        echo base64_encode(json_encode(array('url'=>$login_url)));
    }
    //游戏详情
    public function game_detail(){
        $request=json_decode(file_get_contents("php://input"),true);
        if(empty($request)){
              echo json_encode(array("status"=>0,'msg'=>'数据不能为空'));exit();
        }
        $map['id']=$request['game_id'];
        $user_id=$request['user_id'];
        if($user_id){
            $user=M('User','tab_')
            ->field('collection')
            ->where(array('id'=>$user_id))
            ->find();
        }
        $game=M("game","tab_")
            ->field("icon,game_name,recommend_level,game_type_name,id,screenshot,introduction,screen_type")
            ->where($map)
            ->find();
        if(null!==$game){
            $game['screenshot']=$this->screenshots($game['screenshot']);
            $game['icon'] = "http://".$_SERVER['HTTP_HOST'].get_cover($game['icon'],"path");
            if(null!==$user){
                $cdad=substr($user['collection'],0,strlen($user['collection'])-1);
                $cdata=explode(',',$cdad);
                if(!in_array($request['game_id'],$cdata)){
                    $game['collection']=0;
                }else{
                    $game['collection']=1;
                }
            }else{
                $game['collection']=0;
            }
            $game['play_num']=play_num($request['game_id']);
            echo json_encode(array("status"=>1,"msg"=>'数据返回成功',"data"=>$game));
        }else{
              echo json_encode(array("status"=>0,'msg'=>'数据返回失败'));
        }
    }
   /**
    *游戏截图
    */
    protected function screenshots($str){
        $data = explode(',', $str);
        $screenshots = array();
        foreach ($data as $key => $value) {
            $screenshots[$key] = 'http://'.$_SERVER['HTTP_HOST']. get_cover($value,'path');
        }
        return $screenshots;
    }

    /*
     * 猜你喜欢（随机取出5条数据）
     */
    public function gsULike(){
        $game_list=get_game_list();
        $game_keys=array_rand($game_list,5);
        foreach ($game_keys as $val) {
            $game_like['id']=$game_list[$val]['id'];
            $game_like['icon']="http://".$_SERVER['HTTP_HOST'].get_cover($game_list[$val]['icon'],"path");
            $game_like['game_name']=$game_list[$val]['game_name'];
            $res[]=$game_like;
        }
        echo base64_encode(json_encode($res));
    }

    /**
     * 游戏搜索
     */
    public function game_search(){
        $request = json_decode(base64_decode(file_get_contents("php://input")), true);
        $game_name = $request['game_name'];
        $map['game_name'] = array('like',"%".$game_name."%");
        $map['game_status'] = 1;
        $page = $request['limit'] == "" ? 1 : $request['limit'];
        $data = M('game','tab_')->field('id,game_type_name,icon,game_name,introduction,screen_type')
            ->where($map)
            ->page($page,10)
            ->select();
        foreach ($data as $k=>$v) {
            $data[$k]['cover'] = "http://".$_SERVER['HTTP_HOST'].get_cover($v['icon'],"path");
            $data[$k]['play_num'] = play_num($v['id']);
        }
        echo base64_encode(json_encode($data));
    }

    /**
     * 开服
     */
    public function open_server(){
        $time=mktime(0,0,0,date("m"),date("d"),date("y"));
        $map1['tab_game.game_status']=1;
        $map1['tab_server.show_status']=1;
        $map1['start_time']=array('BETWEEN',array($time,$time+24*60*60-1));
        //今日开服
        $today=M('server','tab_')
            ->join('tab_game on tab_server.game_id=tab_game.id')
            ->where($map1)
            ->select();
        $map1['start_time']=array('gt',$time+24*60*60-1);
        //未来开服
        $future=M('server','tab_')
            ->join('tab_game on tab_server.game_id=tab_game.id')
            ->where($map1)
            ->select();
        foreach ($today as $k=>$v) {
            $today[$k]['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
            $today[$k]['icon'] = $this->set_game_icon($v['game_id']);
        }
        foreach ($future as $k=>$v) {
            $future[$k]['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
            $future[$k]['icon'] = $this->set_game_icon($v['game_id']);
        }
        $res['status'] = 1;
        $res['msg'] = "数据返回成功";
        $res['today'] = $today;
        $res['future'] = $future;
        echo base64_encode(json_encode($res));
    }

    /**
     * 获取活动信息
     */
    public function get_activity(){
        $request = json_decode(base64_decode(file_get_contents("php://input")),true);
        $map['category_id'] = 44;
        $map['deadline'] = array(array('eq',0),array('gt',time()), 'or');
        $map['status']=1;
        $map['page'] = $request['page'] ? $request['page'] : 1; //默认显示第一页数据
        $data = M('document')->where($map)->field('id,title,cover_id,description,create_time,create_time as start_time,deadline as end_time')->page($map['page'],10)->select();
        foreach ($data as $k=>$v) {
            $data[$k]['url'] = "http://".$_SERVER['HTTP_HOST']."/media.php/Subscriber/article/id/".$v['id'];
            $data[$k]['cover_id'] = "http://".$_SERVER['HTTP_HOST'].get_cover($v['cover_id'],"path");
            $data[$k]['create_time'] = date("Y-m-d H:i:s",$v['create_time']);           
        }
        echo base64_encode(json_encode($data));
    }
    /**
     * 游戏分类
     */
    public function get_game_type(){
        $request=json_decode(file_get_contents("php://input"),true);
        $game_type_id=$request['game_type_id'];
        $map['game_status']=1;
        $map['tab_game.game_type_id']=$game_type_id;
        $page=$request['page']==""?1:$request['page'];
        $game=M('game','tab_')
            ->field('tab_game.icon,tab_game.game_name,tab_game.id,tab_game.screen_type,tab_game.game_type_name,tab_game.features,tab_game.recommend_status')
            ->where($map)
            ->order('sort desc')
            ->page($page,10)
            ->select();
        foreach ($game as $key => $value) {
            $game[$key]['pic_link']='http://'.$_SERVER ['HTTP_HOST'].get_cover($value['icon'],'path');
            $play_num=play_num($value['id']);
            $game[$key]['play_num']=$play_num;
        }
        echo json_encode($game);
    }

    /**
     * 收藏
     */
    public function collect()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        $user_id = $request['user_id'];
        $game_id = $request['game_id'];
        $user = M('User', 'tab_');
        $map['id'] = $user_id;
        $cda = $user->where($map)->find();
        $cdad = substr($cda['collection'], 0, strlen($cda['collection']) - 1);
        $cdata = explode(',', $cdad);
        if (!in_array($game_id, $cdata)) {
            $execute = "update tab_user set collection = CONCAT(collection,'{$game_id}" . ",'" . ") WHERE id={$user_id}";
            $data = $user
                ->execute($execute);
            if ($data) {
                $this->ajaxReturn(array("status" => 1, "msg" => "恭喜！收藏成功~"));
            } else {
                $this->ajaxReturn(array("status" => 0, "msg" => ":( 收藏失败~"));
            }
        } else {
            $this->ajaxReturn(array("status" => 0, "msg" => "亲，您已收藏过~"));
        }
    }
}
