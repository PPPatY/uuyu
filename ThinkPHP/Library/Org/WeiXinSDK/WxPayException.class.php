<?php
namespace Org\WeiXinSDK;
use Org\WeiXinSDK\WeiXinOauth;
use Exception;
/**
 * 
 * 微信支付API异常类
 * @author widyhu
 *
 */
class WxPayException extends Exception {
	public function errorMessage()
	{
		return $this->getMessage();
	}
}
