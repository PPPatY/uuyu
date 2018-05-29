<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/5/9
 * Time: 17:54
 */

namespace Common\Model;

class CollectionGameModel extends BaseModel {


	public function getDataList($map,$order="c.create_time desc"){
		$data = $this->alias("c")
			->field("g.screen_type,c.id,c.game_id,g.game_name,g.game_type_name,g.game_type_id,g.icon")
			->where($map)
			->join("tab_game g on g.id = c.game_id")
			->order($order)
			->select();
		foreach ($data as $key=>$val){
			$data[$key]['play_num'] = play_num($val['game_id']);
			$data[$key]['icon'] = 'http://'.$_SERVER ['HTTP_HOST'].get_cover($val['icon'],"path");
		}
		return $data;
	}

	/**
	 * 删除收藏
	 * @param $ids
	 * @param $user_id
	 * @return mixed
	 * author: xmy 280564871@qq.com
	 */
	public function delCollect($ids,$user_id){
		$map['game_id'] = ['in',$ids];
		$map['user_id'] = $user_id;
		return $this->where($map)->delete();
	}


	/**
	 * 收藏游戏
	 * @param $game_id
	 * @param $user_id
	 * @return bool|mixed
	 * author: xmy 280564871@qq.com
	 */
	public function collectGame($game_id,$user_id){
		$map['user_id'] = $user_id;
		$map['game_id'] = $game_id;
		$res = $this->where($map)->find();
		if(empty($res)){
			$data = $map;
			$data['create_time'] = time();
			return $this->add($data);
		}
		return false;
	}


	/**
	 * 检查是否已经收藏该游戏
	 * @param $game_id
	 * @param $user_id
	 * @return bool
	 * author: xmy 280564871@qq.com
	 */
	public function isCollect($game_id,$user_id){
		$map['user_id'] = $user_id;
		$map['game_id'] = $game_id;
		$data = $this->where($map)->find();
		if(empty($data)){
			return false;
		}
		return true;

	}

	/**
	 * 获取收藏数
	 * @param $game_id
	 * @return mixed
	 * author: xmy 280564871@qq.com
	 */
	public function getCollectNum($game_id){
		$map['game_id'] = $game_id;
		$num = $this->where($map)->count();
		return $num;
	}
}