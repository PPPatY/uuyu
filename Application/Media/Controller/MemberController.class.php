<?php
namespace Media\Controller;
use Think\Controller;
use User\Api\UserApi;
use Common\Api\GameApi;
use Org\ThinkSDK\ThinkOauth;
use Org\WeiXinSDK\WeiXinOauth;
use Org\UcpaasSDK\Ucpaas;
use Org\XiguSDK\Xigu;
use Common\Api\PayApi;


class MemberController extends BaseController {
	
	/** 个人中心 */
	public function index() {
		$this->display();
	}
	
	/**
	* 登陆
	*/
	public function login($username='',$password='') {
        
	}
	
}