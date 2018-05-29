<?php

namespace Mobile\Controller;

use Think\Controller;

use User\Api\MemberApi;

use Common\Api\GameApi;

use Org\XiguSDK\Xigu;

use Org\ThinkSDK\ThinkOauth;

use Common\Api\PayApi;

use Com\Wechat;

use Com\WechatAuth;

use Org\UcenterSDK\Ucservice;



class TuiRegisterController extends BaseController {

    

    protected function _initialize(){

        /* 读取站点配置 */

        $config = api('Config/lists');

        C($config); //添加配置



    }

    public function __construct() {
        if(is_weixin()){
            if(is_login_user()){
                D('User')->logout();
                session("wechat_token",null);
            }
        }
        session("tuiguangurl",1);

        parent::__construct();

        if(is_weixin()){

            $result=auto_get_access_token(dirname(__FILE__).'/access_token_validity.txt');

            if($result['is_validity']){

                session('token',$result['access_token']);

            }else{

                $appid     = C('wechat.appid');

                $appsecret = C('wechat.appsecret');

                $auth  = new WechatAuth($appid, $appsecret);

                $token = $auth->getAccessToken();

                $token['expires_in_validity']=time()+$token['expires_in'];

                wite_text(json_encode($token),dirname(__FILE__).'/access_token_validity.txt');

                session('token',$token['access_token']);

            }
            if(session("wechat_token.openid")){

                session('openid',session("wechat_token.openid"));

            }

            else{
                if(is_weixin()&&strpos('模块'.$_SERVER['PATH_INFO'],"TuiRegister/wechat_login_game")===false){

                    $appid     = C('wechat.appid');

                    $appsecret = C('wechat.appsecret');

                    $auth  = new WechatAuth($appid, $appsecret);
                    if(isset($_REQUEST['encrypt'])){
                         $_REQUEST['gid'] = simple_decode( $_REQUEST['gid']);
                    }
                    if (empty($_REQUEST['gid'])) {

                        $_REQUEST['gid'] = 0;

                    }
                    if(session('for_third')==C(PC_SET_DOMAIM)){

                        $state=$_SERVER['HTTP_HOST'];

                        $redirect_uri = "http://".session('for_third')."/mobile.php/TuiRegister/wechat_login_game/gid/".$_REQUEST['gid'].'/pid/'.$_REQUEST['pid'];

                    }else{

                        $redirect_uri = "http://".$_SERVER['HTTP_HOST']."/mobile.php/TuiRegister/wechat_login_game/gid/".$_REQUEST['gid'].'/pid/'.$_REQUEST['pid'];

                    }

                    redirect($auth->getRequestCodeURL($redirect_uri,$state,'snsapi_userinfo'));

                }

            }

        }

    }





    public function index(){

        $this->display();

    }



    /**

    * 登陆

    */

    public function login($username='',$password='',$type="") {

        if(IS_POST){
            if($_POST['jzmm']){
                setcookie('media_account',$username,time()+3600*10000,$_SERVER["HTTP_HOST"]);
                setcookie('media_pas',$password,time()+3600*10000,$_SERVER["HTTP_HOST"]);
            }else{
                setcookie('media_account',$username,time()-1,$_SERVER["HTTP_HOST"]);
                setcookie('media_pas',$password,time()-1,$_SERVER["HTTP_HOST"]);
            }
            if(session('wechat_token')!=''){

               session('wechat_token',null); 

            }

            if($type == "phone" && !preg_match("/^1[34578][0-9]{9}$/",$username)){

                $this->ajaxReturn(array("status"=>0,"msg"=>"请输入正确的手机号码"));exit;

            }

            $user = new MemberApi();

            $uc   =new  Ucservice();


            $result = $user->login($username,$password);

            switch ($result) {

                case -1:#账号不存在

                    if($type=="phone"){

                        $this->telsvcode($username,1,0,$type);

                    }

                    else{

                        $this->ajaxReturn(array("status"=>0,"msg"=>"账号不存在"));

                    }

                    break;

                case -2:

                    $this->ajaxReturn(array("status"=>0,"msg"=>"密码错误"));

                    break;

                case -4:

                    $this->ajaxReturn(array("status"=>0,"msg"=>"用户被锁定"));

                    break;

                default:
                    if(session('game_url')){
                        $url = session('game_url');
                    }else{

                        $url=U('Subscriber/index');

                    }

                    $this->ajaxReturn(array("status"=>1,"msg"=>"登陆成功","url"=>$url));

                    break;

            }


        }else{

            header("Content-type: text/html; charset=utf-8");

            if($_GET['pid']==''||$_GET['gid']==''){

                echo "<script>alert('参数非法！');</script>";

                exit();

            }else{

                $apply=M('Apply','tab_')->where(array('game_id'=>$_GET['gid'],'promote_id'=>$_GET['pid']))->find();

                if(empty( $apply)){

                    echo "<script>alert('参数非法！');</script>";

                    exit();

                }elseif($apply['status']!=1){

                    echo "<script>alert('链接未审核~');</script>";

                    exit();

                }

            }

            $map1['name']='PC_QQ_GROUP';

            $info1 = M('Config')->field('value')->where($map1)->find();

            $map['name']='PC_QQ_GROUP_KEY';

            $info = M('Config')->field('value')->where($map)->find();

            $map3['name']='PC_SET_DOMAIM';

            $info3 = M('Config')->field('value')->where($map3)->find();

            $this->assign('info3',$info3);

            $this->assign('info',$info);

            $this->assign('info1',$info1);

            $this->display();

        }

    }



