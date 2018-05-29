<?php

namespace Media\Controller;

use Think\Controller;

use User\Api\MemberApi;

use Common\Api\GameApi;

use Com\Wechat;

use Com\WechatAuth;





class ThirdLoginController extends Controller {

    public function wechat_login(){
        $serverhostarr = array('in',$_GET['state'].",http://".$_GET['state'].",https://".$_GET['state']);
        $host=M('apply_union','tab_')->where(array('domain_url'=>$serverhostarr))->find();
        if($host&&$_GET['state']!=$_SERVER['HTTP_HOST']){
            $url='http://'.$_GET['state'].'/mobile.php/ThirdLogin/wechat_login?'.http_build_query($_GET);
            Header("Location: $url"); exit;
        }
        if(is_weixin()){
            $appid     = C('wechat.appid');
            $appsecret = C('wechat.appsecret');
        }else{
            $appid     = C('wx_login.appid');
            $appsecret = C('wx_login.appsecret');
        }
        $auth  = new WechatAuth($appid, $appsecret);
        $token = $auth->getAccessToken("code",$_GET['code']);
        if(isset($_GET['auto_get_openid'])){
            if(base64_decode($_GET['auto_get_openid'])!='auto_get_openid'){
                die('非法操作！');
            }
            else{
                session('wechat_token',array('openid'=>$token['openid']));
                session('openid',$token['openid']);
            }
        }else{
            if(session('union_host')!=''){
                $_REQUEST['pid']=session('union_host')['union_id'];//判断是否联盟站域名
                $login_data['is_union']=1;
            }
            // else{
            //     $_REQUEST['pid'] = simple_decode($_REQUEST['pid']);
            // }
            // $_REQUEST['gid'] = simple_decode($_REQUEST['gid']);
            $userInfo = $auth->getUserInfo($token['openid']);
            file_put_contents(dirname(__FILE__).'/userinfo.txt',json_encode($userInfo));
            $login_data['account']  = "WX_".date ( 's' ).sp_random_string(6);
            $login_data['nickname'] = $userInfo['nickname'];
            $login_data['sex'] = $userInfo['sex'];
            $login_data['head_icon'] = $userInfo['headimgurl'];
            $login_data['openid']     = $userInfo['unionid'];
            $login_data['promote_id'] = $_REQUEST['pid'];
            $login_data['promote_account'] = get_promote_name($_REQUEST['pid']);
            $login_data['parent_id']        = get_fu_id($_REQUEST['pid']);
            $login_data['third_login_type']  = 2;
            $login_data['register_way']=3;
						session('nickname',$login_data['nickname']);
            if(get_game_name($_REQUEST['gid'])){
                $login_data['fgame_id']  = $_REQUEST['gid'];
                $login_data['fgame_name']  = get_game_name($_REQUEST['gid']);
            }
            session("wechat_token",null);
            if(empty(session("wechat_token"))){
                session(array('expire' => $token['expires_in']));
                $token['headimgurl'] = $userInfo['headimgurl'];
                session("wechat_token", $token);
            }
            $user = new MemberApi();
            $result = $user->third_login($login_data);
        }
				
				if(session('login_reg')) {
					$login_reg = session('login_reg');session('login_reg',null);
					echo '<script>window.location.href="'.$login_reg.'"</script>';exit;
				}
				
				
        if($_GET['state']===0){
            $this->redirect("Index/index");
        }else{
            if($_REQUEST['gid']!=0&&get_game_name($_REQUEST['gid'])!=''){
                $this->redirect("Game/open_game/game_id/".$_REQUEST['gid']);
            }else{
               $this->redirect("Subscriber/user"); 
            }
        }
     }

     public function tiaozhuan(){

        $this->error('网页登陆授权过时，请重新登陆');

     }

}