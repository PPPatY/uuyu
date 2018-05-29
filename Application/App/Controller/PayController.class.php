<?php
namespace App\Controller;
use Org\WeiXinSDK\Weixin;
use Org\JtpaySDK\Jtpay;

class PayController extends BaseController{

    const ALI_PAY = 1;          //支付宝支付
    const WEIXIN_PAY =2;        //微信支付
    const PLATFORM_COIN = 1;        //平台币
    const BIND_PLATFORM_COIN = 2;   //绑定平台币

    public function payWay(){
        $paytype = M('tool', 'tab_')->field('status,name')->where(['name'=>['in','weixin,wei_xin_app,alipay,jft,goldpig']])->select();
        foreach ($paytype as $key => $value) {
            $pay[$value['name']] = $value['status'];
        }
        if($pay['wei_xin_app']==1){
            $pay['weixin'] = 1;
        }
        unset($pay['wei_xin_app']);
        $this->set_message(200,'success',$pay);
    }

    /**
     * 支付宝移动支付
     * @param  string user_id 用户ID
     * @param  string account 充值账号
     * @param  int price 充值金额
     * @return base64加密的json格式
     * @author lyf
     */
    public function alipay_pay($token){
        $this->auth($token);
        $request = I("post.");
        if (pay_set_status('alipay') == 0) {
            $this->set_message(1115,'支付宝支付未开启');
        }
        if($request['price']<0){
            $this->set_message(1011,"充值金额有误");
        }
        $request['user_id'] = USER_ID;
        $request['apitype'] = "alipay";
        $request['config']  = "alipay";
        $request['signtype']= "MD5";
        $request['server']  = "mobile.securitypay.pay";
        $request['payway']  = 1;
        $request['method']  = 'mobile';
        $data = $this->pay($request);
        $md5_sign = $this->encrypt_md5($data['arg'],'mengchuang');
        $data = array('status'=>1,"orderInfo"=>$data['arg'],"out_trade_no"=>$data['out_trade_no'],"order_sign"=>$data['sign'],"md5_sign"=>$md5_sign);

        $this->set_message(200,'success',$data);
    }


    public function wx_pay($token){
        $this->auth($token);
        $request = !empty(I("post."))?I('post.'):I('get.');
        $request['user_id'] = USER_ID;
        C(api('Config/lists'));
        $request['pay_amount'] = $request['price'];
        if($request['price']<0){
            $this->set_message(1011,"充值金额有误");
        }
        if (get_wx_type() == 0) {//官方
            $prefix = "PF_";
            $request['pay_order_number'] = $prefix . date('Ymd') . date('His') . sp_random_string(4);
            $request['pay_way'] = 3;
            $request['pay_status'] = 0;
            $request['spend_ip'] = get_client_ip();
            $weixn = new Weixin();
            $is_pay = json_decode($weixn->weixin_pay($request['title'], $request['pay_order_number'], $request['price'], 'APP', 2), true);
            if ($is_pay['status'] === 1) {
                    $this->add_deposit($request);
                $json_data['appid'] = $is_pay['appid'];
                $json_data['partnerid'] = $is_pay['mch_id'];
                $json_data['out_trade_no'] = $is_pay['prepay_id'];
                $json_data['noncestr'] = $is_pay['noncestr'];
                $json_data['timestamp'] = $is_pay['time'];
                $json_data['package'] = "Sign=WXPay";
                $json_data['sign'] = $is_pay['sign'];
                $json_data['status'] = 1;
                $json_data['return_msg'] = "下单成功";
                $json_data['wxtype'] = "wx";
                $this->set_message(200,'success',$json_data);
            }else{
                $this->set_message(1028,$is_pay['return_msg']);
            }
        } else {
            $game_set_data = get_game_set_info($request['game_id']);
            if(empty(C("weixin.partner"))||empty(C("weixin.key"))){
                $this->set_message(0, "faill", "未设置威富通账号");
            }
            $request['apitype'] = "swiftpass";
            $request['config']  = "weixin";
            $request['signtype']= "MD5";
            $request['server']  = "unified.trade.pay";
            $request['payway']  = 2;
            $result_data = $this->pay($request);
            $data['status'] = 1;
            $data['return_code'] = "success";
            $data['return_msg'] = "下单成功";
            $data['token_id'] = $result_data['token_id'];
            $data['out_trade_no'] = $result_data['out_trade_no'];
            $data['game_pay_appid'] = $game_set_data['game_pay_appid'];
            $data['wxtype'] = "wft";
            $this->set_message(200,'success',$data);
        }

    }


