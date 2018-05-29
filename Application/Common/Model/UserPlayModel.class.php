<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/3/28
 * Time: 9:03
 */
namespace Common\Model;

class UserPlayModel extends BaseModel{

	/**
	 * 游戏列表
	 * @param string $map
	 * @param string $order
	 * @param int $p
	 * @return mixed
	 * author: xmy 280564871@qq.com
	 */
	public function getUserPlay($map="",$order="u.play_time desc",$p=1,$row=10){
	    $page = $p ? $p :1;
		$map['g.game_status'] = 1;
		$map['g.test_status'] = 1;
		$data=M('User_play as u','tab_')
            ->field('g.id,g.game_name,icon,bind_balance')
            ->join('tab_game g on u.game_id = g.id ')
            ->where($map)
            ->order($order)
            ->page($page,$row)
            ->select();
        $mm = strtolower(MODULE_NAME);
		if($mm!='media'&&$mm!='mediawide'){
			$mm = 'mobile';
		}
        foreach ($data as $key => $val){
			$data[$key]['icon'] = icon_url($val['icon']);
			if(strtolower(MODULE_NAME)=='app'){
//				$data[$key]['play_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$val['id'];
				$data[$key]['play_url']=get_http_url() . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$val['id'];
				$data[$key]['play_detail_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/detail/game_id/".$val['id'];
			}else{
				$data[$key]['play_url']=get_http_url() . $_SERVER['HTTP_HOST']."/".$mm.".php?s=Game/open_game/game_id/".$val['id'];
				$data[$key]['play_detail_url']=U('Game/detail',array('game_id'=>$val['id']));
			}
		}
		return $data;
	}
	
    
	public function getPlayList($map="",$order="u.play_time desc",$p=1,$row=10){
	    $page = $p ? $p :1;
	    $map['g.game_status'] = 1;
	    $map['g.test_status'] = 1;
	    $data=M('User_play as u','tab_')
	    ->field('g.id,g.game_name,icon,bind_balance')
	    ->join('tab_game g on u.game_id = g.id ')
	    ->where($map)
	    ->order($order)
	    ->page($page,$row)
	    ->select();
	    $count = M('User_play as u','tab_')
	    ->field('g.id,g.game_name,icon,bind_balance')
	    ->join('tab_game g on u.game_id = g.id ')
	    ->where($map)
	    ->count();
	    $mm = strtolower(MODULE_NAME);
		if($mm!='media'&&$mm!='mediawide'){
			$mm = 'mobile';
		}
	    foreach ($data as $key => $val){
	        $data[$key]['icon'] = icon_url($val['icon']);
	        if(strtolower(MODULE_NAME)=='app'){
	            //				$data[$key]['play_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$val['id'];
	            $data[$key]['play_url']=get_http_url() . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$val['id'];
	            $data[$key]['play_detail_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/detail/game_id/".$val['id'];
	        }else{
	            $data[$key]['play_url']=get_http_url() . $_SERVER['HTTP_HOST']."/".$mm.".php?s=Game/open_game/game_id/".$val['id'];
	            $data[$key]['play_detail_url']=U('Game/detail',array('game_id'=>$val['id']));
	        }
	    }
	    $datas['data'] = $data;
	    $datas['count'] = $count;
	    return $datas;
	}

}