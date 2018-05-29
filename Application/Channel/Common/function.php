<?php
// +----------------------------------------------------------------------
// | 徐州梦创信息科技有限公司—专业的游戏运营，推广解决方案.
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.vlcms.com  All rights reserved.
// +----------------------------------------------------------------------
// | Author: kefu@vlcms.com QQ：97471547
// +----------------------------------------------------------------------

function think_md5($str, $key = 'ThinkUCenter'){
    return '' === $str ? '' : md5($str.$key);
}

function get_promote_entity($id){
	$map['id']=$id;				
	$data=M('promote','tab_')->where($map)->find();
	return $data;
}
function get_game_set_info($game_id = 0){
    $game = M('GameSet','tab_');
    $map['game_id'] = $game_id;
    $data = $game->where($map)->find();
    return $data;
}
//获取支付方式
function get_pay_way($id=null)
{
    if(!isset($id)){
        return false;
    }
    switch ($id) {
        case -1:
            return "绑定平台币";
            break;
        case 0:
            return "平台币";
            break;
        case 1:
            return "支付宝";
            break;
        case 2:
            return "微信（扫码）";
            break;
        case 3:
            return "微信APP";
            break;
        case 4:
            return "平台币";
            break;
        case 5:
            return "聚宝云";
            break;
        case 6:
            return "汇付宝";
            break;
        case 7:
            return "苹果支付";
            break;
    }
}