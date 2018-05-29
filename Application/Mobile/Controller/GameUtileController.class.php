<?php
namespace Mobile\Controller;
use Think\Controller;
use Common\Api\GameApi;
use Com\WechatAuth;

class GameUtileController extends Controller{
    public function __construct() {
        parent::__construct();
    }
    public function h5paySdk(){
        $payver = $this->sdkpayVerify($_REQUEST);
    }

    public function sdkpayVerify($data=''){
        if(strtolower($data['callback']) == strtolower("h5paySdkCallback")){ 
            // ajax 请求的处理方式 
            $map['game_appid']=$data['game_appid'];
            $gamedata=M('game','tab_')->field('id')->where($map)->find();
            $uid=session("user_auth.user_id");
            $gamesetdata=M('game_set','tab_')->where(array('game_id'=>$gamedata['id']))->find();
            $channelExt = simple_decode($_REQUEST['channelExt']);
            $channelExt = json_decode($channelExt,true);
            if($data['islm']){
                $serverhostarr = array('in',$channelExt['ext'].",http://".$channelExt['ext'].",https://".$channelExt['ext']);
                $unionmap['domain_url'] = $serverhostarr;
                $islm = M('ApplyUnion','tab_')->where($unionmap)->find();
                if(!empty($islm)){
                    $this->ajaxReturn(array('code'=>-200,'lm_url'=>$channelExt['ext'].'/'),'jsonp');
                }
            }
            //如果没登录
            if(!$uid&&0){
                $this->ajaxReturn(array('code'=>-1,'msg'=>'用户未登录','data'=>''));
            }

            $dataverify['amount']           = $data['amount'];
            $dataverify['props_name']       = $data['props_name'];
            $dataverify['trade_no']         = $data['trade_no'];
            $dataverify['user_id']          = $data['user_id'];
            $dataverify['game_appid']       = $data['game_appid'];
            $dataverify['sdkloginmodel']    = $data['sdkloginmodel'];
            $dataverify['channelExt']       = $data['channelExt'];
            ksort($dataverify);//字典排序
            foreach ($dataverify as $k => $v) {
                $tmp[] = $k . '=' . $v;
            }
            $str = implode('&', $tmp) . $gamesetdata['game_key'];
            $dataverify['sign'] = md5($str);
            if($dataverify['sign']!=$data['sign']){
                $data1['code'] = -1;
                $data1['msg'] = '充值签名失败';
                $this->ajaxReturn($data1,'jsonp');
            }else{
                $dataverify['server_id']        = $data['server_id'];
                $dataverify['server_name']      = $data['server_name'];
                $dataverify['role_id']          = $data['role_id'];
                $dataverify['role_name']        = $data['role_name'];
                $this->assign('paydata',$dataverify);
                session('game_pay_sign',$data['sign']);
                $this->assign('data',$data);
                $userdata = M('user','tab_')
                          ->field('bind_balance,balance')
                          ->join('tab_user_play on tab_user.id = tab_user_play.user_id')
                          ->where(array('tab_user.id'=>is_login(),'game_id'=>$gamedata['id']))
                          ->find();
                $this->assign('userdata',$userdata);
                $paytype = M('tool', 'tab_')->field('status,name')->where(['name'=>['in','weixin,wei_xin,alipay,jft,goldpig']])->select();
                foreach ($paytype as $key => $value) {
                    $pay[$value['name']] = $value['status'];
                }
                $this->assign('paytype',$pay);
                $content = $this->fetch('Game:paysdk');
                if($content!=''){
                    $data1['code'] = 200;
                    $data1['content'] = $content;
                }
                $this->ajaxReturn($data1,'jsonp');
            }
        };
    }
    //分享
    public function shareSdk(){
        if(!$_REQUEST['game_appid']){
            $data['code'] = -1;
            $data['msg'] = "game_appid不能为空";
            $this->ajaxReturn($data,'jsonp');
        }else{
            $gamedata=D('Game')->field('id,icon')->where(array('game_appid'=>$_REQUEST['game_appid']))->find();
            if(empty($gamedata)){
                $data['code'] = -1;
                $data['msg'] = "游戏不存在";
                $this->ajaxReturn($data,'jsonp');
            }
        }

        if($_REQUEST['title']==''){
            $data['code'] = -1;
            $data['msg'] = "title不能为空";
            $this->ajaxReturn($data,'jsonp');
        }else{
            $data['code'] = 200;
            $shareparams['title'] = $_REQUEST['title']; 
            $shareparams['desc'] = $_REQUEST['desc']; 
            $shareparams['imgUrl'] = get_cover($gamedata['icon'],'path');
            $data['shareparams'] = $shareparams;
            $this->ajaxReturn($data,'jsonp');
        }
    }
     //分享
    public function isSubscribeWx(){
        $appid = C('wechat.appid');
        $appsecret = C('wechat.appsecret');
        $result = auto_get_access_token(dirname(__FILE__) . '/access_token_validity.txt');
        if ($result['is_validity']) {
            session('token', $result['access_token']);
        } else {
            $auth = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();
            $token['expires_in_validity'] = time() + $token['expires_in'];
            wite_text(json_encode($token), dirname(__FILE__) . '/access_token_validity.txt');
            session('token', $token['access_token']);
        }
        $openid = session('wechat_token.openid');
        if($openid==''){
            $data['code'] = -1;
            $data['msg'] = "无法获取open_id";
            $this->ajaxReturn($data,'jsonp');
        }else{
            $auth = new WechatAuth($appid, $appsecret,session('token'));
            $userinfo = $auth->userInfo($openid);
            if($userinfo['subscribe']==1){
                $data['code'] = 200;
                $data['msg'] = "用户已关注";
            }else{
                $data['code'] = -2;
                $data['msg'] = "用户未关注";
            }
            $this->ajaxReturn($data,'jsonp');
        }
    }
}

