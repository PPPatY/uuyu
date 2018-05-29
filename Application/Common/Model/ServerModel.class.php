<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/3/28
 * Time: 9:03
 */
namespace Common\Model;

class ServerModel extends BaseModel{
	public function server($type=0,$p,$row=10,$user,$is_page=0){
		$page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $map1['tab_game.game_status']=1;
        $map1['tab_game.test_status']=1;
        $map1['tab_server.show_status']=1;
        $mm = strtolower(MODULE_NAME);
        if($mm!='media'&&$mm!='mediawide'){
            $mm = 'mobile';
        }
        switch ($type) {
            case '0'://已开新服
                $map1['tab_server.start_time'] = array('between',array(time()-60*60*24*7,time()));
                $opened=$this
                    ->field('tab_server.game_id,tab_game.game_name,tab_game.icon,tab_server.server_name,tab_server.start_time,game_type_id,tab_server.id as server_id')
                    ->join('tab_game on tab_server.game_id=tab_game.id')
                    ->where($map1)
                    ->page($page, $row)
                    ->order('start_time desc')
                    ->select();
                $count=$this
                    ->field('tab_server.game_id,tab_game.game_name,tab_game.icon,tab_server.server_name,tab_server.start_time,game_type_id')
                    ->join('tab_game on tab_server.game_id=tab_game.id')
                    ->where($map1)
                    ->count();
                foreach ($opened as $key => &$value) {
                    $past= $this->format_date($value['start_time']);
                    if($past['danwei']=='天'&&$past['num']>7){
                        unset($opened[$key]);
                    }else{
                        $value['pastTime'] = '已开服'.$past['num'].$past['danwei']; 
                    }
                    if (mb_strlen($value['server_name'],'utf-8')>5){
                        $value['server_name'] = mb_substr($value['server_name'],0,5,'utf-8').'...';
                    }

                    $value['icon'] = icon_url($value['icon']);
                    $value['play_detail_url']=U('Game/detail',array('game_id'=>$value['game_id']));
                    // $value['play_url']='http://' . $_SERVER['HTTP_HOST'] .U('Game/open_game',array('game_id'=>$value['game_id']));
                    $value['play_url']=get_http_url().$_SERVER['HTTP_HOST']."/".$mm.".php?s=Game/open_game/game_id/".$value['game_id'];
                    $value['game_type_name'] = get_game_type($value['game_type_id']);
                }
                break;
            case '1'://即将开服
                $time1 = time()+1;
                $time2 = (mktime(0,0,0,date("m"),date("d")+2,date("y")))-1;
                $map1['tab_server.start_time'] = array('BETWEEN',array($time1,$time2));
                $opened=$this
                    ->field('tab_server.id as server_id,tab_server.game_id,tab_game.game_name,tab_game.icon,tab_server.server_name,tab_server.start_time,open_notice,game_type_id')
                    ->join('tab_game on tab_server.game_id=tab_game.id')
                    ->where($map1)
                    ->page($page, $row)
                    ->order('start_time asc')
                    ->select();
                $count=$this
                    ->field('tab_server.id as server_id,tab_server.game_id,tab_game.game_name,tab_game.icon,tab_server.server_name,tab_server.start_time,open_notice,game_type_id')
                    ->join('tab_game on tab_server.game_id=tab_game.id')
                    ->where($map1)
                    ->count();
                foreach ($opened as $key => &$value) {
                    $s_time = date('Y-m-d',$value['start_time']);
                    $n_time = date('Y-m-d');
                    $t_time = date("Y-m-d",strtotime("+1 day"));
                    if($s_time==$n_time){
                        $value['start_date'] = '今日'.date('H:i',$value['start_time']); 
                    }elseif($s_time==$t_time){
                        $value['start_date'] = '明日'.date('H:i',$value['start_time']); 
                    }else{
                        $value['start_date'] = date('Y-m-d H:i'); 
                    }
										if (mb_strlen($value['server_name'],'utf-8')>5)
											$value['server_name'] = mb_substr($value['server_name'],0,5,'utf-8').'...';
                    $value['icon'] = icon_url($value['icon']);
                    $value['play_detail_url']=U('Game/detail',array('game_id'=>$value['game_id']));
                    // $value['play_url']='http://' . $_SERVER['HTTP_HOST'] .U('Game/open_game',array('game_id'=>$value['game_id']));
                    $value['play_url']=get_http_url().$_SERVER['HTTP_HOST']."/".$mm.".php?s=Game/open_game/game_id/".$value['game_id'];
                    $value['game_type_name'] = get_game_type($value['game_type_id']);
                    $value['isnotice'] = 0;
                    if($user){
                        $open_notice = $value['open_notice'];
                        $noticeuser = explode(',',$open_notice);
                        $value['isnotice'] = in_array($user, $noticeuser)?1:0;
                    }
                    unset($value['open_notice']);
                }
                break;
            case '2'://今日开服
                $time1 = mktime(0,0,0,date("m"),date("d"),date("y"));
                $time2 = (mktime(0,0,0,date("m"),date("d")+1,date("y")))-1;
                $map1['tab_server.start_time'] = array('BETWEEN',array($time1,$time2));
                $opened=$this
                    ->field('tab_server.id as server_id,tab_server.game_id,tab_game.game_name,tab_game.icon,tab_server.server_name,tab_server.start_time,open_notice,game_type_id')
                    ->join('tab_game on tab_server.game_id=tab_game.id')
                    ->where($map1)
                    ->page($page, $row)
                    ->order('start_time asc')
                    ->select();
                $count=$this
                    ->field('tab_server.id as server_id,tab_server.game_id,tab_game.game_name,tab_game.icon,tab_server.server_name,tab_server.start_time,open_notice,game_type_id')
                    ->join('tab_game on tab_server.game_id=tab_game.id')
                    ->where($map1)
                    ->count();
                foreach ($opened as $key => &$value) {
                    $s_time = date('Y-m-d',$value['start_time']);
                    $n_time = date('Y-m-d');
                    $t_time = date("Y-m-d",strtotime("+1 day"));
                    $value['start_date'] = '今日'.date('H:i',$value['start_time']); 
										if (mb_strlen($value['server_name'],'utf-8')>5)
											$value['server_name'] = mb_substr($value['server_name'],0,5,'utf-8').'...';
                    $value['icon'] = icon_url($value['icon']);
                    $value['play_detail_url']=U('Game/detail',array('game_id'=>$value['game_id']));
                    $value['play_url']=get_http_url().$_SERVER['HTTP_HOST']."/".$mm.".php?s=Game/open_game/game_id/".$value['game_id'];
                    $value['game_type_name'] = get_game_type($value['game_type_id']);
                    $value['isnotice'] = 0;
                    if($user){
                        $open_notice = $value['open_notice'];
                        $noticeuser = explode(',',$open_notice);
                        $value['isnotice'] = in_array($user, $noticeuser)?1:0;
                    }
                    unset($value['open_notice']);
                }
                break;
            default:
                return array('code'=>-1,'msg'=>'参数错误');
                break;
        }
        if($is_page){
            $opened['list'] = $opened;
            $opened['count'] = $count;
        }
        return $opened;
	}

