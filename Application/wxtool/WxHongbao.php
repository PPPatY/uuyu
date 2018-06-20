<?php
namespace wxtool;

/**
 * 微信红包发送操作类
 * @author Administrator
 *
 */
class WxHongbao {
	private $app_id = 'wx295ed8e5b6a937c6';
    private $app_mchid = '1503022721'; //商户号
    private $partnerkey = 'vPrr5rvnMztk5TCa5HXeDU3DYgyiMaQo'; /* 微信支付商户平台 API密钥 */
    /**
     * 发放红包使用场景，红包金额大于200或者小于1元时必传
     * @var string
     */
    private $scene_id='PRODUCT_2';
    
    function __construct(){
		require_once 'CommonUtil.php';
		require_once 'WxHongBaoHelper.php';
		require_once 'MD5SignUtil.php';
		require_once 'SDKRuntimeException.php';
    }
    
    /**
     * 发送红包
     * 
     * @param string $openid 用户openid
     */
    public function send($params)
    {
        $commonUtil = new CommonUtil();
        $wxHongBaoHelper = new WxHongBaoHelper($this->partnerkey);
        $wxHongBaoHelper->setParameter("nonce_str", $this->great_rand());//随机字符串，丌长于 32 位
        $wxHongBaoHelper->setParameter("mch_billno", $this->app_mchid.date('YmdHis').rand(1000, 9999));//订单号
        $wxHongBaoHelper->setParameter("mch_id", $this->app_mchid);//商户号
        $wxHongBaoHelper->setParameter("wxappid", $this->app_id);
        $wxHongBaoHelper->setParameter("nick_name", $params['nick_name']);//提供方名称
        $wxHongBaoHelper->setParameter("send_name", $params['send_name']);//红包发送者名称
        $wxHongBaoHelper->setParameter("re_openid", $params['re_openid']);//用户openid
        $wxHongBaoHelper->setParameter("total_amount", $params['total_amount']);//付款金额，单位分
        /* $wxHongBaoHelper->setParameter("min_value", 100);//最小红包金额，单位分
        $wxHongBaoHelper->setParameter("max_value", 100);//最大红包金额，单位分 */
        $wxHongBaoHelper->setParameter("total_num", 1);//红包収放总人数
        $wxHongBaoHelper->setParameter("wishing", $params['wishing']);//红包祝福诧
        $wxHongBaoHelper->setParameter("client_ip", $this->getIP());//调用接口的机器 Ip 地址
        $wxHongBaoHelper->setParameter("act_name", $params['act_name']);//活劢名称
        $wxHongBaoHelper->setParameter("remark", $params['remark']);//备注信息
        $this->scene_id && $wxHongBaoHelper->setParameter("scene_id", $this->scene_id);//场景id

        $postXml = $wxHongBaoHelper->create_hongbao_xml();
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
		$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
		/* return $responseObj->return_code; */
		return $responseObj;
    }
    
    /**
     * 返回微信红包是否发送成功
     * @param json_class $res 微信红包结果
     * @return boolean
     */
    public function get_issuccess($res){
        if($res && $res->return_code=='SUCCESS' && $res->result_code=='SUCCESS')
        {
            return true;
        }
        return false;
    }
    
    private function getIP() {
		if (getenv('HTTP_CLIENT_IP')) {
			$ip = getenv('HTTP_CLIENT_IP');
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_X_FORWARDED')) {
			$ip = getenv('HTTP_X_FORWARDED');
		}
		elseif (getenv('HTTP_FORWARDED_FOR')) {
			$ip = getenv('HTTP_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_FORWARDED')) {
			$ip = getenv('HTTP_FORWARDED');
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
    /**
     * 生成随机数
     * 
     */     
    public function great_rand(){
        $str = '1234567890abcdefghijklmnopqrstuvwxyz';
        $t1='';
        for($i=0;$i<30;$i++){
            $j=rand(0,35);
            $t1 .= $str[$j];
        }
        return $t1;    
    }
}
?>