    /** * 第三方登陆 * */

    public function thirdlogin($type = null,$gid=0,$pid=0){
        // if(isset($_REQUEST['encrypt'])){
        //     //encrypt sdk使用
        //     $gid = simple_decode($gid);
        //     $pid = simple_decode($pid);
        // }
        empty($type) && $this->error('参数错误');

        if(session('union_host')!=''){

            $pid=session('union_host')['union_id'];//判断是否联盟站域名

        }
        if($type=="baidu"){
            $appkey = C('THINK_SDK_BAIDU.APP_KEY');
            $appsecret = C('THINK_SDK_BAIDU.APP_SECRET');
            $callback = C('THINK_SDK_BAIDU.CALLBACK');
            setcookie('bdlog_gid', $gid, time() + 20, '/');
            setcookie('bdlog_pid', $pid, time() + 20, '/');
            Vendor("BaiDuSDK.Baidu");
            $baidu = new \Baidu($appkey, $appsecret, $callback, new \BaiduCookieStore($appkey));
            $logindisplay = is_mobile_request()?'mobile':'page';
            $loginUrl = $baidu->getLoginUrl('', $logindisplay);
						
						$hash = I('get.hash','');
						session('login_reg',$_SERVER['HTTP_REFERER'].($hash?'#'.$hash:''));
						
            redirect($loginUrl);
            exit;
        }
        $sns  = ThinkOauth::getInstance($type);

        if(!empty($sns)){
						$hash = I('get.hash','');
						session('login_reg',$_SERVER['HTTP_REFERER'].($hash?'#'.$hash:''));
            if($type=="weixin"){

                if(is_weixin()){

                    $this->wechat_login(1,$gid,$pid);

                }else{

                    $this->wechat_qrcode_login(1,$gid,$pid);

                }

            }elseif($type=="weibo"){
                $appkey = C('THINK_SDK_WEIBO.APP_KEY');
                $scope = '';
                $callback = C('THINK_SDK_WEIBO.CALLBACK');
                $ud['gid'] = $gid;
                $ud['pid'] = $pid;
                $callback = $callback.'&'.http_build_query($ud);
                $wburl=$sns->login($appkey, $callback, $scope);
                redirect($wburl);
            }else{

                //跳转到授权页面

                if(get_game_name($gid)!=''){

                    $_REQUEST['gid'] = $gid;
                    $_REQUEST['pid'] = $pid;
                    $qqurl=$sns->getRequestCodeURL();

                }else{

                    $qqurl=$sns->getRequestCodeURL();

                }

                redirect($qqurl);

            }

        }

    }

    



    //注册

