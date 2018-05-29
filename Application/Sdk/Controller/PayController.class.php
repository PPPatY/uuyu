<?php
namespace Sdk\Controller;
use Think\Controller;
use Common\Api\GameApi;
use Org\WeixinSDK\Weixin;
class PayController extends BaseController{

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
    *支付宝移动支付
    */
    public function alipay_pay(){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $request = json_decode(base64_decode(file_get_contents("php://input")),true);
        // if(empty($request)){$this->set_message(0,"fail","支付数据不能为空");}
        $request['apitype'] = "alipay";
        $request['config']  = "alipay";
        $request['signtype']= "MD5";
        $request['server']  = "mobile.securitypay.pay";
        $request['payway']  = 1;
        $request['method']  = 'mobile';
        $data = $this->pay($request);
        $md5_sign = $this->encrypt_md5(base64_encode($data['arg']),'mengchuang');
        $data = array('status'=>1,"orderInfo"=>base64_encode($data['arg']),"out_trade_no"=>$data['out_trade_no'],"order_sign"=>$data['sign'],"md5_sign"=>$md5_sign);
        echo base64_encode(json_encode($data));
    }

    public function outher_pay(){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组 
        $request = json_decode(base64_decode(file_get_contents("php://input")),true);
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
        $data['wxtype']="wft";
        echo base64_encode(json_encode($data));
    }

    public function jubaobar_pay(){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $request = json_decode(base64_decode(file_get_contents("php://input")),true);
        $prefix = $request['code'] == 1 ? "SP_" : "PF_";
        $out_trade_no = $prefix.date('Ymd').date('His').sp_random_string(4);
        $request['pay_order_number'] = $out_trade_no;
        $request['pay_status'] = 0;
        $request['pay_way']    = 3;
        $request['spend_ip']   = get_client_ip();
        if($request['code'] == 1 ){
            #TODO添加消费记录
            $this->add_spend($request);
        }else{
            #TODO添加平台币充值记录
            $this->add_deposit($request);
        }
        $data['status'] = 1;
        $data['return_code'] = "success";
        $data['return_msg']  = "下单成功";
        $data['appid']  =   C("jubaobar.appid");
        $data['out_trade_no'] = $out_trade_no;
        echo base64_encode(json_encode($data));
    }

    /**
    *平台币支付
    */
    public function platform_coin_pay(){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $request = json_decode(base64_decode(file_get_contents("php://input")),true);
        #记录信息
        $user_entity = get_user_entity($request['user_id']);
        $out_trade_no = "PF_".date('Ymd').date('His').sp_random_string(4);
        $request['order_number']     = $out_trade_no;
        $request['pay_order_number'] = $out_trade_no;
        $request['out_trade_no']     = $out_trade_no;
        $request['title'] = $request['title'];
        $request['pay_status'] = 1;
        $request['pay_way'] = 0;
        $request['spend_ip']   = get_client_ip();
        $result = false;
        switch ($request['code']) {
            case 1:#非绑定平台币
                $user = M("user","tab_");
                if($user_entity['balance'] < $request['price']){
                    echo base64_encode(json_encode(array("status"=>-2,"return_code"=>"fail","return_msg"=>"余额不足")));
                    exit();
                }
                #扣除平台币
                $user->where("id=".$request["user_id"])->setDec("balance",$request['price']);
                #TODO 添加绑定平台币消费记录
                $result = $this->add_spend($request);
                #检查返利设置
                $this->set_ratio($request['pay_order_number']);
                break;
             case 2:#绑定平台币
                $user_play = M("UserPlay","tab_");
                $user_play_map['user_id'] = $request['user_id'];
                $user_play_map['game_id'] = $request['game_id'];
                $user_play_data = $user_play->where($user_play_map)->find();

                if($user_play_data['bind_balance'] < $request['price']){
                    echo base64_encode(json_encode(array("status"=>-2,"return_code"=>"fail","return_msg"=>"余额不足")));
                    exit();
                }
                #扣除平台币
                $user_play->where($user_play_map)->setDec("bind_balance",$request['price']);
                #TODO 添加绑定平台币消费记录
                $result = $this->add_bind_spned($request);
                break;
            default:
                echo base64_encode(json_encode(array("status"=>-3,"return_code"=>"fail","return_msg"=>"支付方式不明确")));
                exit();
            break;
        }
        $game = new GameApi();
        $game->game_pay_notify($request,$request['code']);
        if($result){
            echo base64_encode(json_encode(array("return_status"=>1,"return_code"=>"success","return_msg"=>"支付成功","out_trade_no"=>$out_trade_no)));
        }
        else{
            echo base64_encode(json_encode(array("status"=>-1,"return_code"=>"fail","return_msg"=>"支付失败")));
        }
    }

