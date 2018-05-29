<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace Common\Api;
use Org\UcenterSDK\Ucservice;
use User\Api\MemberApi;
use Common\Api\GameApi;
use Com\Wechat;
use Com\WechatAuth;
use Org\JtpaySDK\Jtpay;
use Org\SwiftpassSDK\Swiftpass;
use Org\WeiXinSDK\WeiXinOauth;
use Org\WeiXinSDK\Weixin;
class PayApi {

	/**
	*支付宝 威富通等其他执法
	*/
	public function other_pay($param=array(),$config=array(),$game_id='')
	{	
        $related_site = $related_site?$related_site:(is_weixin()?3:(is_mobile_request()?2:1));
		if($param['code'] == 1){
			$table="spend";
		}else if($param['code'] == 3){
			$table="agent";
		}else{
			$table="deposit";
		}
		switch ($param['pay_type']) {
            case 'swiftpass':
                $pay = new \Think\Pay($param['pay_type'], $config);
                break;
            default:
                if ($game_id) {
                    $pay = new \Think\Pay($param['pay_type'], $config, $game_id);
                } else {
                    $pay = new \Think\Pay($param['pay_type'], $config);
                }
                break;
        }
        $vo = new \Think\Pay\PayVo();
        $vo->setBody("充值记录描述")
            ->setFee($param['fee'])//支付金额
            ->setTitle($param['title'])
            ->setOrderNo($param['order_no'])
            ->setService($param['server'])
            ->setSignType($param['signtype'])
            ->setPayMethod($param['method'])
            ->setTable($table)
            ->setPayWay($param['pay_way'])
            ->setGameId($param['game_id'])
            ->setGameName($param['game_name'])
            ->setGameAppid($param['game_appid'])
            ->setServerId($param['server_id'])
            ->setServerName($param['server_name'])
            ->setRoleId($param['game_player_id'])
            ->setRoleName($param['game_player_name'])
            ->setUserId($param['user_id'])
            ->setAccount($param['user_account'])
            ->setUserNickName($param['user_nickname'])
            ->setPromoteId($param['promote_id'])
            ->setPromoteName($param['promote_account'])
            ->setOpenid($param['openid'])
            ->setExtend($param['extend'])
            ->setCallback($param['callbackurl'])
            ->setMoney($param['amount'])
            ->setParam($param['zhekou'])
            ->setUc($param['uc']);
        return $pay->buildRequestForm($vo);
	}

	/**
	*微信支付 暂时没用
	*/
	public function weixin_pay($data=array(),$config=array()){
		switch ($data['options']) {
			case 'spend':
				$this->add_spend($data);
				break;
			case 'deposit':
				$this->add_deposit($data);
				break;
			case 'agent':
				$this->add_agent($data);
				break;
		}
		//页面上通过表单选择在线支付类型，支付宝为alipay 财付通为tenpay
        $pay = new \Think\Pay($data['pay_type'],$config);
        $vo = new \Think\Pay\PayVo();
        $vo->setBody("充值记录描述")
            ->setFee($data['fee'])//支付金额$pay_amount
            ->setOrderNo($data['order_no'])
            ->setMoney($data['fee'])//$money
            ->setTitle('游戏充值')
            ->setCallback("Pay/index/pay")
            ->setService($data['service'])
            ->setGid($data['opneid'])
            ->setNotifyUrl('http://'.$_SERVER['HTTP_HOST'].'/callback.php/Notify/weixi_notify/apitype/swiftpass')
            ->setParam(array('orderid'=>$data['order_no'],'tag'=>$data['order_no'],'user_login'=>'http://www.baidu.com'));
        return $pay->buildRequestForm($vo);
	}

