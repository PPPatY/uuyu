<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi;
use Com\Wechat;
use Com\WechatAuth;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class PublicController extends \Think\Controller {



        public function asd(){
            $sdata['user_id']    = "";//uid同步平台uid唯一值,
            $sdata['game_appid'] = "4A65EC5976CFF92BD";
            $sdata['email']      = "";//同步平台账号
            $sdata['new_time']   = time();
            $sdata['sdklogindomain']   = "http://newh5.vlcms.com";
            $sdata['sdkloginmodel']   = "media";
            $sdata['channelExt']    = "xxxx";//透传信息 支付时传回来
            $sdata['loginplatform2cp']   = get_client_ip();//白鹭不需要此参数。用于CP要求平台特别传输其他参数，默认是访问ip，可以改变 根据情况自由发挥，若需要传多个参数，可仿照登陆透传信息 对称加密加密传输
            ksort($sdata);//字典排序
            $sdata['sign']       = MD5(http_build_query($sdata)."4A65EC5976CFF92BDqipai");
            $this->assign('data',json_encode($sdata));
            $this->display();
        }



    /**
     * 后台用户登录
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function login($username = null, $password = null, $verify = null){
        if(IS_POST){
            /* 检测验证码 TODO: */
            if(!($_POST['username']&&$_POST['password'])){
                $this->ajaxReturn(array('status'=>0,'msg'=>'账号或密码不能为空'));
            }
            if(!check_verify($verify)){
                // $this->error('验证码输入错误！');
                $this->ajaxReturn(array('status'=>-1,'msg'=>'验证码输入错误！'));
            }

            /* 调用UC登录接口登录 */
            $User = new UserApi;
            $uid = $User->login($username, $password);
            if(0 < $uid){ //UC登录成功
                /* 登录用户 */
                $Member = D('Member');
                if($Member->login($uid)){ //登录用户
								
										if($_POST['remember']==1){
												setcookie('admin_usn',$_POST['username'],time()+3600*10000,$_SERVER["HTTP_HOST"]);
												setcookie('admin_pwd',$_POST['password'],time()+3600*10000,$_SERVER["HTTP_HOST"]);
										}else{
												setcookie('admin_usn',$_POST['username'],time()-1,$_SERVER["HTTP_HOST"]);
												setcookie('admin_pwd',$_POST['password'],time()-1,$_SERVER["HTTP_HOST"]);
										}
								
                    //TODO:跳转到登录前页面
                    $this->ajaxReturn(array('status'=>1,'msg'=>'登录成功！'));
                    
                    // $this->success('登录成功！', U('Index/index'));
                } else {
                    $this->ajaxReturn(array('status'=>0,'msg'=>$Member->getError()));

                    // $this->error($Member->getError());
                }

            } else { //登录失败
                switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
                }
                    $this->ajaxReturn(array('status'=>0,'msg'=>$error));
                // $this->error($error);
            }
        } else {
            if(is_login()){
                $this->redirect('Index/index');
            }else{
                /* 读取数据库中的配置 */
                $config =   S('DB_CONFIG_DATA');
                if(!$config){
                    $config =   D('Config')->lists();
                    S('DB_CONFIG_DATA',$config);
                }
                C($config); //添加配置
                $data['channelExt']="xx";
                $data['email']="xx";
                $data['game_appid']="352FDE41014F786F5";
                $data['new_time']=time();
                $data['user_id']="xx";
                $data['sdklogindomain']="media";
                $data['sdkloginmodel']="media";

                 ksort($data);//字典排序
                  $data['sign']       = MD5(http_build_query($data)."dbsgz");
                $this->display();
            }
        }
    }

    /* 退出登录 */
    public function logout(){
        if(is_login()){
            D('Member')->logout();
            session('[destroy]');
            // $this->success('退出成功！', U('login'));
            $this->ajaxReturn(array('status'=>1,'msg'=>'退出成功！'));
        } else {
            $this->redirect('login');
        }
    }
    public function checkVerify(){
        $verify=$_POST['verify'];
        if(!check_verify($verify)){
            $this->ajaxReturn(array('status'=>0,'msg'=>'验证码输入错误！'));
        }
    }
    public function verify(){
        $config = array(
            'seKey'     => 'ThinkPHP.CN',   //验证码加密密钥
            'fontSize'  => 22,              // 验证码字体大小(px)
            'imageH'    => 50,               // 验证码图片高度
            'imageW'    => 180,               // 验证码图片宽度
            'length'    => 4,               // 验证码位数
            'fontttf'   => '4.ttf',              // 验证码字体，不设置随机获取
            'codeSet'   => '0123456789',
        );
        ob_clean();
        $verify = new \Think\Verify($config);
        $verify->entry(1);
    }
    public function get_openid(){
        // $User = new UserApi;
        // if($_POST['id']>999){
        //     $this->ajaxReturn(array('status'=>0,'msg'=>'管理员id不能大于999'));
        // }
        // $data = $User->verifyPwd($_POST['id'], $_POST['pwd']);
        // if(!$data){
        //     $this->ajaxReturn(array('status'=>0,'msg'=>'密码错误，请重新选择'));
        // }
        $appid     = C('wechat.appid');
        $appsecret = C('wechat.appsecret');
        $result=auto_get_access_token(dirname(__FILE__).'/access_token_validity.txt');
        if($result['is_validity']){
            session('token',$result['access_token']);
            $auth  = new WechatAuth($appid, $appsecret,$result['access_token']);
        }else{
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();
            $token['expires_in_validity']=time()+$token['expires_in'];
            wite_text(json_encode($token),dirname(__FILE__).'/access_token_validity.txt');
            session('token',$token['access_token']);
        }
        $scene_id=sp_random_num(4).'0';
        $ticket = $auth->qrcodeCreate($scene_id,120);//10分钟
        if($ticket['errcode']){
            $return=array('status'=>0,'data'=>'获取ticket失败！');
        }else{
            $qrcode = $auth->showqrcode($ticket['ticket']);
            $return=array('status'=>1,'data'=>$qrcode,'token'=>$scene_id);
        }
        $this->ajaxReturn($return);
    }
    public function wite_token(){
        $appid     = C('wechat.appid');
        $appsecret = C('wechat.appsecret');
        $auth  = new WechatAuth($appid, $appsecret);
        $token = $auth->getAccessToken();
        $token['expires_in_validity']=time()+$token['expires_in'];
        wite_text(json_encode($token),dirname(__FILE__).'/access_token_validity.txt');
        session('token',$token['access_token']);
        $this->get_openid();
    }
    /** * 第三方微信扫码登陆 * */
    public function wechat_qrcode_login($state=1){
        // var_dump(session());exit;
        if(empty(session("user_auth.user_id")) && !is_weixin()){
            $appid     = C('wx_login.appid');
            $appsecret = C('wx_login.appsecret');
            $auth  = new WechatAuth($appid, $appsecret);
            $result=auto_get_access_token(dirname(__FILE__).'/qr_access_token_validity.txt');
            if($result['is_validity']){
                session('token',$result['access_token']);
            }else{
                $token = $auth->getAccessToken();
                $token['expires_in_validity']=time()+$token['expires_in'];
                wite_text(json_encode($token),dirname(__FILE__).'/qr_access_token_validity.txt');
                session('token',$token['access_token']);
            }
            $redirect_uri = "http://".$_SERVER['HTTP_HOST']."/admin.php/Public/wechat_login_callback";
            redirect($auth->getQrconnectURL($redirect_uri,$state));
        }
    }
    public function wechat_login_callback(){
        if($host&&$_GET['state']!=$_SERVER['HTTP_HOST']){
            $url='http://'.$_GET['state'].'/admin.php/Public/wechat_login_callback?'.http_build_query($_GET);
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
                session('admin_wechat_token',array('openid'=>$token['openid']));
                session('admin_openid',$token['openid']);
            }
        }
        $Member=D('UcenterMember');
        $admin=$Member->where(array('admin_openid'=>$token['openid']))->find();
        if($admin==''){
            $this->error("微信未绑定管理员账号！");
        }else{
            $User = new UserApi;
            $uid = $User->login($admin['username'], $admin['password']);
            if(0 < $uid){ //UC登录成功
                /* 登录用户 */
                $Member = D('Member');
                if($Member->login($uid)){ //登录用户
                    //TODO:跳转到登录前页面
                    $this->success('登录成功！', U('Index/index'));
                } else {
                    $this->error($Member->getError());
                }

            } else { //登录失败
                switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }
        }
     }
}
