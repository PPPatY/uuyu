<?php
namespace Media\Controller;
use Think\Controller;
use User\Api\MemberApi;
use Common\Api\GameApi;
use Common\Api\PayApi;
use Com\Wechat;
use Com\WechatAuth;
use Org\JtpaySDK\Jtpay;
use Org\SwiftpassSDK\Swiftpass;
use Org\WeiXinSDK\WeiXinOauth;
use Org\WeiXinSDK\Weixin;
class PayController extends SubscriberController {
    /**
     * 微信官方、威富通官方wap支付
     */
    public function weixin_wap_pay()
    {
        $user = get_user_entity(is_login());
        if (empty($user)) {
            $this->error("用户不存在");
        }
        if (pay_set_status('wei_xin') == 0 && pay_set_status('weixin') == 0) {
            $this->error("网站未启用微信充值", '', 1);
            exit();
        }
        if ($_POST['amount'] <= 0) {
            $this->error('充值金额有误');
            exit();
        }
        $data['out_trade_no'] = 'PF_' . date('Ymd') . date('His') . sp_random_string(4);
        $data['pay_way'] = 2;
        $data['amount'] = $_POST['amount'];
        $param['service'] = "pay.weixin.wappay";
        $param['ip'] = get_client_ip();
        $param['game_name'] = 'vlcms';
        $param['pay_amount'] = $_POST['amount'];
        $param['out_trade_no'] = $data['out_trade_no'];
        $param['body'] = "平台币充值";
        //0 官方 1威富通
        if (get_wx_type() == 0) {
            $weixn = new Weixin();
            $is_pay = json_decode($weixn->weixin_pay("平台币充值", $param['out_trade_no'], $data['amount'], 'MWEB'), true);
            if($is_pay['status']==1){
                $this->add_deposit($data, $user);
                $json_data = array('status' => 1, 'pay_info' => $is_pay['mweb_url']);
            }else{
                $json_data = array('status' => -1,'info'=>$is_pay['return_msg']);
            }
        } else {
            $Swiftpass = new Swiftpass(C('weixin.partner'), C('weixin.key'));
            $url = $Swiftpass->submitOrderInfo($param);
            if ($url['status'] == 000) {
                $this->add_deposit($data, $user);
                $json_data = array('status' => 1, 'pay_info' => $url['pay_info']);
            }else{
                $json_data = array('status' => -1, 'info' => $url['msg']);
            }
        }

        $this->ajaxReturn($json_data);

    }
    /**

    *微信支付

    */

