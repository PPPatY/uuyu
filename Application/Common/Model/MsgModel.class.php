<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/3/28
 * Time: 9:03
 */
namespace Common\Model;

class MsgModel extends BaseModel{

	public function optionMsg($map){
		$data['status'] = '1';
		$data['read_time'] = time();
		$res=$this->where($map)->save($data);
	}

	public function getMsglist($map="",$order="create_time desc,id desc",$p=1,$row=10){
	    $page = $p?$p:1;
		$data=$this
			->field('tab_msg.*,tab_game.icon')
            ->where($map)
            ->join('tab_game on tab_game.id = tab_msg.game_id','left')
            ->order($order)
            ->page($page,$row)
            ->select();
        foreach ($data as $key => $val){
			$data[$key]['icon'] = icon_url($val['icon']);
		}
		return $data;
	}

}