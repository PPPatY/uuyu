<?php

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function check_verify($code, $id = 1){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}
/**
 * [get_createtime 根据ID获取时间]
 * @param  [type] $uid [ID]
 * @return [type]      [时间]
 */
function get_createtime($uid){
	$register_time = M('User',"tab_")->field('register_time')->where(array('id'=>$uid))->find();
	return  $register_time['register_time'];
}
/**
 * [get_id 根据时间获取ID]
 * @param  [type] $uid [description]
 * @return [type]      [description]
 */
function get_id($register_time){
	$register_time = M('User',"tab_")->field('id')->where(array('register_time'=>$register_time))->find();
	return  $register_time['id'];
}
/**
 * [get_spend_count 获取充值人数]
 * @param  [type] $game_name       [游戏名称]
 * @param  [type] $promote_account [推广账号]
 * @return [string]                  [description]
 */
function get_spend_count($game_name=null,$promote_account=null,$time=null){
	if(null != $game_name){
	$map['game_name']=$game_name;
	}
	if(null != $promote_account){
	$map['promote_account']=$promote_account;
	}
	if(null != $time){
		$begtime = strtotime(date('Y-m-d',$time));
	$map['pay_time']=array('between',array($begtime,$begtime + 24*60*60));
	}
	$count = M('Spend',"tab_")->field('distinct user_nickname')->where($map)->select();
	return  count($count);
}
/**
 * [get_play_count 获取注册总次数]
 * @param  [type] $game_name       [游戏名称]
 * @param  [type] $promote_account [推广账号]
 * @return [string]                  [description]
 */
function get_play_count($game_name=null,$promote_account=null,$time=null){
	if(null != $game_name){
	$map['game_name']=$game_name;
	}
	if(null != $game_name){
	$map['promote_account']=$promote_account;
	}
	if(null != $time){
		$begtime = strtotime(date('Y-m-d',$time));
	$map['play_time']=array('between',array($begtime,$begtime + 24*60*60));
	}
	$count = M('User_play',"tab_")->field('count(id) as count')->where($map)->find();
	return  intval($count['count']);
}
/**
 * [get_spend_num 获取充值总金额]
 * @param  [type] $game_name       [游戏名称]
 * @param  [type] $promote_account [推广账号]
 * @return [string]                [description]
 */
function get_spend_num($game_name=null,$promote_account=null,$time){
	if(null != $game_name){
	$map['game_name']=$game_name;
	}
	if(null != $game_name){
	$map['promote_account']=$promote_account;
	}
	if(null != $time){
		$begtime = strtotime(date('Y-m-d',$time));
	$map['pay_time']=array('between',array($begtime,$begtime + 24*60*60));
	}

	$count = M('Spend',"tab_")->field('sum(pay_amount) as pay_amount')->where($map)->find();
	return  $count['pay_amount']? $count['pay_amount']:0;
}
	/**
     * [str_arr 将以逗号隔开的字符串 转成一维数组]
     * @param  [str] $str [要进行转换的字符串]
     * @return [array]    [数组]
     */
function str_to_arr($str){
        $arr = explode(',',$str);
        foreach ($arr as $key => $value) {
            if($value == '' || $value == ' '){
                unset($arr[$key]);
            }
        }
        return $arr;
    }

/**
 * [show_promote 查询所有的推广账号ID]
 * @param  [str] $account [商务专员账号]
 * @return [str]          [推广账号ID]
 */
 function show_promote_allid($account){
    if(!empty($account)){
        $map['account']='asdasd';
    }
    $promote_id = M('Commissioner','tab_')->field('promote_id')->where($map)->find();  
    $promote_id = $promote_id['promote_id'];
    if(empty($promote_id)){
            $promote_id= '0';
    }
    return $promote_id;
}

function get_promote_list($select='') {
    $list = M("Promote","tab_")->field('id,account,balance_coin')->where("status=1")->select();
    if (empty($list)){return '';}
    if($select==111){
        $new['id']=-1;
        $new['account']="全站用户";
        array_unshift($list,$new);
        }
    return $list;
}

/**
*根据商务专员id 查找推广员
*/
function get_busier_promote_list($ba_id='') {
    $list = M("Promote","tab_")->field('id,account,balance_coin')->where("status=1 and busier_id=".$ba_id)->select();
    if (empty($list)){return '';}
    if($select==111){
        $new['id']=-1;
        $new['account']="全站用户";
        array_unshift($list,$new);
        }
    return $list;
}