    public function game_wx_pay(){
        #判断账号是否存在
         if($_REQUEST['sign']!=session('game_pay_sign')){
            $this->ajaxReturn(array("status"=>0,"msg"=>'数据异常！'));exit;
         }
        $user = get_user_entity(is_login());
        
        if (empty($user)) {
            $this->error("用户不存在");exit();
        }else{
            $data["user_id"]       = $user["id"];
            $data["user_account"]  = $user['account'];
            $data["user_nickname"] = $user['nickname'];
        }
        if ($_POST['amount'] <= 0) {
            $this->error('充值金额有误');
            exit();
        }
        $game_data = get_game_entity($_POST["game_appid"]);
        if(empty($game_data)){$this->error("游戏不存在");exit();}
        #支付配置
        $data['options']  = 'spend';
        $data['order_no'] = 'SP_'.date('Ymd').date ( 'His' ).sp_random_string(4);
        $data['fee']      = $_POST['amount']/100;
        $data['pay_type'] = 'swiftpass'; 
        $data['server'] = "pay.weixin.native";
        #平台币记录数据
        $data["game_id"]       = $game_data['id'];
        $data["game_appid"]    = $_POST["game_appid"];
        $data["game_name"]     = $game_data["game_name"];
        $data["server_id"]     = $_POST["server_id"];
        $data["server_name"]   = $_POST["server_name"];
        $data["game_player_id"]     = $_POST["role_id"];
        $data["game_player_name"]   = $_POST["role_name"];
        $data["promote_id"]    = $user['promote_id'];
        $data["promote_account"] = $user['promote_account'];
        $data["pay_order_number"]= $data['order_no'];
        $data["title"] = $_POST['props_name'];
        $data["pay_amount"]   = $_POST['amount']/100;
        $data["pay_way"] = 2;
        $data['extend']  = $_POST['trade_no'];
        $data['code']  = 1;
        $pay = new PayApi();
        if(get_wx_type()==0){//0官方
            $weixn=new Weixin();
            $is_pay=json_decode($weixn->weixin_pay($data["title"],$data['order_no'],$data['fee'],'NATIVE'),true);
            if ($is_pay['status'] === 1) {
                $pay->add_spend($data);  
                $this->ajaxReturn(array("status" => 1,"amount" => $data['fee'], "out_trade_no" => $data["order_no"], "qrcode_url" =>  U('Subscriber/qrcode', array('level' => 3, 'size' => 4, 'url' => base64_encode(base64_encode($is_pay['url']))))));   
            }else{
                $this->ajaxReturn(array("status"=>0,"info"=>'支付数据错误！'));
            }
        }else{
            $data['pay_way']=4;
            $arr = $pay->other_pay($data,C('weixin'));
            if($arr['status1'] === 500){
                \Think\Log::record($arr['msg']);
                $html ='<div class="d_body" style="height:px;">
                        <div class="d_content">
                            <div class="text_center">'.$arr["msg"].'</div>
                        </div>
                        </div>';
                $json_data = array("status"=>0,"html"=>$html,"info"=>$arr['msg']);
            }else{
                $html ='<div class="d_body" style="height:px;">
                        <div class="d_content">
                            <div class="text_center">
                                <table class="list" width="100%">
                                    <tbody>
                                    <tr>
                                        <td class="text_right">订单号</td>
                                        <td class="text_left">'.$arr["out_trade_no"].'</td>
                                    </tr>
                                    <tr>
                                        <td class="text_right">充值金额</td>
                                        <td class="text_left">本次充值'.$data["pay_amount"].'元，实际付款'.$data["pay_amount"].'元</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <img src="'.$arr["code_img_url"].'" height="301" width="301">
                                <br/>&nbsp;&nbsp;&nbsp;&nbsp;
                                <img src="/Public/Media/images/wx_pay_tips.png">
                            </div>
                        </div>
                    </div>';
                $json_data = array("status"=>1,"out_trade_no"=>$data['order_no'],"qrcode_url"=>$arr["code_img_url"],"amount"=>$data["pay_amount"]);
            }
            $this->ajaxReturn($json_data);
        }
    }

    /**
     *微信pc支付
     */