    public function allserver($map=[],$p,$row=10){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $map['tab_game.game_status']=1;
        $map['tab_game.test_status']=1;
        $map['tab_server.show_status']=1;
        $opened=$this
                    ->field('tab_server.game_id,tab_game.game_name,tab_game.icon,tab_server.server_name,tab_server.start_time')
                    ->join('tab_game on tab_server.game_id=tab_game.id')
                    ->where($map1)
                    ->page($page, $row)
                    ->order('start_time desc')
                    ->select();
        $mm = strtolower(MODULE_NAME);
        if($mm!='media'&&$mm!='mediawide'){
            $mm = 'mobile';
        }
        foreach ($opened as $key => &$value) {
            $s_time = date('Y-m-d',$value['start_time']);
            $n_time = date('Y-m-d');
            $t_time = date("Y-m-d",strtotime("+1 day"));
            if($s_time==$n_time){
                $value['start_date'] = '今日'.date('H:i',$value['start_time']); 
            }elseif($s_time==$t_time){
                $value['start_date'] = '明日'.date('H:i',$value['start_time']); 
            }else{
                $value['start_date'] = date('Y-m-d'); 
            }
            if (mb_strlen($value['server_name'],'utf-8')>7) $value['server_name'] = mb_substr($value['server_name'],0,5,'utf-8').'...';
            $value['icon'] = icon_url($value['icon']);
            $value['play_detail_url']=U('Game/detail',array('game_id'=>$value['game_id']));
            // $value['play_url']='http://' . $_SERVER['HTTP_HOST'] .U('Game/open_game',array('game_id'=>$value['game_id']));
            $value['play_url']=get_http_url().$_SERVER['HTTP_HOST']."/".$mm.".php?s=Game/open_game/game_id/".$value['game_id'];
            $value['isnotice'] = 0;
            if($user){
                $open_notice = $value['open_notice'];
                $noticeuser = explode(',',$open_notice);
                $value['isnotice'] = in_array($user, $noticeuser)?1:0;
            }
            unset($value['open_notice']);
        }
        return $opened;
    }


