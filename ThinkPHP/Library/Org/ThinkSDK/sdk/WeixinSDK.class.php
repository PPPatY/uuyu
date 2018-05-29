<?php
/** * 微信 SDk  lwx */
namespace Org\ThinkSDK\sdk;
use Org\ThinkSDK\ThinkOauth;

class WeixinSDK extends ThinkOauth {
    /**
     * 获取requestCode的api接口
     * @var string
     */
    /**微信二维码登陆*/
    protected $GetRequestCodeURL_qrconnect = 'https://open.weixin.qq.com/connect/qrconnect';
    /**微信账号登陆*/
    protected $GetRequestCodeURL_authorize = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    /**
     * 获取access_token的api接口
     * @var string
     */
    protected $GetAccessTokenURL = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    //
    /**
     * API根路径
     * @var string
     */
    protected $ApiBase = 'https://api.weixin.qq.com/';
    
    public function getRequestCodeURL() {
        $params = array(
            'appid' => $this->AppKey,          
            'redirect_uri'=>$this->Callback,
            'response_type'=>'code',
            'scope'=>'snsapi_base',//snsapi_userinfo
            'state'=>'STATE'
        );
        return $this->GetRequestCodeURL_authorize . '?' . http_build_query($params).'#wechat_redirect';
    }

    public function getQrconnectURL() {
        $params = array(
            'appid' => $this->AppKey,          
            'redirect_uri'=>$this->Callback,
            'response_type'=>'code',
            'scope'=>'snsapi_base',//snsapi_base snsapi_login
            'state'=>'STATE'
        );
        if(isset($_REQUEST['gid'])&&isset($_REQUEST['pid'])){
            $params['redirect_uri']=$params['redirect_uri'].'/gid/'.$_REQUEST['gid'].'/pid/'.$_REQUEST['pid'];
        }
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return $this->GetRequestCodeURL_authorize . '?' . http_build_query($params)."#wechat_redirect";
        }
        return $this->GetRequestCodeURL_qrconnect . '?' . http_build_query($params)."#wechat_redirect";
    }
    
    /**
     * 获取access_token
     * @param string $code 上一步请求到的code
     */
    public function getAccessToken($code, $extend = null) {
        //parent::config();
        $params = array(
            'appid'     => $this->AppKey,
            'secret'    => $this->AppSecret,
            'grant_type'    => $this->GrantType,
            'code'          => $code,
        );

        $data = $this->http($this->GetAccessTokenURL, $params, 'POST');
        $this->Token = $this->parseToken($data, $extend);
        return $this->Token;
    }

    public function getUserInfo($access_token,$openid){
        $params = array(
            "access_token"=>$access_token,
            "openid"=>$openid
        );
        $url_api = $this->ApiBase."sns/userinfo";

        $data = $this->http($url_api, $params, 'POST');
        return $data;
    }

    
    /**
     * 组装接口调用参数 并调用接口
     * @param  string $api    微信API
     * @param  string $param  调用API的额外参数
     * @param  string $method HTTP请求方法 默认为GET
     * @return json
     */
    public function call($api, $param = '', $method = 'GET', $multi = false) {
        /* 微信调用公共参数 */
        $params = array(
            'access_token'       => $this->Token['access_token'],
            'openid'             => $this->openid(),
        );

        $vars = $this->param($params, $param);
        $data = $this->http($this->url($api), $vars, $method, array(), $multi);
        return json_decode($data, true);
    }
    
    
    /**
     * 解析access_token方法请求后的返回值
     */
    protected function parseToken($result, $extend) {
        $data = json_decode($result,true);
        //parse_str($result, $data);
        if($data['access_token'] && $data['expires_in']){
            $this->Token    = $data;
            $data['openid'] = $this->openid();
            return $data;
        } else
            E("获取微信 ACCESS_TOKEN 出错：{$result}");
    }
    
    /**
     * 获取当前授权应用的openid
     */
    public function openid() {
        $data = $this->Token;
        if(!empty($data['openid']))
            return $data['openid'];
        else
            exit('没有获取到微信用户ID！');
    }
}

?>