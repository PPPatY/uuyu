<?php
namespace Channel\Controller;
use Common\Api\GameApi;
use Think\Controller;
use Org\WeixinSDK\Weixin;
class PayController extends BaseController{

    private function pay($param=array()){
        $out_trade_no = "AG_".date('Ymd').date('His').sp_random_string(4);
        $user = get_user_entity($param['account'],true);
        $game_set_data = get_game_set_info($param['game_id']);

        switch ($param['apitype']) {
            case 'swiftpass':
                $pay  = new \Think\Pay($param['apitype'],$param['config']);
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
            ->setPayMethod("mobile")
            ->setTable("agent")
            ->setPayWay($param['payway'])
            ->setGameId($param['game_id'])
            ->setGameName($param['game_name'])
            ->setGameAppid($param['game_appid'])
            ->setUserId($param['user_id'])
            ->setAccount($user['account'])
            ->setUserNickName($user['nickname'])
            ->setPromoteId($user['promote_id'])
            ->setPromoteName($user['promote_account'])
            ->setExtend($param['extend'])
            ->setSdkVersion($param['sdk_version'])
            ->setParam($param['zhekou'])
            ->setMoney($param['amount']);
        return $pay->buildRequestForm($vo);
    }

    /**
    *支付宝移动支付
    */
    public function alipay_pay($request=''){
        // 获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $request = json_decode(base64_decode(file_get_contents("php://input")),true);
        if(empty($request)){
            $this->set_message(0,"fail","数据不能为空");
        }
        $request['apitype'] = "alipay";
        $request['config']  = "alipay";
        $request['signtype']= "MD5";
        $request['server']  = "mobile.securitypay.pay";
        $request['payway']  = 1;
        $data = $this->pay($request);

        $md5_sign = $this->encrypt_md5(base64_encode($data['arg']),"mengchuang");
        $data = array("status"=>1,"orderInfo"=>base64_encode($data['arg']),"out_trade_no"=>$data['out_trade_no'],"order_sign"=>$data['sign'],"md5_sign"=>$md5_sign);
        echo base64_encode(json_encode($data));
    }

    public function jubaobar_pay(){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $request = json_decode(base64_decode(file_get_contents("php://input")),true);
        if(empty($request)){
            $this->set_message(0,"fail","数据不能为空");
        }
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
        if(empty($request)){
            $this->set_message(0,"fail","数据不能为空");
        }
        #记录信息
        $user_entity = get_user_entity($request['user_id']);
        $out_trade_no = "SP_".date('Ymd').date('His').sp_random_string(4);
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
                #添加绑定平台币消费记录
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
        }else{
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
}