    public function wx_pay()
    {
        #判断账号是否存在
        $user = get_user_entity(is_login());
        if (empty($user)) {
            $this->error("用户不存在");
            exit();
        }
        if (pay_set_status('wei_xin') == 0 && pay_set_status('weixin') == 0) {
            $this->error("网站未启用微信充值", '', 1);
            exit();
        }
        if ($_POST['amount'] <= 0) {
            $this->error('充值金额有误');
            exit();
        }
        if (get_wx_type() == 0) {//0官方
            $weixn = new Weixin();
            $datt['out_trade_no'] = "PF_" . date('Ymd') . date('His') . sp_random_string(4);
            $datt['amount'] = $_POST['amount'];
            $datt['pay_status'] = 0;
            $datt['pay_way'] = 2;
            $datt['pay_source'] = 1;
            $is_pay = json_decode($weixn->weixin_pay("平台币充值", $datt['out_trade_no'], $datt['amount'], 'NATIVE'), true);
            if ($is_pay['status'] === 1) {
                $this->add_deposit($datt, $user);
                $this->ajaxReturn(array("status" => 1,"amount" => $datt['amount'], "out_trade_no" => $datt["out_trade_no"], "qrcode_url" =>  U('Subscriber/qrcode', array('level' => 3, 'size' => 4, 'url' => base64_encode(base64_encode($is_pay['url']))))));
            }
        } else {
            #支付配置
            $data['code'] = 2;
            $data['fee'] = $_POST['amount'];
            $data['body'] = '平台币购买';
            $data['title'] = '平台币购买';
            $data['apitype'] = 'swiftpass';
            $data['config'] = 'weixin';
            $data['method'] = 'direct';
            $data['server'] = 'pay.weixin.native';
            $data['signtype'] = 'MD5';
            #平台币记录数据
            $data['user_id'] = $user['id'];
            $data['user_account'] = $user['account'];
            $data['user_nickname'] = $user['nickname'];
            $data['promote_id'] = $user['promote_id'];
            $data['promote_account'] = $user['promote_account'];
            // $data['pay_amount']       = $_POST['amount'];
            $data['pay_amount'] = $_POST['amount'];
            $data['pay_status'] = 0;
            $data['pay_way'] = 4;
            $data['pay_source'] = 1;
            $data['pay_type'] = 'swiftpass'; 
            $data['order_no'] = "PF_" . date('Ymd') . date('His') . sp_random_string(4);
            $pay = new PayApi();
            $arr = $pay->other_pay($data,C('weixin'));
            if($arr['status1'] === 500){
                \Think\Log::record($arr['msg']);
                $html ='<div class="d_body" style="height:px;">
                        <div class="d_content">
                            <div class="text_center">'.$arr["msg"].'</div>
                        </div>
                        </div>';
                $json_data = array("status"=>0,"html"=>$html,"info"=>$arr['msg']);
            }else{
                $json_data = array("status"=>1,"out_trade_no"=>$data['order_no'],"qrcode_url"=>$arr["code_img_url"],"amount"=>$data["pay_amount"]);
            }
            $this->ajaxReturn($json_data);
        }
    }


    /*支付宝支付*/
    public function alipay($amount = 0.01, $game_id = '')
    {
        #判断账号是否存在
        $user = get_user_entity(is_login());
        if ($user<=0) {
            if(IS_AJAX){
                $this->ajaxReturn(array('status'=>-1,'msg'=>'未登录','url'=>U('Subscriber/user')));
            }
            redirect(U('Subscriber/user'));exit;
        }
        if (ceil($amount) < 1) {
            $this->ajaxReturn(array('status'=>-1,'msg'=>'充值金额有误'));
        }
        #支付配置
        $data['code'] = 2;
        $data['fee'] = $amount;
        $data['title'] = '平台币购买';
        $data['body'] = '购买'.intval($amount).'平台币';
        $data['pay_type'] = 'alipay';
        $data['signtype'] = 'MD5';
        if (is_mobile_request()) {
            $data['server'] = 'alipay.wap.create.direct.pay.by.user';
            $data['method'] = 'wap';
        } else {
            $data['server'] = 'create_direct_pay_by_user';
            $data['method'] = 'direct';
        }
        if($param['code'] == 1){
            $prefix = 'SP_';
        }else{
            $prefix = 'PF_';
        }
        $out_trade_no = $prefix . date('Ymd') . date('His') . sp_random_string(4);
        #平台币记录数据
        $data['order_no'] = $out_trade_no;
        $data['user_id'] = $user['id'];
        $data['user_account'] = $user['account'];
        $data['user_nickname'] = $user['nickname'];
        $data['promote_id'] = $user['promote_id'];
        $data['promote_account'] = $user['promote_account'];
        $data['pay_amount'] = $amount;
        $data['pay_status'] = 0;
        $data['pay_way'] = 1;
        $data['pay_source'] = 1;
        if ($game_id) {
            $data['game_id'] = $game_id;
        }
        $api = new PayApi();
        $url = $api->other_pay($data,C('alipay'),$game_id);
        if (is_mobile_request()) {
            if(IS_AJAX){
                $this->ajaxReturn(array('status'=>1,'url'=>$url));
            }else{
                redirect($url);
            }
        } else {
            echo $url;
        }
    }


