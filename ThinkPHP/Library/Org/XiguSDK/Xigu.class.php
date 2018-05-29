<?php
namespace Org\XiguSDK;
use Think\Exception;
use Think\JSMS;
use AliyunMNS\Client;
use AliyunMNS\Topic;
use AliyunMNS\Constants;
use AliyunMNS\Model\MailAttributes;
use AliyunMNS\Model\SmsAttributes;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;

class Xigu  {

    /**
     * API请求地址
     */
    const BaseUrl = "http://yun.vlcms.com/index.php";
    /**
     * 
     * 用户账号ID。由32个英文字母和阿拉伯数字组成的开发者账号唯一标识符。
     */
    private $accountid;

    /**
     * 
     * 时间戳
     */
    private $timestamp;


    /**
     * @param $options 数组参数必填
     * $options = array(
     *
     * )
     * @throws Exception
     */
    public function  __construct($accountid)
    {
        if (!empty($accountid)) {
            $this->accountid = isset($accountid) ? $accountid : '';
            $this->timestamp = date("YmdHis") + 7200;
        } else {
            throw new Exception("非法参数");
        }
    }

    /**
     * @return string
     * 包头验证信息,使用Base64编码（账户Id:时间戳）
     */
    private function getAuthorization()
    {
        $data = $this->accountid . ":" . $this->timestamp;
        return trim(base64_encode($data));
    }

    /**
     * @return string
     * 验证参数,URL后必须带有sig参数，sig= MD5（账户Id +  时间戳，共32位）(注:转成大写)
     */
    private function getSigParameter()
    {
        $sig = $this->accountid .  $this->timestamp;
        return strtoupper(md5($sig));
    }

    /**
     * @param $url
     * @param string $type
     * @return mixed|string
     */
    private function getResult($url, $body = null, $type = 'json',$method)
    {   
        $data = $this->connection($url,$body,$type,$method);
        if (isset($data) && !empty($data)) {
            $result = $data;
        } else {
            $result = '没有返回数据';
        }
        return $result;
    }

