<?php
require_once "write.php";
/**
 *connect.php
 *--
 *by:集成显卡  1053214511@qq.com
 *2011-8-21--下午12:15:20
 */
header("Content-Type:text/html;charset=UTF-8");
//定义一组用于链接的参数
define("MYSQL_SERVER", "");//连接地址
define("MYSQL_USER", "");//数据库用户名
define("MYSQL_PASSWORD", "");//数据库密码
define("MYSQL_DATABASE", "");//要链接并使用的数据库名
define("MYSQL_ENCODE", "UTF8");

/**
 * 获取一个mysql的连接 ，返回这个连接，使用的是默认的设置
 */
function getMysqlConnection(){
	//链接数据库
	$conn=@mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASSWORD);
	if(!$conn){
		$error="链接mysql数据库失败。错误信息：".mysql_error();
		logError($error);
		die($error);
	}
	$dbhost=@mysql_select_db(MYSQL_DATABASE,$conn);
	if(!$dbhost){
		$error="无法找到指定的数据库：".MYSQL_DATABASE." 错误信息：".mysql_error();
		logError($error);
		die ($error);
	}
	$encode=mysql_query('SET NAMES '.MYSQL_ENCODE,$conn);
	if(!$encode){
		$error='字符集设置错误'.mysql_error();
		logError($error);
		die($error);
	}
	logError("数据库连接成功！",2);
	return $conn;
}
?>