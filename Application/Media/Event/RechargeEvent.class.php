<?php

namespace Media\Event;

use Think\Controller;

use Common\Api\GameApi;

class RechargeEvent extends Controller {

	

	public function is_exist($out_trade_no){

		$recharge = M('recharge','tab_');

		$map['pay_status'] = 1;

		$map['pay_order_number'] = $out_trade_no;

		$res = $recharge->where($map)->find();

		if(empty($res)){

			return  false;

		}

		else{

			return true;

		}

	}





	public function update_recharge($data){

		$recharge = M('recharge','tab_');

		$r_data["pay_status"] = 1;

		$r_data["order_number"]     = $data["trade_no"];

		$map['pay_order_number'] = $data["out_trade_no"];

		$res = $recharge->where($map)->save($r_data);

		if($res){

			$gameapi = new GameApi();

			$entity  = $recharge->where($map)->find();

			$user_id = get_user_id($entity['user_account']);

			$game_data = $gameapi->game_pay($entity['game_id'],$entity['area_id'],$user_id,$entity['pay_amount'],$data["out_trade_no"]);

			if($game_data['status'] == 1){

				$r_data1["pay_game_status"] = 1;

				$map1['pay_order_number'] = $data["out_trade_no"];

				$res1 = $recharge->where($map1)->save($r_data1);

			}

			return true;

		}

		else{

			return false;

		}

	}

}