	/**
	*消费表添加数据
	*/
	public function add_spend($data,$related_site=0){
        $related_site = $related_site?$related_site:(is_weixin()?3:(is_mobile_request()?2:1));
		if (!empty($data['uc'])) {
			$uc = new Ucservice();
			$uc_user=$uc->get_user_from_uid($data['user_id']);
			$uc_id = $uc->uc_recharge($data['user_id'],$data['user_account'],$data['user_nickname'],$data['game_id'],$data['game_appid'],$data['game_name'],0,'',$data['promote_id'],$data['promote_account'],"",$data['order_no'],$data['pay_amount'],time(),$data['extend'],$data['pay_way'],get_client_ip(),'',6,$uc_user['platform'],'','');
		}else{
			$spend = M("spend","tab_");
    		$ordercheck = $spend->where(array('pay_order_number'=>$data["order_no"]))->find();
            if($ordercheck)$this->error("订单已经存在，请刷新充值页面重新下单！");
            $spend_data['order_number']     = "";
    		$spend_data['pay_order_number'] = $data["order_no"];
    		$spend_data['user_id']          = $data["user_id"];
    		$spend_data['user_account']     = $data["user_account"];
    		$spend_data['user_nickname']    = $data["user_nickname"];
    		$spend_data['game_id']          = $data["game_id"];
    		$spend_data['game_appid']       = $data["game_appid"];
    		$spend_data['game_name']        = $data["game_name"];
    		$spend_data["server_id"]        = $data["server_id"];
            $spend_data["server_name"]      = $data["server_name"];
            $spend_data["game_player_id"]   = $data["game_player_id"];
            $spend_data["game_player_name"] = $data["game_player_name"];
    		$spend_data['promote_id']       = $data["promote_id"];
    		$spend_data['promote_account']  = $data["promote_account"];
    		$spend_data['pay_order_number'] = $data["order_no"];
    		$spend_data['props_name']       = $data["title"];
    		$spend_data['pay_amount']       = $data["pay_amount"];
    		$spend_data['pay_way']          = $data["pay_way"];
    		$spend_data['pay_time']			= NOW_TIME;
    		$spend_data['pay_status']		= 0;
    		$spend_data['pay_game_status']	= 0;
    		$spend_data['extend']		    = $data['extend'];
    		$spend_data['spend_ip']         = get_client_ip();
            $spend_data['related_site'] =$related_site;
    		$res = $spend->add($spend_data);
            return $res;
		}
	}

	/**
	*平台币充值记录
	*/
	public function add_deposit($data,$related_site=0){
        $related_site = $related_site?$related_site:(is_weixin()?3:(is_mobile_request()?2:1));
		$deposit = M("deposit","tab_");
		$ordercheck = $deposit->where(array('pay_order_number'=>$data["order_no"]))->find();
        if($ordercheck)$this->error("订单已经存在，请刷新充值页面重新下单！");

		$deposit_data['order_number']     = "";
		$deposit_data['pay_order_number'] = $data["order_no"];
		$deposit_data['user_id']          = $data["user_id"];
		$deposit_data['user_account']     = $data["user_account"];
		$deposit_data['user_nickname']    = $data["user_nickname"];
		$deposit_data['promote_id']       = $data["promote_id"];
		$deposit_data['promote_account']  = $data["promote_account"];
		$deposit_data['pay_amount']       = $data["fee"];
		$deposit_data['reality_amount']   = 0;
		$deposit_data['pay_status']       = 0;
		$deposit_data['pay_way']          = $data['pay_way'];
		$deposit_data['pay_source']		  = $data["pay_source"];
		$deposit_data['pay_ip']           = get_client_ip();
		$deposit_data['pay_source']       = $data['pay_source'];
		$deposit_data['create_time']      = NOW_TIME;
        $deposit_data['related_site'] =$related_site;
		
        $deposit->add($deposit_data);
	}

	/**
	*添加代充记录
	*/
	public function add_agent($data){
		$agent = M("agent","tab_");
		$ordercheck = $agent->where(array('pay_order_number'=>$data["order_no"]))->find();
        if($ordercheck)$this->error("订单已经存在，请刷新充值页面重新下单！");
		$agnet_data['order_number']     = "";
		$agnet_data['pay_order_number'] = $data["order_no"];
		$agnet_data['game_id']          = $data["game_id"];
		$agnet_data['game_appid']       = $data["game_appid"];
		$agnet_data['game_name']        = $data["game_name"];
		$agnet_data['promote_id']       = $data["promote_id"];
		$agnet_data['promote_account']  = $data["promote_account"];
		$agnet_data['user_id']          = $data["user_id"];
		$agnet_data['user_account']     = $data["user_account"];
		$agnet_data['user_nickname']    = $data["user_nickname"];
		$agnet_data['pay_type']         = 0;
		$agnet_data['amount']       	= $data["amount"];
		$agnet_data['real_amount']      = $data["real_amount"];
		$agnet_data['pay_status']       = 0;
		$agnet_data['pay_type']			= $data['pay_way'];
		$agnet_data['create_time']      = time();
		$agent_data['zhekou']			=0;
		

		$agent->create($agnet_data);
		$resutl = $agent->add();
	}