    /**
    *支付验证
    */
    public function pay_validation(){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $request = json_decode(base64_decode(file_get_contents("php://input")),true);
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
                exit('accident order data');
                break;
        }
        if($result){
            echo base64_encode(json_encode(array("status"=>1,"return_code"=>"success","return_msg"=>"支付成功")));
            exit();
        }else{
            echo base64_encode(json_encode(array("status"=>0,"return_code"=>"fail","return_msg"=>"支付失败")));
            exit();
        }
    }

    /**
    *sdk客户端显示支付
    */
    public function payShow(){
        $map['type'] = 1;
        $map['status'] = 1;
        $data = M("tool","tab_")->where($map)->select();
        if(empty($data)){
            echo base64_encode(json_encode(array("status"=>0,"return_code"=>"fail","return_msg"=>"暂无数据")));
            exit();
        }
        foreach ($data as $key => $value) {
            $pay_show_data[$key]['mark']  = $value['name'];
            $pay_show_data[$key]['title'] = $value['title'];
        }
        echo base64_encode(json_encode(array("status"=>0,"return_code"=>"fail","return_msg"=>"成功","pay_show_data"=>$pay_show_data)));
        exit();
    }


    /**
    *游戏返利
    */
    public function set_ratio($data){
        $map['pay_order_number']=$data;
        $spend=M("Spend","tab_")->where($map)->find();
        $reb_map['game_id']=$spend['game_id'];
        $rebate=M("Rebate","tab_")->where($reb_map)->find();
        if($rebate['ratio']==0||null==$rebate){
            return false;
        }else{
            if($rebate['money']>0&&$rebate['status']==1){
                if($spend['pay_amount']>=$rebate['money']){
                    $this->compute($spend,$rebate);
                }else{
                    return false;
                }
            }else{
                $this->compute($spend,$rebate);
            }
        }
    }

    //计算返利
    public function compute($spend,$rebate){
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
    protected function add_rebate_list($data){
        $add['pay_order_number']=$data['pay_order_number'];
        $add['game_id']=$data['game_id'];
        $add['game_name']=$data['game_name'];
        $add['user_id']=$data['user_id'];
        $add['pay_amount']=$data['pay_amount'];
        $add['ratio']=$data['ratio'];
        $add['ratio_amount']=$data['ratio_amount'];
        $add['promote_id']=$data['promote_id'];
        $add['promote_name']=$data['promote_account'];
        $add['create_time']=time();
        return $add;
    }

   private function wite_text($txt,$name){
    $myfile = fopen($name, "w") or die("Unable to open file!");
    fwrite($myfile, $txt);
    fclose($myfile);
}

    

    //竣付通支付
    public function jft_pay(){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $request = json_decode(base64_decode(file_get_contents("php://input")),true);
        C(api('Config/lists'));
          if($request['price']<0){
            $this->set_message(0,"fail","充值金额有误");
        }
        $prefix = $request['code'] == 1 ? "SP_" : "PF_";
        $out_trade_no = $prefix.date('Ymd').date('His').sp_random_string(4);
        $request['pay_order_number'] = $out_trade_no;
        $request['pay_status'] = 0;
        $request['pay_way']    = 6;
        $request['spend_ip']   = get_client_ip();
        if (C('UC_SET') == 1&&$request['is_uc']==1) {
            $uc = new Ucservice();
            $uc_user=$uc->get_user_from_uid($request['user_id']);
            if($uc_user){
                if($request['code'] == 1){
                    $game = M('GameSet',"tab_");
                    $map['game_id'] = $request['game_id'];
                    $game_data = $game->where($map)->find();
                    $uc_id = $uc->uc_recharge($request['user_id'],$uc_user['username'],$uc_user['username'],$request['game_id'],$request['game_appid'],get_game_name($request['game_id']),0,'',$uc_user['promote_id'],$uc_user['promote_account'],"",$request['pay_order_number'],$request['price'],time(),$request['extend'],$request['pay_way'],get_client_ip(),$request['sdk_version'],1,$uc_user['platform'],$game_data['pay_notify_url'],$game_data['game_key']);
                }else{
                    $uc_id = $uc->uc_deposit($request['user_id'],$uc_user['username'],$uc_user['username'],$request['game_id'],$request['game_appid'],get_game_name($request['game_id']),0,'',$uc_user['promote_id'],$uc_user['promote_account'],"",$out_trade_no,$request['price'],time(),$request['extend'],$request['pay_way'],get_client_ip(),$request['sdk_version'],1,$uc_user['platform'],$game_data['pay_notify_url'],$game_data['game_key']);
                }
            }
        }
        $user = get_user_entity($request['user_id']);
        if(!$request['is_uc']||C('UC_SET')==0||find_uc_account($user['account'])){
            if($request['code'] == 1 ){
            #TODO添加消费记录
                $this->add_spend($request);
            }else{
            #TODO添加平台币充值记录
                $this->add_deposit($request);
            }
        }
        $discount = $this->get_discount($request['game_id'],$user['promote_id'],$request['user_id']);
        $discount = $discount['discount'];
        $pay_amount = $discount * $request['price'] / 10;

        $data['status'] = 1;
        $data['return_code'] = "success";
        $data['return_msg']  = "下单成功";
        $data['ordertime']=date("Ymdhms",time());
        $data['out_trade_no'] = $out_trade_no;     
        $data['partner'] = C('jft.partner');
        $data['appid'] = C('jft.appid');
        $data['com_key'] = C('jft.key');
        $data['key'] = C('jft.appkey');
        $data['vector'] = C('jft.vector');
        $data['returnurl'] = "http://www.vlcms.com";
        $data['notifyurl'] =  "http://".$_SERVER['HTTP_HOST']."/callback.php/Notify/jft_callback";
        $data['ordertime']=date("Ymdhms",time());
        echo base64_encode(json_encode($data));
    }



}
