<?php

namespace Think\Pay\Driver;

class Alipay extends \Think\Pay\Pay {

    protected $gateway = 'https://mapi.alipay.com/gateway.do';
    protected $page_gateway = 'http://wappaygw.alipay.com/service/rest.htm';
    protected $verify_url = 'http://notify.alipay.com/trade/notify_query.do';
    protected $config = array(
        'email' => '',
        'key' => '',
        'partner' => ''
    );

    public function check() {
        if (!$this->config['email'] || !$this->config['key'] || !$this->config['partner']) {
            E("支付宝设置有误！");
        }
        return true;
    }

    public function buildRequestForm(\Think\Pay\PayVo $vo) {
        header("Content-type:text/html;charset=utf-8");
        //$seller = $vo->getPayMethod()=="mobile"?"seller_id":"seller_email";
    if ($vo->getPayMethod() == "refund") {
            $param = array(
                'service' => $vo->getService(),//create_direct_pay_by_user
                'payment_type' => '1',
                '_input_charset' => 'utf-8',
                'partner' => $this->config['partner'],
                'notify_url' => 'http://' . $_SERVER ['HTTP_HOST'] . "/callback.php/Notify/refund_validation"
            );
        } else {
        $param = array(
            'service'        => $vo->getService(),//create_direct_pay_by_user
            'payment_type'   => '1',
            '_input_charset' => 'utf-8',
            'seller_id'      => $this->config['partner'],//seller_email
            'partner'        => $this->config['partner'],
            'notify_url'     => $this->config['notify_url'],
            'return_url'     => $this->config['return_url'],
            'out_trade_no'   => $vo->getOrderNo(),
            'subject'        => $vo->getTitle(),
            'body'           => $vo->getBody(),
            'total_fee'      => $vo->getFee(),
            // 'total_fee'      => 0.01,//测试金额  务必改回
            'it_b_pay'       => '30m'
        );
}
        switch ($vo->getPayMethod()) {
            case 'direct':
                $param['seller_email'] = $this->config['email'];
                break;
            case 'mobile':
                $param['seller_id'] = $this->config['email'];
                $param['app_pay'] = 'Y';
                break;
            case 'wap':
                $param['seller_id'] = $this->config['partner'];

                $table=$vo->getTable();
                if($table=='spend'){
                    $param['show_url']  = 'http://'.$_SERVER['HTTP_HOST'].'/'.$_SESSION['media'].'?s=/Game/game_pay_callback/order_number/'.$param['out_trade_no'].'/game_id/'.$vo->getGameId();
                }else{
                    if($vo->getGameId()){
                        $param['show_url']  = 'http://'.$_SERVER['HTTP_HOST'].'/'.$_SESSION['media'].'?s=/Game/game_pay_callback/order_number/'.$param['out_trade_no'].'/game_id/'.$vo->getGameId();
                    }else{
                        $param['show_url']  = 'http://'.$_SERVER['HTTP_HOST'].'/'.$_SESSION['media'].'?s=/Subscriber/index.html';
                    }
                }
                break;
            case 'refund':
                $param['seller_email'] = $this->config['email'];
                $param['refund_date'] = date("Y-m-d H:i:s");
                $param['batch_no'] = $vo->getBatchNo();
                $param['batch_num'] = 1;//总数量
                $param['detail_data'] = $vo->getDetailData();
                break;
            default:
                $param['seller_email'] = $this->config['email'];
                break;
        }

        #对数组进行排序
        $param = $this->argSort($param);
        // $wap_type=$vo->getPayMethod()=="wap"?"alipay_wap":"";
        switch ($vo->getPayMethod()) {
            case 'refund':
            case 'wap':
                $wap_type = "alipay_wap";
                break;
            default:
                $wap_type = "";
                break;
        }
        switch ($vo->getPayMethod()) {
            case 'wap':
            case 'direct':
            case 'refund':
                #把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
                $arg = $this->createLinkstring($param);
                $param['sign'] = md5($arg . $this->config['key']);
                $param['sign_type'] = 'MD5';
                $sHtml = $this->_buildForm($param, $this->gateway, 'get', 'utf-8', $wap_type);

                break;
            case 'mobile':
                #把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串 并对字符串做urlencode编码
                $arg = $this->createLinkstring($param);
                $param['sign'] = $this->sign($arg);
                $sHtml['arg']  = $arg."&sign=".urlencode($param['sign'])."&sign_type=RSA";
                $sHtml['sign'] = $param['sign'];
                $sHtml['out_trade_no'] = $vo->getOrderNo();
                break;
        }
        return $sHtml;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    protected function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        
        return $arg;
    }
    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    protected function createLinkstringUrlencode($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".urlencode($val)."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        return $arg;
    }
    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    protected function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $key == "sign_type" || $val == "")continue;
            else    $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }
    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    protected function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    //RSA签名
    public function sign($data) {
        //读取私钥文件
        $priKey = file_get_contents("./Application/Sdk/SecretKey/alipay/rsa_private_key.pem");//私钥文件路径
        //转换为openssl密钥，必须是没有经过pkcs8转换的私钥
        $res = openssl_get_privatekey($priKey);
        //$res = openssl_pkey_get_private($priKey);
        //调用openssl内置签名方法，生成签名$sign
        openssl_sign($data, $sign, $res);
        //释放资源
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    //验签
    public function rsa_verify($data, $sign) {
        // 读取公钥文件
        $pubKey = file_get_contents("./Application/Sdk/SecretKey/alipay/ali_public_key.pem");//私钥文件路径
        // 转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);
        // 调用openssl内置方法验签，返回bool值
        $result = ( bool ) openssl_verify ( $data, base64_decode ( $sign ), $res );
        // 释放资源
        openssl_free_key ( $res );   
        return $result;
    }

    /**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
    protected function getSignVeryfy($param, $sign) {
        
        $param_filter = array();
        #除去待签名参数数组中的空值和签名参数
        $param_filter = $this->paraFilter($param);
        #对数组排序
        $param_filter = $this->argSort($param_filter);

        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($param_filter);
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $isSgin = false;
        switch (strtoupper(trim($param['sign_type']))) {
            case "MD5" :
                $mysgin = MD5($prestr . $this->config['key']);
                $isSgin = $mysgin == $sign ? true:false;
                break;
            case "RSA" :
                $isSgin = $this->rsa_verify($prestr,$sign);
                break;
            default :
                $isSgin = false;
        }
        return $isSgin;
    }

    /**
     * 针对notify_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
    public function verifyNotify($notify) {
        //生成签名结果
        $isSign = $this->getSignVeryfy($notify, $notify["sign"]);
        //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
        $responseTxt = 'true';
        if (!empty($notify["notify_id"])) {
            $responseTxt = $this->getResponse($notify["notify_id"]);
        }

        if (preg_match("/true$/i", $responseTxt) && $isSign) {
            $this->setInfo($notify);
            return true;
        } else {
            return false;
        }
    }

    protected function setInfo($notify) {
        $info = array();
        //支付状态
        $info['status'] = ($notify['trade_status'] == 'TRADE_FINISHED' || $notify['trade_status'] == 'TRADE_SUCCESS') ? true : false;
        $info['money']  = $notify['total_fee'];
        $info['trade_no'] = $notify['trade_no'];
        $info['out_trade_no'] = $notify['out_trade_no'];
        $this->info = $info;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    protected function getResponse($notify_id) {
        $partner = $this->config['partner'];
        $veryfy_url = $this->verify_url . "?partner=" . $partner . "&notify_id=" . $notify_id;
        $responseTxt = $this->fsockOpen($veryfy_url);
        return $responseTxt;
    }

}
