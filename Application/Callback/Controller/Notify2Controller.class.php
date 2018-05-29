<?php

namespace Callback\Controller;

use Org\UcenterSDK\Ucservice;

use Org\SwiftpassSDK\Swiftpass;


/**
 * 新版支付回调控制器
 * @author 小纯洁
 */
class Notify2Controller extends BaseController
{
    /**
     *通知方法
     */
    public function notify()
    {
        C(api('Config/lists'));
        $apitype = I('get.apitype');#获取支付api类型
        if (IS_POST && !empty($_POST)) {
            $notify = $_POST;
        } elseif (IS_GET && !empty($_GET)) {
            $notify = $_GET;
            unset($notify['methodtype']);
            unset($notify['apitype']);
        } else {
            $notify = file_get_contents("php://input");
            if (empty($notify)) {
                $this->record_logs("Access Denied");
                exit('Access Denied');
            }
        }

        $pay_way = $apitype;
        if ($apitype == "swiftpass") {
            $apitype = "weixin";
        }
        Vendor('Alipay.AopSdk');
        $aop = new \AopClient();
        $aop->alipayrsaPublicKey = file_get_contents("./Application/Sdk/SecretKey/alipay/alipay2_public_key.txt");
        $result = $aop->rsaCheckV1($notify,'','RSA2');
        if ($result) {
            //获取回调订单信息
            if (I('get.methodtype') == "notify") {
                $order_info = $notify;
                $order_info['real_amount'] = $order_info['total_amount'];
                if($order_info['trade_status'] == 'TRADE_SUCCESS'){
                    $pay_where = substr($order_info['out_trade_no'], 0, 2);
                    $result = false;
                    switch ($pay_where) {
                        case 'SP':
                            $Spend = M('Spend', "tab_");
                            $map['pay_order_number'] = $order_info['out_trade_no'];
                            $orderinfo = $Spend->where($map)->find();
                            $result = $this->changepaystatus($order_info, $orderinfo, 'Spend');
                            if($result){
                                $this->set_spend($orderinfo);//订单信息
                            }
                            break;
                        case 'PF':
                            $deposit = M('deposit', "tab_");
                            $map['pay_order_number'] = $order_info['out_trade_no'];
                            $orderinfo = $deposit->where($map)->find();
                            $result = $this->changepaystatus($order_info, $orderinfo, 'deposit');
                            if($result){
                               $this->set_deposit($orderinfo);
                            }
                            break;
                        case 'AG':
                            $agent = M("agent", "tab_");
                            $map['pay_order_number'] = $order_info['out_trade_no'];
                            $orderinfo = $agent->where($map)->find();
                            $result = $this->changepaystatus($order_info, $orderinfo, 'agent');
                            if($result){
                                $this->set_agent($orderinfo);
                            }
                            break;
                        default:
                            exit('accident order data');
                            break;
                    }
                    echo "success";
                }else{
                    $this->record_logs("支付失败！");
                    echo "fail";
                }
            }elseif (I('get.methodtype') == "return") {
                    $order_info = $notify;
                    $pay_where = substr($order_info['out_trade_no'], 0, 2);
                    switch ($pay_where) {
                        case 'SP':
                            $map['pay_order_number'] = $order_info['out_trade_no'];
                            $extend_data = M("spend", "tab_")->where($map)->find();
                            redirect('http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media'] . '?s=/Game/game_pay_callback/order_number/' . $order_info['out_trade_no'] . '/game_id/' . $extend_data['game_id']);
                            break;
                        case 'PF':
                            $game_id = I('get.game_id');
                            if ($game_id) {
                                redirect('http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media'] . '?s=/Game/game_pay_callback/order_number/' . $order_info['out_trade_no'] . '/game_id/' . $game_id);
                            } else {
                                redirect("http://" . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media'] . "?s=/Subscriber/user_recharge/pay_order_number/".$order_info['out_trade_no'].".html");
                            }
                            break;
                        case 'AG':
                            redirect("http://" . $_SERVER['HTTP_HOST'] . "/index.php?s=/Home/Charge/agent_pay_list");
                            break;
                        default:
                            redirect('http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media']);
                            break;
                    }
            }
        } else {
            $this->record_logs("支付验证失败");
            redirect('http://' . $_SERVER['HTTP_HOST'] . '/media.php', 3, '支付验证失败');
        }
    }

    /**
     * 威富通wap支付回调
     */
    public function swiftpass_callback(){
        $xml = file_get_contents('php://input');
        $Swiftpass=new Swiftpass(C('weixin.partner'),C('weixin.key'));
        $Swiftpass->resHandler->setContent($xml);
        $Swiftpass->resHandler->setKey(C('weixin.key'));
        if($Swiftpass->resHandler->isTenpaySign()){
            if($Swiftpass->resHandler->getParameter('status') == 0 && $Swiftpass->resHandler->getParameter('result_code') == 0){
                $pay_where = substr($Swiftpass->resHandler->getParameter('out_trade_no'),0,2);
                $order_info['trade_no']=$Swiftpass->resHandler->getParameter('transaction_id');
                $order_info['out_trade_no']=$Swiftpass->resHandler->getParameter('out_trade_no');
                $order_info['real_amount']=$Swiftpass->resHandler->getParameter('total_fee');
                $result = false;
                switch ($pay_where) {
                    case 'SP':
                        $Spend = M('Spend', "tab_");
                        $map['pay_order_number'] = $order_info['out_trade_no'];
                        $orderinfo = $Spend->where($map)->find();
                        $result = $this->changepaystatus($order_info, $orderinfo, 'Spend');
                        if($result){
                            $this->set_spend($orderinfo);//订单信息
                        }
                        break;
                    case 'PF':
                        $deposit = M('deposit', "tab_");
                        $map['pay_order_number'] = $order_info['out_trade_no'];
                        $orderinfo = $deposit->where($map)->find();
                        $result = $this->changepaystatus($order_info, $orderinfo, 'deposit');
                        if($result){
                            $this->set_deposit($orderinfo);
                        }
                        break;
                    case 'AG':
                        $agent = M("agent", "tab_");
                        $map['pay_order_number'] = $order_info['out_trade_no'];
                        $orderinfo = $agent->where($map)->find();
                        $result = $this->changepaystatus($order_info, $orderinfo, 'agent');
                        if($result){
                            $this->set_agent($orderinfo);
                        }
                        break;
                    default:
                        exit('accident order data');
                        break;
                }
                echo 'success';
                exit();
            }else{
                echo 'failure';
                exit();
            }
        }else{
            echo 'failure';
        }

    }

    public function goldpig_callback(){
        $arr = $_POST;
        //接口ID
        $UserID='357p';//此项固定为357p
        //接口密钥
        $Key=C('goldpig.key');//此项需要您设置，和金猪平台一致

        $ProID=$arr['ProID'];//产品ID

        $OrderID=$arr['OrderID'];//订单号

        $Num=$arr['Num'];//充值数量

        $UserName=$arr['UserName'];//充值账号或角色名

        $Money=$arr['Money'];//充值金额

        $yuanbao=$arr['yuanbao'];//货币数量

        $Sign=$arr['Sign'];//与金猪服务器通讯加密字符串

        $fencheng=$arr['fencheng'];//商户分成金额，适用于纯接口模式
        $jinzhua=$arr['jinzhua'];//平台订单号
        $jinzhub=$arr['jinzhub'];//平台验证参数
        $jinzhuc=$arr['jinzhuc'];//预留回调3

        $Str='UserID='.$UserID.'&ProID='.$ProID.'&OrderID='.$OrderID.'&Num='.$Num.'&yuanbao='.$yuanbao.'&UserName='.$UserName.'&Money='.$Money.'&Key='.$Key;
        $MySign=md5($Str);
        if(trim($Sign)==trim($MySign)){
            $ptsignarr['UserName'] = $arr['UserName'];
            $ptsignarr['fee'] = $Money;
            $ptsignarr['jinzhua'] = $arr['jinzhua'];
            $ptjinzhub = signsortData($ptsignarr,C('goldpig.key'));
            // if($ptjinzhub!=$jinzhub){
            if(0){
                exit('平台 sign验证失败');
            }else{
                $pay_where = substr($jinzhua,0,2);
                $order_info['trade_no']=$arr['OrderID'];
                $order_info['out_trade_no']=$jinzhua;
                $order_info['real_amount']=$ptsignarr['fee'];
                $result = false;
                switch ($pay_where) {
                    case 'SP':
                        $Spend = M('Spend', "tab_");
                        $map['pay_order_number'] = $order_info['out_trade_no'];
                        $orderinfo = $Spend->where($map)->find();
                        $result = $this->changepaystatus($order_info, $orderinfo, 'Spend');
                        if($result){
                            $this->set_spend($orderinfo);//订单信息
                        }
                        break;
                    case 'PF':
                        $deposit = M('deposit', "tab_");
                        $map['pay_order_number'] = $order_info['out_trade_no'];
                        $orderinfo = $deposit->where($map)->find();
                        $result = $this->changepaystatus($order_info, $orderinfo, 'deposit');
                        if($result){
                            $this->set_deposit($orderinfo);
                        }
                        break;
                    case 'AG':
                        $agent = M("agent", "tab_");
                        $map['pay_order_number'] = $order_info['out_trade_no'];
                        $orderinfo = $agent->where($map)->find();
                        $result = $this->changepaystatus($order_info, $orderinfo, 'agent');
                        if($result){
                            $this->set_agent($orderinfo);
                        }
                        break;
                    default:
                        exit('accident order data');
                        break;
                }
                exit('success');
            }
        }else{
            exit('goldpig sign验证失败');
        }
    }

    /**
     * 支付宝退款回调
     * @return [type] [description]
     */
    public function refund_validation()
    {
        if (empty($_POST)) {
            $this->record_logs("回调！");
        } else {
            $pay = new \Think\Pay('alipay', C('alipay'));

            if ($pay->verifyNotify($_POST)) {
                //批次号
                $batch_no = $_POST['batch_no'];
                //批量退款数据中转账成功的笔数
                $success_num = $_POST['success_num'];
                if ($success_num > 0) {
                    $map['batch_no'] = $batch_no;
                    $date['tui_status'] = 1;
                    $date['tui_time'] = time();
                    M('refund_record', 'tab_')->where($map)->save($date);
                    file_put_contents(dirname(__FILE__)."/as.txt", json_encode(M('refund_record','tab_')->getlastsql()));
                   
                    $map_spend['pay_order_number'] = get_refund_pay_order_number($batch_no);
                    $spen_date['sub_status']=1;
                    $spen_date['settle_check']= 1;
                    M('spend','tab_')->where($map_spend)->save($spen_date);
                }
                echo "success";        //请不要修改或删除

            } else {
                //验证失败
                echo "fail";
            }
        }
    }


    /**
     *判断平台币充值是否存在
     */

    protected function deposit_is_exist($out_trade_no)
    {

        $deposit = M('deposit', 'tab_');

        $map['pay_status'] = 1;

        $map['pay_order_number'] = $out_trade_no;

        $res = $deposit->where($map)->find();

        if (empty($res)) {

            return false;

        } else {

            return true;

        }

    }


    //判断充值是否存在

    public function recharge_is_exist($out_trade_no)
    {

        $recharge = M('spend', 'tab_');

        $map['pay_status'] = 1;

        $map['pay_order_number'] = $out_trade_no;

        $res = $recharge->where($map)->find();

        if (empty($res)) {

            return false;

        } else {

            return true;

        }

    }


    //判断代充是否存在

    public function agent_is_exist($out_trade_no)
    {

        $recharge = M('agent', 'tab_');

        $map['pay_status'] = 1;

        $map['pay_order_number'] = $out_trade_no;

        $res = $recharge->where($map)->find();

        if (empty($res)) {

            return false;

        } else {

            return true;

        }

    }



}