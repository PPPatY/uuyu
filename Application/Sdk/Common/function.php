<?php
// +----------------------------------------------------------------------
// | 徐州梦创信息科技有限公司—专业的游戏运营，推广解决方案.
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.vlcms.com  All rights reserved.
// +----------------------------------------------------------------------
// | Author: kefu@vlcms.com QQ：97471547
// +----------------------------------------------------------------------

/*
*获取游戏设置信息
*/
function get_game_set_info($game_id = 0){
	$game = M('game','tab_');
	$map['game_id'] = $game_id;
	$data = $game->where($map)->find();
	return $data;
}