    public function register() {

        if(IS_POST){
            $user_allow=M('config')->where(array('name'=>'USER_ALLOW_REGISTER'))->find();

            if($user_allow['value']==1){

                $type=$_POST['type'];

                if($type=='account'){

                    $verify = new \Think\Verify();
                     if($verify->check(I('verify'))){

                        if(empty($_POST['account']) || empty($_POST['password'])){

                            return $this->ajaxReturn(array('status'=>0,'msg'=>'账号或密码不能为空'));exit;

                        } else if(strlen($_POST['account'])>15||strlen($_POST['account'])<6){

                            return $this->ajaxReturn(array('status'=>0,'msg'=>'用户名长度在6~15个字符'));

                        }else if(!preg_match('/^[a-zA-Z0-9]{6,15}$/', $_POST['account'])){

                            return $this->ajaxReturn(array('status'=>0,'msg'=>'用户名包含特殊字符'));exit;

                        }

                    }

                    else{

                        return $this->ajaxReturn(array('status'=>0,'msg'=>'验证码错误'));exit;

                    }

                }

                $user = new MemberApi();

                $data['account']  = $_POST['account'];

                $data['password'] = $_POST['password'];

                $data['nickname'] = $_POST['account'];

                $data['phone']    = $type=='phone'?$_POST['account']:"";

                $data['promote_id']        = $_REQUEST['p_id'];

                $data['promote_account']   = get_promote_name($_REQUEST['p_id']);

                $data['parent_id']        = get_fu_id($_REQUEST['p_id']);

                $data['parent_id']        = get_fu_id($_REQUEST['p_id']);

                $data['parent_name']        = get_parent_name($_REQUEST['p_id']);

                $data['fgame_name']        = get_game_name($_POST['g_id']);

                $data['fgame_id']        = $_REQUEST['g_id'];

                $data['register_time']        = time();

                $data['third_login_type']  = $type == "phone"?1:0;
                if($type=='phone'){

                    $data['register_way'] = 2;

                }elseif ($type=='account'){

                    $data['register_way'] = 1;

                }else{

                    $data['register_way'] = 3;

                }

            if($uc_id==-3){

                $this->ajaxReturn(array("status"=>-3,"msg"=>"账号已存在"));exit();

            }

                $result = $user->register($data);

                if($type=="account"||$type='phone'){

                    switch ($result) {

                        case -3:

                            $return=array("status"=>-3,"msg"=>"账号已存在");

                            break;

                        case 0:

                            $return=array("status"=>0,"msg"=>"注册失败");

                            break;

                        default:
                            if(session('game_url')!=''){
                                $url=session('game_url');
                            }else{

                                $url=U('Subscriber/user');

                            }
                            $this->ajaxReturn(array("status"=>1,"msg"=>"注册成功","url"=>$url));

                            break;

                    }

                    return $this->ajaxReturn($return);exit;

                }

            }else{

                return $this->ajaxReturn(array('status'=>0,'msg'=>'未开放注册！！'));

            }

        }else{

            $map1['name']='PC_QQ_GROUP';

            $info1 = M('Config')->field('value')->where($map1)->find();

            $map['name']='PC_QQ_GROUP_KEY';

            $info = M('Config')->field('value')->where($map)->find();

            $map3['name']='PC_SET_DOMAIM';

            $info3 = M('Config')->field('value')->where($map3)->find();

            $this->assign('info3',$info3);

            $this->assign('info',$info);

            $this->assign('info1',$info1);

            $this->display();

        }

    }





    // 发送手机安全码

    public function telsvcode($phone=null,$delay=10,$way=1,$type="phone") {

        /// 产生手机安全码并发送到手机且存到session

        $rand = rand(100000,999999);

        $xigu = new Xigu(C('sms_set.smtp'));
        
        checksendcode($phone,C('sms_set.limit'));
        
        $param = $rand.",".$delay;

        $result = json_decode($xigu->sendSM(C('sms_set.smtp_account'),$phone,C('sms_set.smtp_port'),$param),true); 

                       

        // 存储短信发送记录信息

        $result['create_time'] = time();

        $result['pid']=0;
        
        $result['create_ip']=get_client_ip();

        $r = M('Short_message')->add($result);

        if($result['send_status'] != '000000') {

            echo json_encode(array('status'=>0,'msg'=>'发送失败，请重新获取'));exit;

        }        

        $telsvcode['code']  = $rand;

        $telsvcode['phone'] = $phone;

        $telsvcode['time']  = $result['create_time'];

        $telsvcode['delay'] = $delay;

        session('telsvcode',$telsvcode);

        

        if ($way == 0) {

            echo json_encode(array(

                'status'=> 2,

                'msg'   => "注册成功！请在".$delay."分钟内完成<br/>验证码已经发送到 $phone",

                "type"  => $type,

                'data'  => $telsvcode)

            );           

        } else if ($way == 1) {

            echo json_encode(array('status'=>2,'msg'=>'验证码已重新发送，请查看您的手机',"type"=>$type,'data'=>$telsvcode));

        } else if ($way == 2){

            echo json_encode(array('status'=>2,'msg'=>"请输入验证码，验证码已经发送到 $phone","type"=>$type,'data'=>$telsvcode));

        }            

    }



    /**

    *短信验证

    */