    public function game_alipay($param){
        //支付sign验证
        if(($param['sign']!=session('game_pay_sign'))&&$_REQUEST['from']!='wxgzh'){
            $this->error("非法操作！");exit();
        }
        #判断账号是否存在
        $userid = $_REQUEST['from']!='wxgzh'?is_login():$_REQUEST['user_id'];
        $user = get_user_entity($userid);
        if (empty($user)) {
            $this->error("用户不存在");exit();
        }else{
            $data["user_id"]       = $user["id"];
            $data["user_account"]  = $user['account'];
            $data["user_nickname"] = $user['nickname'];
        }
        $game_data = get_game_entity($param['game_appid']);
        if(empty($game_data)){$this->error("游戏不存在");exit();}
        #支付配置
        $data['options']  = 'spend';
        $data['order_no'] = 'SP_'.date('Ymd').date ( 'His' ).sp_random_string(4);
        $data['fee']      = $param['price']/100;
        $data['pay_type'] = 'alipay'; 
        #平台币记录数据
        $data["game_id"]       = $game_data['id'];
        $data["game_appid"]    = $param["game_appid"];
        $data["game_name"]     = $game_data["game_name"];
        $data["server_id"]     = $param["server_id"];
        $data["server_name"]   = $param["server_name"];
        $data["game_player_id"]     = $param["role_id"];
        $data["game_player_name"]   = $param["role_name"];
        $data["promote_id"]    = $user['promote_id'];
        $data["promote_account"] = $user['promote_account'];
        $data["pay_order_number"]= $data['order_no'];
        $data["title"] = $param['props_name'];
        $data["pay_amount"]   = $param['price']/100;
        $data["pay_way"] = 1;
        $data['code']=1;
        $data['extend']  = $param['trade_no'];
        if (is_mobile_request()) {
            $data['server'] = 'alipay.wap.create.direct.pay.by.user';
            $data['method'] = 'wap';
        } else {
            $data['signtype']            = "RSA2";
            $data['method'] = 'f2fscan';//面对面30min
        }
        $pay = new PayApi();
        $url = $pay->other_pay($data,C('alipay'),$data["game_id"]);
        if (is_mobile_request()) {
            redirect($url);exit;
        }else{
            if ($url['status'] === 200) {
                $this->ajaxReturn(array("status" => 1,"amount" => $url['fee'], "out_trade_no" => $url["order_no"], "qrcode_url" =>  U('Subscriber/qrcode', array('level' => 3, 'size' => 4, 'url' => base64_encode(base64_encode($url['payurl']))))));   
            }else{
                $this->ajaxReturn(array("status"=>0,"info"=>'支付数据错误！'));
            }
        }
    }
   

    public function goldPig()
    {
        $user = get_user_entity(is_login());
        if (empty($user)) {
            $this->error("用户不存在");
        }
        if (pay_set_status('goldpig') == 0 ) {
            $this->error("网站未启用金猪充值", '', 1);
            exit();
        }
        if (ceil($_POST['money']) < 1) {
            $this->error('充值金额不能小于1');
            exit();
        }
        $data['out_trade_no'] = 'PF_' . date('Ymd') . date('His') . sp_random_string(4);
        $data['pay_way'] = 7;
        $data['amount'] = $_POST['money'];
        $res = $this->add_deposit($data, $user);
        if(!$res){
            $this->error("订单创建失败，请重新创建");
        }
        $urlparams['UserName'] = $user['account'];
        $urlparams['fee'] = $data["amount"];
        $urlparams['jinzhua'] = $data["out_trade_no"];
        $urlparams['jinzhub'] = signsortData($urlparams,C('goldpig.key'));
        $urlparams['gamename'] = '平台币充值';
        $url = C('goldpig.partner').'&'.sortData($urlparams);
        if(IS_AJAX){
            $this->ajaxReturn(array('status'=>1,'url'=>$url));
        }else{
            redirect($url);
        }

    }
		
