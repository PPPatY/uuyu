<?php
/** * 微博 SDk  yyh */
namespace Org\ThinkSDK\sdk;
use Org\ThinkSDK\ThinkOauth;

class WeiboSDK extends ThinkOauth {

    private function get_access_token($appkey, $appsecretkey, $code, $callback, $state=null) {
        $url = "https://api.weibo.com/oauth2/access_token";
        $param = array(
            "grant_type"    =>    "authorization_code",
            "client_id"     =>    $appkey,
            "client_secret" =>    $appsecretkey,
            "code"          =>    $code,
            "redirect_uri"  =>    $callback
        );

        $param = http_build_query($param);
        $response = $this->post($url, $param);
        if($response == false) {
            return false;
        }
        $params = json_decode($response, true);
        return $params["access_token"];
    }

    private function get_openid($access_token) {
        $url = "https://api.weibo.com/oauth2/get_token_info"; 
        $param = array(
            "access_token"    => $access_token
        );

        $param = http_build_query($param);
        $response  = $this->post($url, $param);
        if($response == false) {
            return false;
        }
        $params = json_decode($response, true);
        return $params['uid'];
    }

    public function get_user_info($token, $openid, $appkey=null, $format = "json") {
        $url = "https://api.weibo.com/2/users/show.json";
        $param = array(
            "access_token"      =>    $token,
            "uid"               =>    $openid
        );

        $response = $this->get($url, $param);
        if($response == false) {
            return false;
        }

        $user = json_decode($response, true);
        return $user;
    }

    public function login($appkey, $callback, $scope='') {
        $login_url = "https://api.weibo.com/oauth2/authorize?response_type=code&client_id=" 
            . $appkey . "&scope=$scope&redirect_uri=" . urlencode($callback);
        return $login_url;
    }

    public function callback($appkey, $appsecretkey, $callback) {
        $code = $_GET['code'];

        $token = $this->get_access_token($appkey, $appsecretkey, $code, $callback);
        $openid = $this->get_openid($token);
        if(!$token || !$openid) {
            exit('get token or openid error!');
        }

        return array('openid' => $openid, 'token' => $token);
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

    /*
 * HTTP GET Request
*/
function get($url, $param = null) {
    if($param != null) {
        $query = http_build_query($param);
        $url = $url . '?' . $query;
    }   
    $ch = curl_init();
    if(stripos($url, "https://") !== false){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }   

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    $content = curl_exec($ch);
    $status = curl_getinfo($ch);
    curl_close($ch);
    if(intval($status["http_code"]) == 200) {
        return $content;
    }else{
        echo $status["http_code"];
        return false;
    }   
}

    /*
     * HTTP POST Request
    */
    function post($url, $params) {
        $ch = curl_init();
        if(stripos($url, "https://") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $content = curl_exec($ch);
        $status = curl_getinfo($ch);
        curl_close($ch);
        if(intval($status["http_code"]) == 200) {
            return $content;
        } else {
            echo $status["http_code"];
            return false;
        }
    }

    function http_build_query_multi($params, $boundary) {
        if (!$params) return '';

        uksort($params, 'strcmp');

        $MPboundary = '--'.$boundary;
        $endMPboundary = $MPboundary. '--';
        $multipartbody = '';

        foreach ($params as $parameter => $value) {

            if( in_array($parameter, array('pic', 'image')) ) {
                $content = file_get_contents( $value );
                $filename = 'upload.jpg';

                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
                $multipartbody .= "Content-Type: image/unknown\r\n\r\n";
                $multipartbody .= $content. "\r\n";
            } else {
                $multipartbody .= $MPboundary . "\r\n";
                $multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
                $multipartbody .= $value."\r\n";
            }

        }

        $multipartbody .= $endMPboundary;
        return $multipartbody;
    }

}