    public function check_tel_code($account='',$password='',$type='',$verify='',$way='',$p_id='',$g_id=''){
        $telcode = session('telsvcode');
        //$account是手机号码
        if($account!=$telcode['phone']){
            echo json_encode(array('status'=>0,'msg'=>'发送验证码后手机号不允许修改'));exit;
        }
        $time = (time() - $telcode['time'])/60;

        if ($time>$telcode['delay']) {

            session('telsvcode',null);unset($telcode);

            echo json_encode(array('status'=>0,'msg'=>'时间超时,请重新获取验证码'));exit;

        }

        if ($telcode['code'] == $verify) {

            switch ($way) {

                case 0:#注册成功

                    $result = $this->register($account,$password,$type,$g_id,$p_id,$way);

                    if($result){
                        if(session('game_url')!=''){
                            $url=session('game_url');
                        }else{

                            $url=U('Subscriber/user');

                        }

                        $this->ajaxReturn(array("status"=>1,"msg"=>"成功","url"=>$url));

                    }else{

                        $this->ajaxReturn(array("status"=>0,"msg"=>"失败","url"=>$url));

                    }

                    break;
                case 2:
                    $phone = $account;
                    $account = $p_account;
                    $userp=D('User')->where(array('phone'=>$phone))->find();
                    if($userp){
                        $this->ajaxReturn(array("status"=>0,"msg"=>"手机已使用"));exit;
                    }else{
                        $binddata = D('User')->where(array('id'=>$account))->setField('phone',$phone);
                    }
                    if($binddata!=false){
                        $this->ajaxReturn(array("status"=>1,"msg"=>"绑定成功"));
                    }else{
                        $this->ajaxReturn(array("status"=>0,"msg"=>"绑定失败"));
                    }

                    break;
                default:

                    $result = $this->update_paw($username,$password);

                    if($result > 0){

                        $url = U("Subscriber/index");

                        $this->ajaxReturn(array("status"=>2,"msg"=>"成功","url"=>$url));

                    }

                    else{

                        $msg = $result == -1 ? "账号不存在":"修改失败";

                        $this->ajaxReturn(array("status"=>0,"msg"=>$msg,));

                    }

                    break;

            }

        }else{

            echo json_encode(array('status'=>0,'msg'=>'安全码不正确，请重新输入'));

        }

    }



    /** * 第三方微信公众号登陆 * */

    public function wechat_login($state=0,$gid=0,$pid=0){

        if(is_weixin()){

            $appid     = C('wechat.appid');

            $appsecret = C('wechat.appsecret');

            $token = session("token");

            $auth = new WechatAuth($appid, $appsecret, $token);

            if(session('for_third')==C(PC_SET_DOMAIM)){

                $state=$_SERVER['HTTP_HOST'];

                $redirect_uri = "http://".session('for_third')."/mobile.php/TuiRegister/wechat_login_game/gid/".$gid.'/pid/'.$pid;

            }else{

                $redirect_uri = "http://".$_SERVER['HTTP_HOST']."/mobile.php/TuiRegister/wechat_login_game/gid/".$gid.'/pid/'.$pid;

            }


            redirect($auth->getRequestCodeURL($redirect_uri,$state));

        }

    }



    /** * 第三方微信扫码登陆 * */

    public function wechat_qrcode_login($state=1,$gid=0,$pid=0){

        if(!is_weixin()){

            $appid     = C('wx_login.appid');

            $appsecret = C('wx_login.appsecret');

            $auth  = new WechatAuth($appid, $appsecret);

            if(session('for_third')==C(PC_SET_DOMAIM)){

                $state=$_SERVER['HTTP_HOST'];

                $redirect_uri = "http://".session('for_third')."/mobile.php/TuiRegister/wechat_login_game/gid/".$gid.'/pid/'.$pid;

            }else{

                $redirect_uri = "http://".$_SERVER['HTTP_HOST']."/mobile.php/TuiRegister/wechat_login_game/gid/".$gid.'/pid/'.$pid;

            }
            redirect($auth->getQrconnectURL($redirect_uri,$state));

        }

    }



