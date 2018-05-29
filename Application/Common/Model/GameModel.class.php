<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/3/28
 * Time: 9:03
 */
namespace Common\Model;

class GameModel extends BaseModel{

	/**
	 * 游戏列表
	 * @param string $map
	 * @param string $order
	 * @param int $p
	 * @return mixed
	 * author: xmy 280564871@qq.com
	 */
	public function getGameLists($map="",$order="g.sort desc,g.id desc",$p=0,$row=10,$modul='Mobile',$user_id=''){
		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据
		$map['g.game_status'] = 1;
		$map['g.test_status'] = 1;
		$data = $this->table('tab_game as g')
			->field('g.icon,g.cover,g.game_name,g.id,g.game_type_id,g.features,ifnull(gb.id,0) as gift_id,g.play_count,gb.is_unicode,gb.novice,gb.unicode_num,g.game_score')
			->join('tab_giftbag as gb on gb.game_id = g.id and gb.status = 1','left')
			->where($map)
			->page($page, $row)
			->order($order)
			->group("g.id")
			->select();
		$mm = strtolower(MODULE_NAME);
		if($mm!='media'&&$mm!='mediawide'){
			$mm = 'mobile';
		}
		foreach ($data as $key => $val){
			$y = $val['novice'];
            if(empty($y)||($val['is_unicode']==1&&$val['unicode_num']<=0)) {
            	$data[$key]['gift_id'] = "0";
            }
        	unset($data[$key]['novice']);
			if (mb_strlen($val['game_name'],'utf-8')>8&&strtolower(MODULE_NAME)!='app')
				$data[$key]['game_name'] = mb_substr($val['game_name'],0,8,'utf-8').'...';
					
			$data[$key]['icon'] = icon_url($val['icon']);

			$gametypename = get_game_type($val['game_type_id']);
			if (mb_strlen($gametypename,'utf-8')>5&&strtolower(MODULE_NAME)!='app')
				$data[$key]['game_type_name'] = mb_substr($gametypename,0,5,'utf-8').'...';
			else
				$data[$key]['game_type_name'] = $gametypename;
			
			$data[$key]['cover'] = icon_url($val['cover']);
            $data[$key]['real_game_score'] = $val['game_score'];
			$data[$key]['game_score'] = round($val['game_score'] / 2);
			$data[$key]['play_num']=play_num($val['id']);
			$data[$key]['play_detail_url']=U('Game/detail',array('game_id'=>$val['id']));
			$data[$key]['collect_num']=collect_num($val['id']);
//			$data[$key]['play_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$val['id'];
     		$data[$key]['play_url']=get_http_url().$_SERVER['HTTP_HOST']."/".$mm.".php?s=Game/open_game/game_id/".$val['id'];
            // $data[$key]['game_gift'] = M("Giftbag","tab_")->where(array('game_id'=>$data[$key]['id']))->find()?1:0;
            if (!empty($user_id)){
                $data[$key]['collect_status'] = $this->game_is_collect($data[$key]['id'],$user_id);
            }
		}
		return $data;
	}
	public function getGameListsCounts($map="",$order="g.sort desc,g.id desc",$p=0,$row=10,$modul='Mobile',$user_id=''){
		$map['g.game_status'] = 1;
		$map['g.test_status'] = 1;
		$data = $this->table('tab_game as g')
			->field('g.id')
			->join('tab_giftbag as gb on gb.game_id = g.id and gb.status = 1','left')
			->where($map)
			// ->page($page, $row)
			// ->order($order)
			->group("g.id")
			->select();
		return count($data);
	}

