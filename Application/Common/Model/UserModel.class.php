<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/5/5
 * Time: 16:22
 */

namespace Common\Model;


class UserModel extends BaseModel {

	protected $_auto = [
		['password', 'think_ucenter_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY],
	];
	/**
	 * 绑定手机号
	 * @param $user_id
	 * @param $phone
	 * @return bool
	 * author: xmy 280564871@qq.com
	 */
	public function bindPhone($user_id,$phone){
		$data['id'] = $user_id;
		$data['phone'] = $phone;
		return $this->save($data);
	}

	/**
	 * 第三方注册
	 * @param $user
	 * @return bool|mixed
	 * author: xmy 280564871@qq.com
	 */
	public function thirdRegister($user){
		$data = $this->create($user);
		if(!$data){
			return false;
		}
		return $this->add($data);
	}
	public function getUserOneParam($user_id,$param){
		if(!$param){
			return -1;
		}
		$data = $this
		        ->field($param)
		        ->find($user_id);
		return $data;
	}
	public function updateShopAddress($user,$data){
		$save['id'] = $user;
		$save['shop_address'] = $data;
		$res = $this->save($save);
		return $res;
	}
	/**
	 * [send_msg description]
	 * @param  [type]  $user [用户id]
	 * @param  integer $type [2玩家实名认证]
	 * @param  [type]  $msg  [description]
	 * @return [type]        [description]
	 */
	public function send_msg($user,$type=2,$msg){
		// $
	}
}