    /**
     * @param $url
     * @param $type
     * @param $body  post数据
     * @param $method post或get
     * @return mixed|string
     */
    private function connection($url, $body, $type,$method)
    {   
        //var_dump($url);exit;
        if ($type == 'json') {
            $mine = 'application/json';
        } else {
            $mine = 'application/xml';
        }
        if (function_exists("curl_init")) {
            $header = array(
                'Accept:' . $mine,
                'Content-Type:' . $mine . ';charset=utf-8',
                'Authorization:' . $this->getAuthorization(),
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            if($method == 'post'){
                curl_setopt($ch,CURLOPT_POST,1);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$body);
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $opts = array();
            $opts['http'] = array();
            $headers = array(
                "method" => strtoupper($method),
            );
            $headers[]= 'Accept:'.$mine;
            $headers['header'] = array();
            $headers['header'][] = "Authorization: ".$this->getAuthorization();
            $headers['header'][]= 'Content-Type:'.$mine.';charset=utf-8';

            if(!empty($body)) {
                $headers['header'][]= 'Content-Length:'.strlen($body);
                $headers['content']= $body;
            }

            $opts['http'] = $headers;
            $result = file_get_contents($url, false, stream_context_create($opts));
        }
        return $result;
    }

    /**
     * @param $appId
     * @param $verifyCode
     * @param $to
     * @param string $type
     * @return mixed|string
     * @throws Exception
     */
    public function voiceCode($appId,$verifyCode,$to,$type = 'json'){
        $url = self::BaseUrl .  '?s=/SendCode/voice/accountid/' . $this->accountid . '/sig/' . $this->getSigParameter();
        if($type == 'json'){
            $body_json = array('voiceCode'=>array(
                'appId'=>$appId,
                'verifyCode'=>$verifyCode,
                'to'=>$to
            ));
            $body = json_encode($body_json);
        }elseif($type == 'xml'){
            $body_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <voiceCode>
                            <verifyCode>'.$verifyCode.'</clientNumber>
                            <to>'.$to.'</charge>
                            <appId>'.$appId.'</appId>
                        </voiceCode>';
            $body = trim($body_xml);
        }else {
            throw new Exception("只能json或xml，默认为json");
        }
        $data = $this->getResult($url, $body, $type,'post');
        return $data;
    }

    /**
     * @param $appId
     * @param $to
     * @param $templateId
     * @param null $param
     * @param string $type
     * @return mixed|string
     * @throws Exception
     */
    public function sendSM($appId,$to,$templateId,$param=null,$type = 'json'){
        $url = self::BaseUrl .  '?s=/SendCode/send/accountid/' . $this->accountid . '/sig/' . $this->getSigParameter();
        if($type == 'json'){
            $body_json = array('sendSM'=>array(
                'appId'=>$appId,
                'templateId'=>$templateId,
                'to'=>$to,
                'param'=>$param
            ));
            $body = json_encode($body_json);
        }elseif($type == 'xml'){
            $body_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <sendSM>
                            <templateId>'.$templateId.'</templateId>
                            <to>'.$to.'</to>
                            <param>'.$param.'</param>
                            <appId>'.$appId.'</appId>
                        </sendSM>';
            $body = trim($body_xml);
        }else {
            throw new Exception("只能json或xml，默认为json");
        }
        
        $data = $this->getResult($url, $body, $type,'post');
        return $data;
    }


	/**
	 * 阿里大于
	 * @param $phone    手机号
	 * @param $code     验证码
	 * @param int $delay 有效时间
	 * @return bool
	 * author: xmy 280564871@qq.com
	 */
    public function alidayu_send($phone,$code,$delay=10){
	    $result = $this->alidayu($phone,$code,$delay);
	    if($result->result->success == true){
	    	return true;
	    }else{
	    	return false;
	    }
    }

    /**
     * 阿里大于新
     * @param $phone    手机号
     * @param $code     验证码
     * @param int $delay 有效时间
     * @return bool
     * author: wyr 840186209@qq.com
     */
    public function alidayunew_send($phone,$code,$delay=10){
        $result = $this->alidayunew($phone,$code,$delay);
        if($result['Code'] == 'OK'){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 阿里大于(消息服务)
     * @param $phone    手机号
     * @param $code     验证码
     * @param int $delay 有效时间
     * @return bool
     * author: cy 707670631@qq.com
     */
    public function alidayumsg_send($phone,$code,$delay=10){
        $result = $this->alidayumsg($phone,$code,$delay);
        return $result;
    }


	/**
	 * 阿里大于 发送短信
	 * @param $phone
	 * @param $code
	 * @param int $delay
	 * author: xmy 280564871@qq.com
	 */
    private function alidayu($phone,$code,$delay=10){
	    $config = api('Config/lists');
	    C($config); //添加配置

	    $temp_code = C("alidayu.template_id");    //模板ID
	    $param = "{'code':'$code'}";   //参数
//        $param = "{'code':'$code','delay','$delay'}";   //参数
	    $sing_name = C("alidayu.sign");              //审核过的签名

	    vendor('Alidayu.top.TopClient');
	    vendor('Alidayu.top.ResultSet');
	    vendor('Alidayu.top.RequestCheckUtil');
	    vendor('Alidayu.top.TopLogger');
	    vendor('Alidayu.top.request.AlibabaAliqinFcSmsNumSendRequest');

	    $dayu = new \TopClient();
	    $dayu->appkey = C("alidayu.appkey");
	    $dayu->secretKey = C("alidayu.secretKey");
	    $req = new \AlibabaAliqinFcSmsNumSendRequest();
	    $req->setExtend("");
	    $req->setSmsType("normal");//设置短信类型
	    $req->setSmsFreeSignName($sing_name);//短信签名
	    //短信模板变量，传参规则{"key":"value"}，key的名字须和申请模板中的变量名一致，多个变量之间以逗号隔开。
	    //示例：针对模板“验证码${code}，您正在进行${product}身份验证，打死不要告诉别人哦！”，传参时需传入{"code":"1234","product":"alidayu"}
	    $req->setSmsParam($param);
	    $req->setRecNum($phone);//发送电话号
	    $req->setSmsTemplateCode($temp_code);//模板ID
	    $resp = $dayu->execute($req);
	    return $resp;
    }

    /**
     * 阿里大于新 发送短信
     * @param $phone
     * @param $code
     * @param int $delay
     * author: wyr 840186209@qq.com
     */
    private function alidayunew($phone,$code,$delay=10){
        $config = api('Config/lists');
        C($config); //添加配置

        $temp_code = C("alidayunew.template_id");    //模板ID
        $sing_name = C("alidayunew.sign");              //审核过的签名
        $region =  C("alidayunew.region");    //region(区域)
        $domain =  C("alidayunew.domain");    //短信API产品域名
        $accessKeyId =  C("alidayunew.appkey");    //
        $accessKeySecret =  C("alidayunew.secretKey");    //
        $product =  C("alidayunew.product");    //
       // $param = '{\"code\":\"$code"\",\"product\":\"$sing_name\}';   //参数
        $param = json_encode(array('code'=>$code));   //参数
        //初始化访问的acsCleint 
        vendor('Alidayunew.msg_sdk.aliyun-php-sdk-core.Config');
        vendor('Alidayunew.api_sdk.Dysmsapi.Request.V20170525.SendSmsRequest');
        vendor('Alidayunew.api_sdk.Dysmsapi.Request.V20170525.QuerySendDetailsRequest');

        $profile = \DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        \DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        $acsClient= new \DefaultAcsClient($profile);
        $request = new \Dysmsapi\Request\V20170525\SendSmsRequest;
       //必填-短信接收号码。支持以逗号分隔的形式进行批量调用，批量上限为20个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
        // $request->setExtend("");
        // $request->setSmsType("normal");//设置短信类型

        $request->setPhoneNumbers($phone);
        //必填-短信签名
        $request->setSignName($sing_name);
        //必填-短信模板Code
        $request->setTemplateCode($temp_code);
        //选填-假如模板中存在变量需要替换则为必填(JSON格式)
        $request->setTemplateParam($param);

        //选填-发送短信流水号
        // $request->setOutId("1234");
        $resp = $acsClient->getAcsResponse($request);
        //var_dump($resp);
        $resp = json_decode(json_encode($resp),true);
        return $resp;
        //发起访问请求
        //$acsResponse = $acsClient->getAcsResponse($request);
    }


    /**
     * 阿里大于消息服务
     * @param $phone
     * @param $code
     * @param int $delay
     * author: cy 707670631@qq.com
     */
    private function alidayumsg($phone,$code,$delay=10){
        include  './ThinkPHP/Library/Vendor/Alidayumsg/php_sdk/mns-autoloader.php';
        $this->endPoint = C("alidayumsg.domain"); // eg. http://1234567890123456.mns.cn-shenzhen.aliyuncs.com
        $this->accessId = C("alidayumsg.appkey");
        $this->accessKey = C("alidayumsg.secretKey");

        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
        /**
         * Step 2. 获取主题引用
         */
        $topicName = C("alidayumsg.topic");
        $topic = $this->client->getTopicRef($topicName);
        /**
         * Step 3. 生成SMS消息属性
         */
        // 3.1 设置发送短信的签名（SMSSignName）和模板（SMSTemplateCode）
        $batchSmsAttributes = new BatchSmsAttributes(C("alidayumsg.sign"), C("alidayumsg.template_id"));
        // 3.2 （如果在短信模板中定义了参数）指定短信模板中对应参数的值
        $batchSmsAttributes->addReceiver($phone, array("code" => (string)$code));
//        $batchSmsAttributes->addReceiver("15751006847", array("code" => "123456"));

        $messageAttributes = new MessageAttributes(array($batchSmsAttributes));
        /**
         * Step 4. 设置SMS消息体（必须）
         *
         * 注：目前暂时不支持消息内容为空，需要指定消息内容，不为空即可。
         */
        $messageBody = "smsmessage";
        /**
         * Step 5. 发布SMS消息
         */
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        try
        {
            $res = $topic->publishMessage($request);
            return $res->isSucceed();

        }
        catch (MnsException $e)
        {
            return false;
        }
    }

    /**
     * 极光 发送短信
     * @param $phone
     * @param $code
     * @param int $delay
     */
    public function jiguang($phone,$code,$delay){
        vendor('Jiguang.JSMS');

        $appKey = C("jiguang.appkey");
        $masterSecret = C("jiguang.secretKey");

        $temp_id = C("jiguang.temp");
        $temp_para = ['code' => "$code"];

        $client = new JSMS($appKey, $masterSecret);
        $response = $client->sendMessage($phone, $temp_id, $temp_para);

        if ($response['http_code'] == '200'){
            return true;
        }else{
            return false;
        }
    }

    //极光声音验证码
    public function jiguang_voice($phone){
        $appKey = C("jiguang.appkey");
        $masterSecret = C("jiguang.secretKey");
        $phone = $phone;

        $client = new JSMS($appKey, $masterSecret);
        $response = $client->sendVoiceCode($phone);

        if ($response['http_code'] == '200'){
            return true;
        }else{
            return false;
        }
    }

    public function jiguang_code($phone){

        $appKey = C("jiguang.appkey");
        $masterSecret = C("jiguang.secretKey");
        $phone = $phone;

        $client = new JSMS($appKey, $masterSecret);
        $response = $client->sendCode($phone, 1);

        if ($response['http_code'] == '200'){
            return true;
        }else{
            return false;
        }
    }

    public function jiguang_check($msg_id,$code){
        $appKey = C("jiguang.appkey");
        $masterSecret = C("jiguang.secretKey");
        $msg_id = $msg_id;
        $code = $code;

        $client = new JSMS($appKey, $masterSecret);
        $response = $client->checkCode($msg_id, $code);

        if ($response['http_code'] == '200'){
            return true;
        }else{
            return false;
        }
    }
} 