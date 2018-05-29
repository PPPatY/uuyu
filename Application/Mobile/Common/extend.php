<?php
/**
 * [判断用户是否登录]
 * @return int
 */
function is_login_user(){
    $user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
        return session('user_auth_sign') == data_auth_sign($user) ? $user['user_id'] : 0;
    }
}

function user_play($user_id = 0)
{
    $user_play = M("play","tab_user_");
    $map["user_id"] = $user_id;
    $data = $user_play->where($map)->find();
    if(empty($data)){
        return false;
    }
    return $data;
}

/**
*获取游戏实体
*/
function get_game_entity($data,$field="game_appid"){
    $game = M("Game","tab_");
    $map['status'] = 1;
    $map[$field]   = $data;
    $entity_data   = $game->where($map)->find();
    return $entity_data;
}

function get_uid()
{
    return session("user_auth.uid");
}

function get_uname(){
    return session("user_auth.account");
}
function index_show($param=array()){
    $paramcount=count($param);
    if($paramcount>0){
        $paramm[0][]=$param[0];
        $paramm[0][]=$param[1];
    }
    if($paramcount-2>0){
        $paramm[1][]=$param[2];
        $paramm[1][]=$param[3];
    }
    if($paramcount-4>0){
        $paramm[2][]=$param[4];
        $paramm[2][]=$param[5];
    }
    foreach ($paramm as $key => $value) {
        foreach ($value as $k => $v) {
            if($v==''){
                unset($paramm[$key][$k]);
            }
        }
    }
    return $paramm;
}