		public function recharge_pig() {			
			
			$user = get_user_entity(is_login());
			if (empty($user)) {
					$this->error("用户不存在");
			}
			if (pay_set_status('goldpig') == 0 ) {
					$this->error("网站未启用金猪充值", '', 1);
					exit();
			}
			if (ceil($_POST['money']) < 1) {
					$this->error('充值金额不能小于1');
					exit();
			}
			$data['out_trade_no'] = 'PF_' . date('Ymd') . date('His') . sp_random_string(4);
			$data['pay_way'] = 7;
			$data['amount'] = $_POST['money'];
			$res = $this->add_deposit($data, $user);
			if(!$res){
					$this->error("订单创建失败，请重新创建");
			}
			$urlparams['UserId'] = $user['id'];
			$urlparams['UserName'] = $user['account'];
			$urlparams['fee'] = $data["amount"];
			$urlparams['jinzhua'] = $data["out_trade_no"];
			$urlparams['jinzhub'] = signsortData($urlparams,C('goldpig.key'));
			$urlparams['gamename'] = '平台币充值';
			
			$url = U('Subscriber/user_recharge_pig',sortData($urlparams));
			
			if(IS_AJAX){
					$this->ajaxReturn(array('status'=>1,'url'=>$url));
			}else{
					redirect($url);
			}
		}

    public function gameGoldPig(){
        #判断账号是否存在
        $user = get_user_entity(is_login());
        if ($user<=0) {
            $this->ajaxReturn(array('status'=>-1,'msg'=>'未登录'));
        }else{
            $data["user_id"]       = $user["id"];
            $data["user_account"]  = $user['account'];
            $data["user_nickname"] = $user['nickname'];
        }
        if (pay_set_status('goldpig') == 0 ) {
            $this->error("网站未启用金猪充值", '', 1);
            exit();
        }
        $amount = $_POST['amount'];
        if ($amount/100 < 1) {
            $this->ajaxReturn(array('status'=>-1,'msg'=>'充值金额需要大于1'));
        }
        $game_data = get_game_entity($_POST["game_appid"]);
        if(empty($game_data)){$this->error("游戏不存在");exit();}
        #支付配置
        $data['options']  = 'spend';
        $data['order_no'] = 'SP_'.date('Ymd').date ( 'His' ).sp_random_string(4);
        $data['fee']      = $amount/100;
        $data['pay_type'] = 'goldpig'; 
        #平台币记录数据
        $data["game_id"]       = $game_data['id'];
        $data["game_appid"]    = $_POST["game_appid"];
        $data["game_name"]     = $game_data["game_name"];
        $data["server_id"]     = $_POST["server_id"];
        $data["server_name"]   = $_POST["server_name"];
        $data["game_player_id"]     = $_POST["role_id"];
        $data["game_player_name"]   = $_POST["role_name"];
        $data["promote_id"]    = $user['promote_id'];
        $data["promote_account"] = $user['promote_account'];
        $data["pay_order_number"]= $data['order_no'];
        $data["title"] = $_POST['props_name'];
        $data["pay_amount"]   = $amount/100;
        $data["pay_way"] = 7;
        $data['extend']  = $_POST['trade_no'];
        $data['code']  = 1;
        $pay = new PayApi();
        $res = $pay->add_spend($data);  
        if(!$res){
            $this->error("订单创建失败，请重新创建");
        }
        $urlparams['UserName'] = $user['account'];
        $urlparams['fee'] = $data["pay_amount"];
        $urlparams['jinzhua'] = $data["order_no"];
        $urlparams['jinzhub'] = signsortData($urlparams,'mengchuang');
        $urlparams['gamename'] = $game_data["game_name"];
        $url = 'http://pay000.com/wap/?m=BTGG&'.sortData($urlparams);
        if(IS_AJAX){
            $this->ajaxReturn(array('status'=>1,'url'=>$url));
        }else{
            redirect($url);
        }

    }
		
