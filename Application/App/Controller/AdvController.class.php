<?php
/**
 * Created by Sublime.
 * User: yyh 1010707499@qq.com
 * Date: 2017/10/31
 * Time: 9:55
 */

namespace App\Controller;

use Common\Model\AdvModel;

class AdvController extends BaseController{

	/**
	 * 广告轮播图
	 * author: yyh 1010707499@qq.com
	 */
	public function get_slider(){
		$model = new AdvModel();
		$data = $model->getAdv("slider_app",5);
		if(empty($data)){
			$this->set_message(1033,"暂无数据");
		}else{
			$this->set_message(200,'success',$data);
		}
	}
}