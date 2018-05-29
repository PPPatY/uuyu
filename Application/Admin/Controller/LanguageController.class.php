<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class LanguageController extends ThinkController {
	/*
	 * 语言设置
	 */
	public function set(){
		$this->assign('meta_title','语言设置');
		$this->display();
	}
	
}