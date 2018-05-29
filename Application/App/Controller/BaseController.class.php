<?php
namespace App\Controller;

use Think\Controller\RestController;
use Common\Model\ServerModel;

class BaseController extends RestController
{
	protected function _initialize()
	{
		//加载配置
		C(api('Config/lists'));
	}

	/**
	 * 验证用户登录token
	 * @param $token
	 * author: xmy 280564871@qq.com
	 */
	protected function auth($token,$data=[],$type=0){
		$token = think_decrypt($token);
		if(empty($token)){
			$this->set_message(1032,"信息过期，请重新登录",$data,$type);
		}
		$info = json_decode($token,true);
		if(!$info['user_id']){
			$this->set_message(1117,"token数据不完整",$data,$type);
		}
		$servermodel = new ServerModel();
    	$send_notice = $servermodel->send_server_notice($info['user_id']);
		define("USER_ID",$info['user_id']);
		define("USER_ACCOUNT",$info['account']);
		define("IS_UC",$info['is_uc']);
	}

	/**
	 * 返回输出
	 * @param int $status 状态
	 * @param string $return_msg   错误信息
	 * @param array $data           返回数据
	 * author: xmy 280564871@qq.com
	 */
	public function set_message($status, $return_msg = 0, $data = [],$type=0)
	{
	    if ($status == 1){
	        $status = 200;
        }
		$msg = array(
			"code" => $status,
			"msg"  => $return_msg,
			"data" => $data
		);
        if ($type == 1){
            echo json_encode($msg,JSON_FORCE_OBJECT);
        }else{
            echo json_encode($msg);
        }
		exit;
	}

