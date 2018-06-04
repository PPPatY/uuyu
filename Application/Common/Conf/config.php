<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 系统配文件
 * 所有系统级别的配置
 */
define('UC_AUTH_KEY', 'UmtW6-Z(S^8xvwDn;B:J{X7FG9z2+Np.|C#~QRY"'); //加密KEY
$route = include(CONF_PATH."route.php");
$site_route = include(CONF_PATH."site_route.php");
$cache = include(CONF_PATH."pay_config.php");
if(!empty($route)){
    $route = array_merge($site_route,$route);
}else{
    $route = $site_route;
}
$cache_config = [];
if($cache['CACHE_TYPE']==1){
    $cache_config= array(
          //redis缓存配置设置
        'DATA_CACHE_PREFIX' => 'Redis_',//缓存前缀
        'DATA_CACHE_TYPE'=>'Redis',//默认动态缓存为Redis
        'DATA_CACHE_TIME'=>(int)$cache['CACHE_TYPE_TIME'],//0表示永久缓存
        'REDIS_RW_SEPARATE' => false, //Redis读写分离 true 开启
        'REDIS_HOST'=>$cache['CACHE_TYPE_HOST'], //redis服务器ip，多台用逗号隔开；读写分离开启时，第一台负责写，其它[随机]负责读；
        'REDIS_PORT'=>'6379',//端口号
        'REDIS_TIMEOUT'=>'300',//超时时间
        'REDIS_PERSISTENT'=>false,//是否长连接 false=短连接
        'REDIS_AUTH'=>'',//AUTH认证密码

        );
}elseif ($cache['CACHE_TYPE']==2) {
     $cache_config= array(
           //memcache缓存配置设置
         'DATA_CACHE_PREFIX' => 'Memcache_',//缓存前缀
         'DATA_CACHE_TYPE' => 'Memcache',
         'DATA_CACHE_TIME'=>(int)$cache['CACHE_TYPE_TIME'],//0表示永久缓存
         'MEMCACHE_HOST' => $cache['CACHE_TYPE_HOST'],
         'MEMCACHE_PORT' =>  '11211',

        );
}
$config = array(
    "LOAD_EXT_FILE"=>"extend",
    'LOAD_EXT_CONFIG' => 'pay_config,seo_media_config', 
    /* 模块相关配置 */
    'AUTOLOAD_NAMESPACE' => array('Addons' => ONETHINK_ADDON_PATH), //扩展模块列表
    'DEFAULT_MODULE'     => 'Home',
    'MODULE_DENY_LIST'   => array('Common','User','Admin','Install'),
    //'MODULE_ALLOW_LIST'  => array('Home','Admin'),

    /* 系统数据加密设置 */
    'DATA_AUTH_KEY' => 'UmtW6-Z(S^8xvwDn;B:J{X7FG9z2+Np.|C#~QRY"', //默认数据加密KEY

    /* 用户相关设置 */
    'USER_MAX_CACHE'     => 1000, //最大缓存用户数
    'USER_ADMINISTRATOR' => 1, //管理员用户ID

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 3, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符

    /* 全局过滤配置 */
    'DEFAULT_FILTER' => '', //全局过滤函数

    /* 数据库配置 */
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'uuyu', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'root',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'sys_', // 数据库表前缀
    
    /* 'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'h5_4', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'sys_', // 数据库表前缀 */

    /* 文档模型配置 (文档模型核心配置，请勿更改) */
    'DOCUMENT_MODEL_TYPE' => array(2 => '主题', 1 => '目录', 3 => '段落'),

);
return array_merge($config,$cache_config);