    /**
     * [按照开服时间正序显示，还未开服的距离当前时间最近的时间显示在最上面（还未开服的区服）]
     * @param array $map
     * @param $p
     * @param int $row
     * @return mixed
     * @author 幽灵[syt]
     */
    public function serverOrder($map=[],$p,$row=10){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $map['tab_game.game_status']=1;
        $map['tab_game.test_status']=1;
        $map['tab_server.show_status']=1;
        $map['tab_server.start_time'] = array('egt',time());
        $opened1 = $this
            ->field('tab_server.game_id,tab_game.game_name,tab_game.icon,tab_server.server_name,tab_server.start_time')
            ->join('tab_game on tab_server.game_id=tab_game.id')
            ->where($map)
            ->limit(10)
            ->order('start_time asc')
            ->select();

        if (count($opened1) == 10){
            $opened = $opened1;
        }else{
            $map['tab_server.start_time'] = array('lt',time());
            $opened2 = $this
                ->field('tab_server.game_id,tab_game.game_name,tab_game.icon,tab_server.server_name,tab_server.start_time')
                ->join('tab_game on tab_server.game_id=tab_game.id')
                ->where($map)
                ->limit(10)
                ->order('start_time asc')
                ->select();
            $opened = array_merge($opened1,$opened2);
        }

        $opened = array_slice($opened,0,10);

        $mm = strtolower(MODULE_NAME);
        if($mm!='media'&&$mm!='mediawide'){
            $mm = 'mobile';
        }
        foreach ($opened as $key => &$value) {
            $s_time = date('Y-m-d',$value['start_time']);
            $n_time = date('Y-m-d');
            $t_time = date("Y-m-d",strtotime("+1 day"));
            if($s_time==$n_time){
                $value['start_date'] = '今日'.date('H:i',$value['start_time']);
            }elseif($s_time==$t_time){
                $value['start_date'] = '明日'.date('H:i',$value['start_time']);
            }else{
                $value['start_date'] = date('Y-m-d',$value['start_time']);
            }
            if (mb_strlen($value['server_name'],'utf-8')>7) $value['server_name'] = mb_substr($value['server_name'],0,5,'utf-8').'...';
            $value['icon'] = icon_url($value['icon']);
            $value['play_detail_url']=U('Game/detail',array('game_id'=>$value['game_id']));
            // $value['play_url']='http://' . $_SERVER['HTTP_HOST'] .U('Game/open_game',array('game_id'=>$value['game_id']));
            $value['play_url']=get_http_url().$_SERVER['HTTP_HOST']."/".$mm.".php?s=Game/open_game/game_id/".$value['game_id'];
            $value['isnotice'] = 0;
            if($user){
                $open_notice = $value['open_notice'];
                $noticeuser = explode(',',$open_notice);
                $value['isnotice'] = in_array($user, $noticeuser)?1:0;
            }
            unset($value['open_notice']);
        }
        return $opened;
    }