	public function getHotGame($map="",$order="g.sort desc",$limit=10,$user_id=''){

        $map['g.game_status'] = 1;
        $map['g.test_status'] = 1;
        $data = $this->table('tab_game as g')
            ->field('g.icon,g.cover,g.game_name,g.id,g.game_type_id,g.features,ifnull(gb.id,0) as gift_id')
            ->join('tab_giftbag as gb on gb.game_id = g.id and gb.status = 1','left')
            ->where($map)
            ->limit($limit)
            ->order($order)
            ->group("g.id")
            ->select();
        $mm = strtolower(MODULE_NAME);
		if($mm!='media'&&$mm!='mediawide'){
			$mm = 'mobile';
		}
        foreach ($data as $key => $val){
            $data[$key]['icon'] = icon_url($val['icon']);
            $data[$key]['game_type_name'] = get_game_type($val['game_type_id']);
            $data[$key]['cover'] = icon_url($val['cover']);
            $data[$key]['game_score'] = round($val['game_score'] / 2);
            $data[$key]['play_num']=play_num($val['id']);
            $data[$key]['play_detail_url']=U('Game/detail',array('game_id'=>$val['id']));
            $data[$key]['collect_num']=collect_num($val['id']);
            //$data[$key]['play_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$val['id'];
			$data[$key]['play_url']=get_http_url().$_SERVER['HTTP_HOST']."/".$mm.".php?s=Game/open_game/game_id/".$val['id'];
            $data[$key]['game_gift'] = M("Giftbag","tab_")->where(array('game_id'=>$data[$key]['id']))->find()?1:0;
            if (!empty($user_id)){
                $data[$key]['collect_status'] = $this->game_is_collect($data[$key]['id'],$user_id);
            }
        }
        return $data;
    }

	public function game_is_collect($game_id,$user_id){
	    $map['g.id'] = $game_id;
        $map['g.game_status'] = 1;
        $map['g.test_status'] = 1;
        $map['b.user_id'] = $user_id;
        $map['b.status'] = 1;
        $data = $this->alias('g')
            ->field('g.icon,g.game_name,g.id,g.game_type_id,g.features,g.screenshot,g.introduction,ifnull(b.status,0) as collect_status')
            ->join("tab_user_behavior as b on b.game_id = g.id and b.status = 1",'left')
            ->where($map)
            ->find();
        if ($data){
            return 1;
        }else{
            return 0;
        }
    }

	public function getGroupLists($map){
		$map['g.game_status'] = 1;
		$map['g.test_status'] = 1;
		$map['gt.status'] = 1;
		$jointable = "(SELECT * FROM tab_game ORDER BY sort DESC ,id desc)  as g on g.game_type_id = gt.id";
		$data = M('GameType as gt','tab_')
			->field('count(g.id) as counts,gt.type_name,gt.id as type_id,g.game_name,g.icon,gt.create_time')
			->join($jointable)
			->where($map)
			->group("gt.id")
			->order('counts desc,create_time desc')
			->select();
		foreach ($data as $key => $val){
			$data[$key]['icon'] = icon_url($val['icon']);
		}
		$allgame = $this->alias('g')
					->join('tab_game_type as gt on g.game_type_id = gt.id')
					->where($map)
					->group('g.id')
					->select();
		if(!$allgame){
			return false;
		}
		$dd['all'] = count($allgame);
		$dd['group'] = $data;
		return $dd;
	}

