<?php

require_once 'connect.php';

/**
 * 获取目前记录数
 */
function getSingleOut(){
	$data=array();
	$firstkey=0;

	// $sql1="SELECT pay_order_number FROM tab_spend  LEFT JOIN tab_game_set ON tab_spend.game_id = tab_game_set.game_id WHERE pay_status = 1 AND pay_game_status = 0";

	$sql= "SELECT pay_order_number,1 as code,auto_compensation FROM tab_spend WHERE pay_status = 1 AND pay_game_status = 0 UNION SELECT pay_order_number,2 as code,auto_compensation FROM tab_bind_spend WHERE pay_status = 1 AND pay_game_status = 0";

	$conn=getMysqlConnection();
	$result=mysql_query($sql,$conn);
	if(!$result){
		$error="sql语句执行出错：".mysql_error();
		logError($error);
		die($error);
	}else{
		logError($sql,2);
	}
	if(mysql_num_rows($result)){
		while(!!($row=mysql_fetch_assoc($result))){
			$data[$firstkey++]=$row;
		}
	}
	return $data;
}

function game_pay_notify($pay_order_number=null,$code=1){
	header("Content-type: text/html; charset=utf-8");
	if($code==2){
        $sql= "SELECT * FROM tab_bind_spend WHERE pay_order_number = '{$pay_order_number}'";
    }else{
        $sql= "SELECT * FROM tab_spend WHERE pay_order_number = '{$pay_order_number}'";
    }
    $result=mysql_query($sql);
    $countr=mysql_num_rows($result);
    if($countr>1){
    	logError("存在多条".$pay_order_number."订单");
    	return false;
    }else if($countr<1){
    	logError("不存在".$pay_order_number."订单");
    	return false;
    }else{
    	$param=mysql_fetch_assoc($result);
    	if($param['pay_status']==0){
    		logError($pay_order_number."订单未支付");
    		return false;
    	}
    }
    $gamesetsql = "SELECT * FROM tab_game_set WHERE game_id = '{$param['game_id']}' LIMIT 1";
    $gamesetresule = mysql_query($gamesetsql);
    $game_data =  mysql_fetch_assoc($gamesetresule);

    $gamesql = "SELECT * FROM tab_game WHERE id = '{$param['game_id']}' LIMIT 1";
    $gameresule = mysql_query($gamesql);
    $game_info =  mysql_fetch_assoc($gameresule);
    if(empty($game_data)){ logError("未找到指定游戏数据"); return false;}
	if(empty($game_data['pay_notify_url'])){logError("未设置游戏支付通知地址"); return false;}
	if($game_info['developers']=='egret'||$game_info['developers']=='白鹭'){
		$data = array(
			"userId"       => $param['user_id'],
			"ext"     => $param['extend'],
			"orderId" => $param['pay_order_number'],
			"money"       => $param['pay_amount'],
			"time"   => time(),
		);
		ksort($data);
		$str = "";
		foreach($data as $key=>$value){
			$str .= $key ."=". $value;
		}
		$data["sign"] = md5($str.'哈哈');
	}else{
		$data = array(
			"channel_source"       => "vlcms",
			"trade_no"     => $param['extend'],
			"out_trade_no" => $param['pay_order_number'],
			"amount"       => $param['pay_amount'] * 100,
			"game_appid"   => $game_data['game_pay_appid'],
			// "key"		   => $game_data['game_key'],	
		);
		ksort($data);
		$data["sign"] = MD5(http_build_query($data).$game_data['game_key']);
	}
	$_payUrl = $game_data['pay_notify_url']."?".http_build_query($data);
	logError('通知URL'.$_payUrl,1);
	$result = false;
	// $result = get($_payUrl);
	return $result;
}

/**
*get提交数据
*/
function get($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
$data = getSingleOut();
$count = count($data);
if($count){
	foreach ($data as $key => $value) {
	 	$result = game_pay_notify($value['pay_order_number'],$value['code']);
	 	if($value['code']==1){
	 		$updatesql = "UPDATE tab_spend SET auto_compensation = auto_compensation+1 WHERE pay_order_number = '{$value['pay_order_number']}' ";
	 		mysql_query($updatesql);
	 	}else{
	 		$updatesql = "UPDATE tab_bind_spend SET auto_compensation = auto_compensation+1 WHERE pay_order_number = '{$value['pay_order_number']}' ";
	 		mysql_query($updatesql);
	 	}
	 	logError($updatesql,2);
	 	if($result['status']=='success'||$result['msg']=='success'||$result['code']=='1009'){
	 		if($value['code']==1){
		 		$updatesql = "UPDATE tab_spend SET pay_game_status = 1 WHERE pay_order_number = '{$value['pay_order_number']}' ";
		 		mysql_query($updatesql);
		 	}else{
		 		$updatesql = "UPDATE tab_bind_spend SET pay_game_status = 1 WHERE pay_order_number = '{$value['pay_order_number']}' ";
		 		mysql_query($updatesql);
		 	}
		 	logError($updatesql,2);
	 		logError($value['pay_order_number'].'补单成功',1);
	 	}else{
	 		logError($value['pay_order_number'].'补单失败',1);
	 	}
	}
}
logError("over\r\n\r\n\r\n",1);
exit('over');

?>