    private function format_date($time){
        $t=time()-$time;
        $f=array(
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
        );
        foreach ($f as $k=>$v)    {
            if (0 !=$c=floor($t/(int)$k)) {
                return array('num'=>$c,'danwei'=>$v);
            }
        }
    }
    /**
     * [set_server_notice description]
     * @param [type] $user   [description]
     * @param [type] $server [description]
     * @param [type] $type   [1设置通知  0取消通知]
     */
    public function set_server_notice($user,$server,$type){
        $data = M('Server','tab_')->field('open_notice')->where(array('id'=>$server))->find();
        $open_notice = $data['open_notice'];
        $noticeuser = $open_notice!=''?explode(',', $open_notice):array();
        $noticeuser = array_unique($noticeuser);
        $key = array_search($user,$noticeuser);
        if($type==1){
            if($key === false){
                $noticeuser[] = $user;
            }
        }else{
            if($key !== false){
                unset($noticeuser[$key]);
            }
        }
        array_multisort($noticeuser,SORT_ASC,SORT_NUMERIC);
        $noticeuser = implode(',',$noticeuser);
        $res = M('Server','tab_')->where(array('id'=>$server))->setField('open_notice',$noticeuser);
        return $res;
    }

    public function send_server_notice($user){
        $map['open_notice'] = array('like','%'.$user.'%');
        $data = M('Server','tab_')->field('id,server_name,game_name,game_id,start_time,open_notice')->where($map)->select();
        if(empty($data)){
            return 1;
        }else{
            foreach ($data as $key => $value) {
                $userarr = explode(',',$value['open_notice']);
                foreach ($userarr as $k => $v) {
                    if($v!=$user){
                        unset($v);
                    }else{
                        $notice[$value['id']]['game_name'] = $value['game_name'];
                        $notice[$value['id']]['game_id'] = $value['game_id'];
                        $notice[$value['id']]['start_time'] = $value['start_time'];
                        $notice[$value['id']]['server_id'] = $value['id'];
                        $notice[$value['id']]['server_name'] = $value['server_name'];
                    }
                }
            }
        }
        

        foreach ($notice as $k1 => $v1) {
            $servermap['game_id'] = $v1['game_id'];
            $servermap['server_id'] = $v1['server_id'];
            $servermap['user_id'] = $user;
            $msg = M('Msg','tab_')->field('id')->where($servermap)->find();
            $time = time()-$v1['start_time'];
            if(empty($msg)){
                if($time>-1800){
                    $add['user_id'] = $user; 
                    $add['game_id'] = $v1['game_id']; 
                    $add['server_id'] = $v1['server_id']; 
                    $add['type'] = 3; 
                    $add['status'] = 2; 
                    $add['create_time'] = time();
                    $add['content'] = '您预约的游戏《'.$v1['game_name'].'》- '.$v1['server_name'].' '.date('m月d日 H时i分',$v1['start_time']).'即将开服，请注意关注游戏动向。';
                    M('Msg','tab_')->add($add);
                }
            }else{
                if($time>0){
                    $save['id'] = $msg['id'];
                    $save['type'] = 4; 
                    $save['content'] = '您预约的游戏《'.$v1['game_name'].'》- '.$v1['server_name'].'已开服，赶紧去玩吧。';
                    M('Msg','tab_')->save($save);
                }
            }
        }
    }    
}