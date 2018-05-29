<?php

namespace Mobile\Event;

use Think\Controller;

class DepositEvent extends Controller {

	/**

	*添加平台币充值记录

	*/

	public function add_deposit_record($param){

		$map['account'] = $param['reciver_name'];

		$user = $this->user_entity($map);

		if(empty($user)){$this->error("用户不存在");exit();}

		$data['order_number'] = "";

		$data['pay_order_number'] = $param['pay_order_number'];//"DC_".date('Ymd').date ( 'His' ).sp_random_string(4);

		$data['user_account'] = $param['reciver_name'];

		$data['game_id'] = 0;

		$data['game_name'] = '';

		$data['area_id'] = '';

		$data['area_name'] ='';

		$data['promote_id'] = $user['promote_id'];

		$data['promote_account'] = $user['promote_name'];

		$data['ico'] = 0;

		$data['amount'] = $param['amount'];

		$data['status'] = 0;

		$data['pay_way'] = $param['pay_way'];

		$data['pay_source'] = 0;

		$data['pay_ip'] = get_client_ip();

		$data['create_time'] = NOW_TIME;

		$deposit = M('deposit','tab_');

		$deposit->add($data);

	}



	protected function user_entity($map){

		$entity = M('user','tab_')

				  ->field('tab_user_play.*,tab_user.account')

				  ->join('left join tab_user_play on tab_user.id = tab_user_play.user_id')

				  ->where($map)->find();
		return $entity;

	}

}