    public function jft_pay($token)
    {
        $this->auth($token);
        $request = I('post.');

        if (pay_set_status('jft') == 0) {
            $this->set_message(1115,'竣付通支付未开启');
        }
        #判断账号是否存在
        $user = get_user_entity(USER_ID);

        $data2["user_id"] = $user["id"];
        $data2["user_account"] = $user['account'];
        $data2["user_nickname"] = $user['nickname'];

        if ($request['price'] <= 0) {
            $this->set_message(1101,"充值金额有误");
        }
        #支付配置
        $data['order_no'] = 'PF_' . date('Ymd') . date('His') . sp_random_string(4);
        $data['fee']      = $request['price'];//$_POST['amount'];
        #平台币记录数据
        $data['order_number'] = "";
        $data['pay_order_number'] = $data['order_no'];
        $data['user_id'] = $data2['user_id'];
        $data['user_account'] = $data2['user_account'];
        $data['user_nickname'] = $data2['user_nickname'];
        $data['promote_id'] = $user['promote_id'];
        $data['promote_account'] = $user['promote_account'];
        $data['pay_amount'] =  $request['price'];
        $data['pay_status'] = 0;
        $data['pay_way'] = 6;//竣付通
        $data['pay_source'] = 1;
        $data['spend_ip'] = get_client_ip();
        $this->add_deposit($data);
        $sign = think_encrypt(md5( $request['price'] . $data['order_no']));
        $jtpay = new Jtpay();
        $http = is_https()?'https':'http';
        $result = $http.'://'.$_SERVER["SERVER_NAME"].U('Subscriber/pay_way', array('type' => 'Alipay', 'account' => $data2['user_account'], 'pay_amount' =>  $data['fee'], 'sign' => $sign, 'pay_order_number' => $data['order_no']));
        $result = str_replace("app.php","mobile.php",$result);
        $this->set_message(200,'success',$result);
    }
    public function goldpig_pay($token)
    {
        $this->auth($token);
        $request = I('post.');

        if (pay_set_status('goldpig') == 0) {
            $this->set_message(1115,'金猪支付未开启');
        }
        #判断账号是否存在
        $user = get_user_entity(USER_ID);

        $data2["user_id"] = $user["id"];
        $data2["user_account"] = $user['account'];
        $data2["user_nickname"] = $user['nickname'];

        if ($request['price'] < 1) {
            $this->set_message(1101,"金猪充值金额不能小于1元");
        }
        #支付配置
        $data['order_no'] = 'PF_' . date('Ymd') . date('His') . sp_random_string(4);
        $data['fee']      = $request['price'];//$_POST['amount'];
        #平台币记录数据
        $data['order_number'] = "";
        $data['pay_order_number'] = $data['order_no'];
        $data['user_id'] = $data2['user_id'];
        $data['user_account'] = $data2['user_account'];
        $data['user_nickname'] = $data2['user_nickname'];
        $data['promote_id'] = $user['promote_id'];
        $data['promote_account'] = $user['promote_account'];
        $data['pay_amount'] =  $request['price'];
        $data['pay_status'] = 0;
        $data['pay_way'] = 7;//金猪
        $data['pay_source'] = 1;
        $data['spend_ip'] = get_client_ip();
        $this->add_deposit($data);


        $urlparams['UserName'] = $data['user_account'];
        $urlparams['fee'] = $data["fee"];
        $urlparams['jinzhua'] = $data["pay_order_number"];
        $urlparams['jinzhub'] = signsortData($urlparams,C('goldpig.key'));
        $urlparams['gamename'] = '平台币充值';
        $urlparams['UserId'] = $data['user_id'];
        
        $url = U('Subscriber/user_recharge_pig',sortData($urlparams),false);
        $http = is_https()?'https':'http';
        $result = $http.'://'.$_SERVER["SERVER_NAME"].$url;
        $result = str_replace("app.php","mobile.php",$result);
        $this->set_message(200,'success',$result);

    }


    /**
     * 支付设置
     * @param  array $param 支付参数
     * @return 支付回调信息
     * @author lyf
     */
    private function pay($param=array()){
        $table  = $param['code'] == 1 ? "spend" : "deposit";
        $prefix = $param['code'] == 1 ? "SP_" : "PF_";
        $out_trade_no = $prefix.date('Ymd').date('His').sp_random_string(4);
        $user = get_user_entity($param['user_id']);
        if(empty($user)){$this->set_message(0,"fail","用户不存在");}
        switch ($param['apitype']) {
            case 'swiftpass':
                $pay  = new \Think\Pay($param['apitype'],C($param['config']));
                break;
            default:
                $pay  = new \Think\Pay($param['apitype'],C($param['config']));
                break;
        }
        $vo   = new \Think\Pay\PayVo();
        $vo->setBody("充值记录描述")
            ->setFee($param['price'])//支付金额
            ->setTitle($param['title'])
            ->setBody($param['body'])
            ->setOrderNo($out_trade_no)
            ->setService($param['server'])
            ->setSignType($param['signtype'])
            ->setPayMethod($param['method'])
            ->setTable($table)
            ->setPayWay($param['payway'])
            ->setGameId($param['game_id'])
            ->setGameName($param['game_name'])
            ->setGameAppid($param['game_appid'])
            ->setServerId(0)
            ->setServerName("")
            ->setUserId($param['user_id'])
            ->setAccount($user['account'])
            ->setUserNickName($user['nickname'])
            ->setPromoteId($user['promote_id'])
            ->setPromoteName($user['promote_account'])
            ->setOpenid($param['openid'])
            ->setExtend($param['extend'])
            ->setCallback($param['callbackurl']);
        return $pay->buildRequestForm($vo);
    }


    /**
     *支付验证
     */
    public function pay_validation(){
        $request = I("post.");
        $out_trade_no = $request['out_trade_no'];
        $pay_where = substr($out_trade_no,0,2);
        $result = 0;
        $map['pay_order_number'] = $out_trade_no;
        switch ($pay_where) {
            case 'SP':
                $data = M('spend','tab_')->field('pay_status')->where($map)->find();
                $result = $data['pay_status'];
                break;
            case 'PF':
                $data = M('deposit','tab_')->field('pay_status')->where($map)->find();
                $result = $data['pay_status'];
                break;
            case 'AG':
                $data = M('agent','tab_')->field('pay_status')->where($map)->find();
                $result = $data['pay_status'];
                break;
            default:
                $this->set_message(1090,'参数错误',"");
                break;
        }
        if($result){
            $this->set_message(200,'success','');
        }else{
            $this->set_message(1078,'支付失败','');
        }
    }
}