    public function wechat_login_game(){
        $host=M('apply_union','tab_')->where(array('domain_url'=>$_GET['state']))->find();
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
        if(session('union_host')!=''){
            $_REQUEST['pid']=session('union_host')['union_id'];//判断是否联盟站域名
        }
        
        $auth  = new WechatAuth($appid, $appsecret);
        $token = $auth->getAccessToken("code",$_GET['code']);
        $userInfo = $auth->getUserInfo($token['openid']);
        $login_data['account']  = "WX_".date ( 's' ).sp_random_string(6);
        $login_data['nickname'] = $userInfo['nickname'];
        $login_data['headimgurl'] = $userInfo['headimgurl'];
        $login_data['openid']     = $userInfo['unionid'];
        $login_data['promote_id'] = $_REQUEST['pid'];
        $login_data['promote_account'] = get_promote_name($_REQUEST['pid']);
        $login_data['parent_id']        = get_fu_id($_REQUEST['pid']);
        $login_data['register_way']=3;
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
        if($_GET['state'] === 0){
            $this->redirect("Index/index");
        }else{
            if($_REQUEST['gid']!=0&&get_game_name($_REQUEST['gid'])!=''){
                $this->redirect("Game/open_game/game_id/".$_REQUEST['gid']);
            }else{
               $this->redirect("Subscriber/index"); 
            }
        }
    }
   //打开游戏
    public function open_game()
    {
        $param = I('get.');
        $game_id = $param['gid'];
        unset($param['gid']);
        $param['game_id'] = $game_id;
        $url = U('Game/open_game',$param);
        redirect($url);exit;
        $http = 'http';
        if(is_https()){
            $http = 'https';
        }
        $full_url = $http.'://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        $_1 = strpos($full_url, 'mobile.php/');
        $_2 = strpos($full_url, 'mobile.php?');
        $_3 = strpos($full_url, 'mobile.php/?');//此格式用于微信公众号支付授权页面
        if($_1&&!$_3){
            $url = str_replace('mobile.php/','mobile.php/?s=',$_SERVER['REQUEST_URI']);
        }elseif($_2){
            $url = str_replace('mobile.php?s=','mobile.php/?s=',$_SERVER['REQUEST_URI']);
            if(!$url){
                $url = str_replace('mobile.php?','mobile.php/?s=',$_SERVER['REQUEST_URI']);
            }
        }
        if($url){
            $url = $http.'://'.$_SERVER["HTTP_HOST"].$url;
            header("Location: {$url}");
        }
        $_GET['game_id'] = $_GET['gid'];
        if(!$_GET['game_id']){
            $this->ajaxReturn(array('code'=>0,'data'=>'缺少游戏参数'));
        }
        $type = M('game','tab_')->where(array('id'=>$_GET['game_id']))->find();
        $this->assign('game_name',$type['game_name']);
        if($type['type']==0){
            redirect($type['third_url']);exit;
        }
        if(I('token')){
            $this->app_write_session(I('token'));
        }
        $gmset = M('game_set','tab_')->field('support_sdk')->where(array('id'=>$_GET['game_id']))->find();
        if(is_weixin()){
            //微信分享参数
            $signPackage = $this->sharefun();
            if(empty(session('shareparams'))){
                $shareparams['title'] = $type['game_name']; 
                $shareparams['desc'] = '我正在玩“'.$type['game_name'].'”,快来和我一起玩吧！';  
                $shareparams['imgUrl'] = $http.'://'.$_SERVER["HTTP_HOST"].get_cover($type['icon'],'path'); 
                $shareparams['link'] = $full_url;
            }else{
                $shareparams = session('shareparams');
            }
            $this->assign('signPackage',$signPackage);
            $this->assign('shareparams',$shareparams);
        }
        if($promote_id>0){
            $pid=$promote_id;
        }else{
            $pid =$_GET['pid']==""?0:$_GET['pid'];
        }
        if($gmset['support_sdk']){
            $GameApi = new GameApi();
            $uid = session("user_auth.user_id");
            $game_id = $_GET['game_id'];
            $login_url = $GameApi->game_login($uid,$game_id,$pid);
            $this->assign("login_url",$login_url);
            $this->display('open_game');
        }else{
            $url=$_SERVER['REQUEST_URI'];
            $_SESSION["url"]=$url;
            session('game_url',$url);
            $this->is_login() || redirect(U('Subscriber/login',array('gid'=>$_GET['game_id'],'pid'=>$_GET['pid'])));
            session('game_url',null);
            $GameApi = new GameApi();
            $uid = session("user_auth.user_id");
            $game_id = $_GET['game_id'];
            $login_url = $GameApi->game_login($uid,$game_id,$pid);
            $map['name'] = 'GAME_BACKGROUND_IMG';
            $img = M('config','sys_')->field('value')->where($map)->find();
            $map1['id'] = $img['value'];
            $image = M('picture','sys_')->field('path')->where($map1)->find();
            $this->assign('game_img',$image['path']);
            $this->assign("login_url",$login_url);
            $this->display();
        }
    }
}