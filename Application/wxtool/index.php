<?php
ini_set('max_execution_time', '100');
$_GET['openid']='oUlPQ0Vxaq6cHcc6hgJO7En8NYgU';
if(isset($_GET['openid'])){
	require_once "WxHongbao.php";
	$hb = new \wxtool\WxHongbao();
	$params=array("nick_name"=>'测试红包',
			"send_name"=>'测试公众号',
			"re_openid"=>$_GET['openid'],
			"wishing"=>'感谢您对我们的支持，祝您健康快乐！',
			"act_name"=>'用户提现红包',
			"remark"=>'领取您的提现红包',
			"total_amount"=>100
	);
	$res =  $hb->send($params);
	var_dump($res);
}
?>