		public function gameGoldPigRecharge(){
        #判断账号是否存在
        $user = get_user_entity(is_login());
        if ($user<=0) {
            $this->ajaxReturn(array('status'=>-1,'msg'=>'未登录'));
        }else{
            $data["user_id"]       = $user["id"];
            $data["user_account"]  = $user['account'];
            $data["user_nickname"] = $user['nickname'];
        }
        if (pay_set_status('goldpig') == 0 ) {
            $this->error("网站未启用金猪充值", '', 1);
            exit();
        }
        $amount = $_POST['amount'];
        if ($amount/100 < 1) {
            $this->ajaxReturn(array('status'=>-1,'msg'=>'充值金额需要大于1'));
        }
        $game_data = get_game_entity($_POST["game_appid"]);
        if(empty($game_data)){$this->error("游戏不存在");exit();}
        #支付配置
        $data['options']  = 'spend';
        $data['order_no'] = 'SP_'.date('Ymd').date ( 'His' ).sp_random_string(4);
        $data['fee']      = $amount/100;
        $data['pay_type'] = 'goldpig'; 
        #平台币记录数据
        $data["game_id"]       = $game_data['id'];
        $data["game_appid"]    = $_POST["game_appid"];
        $data["game_name"]     = $game_data["game_name"];
        $data["server_id"]     = $_POST["server_id"];
        $data["server_name"]   = $_POST["server_name"];
        $data["game_player_id"]     = $_POST["role_id"];
        $data["game_player_name"]   = $_POST["role_name"];
        $data["promote_id"]    = $user['promote_id'];
        $data["promote_account"] = $user['promote_account'];
        $data["pay_order_number"]= $data['order_no'];
        $data["title"] = $_POST['props_name'];
        $data["pay_amount"]   = $amount/100;
        $data["pay_way"] = 7;
        $data['extend']  = $_POST['trade_no'];
        $data['code']  = 1;
        $pay = new PayApi();
        $res = $pay->add_spend($data);  
        if(!$res){
            $this->error("订单创建失败，请重新创建");
        }
        $urlparams['UserName'] = $user['account'];
        $urlparams['fee'] = $data["pay_amount"];
        $urlparams['jinzhua'] = $data["order_no"];
        $urlparams['jinzhub'] = signsortData($urlparams,'mengchuang');
        $urlparams['gamename'] = $game_data["game_name"];
        
				$url = U('Subscriber/user_recharge_pig',sortData($urlparams));
				
        if(IS_AJAX){
            $this->ajaxReturn(array('status'=>1,'url'=>$url));
        }else{
            redirect($url);
        }

    }


    /**
     *平台币充值记录
     */

    public function add_deposit($data, $user)
    {
        $deposit = M("deposit", "tab_");
        $deposit_data['order_number'] = '';
        $deposit_data['pay_order_number'] = $data['out_trade_no'];
        $deposit_data['user_id'] = $user['id'];
        $deposit_data['user_account'] = $user['account'];
        $deposit_data['user_nickname'] = $user['nickname'];
        $deposit_data['promote_id'] = $user['promote_id'];
        $deposit_data['promote_account'] = $user['promote_account'];
        $deposit_data['pay_amount'] = $data['amount'];
        $deposit_data['pay_status'] = 0;
        $deposit_data['pay_way'] = $data['pay_way'];
        $deposit_data['pay_source'] = 0;
        $deposit_data['pay_ip'] = get_client_ip();
        $deposit_data['pay_source'] = 0;
        $deposit_data['create_time'] = NOW_TIME;
        $deposit_data['sdk_version'] = '';
        $result = $deposit->add($deposit_data);
        return $result;
    }

    public function checkstatus()
    {
        substr($_REQUEST['out_trade_no'], 0, 2) == "PF" ? $model = 'deposit' : $model = 'spend';
        $data = M($model, 'tab_')->where(array('pay_order_number' => $_REQUEST['out_trade_no']))->find();
        if ($data['pay_status'] == 1) {
            $this->ajaxReturn(array("status" => 1));
        } else {
            $this->ajaxReturn(array("status" => 0));
        }
    }
}