	public function set_ratio($data,$isbind=0)
    {
        $map['pay_order_number']=$data;
        // if(!$isbind){
        // 	$spend=M("Spend","tab_")->where($map)->find();
        // }else{
        // 	$spend=M("BindSpend","tab_")->where($map)->find();
        // }
        $spend=M("Spend","tab_")->where($map)->find(); //5.0版本 绑币消费记录到spend表
        $reb_map['game_id']=$spend['game_id'];
        $time = time();
        $reb_map['starttime'] = ['lt',$time];
        $reb_map_str = "endtime > {$time} or endtime = 0";
        if($spend['promote_id'] == 0){
            $reb_map['promote_id'] = ['in','0,-1'];
        }else{
            $reb_map['promote_id'] = ['neq',0];
        }
        $rebate=M("Rebate","tab_")->where($reb_map)->where($reb_map_str)->find();
        if($isbind&&$rebate['bd_cost']!=1){
        	return false;exit;
        }
        $spend['is_bd'] = $isbind;
        if (!empty($rebate)) {
            \Think\Log::record($data.'返利记录');
            if ($rebate['money'] > 0 && $rebate['status'] == 1) {
                if ($spend['pay_amount'] >= $rebate['money']) {
                    $this->compute($spend, $rebate);
                } else {
                    return false;
                }
            } else {
                $this->compute($spend, $rebate);
            }
        }else {
            return false;
        }
    }

    //计算返利
    private function compute($spend,$rebate){
        $user_map['user_id']=$spend['user_id'];
        $user_map['game_id']=$spend['game_id'];            
        $bind_balance=$spend['pay_amount']*($rebate['ratio']/100);
        $spend['ratio']=$rebate['ratio'];
        $spend['ratio_amount']=$bind_balance;
        M("rebate_list","tab_")->add($this->add_rebate_list($spend));
        $re=M("UserPlay","tab_")->where($user_map)->setInc("bind_balance",$bind_balance);
        return $re;
    }
    /**
    *返利记录
    */
    private function add_rebate_list($data){
        $add['pay_order_number']=$data['pay_order_number'];
        $add['game_id']=$data['game_id'];
        $add['game_name']=$data['game_name'];
        $add['user_id']=$data['user_id'];
        $add['pay_amount']=$data['pay_amount'];
        $add['ratio']=$data['ratio'];
        $add['ratio_amount']=$data['ratio_amount'];
        $add['promote_id']=$data['promote_id'];
        $add['is_bd']=$data['is_bd'];
        $add['promote_name']=$data['promote_account'];
        $add['create_time']=time();
        return $add;
    }

    public function buyshoppoint($spend){
    	$map['key'] = array('in','share_game,share_article');
    	$pointtype = M('pointType','tab_')
    				  ->field('tab_point_type.*,tab_point_record.id as record_id')
    				  ->join("tab_point_record on tab_point_record.type_id = tab_point_type.id and user_id = {$spend['user_id']} and game_id = {$spend['game_id']} and tab_point_record.create_time ".total(1),'left')
    	              ->where($map)
    	              ->select();
      	$record_id_arr = array_column($pointtype,'record_id','key');
      	$type_id_arr = array_column($pointtype,'id','key');
      	$point_arr = array_column($pointtype,'point','key');
      	$kk = 0;
    	if(empty($record_id_arr['share_game'])){
    		$savedata[$kk]['type_id'] = $type_id_arr['share_game'];
    		$savedata[$kk]['user_id'] = $spend['user_id'];
    		$savedata[$kk]['game_id'] = $spend['game_id'];
    		$savedata[$kk]['point'] = $point_arr['share_game'];
    		$savedata[$kk]['type'] = 1;
    		$savedata[$kk]['day'] = 1;
    		$savedata[$kk]['create_time'] = time();
    		$addpoint[$kk]['point'] = $savedata[$kk]['point'];
    		$kk++;
    	}
		$num = intval($spend['pay_amount']);
		if($num>=1){
	    	$savedata[$kk]['type_id'] = $type_id_arr['share_article'];
			$savedata[$kk]['user_id'] = $spend['user_id'];
			$savedata[$kk]['game_id'] = $spend['game_id'];
			$savedata[$kk]['point'] = $point_arr['share_article']*$num;
			$savedata[$kk]['type'] = 1;
			$savedata[$kk]['day'] = $num;
			$savedata[$kk]['create_time'] = time();
			$addpoint[$kk]['point'] = $savedata[$kk]['point'];
		}
		foreach ($addpoint as $key => $value) {
			$add[] = M('User','tab_')->where(['id'=>$spend['user_id']])->setInc('shop_point',$value['point']);
		}
		$res = M('pointRecord','tab_')->addAll($savedata);
		return $res;
    }
}