	public function gameDetail($game_id,$user_id=0){
		$map['g.id'] = $game_id;
		$map['g.game_status'] = 1;
		$map['g.test_status'] = 1;
		$data = $this->alias('g')
				->field('g.icon,g.game_name,g.id,g.game_type_id,g.features,g.screenshot,g.introduction,ifnull(b.status,0) as collect_status')
				->join("tab_user_behavior as b on b.game_id = g.id and b.user_id = {$user_id} and b.status = 1",'left')
				->where($map)
				->find();
		$mm = strtolower(MODULE_NAME);
		if($mm!='media'&&$mm!='mediawide'){
			$mm = 'mobile';
		}
		if(empty($data)){
			return false;
		}elseif(ACTION_NAME!='open_game'){
			$data['icon'] = icon_url($data['icon']);
			
			$gametypename = get_game_type($data['game_type_id']);
			if (mb_strlen($gametypename,'utf-8')>5&&strtolower(MODULE_NAME)!='app')
				$data['game_type_name'] = '（'.mb_substr($gametypename,0,5,'utf-8').'...';
			else
				$data['game_type_name'] = ''.$gametypename.'';
			$data['screenshots'] = $this->screenshots($data['screenshot']);
			$data['play_num']=play_num($data['id']);
			$data['collect_num']=collect_num($data['id']);
//			$data['play_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$data['id'];
			$data['play_url']=get_http_url() . $_SERVER['HTTP_HOST']."/".$mm.".php?s=Game/open_game/game_id/".$data['id'];
			return $data;
		}else{
			return $data;
		}
	}
	public function searchgame($map){
		$order = 'g.sort desc';
		$map['g.game_status'] = 1;
		$map['g.test_status'] = 1;
		$data = $this->table('tab_game as g')
			->field('g.icon,g.cover,g.game_name,g.id,g.game_type_id,g.features')
			->where($map)
//			->page($page, $row)
			->order($order)
			->group("g.id")
			->select();
		$mm = strtolower(MODULE_NAME);
		if($mm!='media'&&$mm!='mediawide'){
			$mm = 'mobile';
		}
		foreach ($data as $key => $val){
			$data[$key]['icon'] = icon_url($val['icon']);
			$data[$key]['game_type_name'] = get_game_type($val['game_type_id']);
			$data[$key]['cover'] = icon_url($val['cover']);
			$data[$key]['game_score'] = round($val['game_score'] / 2);
			$data[$key]['play_num']=play_num($val['id']);
			$data[$key]['play_detail_url']=U('Game/detail',array('game_id'=>$val['id']));
//			$data[$key]['play_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$val['id'];
			$data[$key]['play_url']=get_http_url() . $_SERVER['HTTP_HOST']."/".$mm.".php?s=Game/open_game/game_id/".$val['id'];
		}
		return $data;
	}
	/**
	 * [myGameCollect 用户收藏行为]
	 * @param  [type] $user [description]
	 * @param  [type] $type [-1取消收藏，1已收藏]
	 * @param  [type] $is_page [返回分页数据 0否，1是]
	 * @return [type]       [description]
	 */
	public function myGameCollect($user,$type,$p,$row=10,$is_page=0){
		$map['g.game_status'] = 1;
		$map['g.test_status'] = 1;
		$map['b.status'] = $type;
        $page = $p ? $p :1;
        // $row = 10;
		if($type==1){
			$data = $this->table('tab_user_behavior as b')
						->field('b.id as bid,g.icon,g.game_name,g.id,g.game_type_id,g.features,g.screenshot,g.introduction,ifnull(b.status,0) as collect_status')
						->join('tab_game g on g.id = b.game_id and b.user_id = '.$user)
						->where($map)
						->group("g.id")
						->order('b.create_time desc')
                        ->page($page,$row)
						->select();

			if($is_page){
			    $count = count($this->table('tab_user_behavior as b')
						->field('b.id as bid,g.icon,g.game_name,g.id,g.game_type_id,g.features,g.screenshot,g.introduction,ifnull(b.status,0) as collect_status')
						->join('tab_game g on g.id = b.game_id and b.user_id = '.$user)
						->where($map)
						->group("g.id")
						->select());
			}
		}
		foreach ($data as $key => $val){
			$data[$key]['icon'] = icon_url($val['icon']);
			
			$gametypename = get_game_type($val['game_type_id']);
			if (mb_strlen($gametypename,'utf-8')>5&&strtolower(MODULE_NAME)!='app')
				$data[$key]['game_type_name'] = mb_substr($gametypename,0,5,'utf-8').'...';
			else
				$data[$key]['game_type_name'] = $gametypename;
			
			
            $data[$key]['game_type_count'] = M('Game','tab_')->where(array('game_type_id'=>$data[$key]['game_type_id'],'game_status'=>1,'test_status'=>1))->count();
			$data[$key]['cover'] = icon_url($val['cover']);
			$data[$key]['play_num']=play_num($val['id']);
			// $data[$key]['play_detail_url']=U('Game/detail',array('game_id'=>$val['id']));
			// $data[$key]['play_url']='http://' . $_SERVER['HTTP_HOST'] .U('Game/open_game',array('game_id'=>$val['id']));
			if(strtolower(MODULE_NAME)=='app'){
//				$data[$key]['play_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$val['id'];
				$data[$key]['play_url']=get_http_url() . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$val['id'];
				$data[$key]['play_detail_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/detail/game_id/".$val['id'];
			}else{
//				$data[$key]['play_url']='http://' . $_SERVER['HTTP_HOST'] .U('Game/open_game',array('game_id'=>$val['id']));
				$data[$key]['play_url']=get_http_url() . $_SERVER['HTTP_HOST'] .U('Game/open_game',array('game_id'=>$val['id']));
				$data[$key]['play_detail_url']=U('Game/detail',array('game_id'=>$val['id']));
			}
		}
		if($is_page){
		    $data['list'] = $data;
		    $data['count'] = $count;
		}
		return $data;
	}
	public function myGameFoot($user,$type,$p,$row=10,$is_page=0){
		$map['g.game_status'] = 1;
		$map['g.test_status'] = 1;
		$map['b.status'] = $type;
        $page = $p ? $p :1;
        // $row = 10;
		if($type==2){
			$data = $this->table('tab_user_behavior as b')
						->field('b.id as bid,g.icon,g.game_name,g.id,g.game_type_id,g.features,g.screenshot,g.introduction,ifnull(b.status,0) as collect_status,date_format(FROM_UNIXTIME(b.create_time),"%m月%d日") AS date')
						->join('tab_game g on g.id = b.game_id and b.user_id = '.$user)
						->where($map)
						->group("g.id,date")
						->order('b.update_time desc')
                        ->page($page,$row)
						->select();
			if($is_page){
			    $count = count($this->table('tab_user_behavior as b')
			    ->field('b.id as bid,g.icon,g.game_name,g.id,g.game_type_id,g.features,g.screenshot,g.introduction,ifnull(b.status,0) as collect_status,date_format(FROM_UNIXTIME(b.create_time),"%m月%d日") AS date')
			    ->join('tab_game g on g.id = b.game_id and b.user_id = '.$user)
			    ->where($map)
			    ->group("g.id,date")
			    ->select());
			}
		}
		foreach ($data as $key => $value) {
			$value['icon'] = icon_url($value['icon']);
			$value['game_type_name'] = get_game_type($value['game_type_id']);
            $data[$key]['game_type_count'] = M('Game','tab_')->where(array('game_type_id'=>$data[$key]['game_type_id'],'game_status'=>1,'test_status'=>1))->count();
			$value['cover'] = icon_url($value['cover']);
			$value['play_num']=play_num($value['id']);
			// $value['play_detail_url']=U('Game/detail',array('game_id'=>$value['id']));
			// $value['play_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$value['id'];
			if(strtolower(MODULE_NAME)=='app'){
//				$value['play_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$value['id'];
				$value['play_url']=get_http_url() . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/open_game/game_id/".$value['id'];
				$value['play_detail_url']='http://' . $_SERVER['HTTP_HOST']."/mobile.php?s=Game/detail/game_id/".$value['id'];
			}else{
				$value['play_url']=get_http_url() . $_SERVER['HTTP_HOST'] .U('Game/open_game',array('game_id'=>$value['id']));
				$value['play_detail_url']=U('Game/detail',array('game_id'=>$value['id']));
			}
			$newdata[$value['date']][]=$value;
		}
		if($is_page){
		    $newdata['list'] = $newdata;
		    $newdata['count'] = $count;
		}
		return $newdata;
	}
	public function optionBehavior($user,$type,$map){
		if($type==1){
			$map['status'] = 1;
			$map['user_id'] = $user;
			$data['status'] = -1;
			$data['update_time'] = time();
			$res = M('user_behavior','tab_')
						->where($map)
						->save($data);
			return $res;
		}elseif($type==2){
			$map['status'] = 2;
			$map['user_id'] = $user;
			$data['status'] = -2;
			$data['update_time'] = time();
			$res = M('user_behavior','tab_')
						->where($map)
						->save($data);
			return $res;
		}

	}
	/**
     * 游戏截图
     * @param  string $str 游戏截图ID
     * @return 游戏截图的URL
     * @author lyf
     */
    protected function screenshots($str){
        $data = explode(',', $str);
        $screenshots = array();
        foreach ($data as $key => $value) {
            $screenshots[$key] = icon_url($value);
        }
        return $screenshots;
    }

}