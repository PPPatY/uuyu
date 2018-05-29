<?php
namespace Org\WeiXinSDK;
abstract class WeiXinOauth{
	/**
     * 取得Oauth实例
     * @static
     * @return mixed 返回Oauth
     */
    public static function getInstance($type, $token = null) {
    	$name = $type;//ucfirst(strtolower($type));
		$name = "\Org\WeiXinSDK\\$name";
    	if (class_exists($name)) {
    		return new $name();
    	} else {
			return null;
    	}
    }
}