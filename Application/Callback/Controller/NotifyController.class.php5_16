<?php

namespace Callback\Controller;

use Common\Api\GameApi;
use Org\UcenterSDK\Ucservice;

/**
 * 支付回调控制器
 * @author 小纯洁
 */
class NotifyController extends BaseController
{
    /**
     *通知方法
     */
    public function notify($value = '')
    {
        $apitype = I('get.apitype');#获取支付api类型
        if (IS_POST && !empty($_POST)) {
            $notify = $_POST;
        } elseif (IS_GET && !empty($_GET)) {
            $notify = $_GET;
            unset($notify['method']);
            unset($notify['apitype']);
            unset($notify['game_id']);
        } else {
            $this->record_logs("Access Denied");
            exit('Access Denied');
        }
        $pay = new \Think\Pay($apitype, C($apitype));
        if ($pay->verifyNotify($notify)) {
            //获取回调订单信息
            $order_info = $pay->getInfo();
            $order_info['real_amount'] = $order_info['total_amount'];
            $pay_where = substr($order_info['out_trade_no'], 0, 2);
            if ($order_info['status']) {
                if (I('get.method') != "return") {
                    $this->record_logs($order_info['trade_no']);
                    $depos_trade_no = M('deposit', 'tab_')->where(array('order_number' => $order_info['trade_no']))->find();//平台币表验证订单重复
                    $spend_trade_no = M('spend', 'tab_')->where(array('order_number' => $order_info['trade_no']))->find();//消费表验证订单重复
                    if (!empty($depos_trade_no) || !empty($spend_trade_no)) {
                        $this->record_logs("订单重复");
                        exit('订单重复');
                    }
                }
                $config = M('config', 'sys_')->field('value')->where(array('name' => 'UC_SET'))->find();
                $this->notify_kz(I('get.method'), $order_info, I('get.apitype'));
            } else {
                $this->record_logs("支付失败！");
            }
        } else {
            $this->record_logs("支付宝验证失败");
            redirect('http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media'], 3, '支付宝验证失败');
        }
    }

    function notify_kz($mothod, $order_info, $apitype)
    {
        $pay_where = substr($order_info['out_trade_no'], 0, 2);
        $result = false;
        switch ($pay_where) {
            case 'SP':
                $Spend = M('Spend', "tab_");
                $map['pay_order_number'] = $order_info['out_trade_no'];
                $orderinfo = $Spend->where($map)->find();
                $result = $this->changepaystatus($order_info, $orderinfo, 'Spend');
                break;
            case 'PF':
                $deposit = M('deposit', "tab_");
                $map['pay_order_number'] = $order_info['out_trade_no'];
                $orderinfo = $deposit->where($map)->find();
                $result = $this->changepaystatus($order_info, $orderinfo, 'deposit');
                break;
            case 'AG':
                $agent = M("agent", "tab_");
                $map['pay_order_number'] = $order_info['out_trade_no'];
                $orderinfo = $agent->where($map)->find();
                $result = $this->changepaystatus($order_info, $orderinfo, 'agent');
                break;
            default:
                exit('accident order data');
                break;
        }
        if ($mothod == "return") {
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

                        redirect("http://" . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media'] . "?s=/Subscriber/index.html");
                    }
                    break;
                case 'AG':
                    redirect("http://" . $_SERVER['HTTP_HOST'] . "/index.php?s=/Home/Charge/agent_pay_list");
                    break;
                default:
                    redirect('http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media']);
                    break;
            }

        } else {
            if ($result) {
                $pay = new \Think\Pay($apitype, C($apitype));
                $pay->notifySuccess();
                $this->record_logs("继续执行！", 'INFO');
                //执行充值操作
                switch ($pay_where) {
                    case 'SP':
                        $recharge_resule = $this->set_spend($orderinfo);//订单信息
                        break;
                    case 'PF':
                        $recharge_resule = $this->set_deposit($orderinfo);
                        break;
                    case 'AG':
                        $recharge_resule = $this->set_agent($orderinfo);
                        break;
                    default:
                        exit('accident order data');
                        break;
                }
            }
        }
    }

       
    /**
     * 竣付通回调
     * @return [type] [description]
     */


    public function jft_callback(){
        @$p7_paychannelnum=$_POST['p7_paychannelnum'];
        if(empty($p7_paychannelnum))
        {
            $p7_paychannelnum="";
        }
        $signmsg=C('jft.key');//支付秘钥
        @$md5info_paramet = $_REQUEST['p1_usercode']."&".$_REQUEST['p2_order']."&".$_REQUEST['p3_money']."&".$_REQUEST['p4_status']."&".$_REQUEST['p5_jtpayorder']."&".$_REQUEST['p6_paymethod']."&".$_REQUEST['p7_paychannelnum']."&".$_REQUEST['p8_charset']."&".$_REQUEST['p9_signtype']."&".$signmsg;
        $md5info_tem= strtoupper(md5($md5info_paramet));
        $requestsign=$_REQUEST['p10_sign'];
        if ($md5info_tem == $_REQUEST['p10_sign'])
                {
                    $order_info['trade_no'] = $_REQUEST['p5_jtpayorder'];
                    $order_info['out_trade_no'] = $_REQUEST['p2_order'];
                    $order_info['real_amount'] = $_REQUEST['p3_money'];
                    $pay_where = substr($_REQUEST['p2_order'], 0, 2);
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

            //改变订单状态，及其他业务修改
            echo "success";
                
                switch ($pay_where) {
                    case 'SP':
                        redirect('http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media'] . '?s=/Game/game_pay_callback/order_number/' . $order_info['out_trade_no'] . '/game_id/' . $extends['game_id']);
                        break;
                    case 'PF':
                        $game_id = I('get.game_id');
                        if ($game_id) {
                            redirect('http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media'] . '?s=/Game/game_pay_callback/order_number/' . $order_info['out_trade_no'] . '/game_id/' . $game_id);
                        } else {

                            redirect("http://" . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media'] . "?s=/Subscriber/index.html");
                        }
                        break;
                    case 'AG':
                        redirect("http://" . $_SERVER['HTTP_HOST'] . "/index.php?s=/Home/Charge/agent_pay_list");
                        break;
                    default:
                        redirect('http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media']);
                        break;
                }

            // //改变订单状态，及其他业务修改
            // echo "success";
            //接收通知后必须输出”success“代表接收成功。
             exit();

        }else{
            $this->record_logs("竣付通验证失败！！");
        }

    }



    /**
     * 汇付宝回调
     */
    public function  heepay_notify()
    {

        $result = $_GET['result'];
        $pay_message = $_GET['pay_message'];
        $agent_id = $_GET['agent_id'];
        $jnet_bill_no = $_GET['jnet_bill_no'];
        $agent_bill_id = $_GET['agent_bill_id'];
        $pay_type = $_GET['pay_type'];
        $pay_amt = $_GET['pay_amt'];
        $remark = $_GET['remark'];
        $return_sign = $_GET['sign'];

        $remark = iconv("GB2312", "UTF-8//IGNORE", urldecode($remark));//签名验证中的中文采用UTF-8编码;

        $signStr = '';
        $signStr = $signStr . 'result=' . $result;
        $signStr = $signStr . '&agent_id=' . $agent_id;
        $signStr = $signStr . '&jnet_bill_no=' . $jnet_bill_no;
        $signStr = $signStr . '&agent_bill_id=' . $agent_bill_id;
        $signStr = $signStr . '&pay_type=' . $pay_type;

        $signStr = $signStr . '&pay_amt=' . $pay_amt;
        $signStr = $signStr . '&remark=' . $remark;

        $signStr = $signStr . '&key=' . C('heepay.key'); //商户签名密钥

        $sign = '';
        $sign = md5($signStr);
        if ($sign == $return_sign) {   //比较签名密钥结果是否一致，一致则保证了数据的一致性
            echo 'ok';
            //商户自行处理自己的业务逻辑
            $pay_where = substr($agent_bill_id, 0, 2);
            $order_info['out_trade_no']=$agent_bill_id;
            $order_info['trade_no']=$jnet_bill_no;
            $order_info['real_amount']=$pay_amt;
            $result = false;
            switch ($pay_where) {
                case 'SP':
                    $Spend = M('Spend', "tab_");
                    $map['pay_order_number'] = $agent_bill_id;
                    $orderinfo = $Spend->where($map)->find();
                    $result = $this->changepaystatus($order_info, $orderinfo, 'Spend');
                    break;
                case 'PF':
                    $deposit = M('deposit', "tab_");
                    $map['pay_order_number'] = $agent_bill_id;
                    $orderinfo = $deposit->where($map)->find();
                    $result = $this->changepaystatus($order_info, $orderinfo, 'deposit');
                    break;
                case 'AG':
                    $agent = M("agent", "tab_");
                    $map['pay_order_number'] =$agent_bill_id;
                    $orderinfo = $agent->where($map)->find();
                    $result = $this->changepaystatus($order_info, $orderinfo, 'agent');
                    break;
                default:
                    exit('accident order data');
                    break;
            }
            if (I('get.method') == "return") {
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

                            redirect("http://" . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media'] . "?s=/Subscriber/index.html");
                        }
                        break;
                    case 'AG':
                        redirect("http://" . $_SERVER['HTTP_HOST'] . "/index.php?s=/Home/Charge/agent_pay_list");
                        break;
                    default:
                        redirect('http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media']);
                        break;
                }

            } else {
                if ($result) {
                    $this->record_logs("继续执行！", 'INFO');
                    //执行充值操作
                    switch ($pay_where) {
                        case 'SP':
                            $recharge_resule = $this->set_spend($orderinfo);//订单信息
                            break;
                        case 'PF':
                            $recharge_resule = $this->set_deposit($orderinfo);
                            break;
                        case 'AG':
                            $recharge_resule = $this->set_agent($orderinfo);
                            break;
                        default:
                            exit('accident order data');
                            break;
                    }
                }
            }
        } else {
            echo 'error';
            //商户自行处理，可通过查询接口更新订单状态，也可以通过商户后台自行补发通知，或者反馈运营人工补发
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
                    file_put_contents(dirname(__FILE__) . "/as.txt", json_encode(M('refund_record', 'tab_')->getlastsql()));

                    $map_spend['pay_order_number'] = get_refund_pay_order_number($batch_no);
                    $spen_date['sub_status'] = 1;
                    $spen_date['settle_check'] = 1;
                    M('spend', 'tab_')->where($map_spend)->save($spen_date);
                }
                echo "success";        //请不要修改或删除

            } else {
                //验证失败
                echo "fail";
            }
        }
    }


    /**
     *微信支付//威富通
     */
    public function weixin_notify()
    {
        $request = file_get_contents("php://input");
        $apitype = "swiftpass";
        $pay = new \Think\Pay($apitype, C("weixin"));
        if ($pay->verifyNotify($request)) {
            //获取回调订单信息
            $order_info = $pay->getInfo();
            $order_info['real_amount'] = $order_info['total_fee'];
            if ($order_info['status']) {
                $depos_trade_no = M('deposit', 'tab_')->where(array('order_number' => $order_info['trade_no']))->find();//平台币表验证订单重复
                $spend_trade_no = M('spend', 'tab_')->where(array('order_number' => $order_info['trade_no']))->find();//消费表验证订单重复
                if (($depos_trade_no && $depos_trade_no['pay_status']) || ($spend_trade_no && $spend_trade_no['pay_status'])) {
                    $this->record_logs("订单重复");
                    exit('订单重复');
                }
                $pay_where = substr($order_info['out_trade_no'], 0, 2);
                $result = false;
                $config = M('config', 'sys_')->field('value')->where(array('name' => 'UC_SET'))->find();
                if ($config['value'] == 1) {
                    $map1['pay_order_number'] = $order_info['out_trade_no'];
                    switch ($pay_where) {
                        case 'SP':
                            $res = M('spend', 'tab_')->where($map1)->find();
                            break;
                        case 'PF':
                            $res = M('deposit', 'tab_')->where($map1)->find();
                            break;
                        default:
                            $res = 1;
                            break;
                    }
                    $uc = new Ucservice();
                    $extends = $uc->uc_spend_find($order_info['out_trade_no']);
                    if (!$res) {
                        if ($extends) {
                            if ($extends['pay_status'] !== 1) {
                                $result1 = $uc->uc_rechange_notify($order_info['out_trade_no'], 1);
                            }
                            if ($result) {
                                $extends['uc'] = 1;
                                $this->set_spend($extends);
                            } else {
                                exit('支付状态修改失败');
                            }
                        } else {
                            exit("订单不存在");
                        }
                    } else {
                        switch ($pay_where) {
                            case 'SP':
                                $this->record_logs($order_info['trade_no']);
                                $Spend = M('Spend', "tab_");
                                $map['pay_order_number'] = $order_info['out_trade_no'];
                                $orderinfo = $Spend->where($map)->find();
                                $result = $this->changepaystatus($order_info, $orderinfo, 'Spend');
                                break;
                            case 'PF':
                                $deposit = M('deposit', "tab_");
                                $map['pay_order_number'] = $order_info['out_trade_no'];
                                $orderinfo = $deposit->where($map)->find();
                                $result = $this->changepaystatus($order_info, $orderinfo, 'deposit');
                                break;
                            case 'AG':
                                $agent = M("agent", "tab_");
                                $map['pay_order_number'] = $order_info['out_trade_no'];
                                $orderinfo = $agent->where($map)->find();
                                $result = $this->changepaystatus($order_info, $orderinfo, 'agent');
                                break;
                            default:
                                exit('accident order data');
                                break;
                        }
                        if ($result) {
                            echo 'success';
                            switch ($pay_where) {
                                case 'SP':
                                    $extends['uc'] = 1;
                                    $recharge_resule = $this->set_spend($extends);//订单信息
                                    break;
                                case 'PF':
                                    $recharge_resule = $this->set_deposit($orderinfo);
                                    break;
                                case 'AG':
                                    $recharge_resule = $this->set_agent($orderinfo);
                                    break;
                                default:
                                    redirect('http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media']);
                                    break;
                            }
                        }
                    }
                } else {
                    switch ($pay_where) {
                        case 'SP':
                            $this->record_logs($order_info['trade_no']);
                            $Spend = M('Spend', "tab_");
                            $map['pay_order_number'] = $order_info['out_trade_no'];
                            $orderinfo = $Spend->where($map)->find();
                            $result = $this->changepaystatus($order_info, $orderinfo, 'Spend');
                            break;
                        case 'PF':
                            $deposit = M('deposit', "tab_");
                            $map['pay_order_number'] = $order_info['out_trade_no'];
                            $orderinfo = $deposit->where($map)->find();
                            $result = $this->changepaystatus($order_info, $orderinfo, 'deposit');
                            break;
                        case 'AG':
                            $agent = M("agent", "tab_");
                            $map['pay_order_number'] = $order_info['out_trade_no'];
                            $orderinfo = $agent->where($map)->find();
                            $result = $this->changepaystatus($order_info, $orderinfo, 'agent');
                            break;
                        default:
                            exit('accident order data');
                            break;
                    }
                    if ($result) {
                        echo 'success';
                        switch ($pay_where) {
                            case 'SP':
                                $recharge_resule = $this->set_spend($orderinfo);//订单信息
                                break;
                            case 'PF':
                                $recharge_resule = $this->set_deposit($orderinfo);
                                break;
                            case 'AG':
                                $recharge_resule = $this->set_agent($orderinfo);
                                break;
                            default:
                                redirect('http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['media']);
                                break;
                        }
                    }
                }
            } else {
                $this->record_logs("支付失败！");
            }
        } else {
            $this->record_logs("微信验证失败");
        }
    }


    /**
     *微信回调//微信官方
     */
    public function wxpay_callback()
    {
        $values = array();
        Vendor("WxPayPubHelper.WxPayPubHelper");
        $weixin = A("WeiXin", "Event");
        $request = file_get_contents("php://input");
        $reqdata = $weixin->xmlstr_to_array($request);
        if ($reqdata['return_code'] != 'SUCCESS') {
            $this->record_logs("return_code返回数据错误");
            exit();
        } else {
            if(strtolower($reqdata['trade_type'])=='app'){
                $Common_util_pub = new \Common_util_pub(C('wei_xin_app.email'), C('wei_xin_app.partner'), C('wei_xin_app.key'));
            }else{
                $Common_util_pub = new \Common_util_pub(C('wei_xin.email'), C('wei_xin.partner'), C('wei_xin.key'));
            }
            if ($Common_util_pub->getSign($reqdata) == $reqdata['sign']) {
                $depos_trade_no = M('deposit', 'tab_')->where(array('order_number' => $reqdata['transaction_id']))->find();//平台币表验证订单重复
                $spend_trade_no = M('spend', 'tab_')->where(array('order_number' => $reqdata['transaction_id']))->find();//消费表验证订单重复
                if (($depos_trade_no && $depos_trade_no['pay_status']) || ($spend_trade_no && $spend_trade_no['pay_status'])) {
                    $this->record_logs("订单重复");
                    exit('订单重复');
                }
                $pay_where = substr($reqdata['out_trade_no'], 0, 2);
                $data['trade_no'] = $reqdata['transaction_id'];
                $data['out_trade_no'] = $reqdata['out_trade_no'];
                $data['real_amount'] = $reqdata['total_fee'];
                $config = M('config', 'sys_')->field('value')->where(array('name' => 'UC_SET'))->find();
                if ($config['value'] == 1) {
                    $map1['pay_order_number'] = $order_info['out_trade_no'];
                    switch ($pay_where) {
                        case 'SP':
                            $res = M('spend', 'tab_')->where($map1)->find();
                            break;
                        case 'PF':
                            $res = M('deposit', 'tab_')->where($map1)->find();
                            break;
                        default:
                            $res = 1;
                            break;
                    }
                    $uc = new Ucservice();
                    $extends = $uc->uc_spend_find($data['out_trade_no']);
                    if (!$res) {
                        if ($extends) {
                            if ($extends['pay_status'] !== 1) {
                                $result1 = $uc->uc_rechange_notify($reqdata['out_trade_no'], 1);
                            }
                            if ($result1) {
                                $extends['uc'] = 1;
                                $this->set_spend($extends);
                            } else {
                                exit('支付状态修改失败');
                            }
                        } else {
                            $this->record_logs("订单不存在");
                            exit('订单不存在');
                        }
                    } else {
                        switch ($pay_where) {
                            case 'SP'://充值游戏
                                if ($this->recharge_is_exist($reqdata['out_trade_no'])) {
                                    echo " <xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                    exit();
                                }
                                $Spend = M('Spend', "tab_");
                                $map['pay_order_number'] = $data['out_trade_no'];
                                $orderinfo = $Spend->where($map)->find();
                                $result = $this->changepaystatus($data, $orderinfo, 'Spend');
                                if ($result) {
                                    echo " <xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                    $this->record_logs("继续执行！", 'INFO');
                                    $recharge_resule = $this->set_spend($orderinfo);
                                } else {
                                    echo " <xml> <return_code><![CDATA[FAILURE]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                }
                                break;
                            case 'PF'://充值平台币
                                if ($this->deposit_is_exist($reqdata["out_trade_no"])) {
                                    echo " <xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                    exit();
                                }
                                $deposit = M('deposit', "tab_");
                                $map['pay_order_number'] = $data['out_trade_no'];
                                $orderinfo = $deposit->where($map)->find();
                                $result = $this->changepaystatus($data, $orderinfo, 'deposit');
                                if ($result) {
                                    echo " <xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                    $this->record_logs("继续执行！", 'INFO');
                                    $recharge_resule = $this->set_deposit($orderinfo);
                                } else {
                                    echo " <xml> <return_code><![CDATA[FAILURE]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                }
                                break;
                            case 'AG'://代充
                                if ($this->agent_is_exist($reqdata["out_trade_no"])) {
                                    echo " <xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                    exit();
                                }
                                $agent = M("agent", "tab_");
                                $map['pay_order_number'] = $data['out_trade_no'];
                                $orderinfo = $agent->where($map)->find();
                                $result = $this->changepaystatus($data, $orderinfo, 'agent');
                                if ($result) {
                                    echo " <xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                    $this->record_logs("继续执行！", 'INFO');
                                    $recharge_resule = $this->set_agent($orderinfo);
                                } else {
                                    echo " <xml> <return_code><![CDATA[FAILURE]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                }
                                break;

                            default:
                                $this->record_logs("订单号错误！！");
                                break;
                        }
                    }
                } else {
                    switch ($pay_where) {
                        case 'SP'://充值游戏
                            if ($this->recharge_is_exist($reqdata['out_trade_no'])) {
                                echo " <xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                exit();
                            }
                            $Spend = M('Spend', "tab_");
                            $map['pay_order_number'] = $data['out_trade_no'];
                            $orderinfo = $Spend->where($map)->find();
                            $result = $this->changepaystatus($data, $orderinfo, 'Spend');
                            if ($result) {
                                echo " <xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                $this->record_logs("继续执行！", 'INFO');
                                $recharge_resule = $this->set_spend($orderinfo);
                            } else {
                                echo " <xml> <return_code><![CDATA[FAILURE]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                            }
                            break;
                        case 'PF'://充值平台币
                            if ($this->deposit_is_exist($reqdata["out_trade_no"])) {
                                echo " <xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                exit();
                            }
                            $deposit = M('deposit', "tab_");
                            $map['pay_order_number'] = $data['out_trade_no'];
                            $orderinfo = $deposit->where($map)->find();
                            $result = $this->changepaystatus($data, $orderinfo, 'deposit');
                            if ($result) {
                                echo " <xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                $this->record_logs("继续执行！", 'INFO');
                                $recharge_resule = $this->set_deposit($orderinfo);
                            } else {
                                echo " <xml> <return_code><![CDATA[FAILURE]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                            }
                            break;
                        case 'AG'://代充
                            if ($this->agent_is_exist($reqdata["out_trade_no"])) {
                                echo " <xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                exit();
                            }
                            $agent = M("agent", "tab_");
                            $map['pay_order_number'] = $data['out_trade_no'];
                            $orderinfo = $agent->where($map)->find();
                            $result = $this->changepaystatus($data, $orderinfo, 'agent');
                            if ($result) {
                                echo " <xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                                $this->record_logs("继续执行！", 'INFO');
                                $recharge_resule = $this->set_agent($orderinfo);
                            } else {
                                echo " <xml> <return_code><![CDATA[FAILURE]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
                            }
                            break;

                        default:
                            $this->record_logs("订单号错误！！");
                            break;
                    }
                }

            } else {
                $this->record_logs("支付验证失败");
                redirect('http://' . $_SERVER['HTTP_HOST'] . '/front.php/Recharge/index.html', 3, '支付验证失败');
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