	/**
	 *验证签名
	 */
	public function validation_sign($encrypt = "", $md5_sign = "")
	{
		$signString = $this->arrSort($encrypt);
		$md5Str = $this->encrypt_md5($signString, $key = "");
		if ($md5Str === $md5_sign) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 *对数据进行排序
	 */
	private function arrSort($para)
	{
		ksort($para);
		reset($para);
		return $para;
	}

	/**
	 *MD5验签加密
	 */
	protected function encrypt_md5($param = "", $key = "")
	{
		#对数组进行排序拼接
		if (is_array($param)) {
			$md5Str = implode($this->arrSort($param));
		} else {
			$md5Str = $param;
		}
		$md5 = md5($md5Str . $key);
		return '' === $param ? 'false' : $md5;
	}



	/**
	 *消费记录表 参数
	 */
	private function spend_param($param = array())
	{
		$user_entity = get_user_entity($param['user_id']);
		$data_spned['user_id'] = $param["user_id"];
		$data_spned['user_account'] = $user_entity["account"];
		$data_spned['user_nickname'] = $user_entity["nickname"];
		$data_spned['game_id'] = $param["game_id"];
		$data_spned['game_appid'] = $param["game_appid"];
		$data_spned['game_name'] = $param["game_name"];
		$data_spned['server_id'] = 0;
		$data_spned['server_name'] = "";
		$data_spned['promote_id'] = $user_entity["promote_id"];
		$data_spned['promote_account'] = $user_entity["promote_account"];
		$data_spned['order_number'] = $param["order_number"];
		$data_spned['pay_order_number'] = $param["pay_order_number"];
		$data_spned['props_name'] = $param["title"];
		$data_spned['pay_amount'] = $param["real_pay_amount"];
		$data_spned['pay_time'] = NOW_TIME;
		$data_spned['pay_status'] = $param["pay_status"];
		$data_spned['pay_game_status'] = 0;
		$data_spned['pay_way'] = $param["pay_way"];
		$data_spned['spend_ip'] = $param["spend_ip"];
		return $data_spned;
	}

	/**
	 *平台币充值记录表 参数
	 */
	private function deposit_param($param = array())
	{
		$user_entity = get_user_entity($param['user_id']);
		$data_deposit['order_number'] = $param["order_number"];
		$data_deposit['pay_order_number'] = $param["pay_order_number"];
		$data_deposit['user_id'] = $param["user_id"];
		$data_deposit['user_account'] = $user_entity["account"];
		$data_deposit['user_nickname'] = $user_entity["nickname"];
		$data_deposit['promote_id'] = $user_entity["promote_id"];
		$data_deposit['promote_account'] = $user_entity["promote_account"];
		$data_deposit['pay_amount'] = $param["pay_amount"];
		$data_deposit['reality_amount'] = $param["real_pay_amount"];
		$data_deposit['pay_status'] = 0;
		$data_deposit['pay_source'] = 2;
		$data_deposit['pay_way'] = $param["pay_way"];
		$data_deposit['pay_ip'] = $param["spend_ip"];
		$data_deposit['create_time'] = NOW_TIME;
		return $data_deposit;
	}

	/**
	 *绑定平台币消费
	 */
	private function bind_spend_param($param = array())
	{
		$user_entity = get_user_entity($param['user_id']);
		$data_bind_spned['user_id'] = $param["user_id"];
		$data_bind_spned['user_account'] = $user_entity["account"];
		$data_bind_spned['user_nickname'] = $user_entity["nickname"];
		$data_bind_spned['game_id'] = $param["game_id"];
		$data_bind_spned['game_appid'] = $param["game_appid"];
		$data_bind_spned['game_name'] = $param["game_name"];
		$data_bind_spned['server_id'] = 0;
		$data_bind_spned['server_name'] = "";
		$data_bind_spned['promote_id'] = $user_entity["promote_id"];
		$data_bind_spned['promote_account'] = $user_entity["promote_account"];
		$data_bind_spned['order_number'] = $param["order_number"];
		$data_bind_spned['pay_order_number'] = $param["pay_order_number"];
		$data_bind_spned['props_name'] = $param["title"];
		$data_bind_spned['pay_amount'] = $param["price"];
		$data_bind_spned['pay_time'] = NOW_TIME;
		$data_bind_spned['pay_status'] = $param["pay_status"];
		$data_bind_spned['pay_game_status'] = 0;
		$data_bind_spned['pay_way'] = 1;
		$data_bind_spned['spend_ip'] = $param["spend_ip"];
		return $data_bind_spned;
	}

	/**
	 *消费表添加数据
	 */
	protected function add_spend($data)
	{
		$spend_data = $this->spend_param($data);
		$spend = M("spend", "tab_")->add($spend_data);
		return $spend;
	}

	/*
	*平台币充值记录
	*/
	protected function add_deposit($data)
	{
		$deposit_data = $this->deposit_param($data);
		$deposit = M("deposit", "tab_")->add($deposit_data);
		return $deposit;
	}


	/*
	*绑定平台币消费记录
	*/
	protected function add_bind_spned($data)
	{
		$data_bind_spned = $this->bind_spend_param($data);
		$bind_spned = M("BindSpend", "tab_")->add($data_bind_spned);
		return $bind_spned;
	}


	/**
	 * 增加绑币充值记录
	 * @param $param
	 * author: xmy 280564871@qq.com
	 */
	protected function add_bind_recharge($param){
		$user = get_user_entity($param['user_id']);
		$data['order_number']     = "";
		$data['pay_order_number'] = $param['pay_order_number'];
		$data['game_id']          = $param['game_id'];
		$data['game_appid']       = $param['game_appid'];
		$data['game_name']        = $param['game_name'];
		$data['promote_id']       = $user['promote_id'];
		$data['promote_account']  = $user['promote_account'];
		$data['user_id']          = $param['user_id'];
		$data['user_account']     = $user['account'];
		$data['user_nickname']    = $user['user_nickname'];
		$data['pay_type']         = $param['pay_type'];
		$data['amount']           = $param['pay_amount'];
		$data['real_amount']      = $param['real_pay_amount'];
		$data['pay_status']       = 0;
		$data['pay_way']          = $param['pay_way'];
		$data['create_time']      = time();
		$data['zhekou']           = $param['discount'];
		return M("bind_recharge","tab_")->add($data);
	}


	/**
	 * 关于我们
	 * author: xmy 280564871@qq.com
	 */
	public function about_us()
	{
		$data = array(
			'qq' => C('APP_QQ'),
			'weixin' => C('APP_WEIXIN'),
			'qq_group' => C('APP_QQ_GROUP'),
			'network' => C('APP_NETWORK'),
			'icon' => C('APP_ICON'),
			'version' => C('APP_VERSION'),
			'version_name' => C('APP_VERSION_NAME'),
			'app_download' => C('APP_DOWNLOAD'),
			'app_name' => C('APP_NAME'),
			'app_welcome' => C('APP_SET_COVER'),
			'about_ico' => C('ABOUT_ICO'),
			'ios_version' => C('IOS_VERSION'),
			'ios_version_name' => C('IOS_VERSION_NAME'),
			'ios_app_download' => C('IOS_APP_DOWNLOAD'),
			'weixin_id' =>  C('WEIXIN_ID'),
			'qq_group_key'  =>  C('QQ_GROUP_KEY'),
			'app_introduce'  =>  C('APP_INTRODUCE'),
		);
		$data['app_welcome'] = get_img_url($data['app_welcome']);
		$data['about_ico'] = get_img_url($data['about_ico']);
		$this->set_message(1,1,$data);
	}
}
