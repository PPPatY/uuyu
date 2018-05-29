<?php
date_default_timezone_set('PRC');
header("Content-type: text/html; charset=utf-8");
/**
 * [logError description]
 * @param  [type]  $content [内容 字符串]
 * @param  integer $level   [错误等级 0:error 1:info 2:sql]
 * @return [type]           [description]
 */
function logError($content,$level='') 
{ 
  // var_dump($_SERVER['REQUEST_URI']);exit;
  $logfile = 'logs/'.date('Y-m-d').'.log'; 
  if(!file_exists(dirname($logfile))) 
  { 
    mkdir(dirname($logfile)); 
  } 
  if($level==1){
  	$leveldetail='INFO';
  }else if($level==2){
  	$leveldetail='SQL';
  }else{
  	$leveldetail='ERROR';
  }
  error_log(date("[Y-m-d H:i:s]")."\r\n".$leveldetail.": ".$content."\r\n", 3,$logfile); 
}
?>