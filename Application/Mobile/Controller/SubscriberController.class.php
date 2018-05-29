<?php
namespace Mobile\Controller;
use Think\Controller;
use User\Api\MemberApi;
use Common\Api\GameApi;
use Org\ThinkSDK\ThinkOauth;
use Org\WeiXinSDK\WeiXinOauth;
use Org\WeiXinSDK\Weixin;
use Org\UcpaasSDK\Ucpaas;
use Org\XiguSDK\Xigu;
use Common\Api\PayApi;
use Com\Wechat;
use Com\WechatAuth;
use Org\JtpaySDK\Jtpay;
use Org\SwiftpassSDK\Swiftpass;
use Admin\Model\PointTypeModel;
use Common\Model\UserPlayModel;
use Common\Model\UserModel;
use Common\Model\GameModel;
use Common\Model\GiftbagModel;
use Common\Model\MsgModel;

class SubscriberController extends BaseController
{
    protected function _initialize()
    {
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置
    }
    public function __construct()
    {
        parent::__construct();
        $arr = array(
            "Subscriber/user_recharge", "Subscriber/user_auth",
            "Subscriber/user_bind_phone", "Subscriber/user_address", 
            "Subscriber/user_message","Subscriber/user_bind_modify",
            "Subscriber/user_address_edit","Subscriber/user_address_add",
            "Subscriber/user_password","Subscriber/user_balance"
        );
        $pathinfo = CONTROLLER_NAME.'/'.ACTION_NAME;
        $arrstr = implode(',',$arr);
        $arr = array();
        $arr = explode(',',strtolower($arrstr));
        if (in_array(strtolower($pathinfo), $arr, true)) {
            if(!is_login()){
                $this->redirect("Subscriber/user");
            }
        }


        if(is_login()){
            $user_data = M("user", "tab_")->field('id,account,nickname,real_name,register_way,phone,idcard,balance,shop_point,shop_address,head_icon')->find(is_login());
            if(session('nickname')) {$user_data['nickname']=session('nickname');}
						$this->assign('userinfo',$user_data);
        }
    }
    public function user(){
        $pointtype = new PointTypeModel();
        $bindmap['pt.key'] = 'bind_phone';
        $bindjoin .= ' and pr.user_id = '.is_login();
        $bind = $pointtype->getUserLists($bindmap,$bindjoin,'ctime desc',1,1);
        $this->assign('bindpoint',$bind[0]['point']);
        $cooktime= (strtotime(date('Y-m-d',time()))+24*3600*7-1)-time();
        if(is_login()){
            $cookiekey = 'mobile_userbind'.'_'.is_login();
            if(!$_COOKIE[$cookiekey]){
                $userbind = 1;
                cookie('userbind'.'_'.is_login(),1,array('expire'=>$cooktime,'prefix'=>'mobile_'));
            }else{
                $userbind = 0;
            }
        }
        $this->assign('userbind',$userbind);
        $user_data = M("user", "tab_")->field('id,account,nickname,register_way,phone')->find(is_login());

        $lgmap['pt.key'] = 'sign_in';
        if(is_login()){
            $lgjoin .= ' and pr.user_id = '.is_login();
        }else{
            $lgjoin .= ' and pr.user_id = '.is_login();
        }
        $loginpont = $pointtype->getUserLists($lgmap,$lgjoin,'ctime desc',1,1);
        if(empty($loginpont[0]['user_id'])){
            $issignin = 0;//今日是否签到
        }elseif(!empty($loginpont[0]['user_id'])&&$loginpont[0]['ct']==date('Y-m-d',time())){
            $issignin = 1;
        }else{
            $issignin = 0;
            if($loginpont[0]['ct']!=date("Y-m-d",strtotime("-1 day"))){
                $loginpont[0]['day'] = 0;
            }
        }
        if($loginpont[0]['day']>=7||$loginpont[0]['day']<=0||empty($loginpont[0]['day'])){
            $signday = 1;
        }else{
            $signday = $loginpont[0]['day']+1;
        }

        $addpoint = $loginpont[0]['point']+($signday-1)*$loginpont[0]['time_of_day'];//增加多少，今日签过 改变量不使用
        $this->assign('issignin',$issignin);
        $this->assign('addpoint',$addpoint);//今日

        $this->display();
    }
    public function user_nickname(){
        
    }
    public function user_recharge(){
        $paytype = M('tool', 'tab_')->field('status,name')->where(['name'=>['in','weixin,wei_xin,alipay,jft,goldpig']])->select();
        foreach ($paytype as $key => $value) {
            $pay[$value['name']] = $value['status'];
        }
        $this->assign('paytype',$pay);
        $this->display();
    }
    public function deposit_search($pay_order_number){
        sleep(2);
        $paydata = M('deposit','tab_')
                    ->field('pay_amount,user_account,pay_status')
                    ->where(['pay_order_number'=>$pay_order_number,'user_id'=>is_login()])
                    ->find();
        if(!empty($paydata)){
            $this->ajaxReturn(array('status'=>1,'data'=>array('pay_status'=>$paydata['pay_status'],'user_account'=>$paydata['user_account'],'payamount'=>$paydata['pay_amount'])));
        }else{
            $this->ajaxReturn(array('status'=>0));
        }
    }
    public function user_bind_phone(){
        $this->assign('HTTP_REFERER',U('user'));
        
        $this->display();
    }
    public function user_address(){
        $this->display();
    }
    public function user_address_add($type=0){
        $this->assign('HTTP_REFERER',$_SERVER['HTTP_REFERER']==''?U('user_address'):$_SERVER['HTTP_REFERER']);
        if(IS_POST){
            $model = new UserModel();
            $user = is_login();
            $shop_address['consignee'] = $_POST['consignee'];
            $shop_address['consignee_phone'] = $_POST['consignee_phone'];
            $shop_address['consignee_address'] = $_POST['consignee_address'].'-'.$_POST['detailed_address'];
            if($type==1){
                $shop_address = '';
            }
            $res = $model->updateShopAddress($user,json_encode($shop_address));
            if($res!==false){
                $this->ajaxReturn(array('status'=>1));
            }else{
                $this->ajaxReturn(array('status'=>0));
            }
        }else{
            $user_data = M("user", "tab_")->field('id,account,shop_address')->find(is_login());
            if(json_decode($user_data['shop_address'],true)){
                redirect(U('user_address'));exit;
            }
            $this->display();
        }
    }
    public function user_address_edit(){
        $user_data = M("user", "tab_")->field('id,account,shop_address')->find(is_login());
        if(!json_decode($user_data['shop_address'],true)){
            redirect(U('user_address'));exit;
        }
        $this->display();
    }
    public function user_balance(){
        if(is_login()){
            $model = new UserPlayModel();
            $bindmap['user_id'] = is_login();
            $bindmap['bind_balance'] = array('gt',0);
            $binddata = $model->getUserPlay($bindmap);
        }else{
            $binddata = array();
        }
        $this->assign('binddata',$binddata);
        $this->display();
    }
    public function user_auth(){
        $this->display();
    }
    //竣付通
    public function pay_way()
    {
        if (IS_POST) {
            $msign = think_encrypt(md5($_POST['pay_amount'] . $_POST['pay_order_number']));
            if ($msign !== $_POST['sign']) {
                $this->error('验证失败', U('Index/Index'));
                exit;
            }
            #判断账号是否存在
            $user = get_user_entity($_POST['account'], true);
            $jtpay = new Jtpay();
            #平台币记录数据
            $data['out_trade_no'] = $_POST['pay_order_number'];
            $data['user_id'] = $user['id'];
            $data['user_account'] = $user['account'];
            $data['user_nickname'] = $user['nickname'];
            $data['promote_id'] = $user['promote_id'];
            $data['promote_account'] = $user['promote_account'];
            $data['amount'] = $_POST['pay_amount'];
            $data['pay_way'] = 6;//竣付通
            $data['pay_source'] = 1;
            $p4_returnurl = "http://".$_SERVER['HTTP_HOST'].U('Subscriber/user_recharge');
            $pay = A('Pay');
            $pay->add_deposit($data, $user);
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
                $scene = 2;
            }else{
                $scene = 3;
            }
            switch ($_POST['type']) {
                case 'Alipay':
                    echo $jtpay->jt_pay($_POST['pay_order_number'], $_POST['pay_amount'], $_POST['account'], get_client_ip(), '', 4, $p4_returnurl, 3, $scene);
                    break;
                case 'wapWeChat':
                    echo $jtpay->jt_pay($_POST['pay_order_number'], $_POST['pay_amount'], $_POST['account'], get_client_ip(), '', 3, $p4_returnurl, 3, $scene);
                    break;
                case 'WeChat':
                    echo $jtpay->jt_pay($_POST['pay_order_number'], $_POST['pay_amount'], $_POST['account'], get_client_ip(), '', 3, $p4_returnurl, 4, 2);
                    break;
                case 'QQ':
                    echo $jtpay->jt_pay($_POST['pay_order_number'], $_POST['pay_amount'], $_POST['account'], get_client_ip(), '', 11, $p4_returnurl, 3, 2);
                    break;
                default:
                    echo $jtpay->jt_pay($_POST['pay_order_number'], $_POST['pay_amount'], $_POST['account'], get_client_ip(), "", $_POST['p9_paymethod']);
                    break;
            }

        } else {
            $this->assign('type', $_GET['type']);
            $this->display();
        }
    }
    //验证中文名字
    function isChineseName($name){
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            if(preg_match("/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/",$name)){
                $this->ajaxReturn(array('status'=>1,'msg'=>'中文名正确'));
            }else {
                $this->ajaxReturn(array('status'=>0,'msg'=>'中文名错误'));
            }
        }else{
            if(preg_match("/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/",$name)){
                return true;
            }else {
                return false;
            }
        }
    }
    //验证中文名字
    function isChineseNamereturn($name){
        if(preg_match("/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/",$name)){
            return true;
        }else {
            return false;
        }
    }
    //验证身份证号码
    function isidcard($idcard,$name){
        if (get_tool_status('age') != 0){
            $cardd=M('User','tab_')->where(array('idcard'=>$_POST['idcard']))->find();
            if($cardd){
                $this->ajaxReturn(array('status'=>-2,'msg'=>'身份证号码已被使用'));
            }
            $re = ageVerify($idcard,$name);
            switch ($re)
            {
                case 0:
                    $this->ajaxReturn(array('status'=>-1,'msg'=>'认证失败 请稍后再试'));
                    break;
                case 1://成年
                    cookie(is_login(),1,array('prefix'=>'idcard_'));
                    break;
                case 2://未成年
                    cookie(is_login(),2,array('prefix'=>'idcard_'));
                    break;
                default:
            }
        }else{
            $checkidcard = new \Think\Checkidcard();
            $invidcard=$checkidcard->checkIdentity($idcard);
            if(!$invidcard){
                $this->ajaxReturn(array('status'=>-1,'msg'=>'身份证号码填写不正确，字母需小写'));
            }
        }
        $idcardinfo = getIDCardInfo($_POST['idcard']);
        if($idcardinfo['error']==2&&$idcardinfo['isAdult']==0){
            cookie(is_login(),1,array('prefix'=>'idcard_'));
        }else{
            cookie(is_login(),2,array('prefix'=>'idcard_'));
        }
        $this->ajaxReturn(array('status'=>1,'msg'=>'身份证号码正确未被使用'));
    }
    function isidcardreturn($idcard,$name){
        $cardd=M('User','tab_')->where(array('idcard'=>$_POST['idcard']))->find();
        if($cardd){
            return -2;
        }
        if (get_tool_status('age') != 0){
            $re = ageVerify($idcard,$name);
            switch ($re)
            {
               
                case 0:
                    return -3;//接口问题  余额不足 或接口连接失败
                    break;
                case 1://成年
                    cookie(is_login(),1,array('prefix'=>'idcard_'));
                    break;
                case 2://未成年
                    cookie(is_login(),2,array('prefix'=>'idcard_'));
                    break;
                default:
            }
        }else{
            $checkidcard = new \Think\Checkidcard();
            $invidcard=$checkidcard->checkIdentity($idcard);
            if(!$invidcard){
                return -1;
            }
        }
        $idcardinfo = getIDCardInfo($_POST['idcard']);
        if($idcardinfo['error']==2&&$idcardinfo['isAdult']==0){
            cookie(is_login(),1,array('prefix'=>'idcard_'));
        }else{
            cookie(is_login(),2,array('prefix'=>'idcard_'));
        }
        return 1;
    }
    function checkphoneexsite($phone, $type = '')
    {
        $userp = M('User','tab_')->where(array('phone' => $phone))->find();
        if ($userp) {
            if ($userp['id'] != is_login()) {
                $this->ajaxReturn(array('status' => 1, 'msg' => '此手机号不是您已绑定的手机号', 'code' => 0));//status = 1存在手机号码 code = 0 已登录用户未绑定该手机号码   用于解绑
                exit;
            }
            $this->ajaxReturn(array('status' => 1, 'msg' => '手机存在', 'code' => 1));
            exit;
        } else {
            $this->ajaxReturn(array('status' => 0, 'msg' => '手机不存在', 'code' => 0));
            exit;
        }
    }
    public function bind_idcard($name,$idcard){
        $userid= is_login();
        $isname = $this->isChineseNamereturn($name);
        if(!$isname){
            $this->ajaxReturn(array('status'=>-3,'msg'=>'中文名错误'));
        }else{
            $isidcard = $this->isidcardreturn($idcard,$name);
            switch ($isidcard) {
                case -1:
                    $this->ajaxReturn(array('status'=>-1,'msg'=>'身份证号码填写不正确，字母需小写'));
                    break;
                case -2:
                    $this->ajaxReturn(array('status'=>-2,'msg'=>'身份证号码已被使用'));
                    break;
                case -2:
                    $this->ajaxReturn(array('status'=>-3,'msg'=>'认证失败，请稍后再试'));
                    break;
                case 1:
                    $save['id'] = $userid;
                    $save['real_name'] = $name;
                    $save['idcard'] = $idcard;
                    $idcardid = $_COOKIE['idcard_'.is_login()];
                    if($idcardid==1){
                        $save['age_status'] = 2;
                    }elseif($idcardid==2){
                        $save['age_status'] = 3;
                    }
                    $res = M('User','tab_')->save($save);
                    if($res!==false){
                        $this->ajaxReturn(array('status'=>1,'msg'=>'绑定成功'));
                    }else{
                        $this->ajaxReturn(array('status'=>1,'msg'=>'绑定失败'));
                    }
                    break;
            }
        }
    }


    public function get_wx_code()
    {
        $jieminame = get_uname();
        if ($_GET['state'] != '' && isset($_GET['code'])) {
            $stateparam = explode(',', $_GET['state']);//微信支付参数
            $jieminame = simple_decode($stateparam[2]);
        }
        if ($_REQUEST['game_id'] != '') {
            session('pay_game_id', $_REQUEST['game_id']);
        }
        $user = get_user_entity($jieminame, true);
        if (empty($user)) {
            $this->ajaxReturn(array('msg' => '用户不存在！', 'status' => 0));
            exit();
        }
        Vendor("WxPayPubHelper.WxPayPubHelper");
        // 使用jsapi接口
        $jsApi = new \JsApi_pub(C('wei_xin.appsecret'), C('wei_xin.email'), C('wei_xin.key'));
        //=========步骤1：网页授权获取用户openid============
        //通过code获得openid
        if (!isset($_GET['code'])) {
            //触发微信返回code码
            if (session('for_third') == C(PC_SET_DOMAIM)) {
                $code_url = "http://" . session('for_third') . '/mobile.php/?s=/Subscriber/get_wx_code/';
            } else {
                $code_url = "http://" . $_SERVER['HTTP_HOST'] . '/mobile.php/?s=/Subscriber/get_wx_code/';
            }
            $datt['out_trade_no'] = "PF_" . date('Ymd') . date('His') . sp_random_string(4);
            $datt['amount'] = $_POST['amount'];
            $datt['pay_status'] = 0;
            $datt['pay_way'] = 2;
            $datt['pay_source'] = 1;
            $pay = A('Pay');
            $pay->add_deposit($datt, $user);
            $openid = session("wechat_token.openid");
            $state = $datt["amount"] . ',' . $datt['out_trade_no'] . ',' . simple_encode(get_uname()) . ',' . $_POST["game_id"] . ',' . $_SERVER['HTTP_HOST'].','.$openid;//指针0金额，1订单，2用户名，3游戏id,4发起支付的域名,5openid
            $url = $jsApi->createOauthUrlForCode($code_url, $state);
            $this->ajaxReturn(array('url' => $url, 'status' => 1));
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $jsApi->setCode($code);
            $openid = $stateparam[5];
            $weixn = new Weixin();
            $amount = $stateparam[0];
            $out_trade_no = $stateparam[1];
            $game_id = $stateparam[3];
            if ($game_id != '') {
                $title = '游戏充值';
                $this->assign('game_url', U('Game/game_pay_callback', array('order_number' => $out_trade_no, 'game_id' => $game_id)));

                session('pay_game_id', null);
            } else {
                $title = '平台币充值';
                $this->assign('game_url', U('Subscriber/index'));
            }
            $is_pay = $weixn->weixin_jsapi($title, $out_trade_no, $amount, $jsApi, $openid);
            $this->assign('jsApiParameters', $is_pay);
            $this->assign('hostdomain', $stateparam[4]);
            $this->display();
        }

    }


    public function user_bind_modify(){
        $user = is_login();
        $data = get_user_entity($user);
        if(!preg_match("/^1[34578]\d{9}$/",$data['phone'])){
            redirect(U('user_bind_phone'));
        }
        $this->display();
    }
    public function user_password(){
        if(IS_POST){
            $this->change_paw();
        }
        $user = M('User','tab_')->field('register_way')->find(is_login());
        if($user['register_way']>2){
            redirect(U('Subscriber/user'));exit;
        }
        $this->display();
    }
    public function user_gift(){
        $giftbgmodel = new GiftbagModel();
        $user = is_login();
        $allgamegift = $giftbgmodel->myGiftRecord($user);
        if(!empty($allgamegift)){
            redirect(U('user_gifted'));
        }
        $gamegift = $giftbgmodel->getGiftLists(array('gt',0),1,3,array('giftbag_type'=>1),'','gb.id desc');
        $this->assign('giftlist',count($gamegift)>2?$gamegift:array());
        $this->display();
    }
    public function user_gifted(){
        $giftbgmodel = new GiftbagModel();
        $user = is_login();
        $allgamegift = $giftbgmodel->myGiftRecord($user);
        if(empty($allgamegift)){
            redirect(U('user_gift'));
        }else{
            $this->assign('mygift',$allgamegift);
        }
        $this->display();
    }
    
    public function user_collection(){
        $model = new GameModel();
        $user = is_login();
        $data1 = $model->myGameCollect($user,1,1,10000);
        $data2 = $model->myGameFoot($user,2,1,10000);
       
        $index = A('Index');
        $recgame = $index->more_game(1,1,3);
        $hotgame = $index->more_game(2,1,3);
        $this->assign('coll',$data1);
        $this->assign('foot',$data2);
        $this->assign('recgame',count($recgame['data'])>2?$recgame['data']:array());
        $this->assign('hotgame',count($hotgame['data'])>2?$hotgame['data']:array());
        $this->display();
    }

    public function optionBehavior(){
        $ids = explode(',',$_POST['ids']);
        $ids = array_unique($ids);
        if(empty($ids)) $this->ajaxReturn(array('status'=>0));
        $ids = implode(',',$ids);
        $model = new GameModel();
        $user = is_login();
        $map['id'] = array('in',$ids);
        $data = $model->optionBehavior($user,$_POST['type'],$map);
        if($data!=false){
            $this->ajaxReturn(array('status'=>1));
        }else{
            $this->ajaxReturn(array('status'=>0));
        }
    }

    public function user_contact(){
        $this->display();
    }
    public function user_argeement($type=''){
        if (!empty($type) && $type==1){
            $this->assign('type',$type);
        }
        $this->display();
    }
    public function user_message(){
        $model = new MsgModel();
        $map['user_id'] = is_login();
        $map['status'] = array('gt',0);
        $map1['user_id'] = is_login();
        $map1['status'] = 2;
        $model->optionMsg($map1);
        $msg = $model->getMsglist($map,'create_time desc,id desc',1,100000);
        $this->assign('msg',$msg);
        $this->display();
    }
    /**

    *忘记密码

    */

    private function update_paw($username,$password,$isphone=0){
        if($isphone){
            $map['phone'] = $username;
        }else{
            $map['account'] = $username;
        }
        if($username==''){
            return -1;
        }
        $user_data = M("user","tab_")->where($map)->select();
        if(count($user_data)>=2){
            return -3;//账号或手机被二次使用
        }
        if(empty($user_data)){
            return -2;
        }
        $user = new MemberApi();
        $result = $user->updatePassword($user_data[0]['id'],$password);    
        return $result;
    }
    /**
    *修改密码
    */
    public function change_paw(){
            if(empty($_POST['oldpwd'])){
                return $this->ajaxReturn(array('status'=>0,'msg'=>'原密码不能为空'));exit;
            }else if(strlen($_POST['newpwd'])>20||strlen($_POST['newpwd'])<6){
                return $this->ajaxReturn(array('status'=>0,'msg'=>'新密码长度在6~15个字符'));
            }elseif(!preg_match('/^[a-zA-Z0-9_\.]+$/',$_POST['newpwd'])){
                return $this->ajaxReturn(array('status'=>0,'msg'=>'新密码由字母或数字组成'));
            }
            $user_id = is_login();
            $data['id']=$user_id;
            $data['old_password']=$_POST['oldpwd'];
            $data['password']=$_POST['newpwd'];
            $user = new MemberApi();
            $result = $user->updateUser($data);
            if($result==-2){
                return $this->ajaxReturn(array('status'=>-2,'msg'=>'原密码错误'));
            }elseif($result!==false){
                session('user_auth', null);
                session('user_auth_sign', null);
                session("wechat_token", null);
                return $this->ajaxReturn(array('status'=>1,'msg'=>'修改成功！'));
            }else{
                return $this->ajaxReturn(array('status'=>-1,'msg'=>'修改失败！'));
            }
    }
    /**
     * 登陆
     */
    public function login($username = '', $password = '', $type = "", $gid = '')
    {
        if (IS_POST) {
            $user = new MemberApi();
            $result = $user->login($username, $password, '', $gid);
            switch ($result) {
                case -1:#账号不存在
                    $this->ajaxReturn(array("status" => 0, "msg" => "账号不存在"));
                    break;
                case -2:
                    $this->ajaxReturn(array("status" => 0, "msg" => "密码错误"));
                    break;
                case -4:
                    $this->ajaxReturn(array("status" => 0, "msg" => "用户被锁定"));
                    break;
                case -5:
                    $this->ajaxReturn(array("status" => 0, "msg" => "游戏不存在"));
                    break;
                default:
                    if ($gid) {
                        $GameApi = new GameApi();
                        $sdk2cplogin = $GameApi->sdk2cplogin($result, $gid);
                    }
                    $this->ajaxReturn(array("status" => 1, "msg" => "登陆成功", "url" => $url, 'data' => $sdk2cplogin));
                    break;
            }
        }else{
            $this->ajaxReturn(array("status" => 0, "msg" => "非法操作"));
        }
    }


    /** * 第三方登陆 * */
    public function thirdlogin($type = null)
    {
        empty($type) && $this->error('参数错误');
        //加载ThinkOauth类并实例化一个对象
        $sns = ThinkOauth::getInstance($type);
        if (!empty($sns)) {
						$hash = I('get.hash','');
$hash = preg_replace('/^#.\.php$/ig','',$hash);
						session('login_reg',$_SERVER['HTTP_REFERER'].($hash?'#'.$hash:''));
            if ($type == "weixin") {
                if (is_weixin()) {
                    $this->wechat_login(1);
                } else {
                    $this->wechat_qrcode_login(1);
                }
            }elseif($type=="weibo"){
                $appkey = C('THINK_SDK_WEIBO.APP_KEY');
                $scope = C('THINK_SDK_WEIBO.SCOPE');
                $callback = C('THINK_SDK_WEIBO.CALLBACK');
                $ud['gid'] = $gid;
                $ud['pid'] = $pid;
                $callback = $callback.'&'.http_build_query($ud);
                $wburl=$sns->login($appkey, $callback, $scope);
                redirect($wburl);
            } else {
                //跳转到授权页面
                redirect($sns->getRequestCodeURL());
            }
        }
    }
    public function ykregister(){
        $this->register();
    }
    public function register()
    {
        cookie(null,'idcard_');//清除实名认证成年未成年cookie
        if (IS_POST) {
            if (C("PC_ALLOW_REGISTER") == 1) {
                $type = preg_match ("/^[a-z]/i", substr($_POST['account'],0,1))?'account':'phone';
                if($_POST['ykregister']=='yk'){
                    $type ='yk';
                }
                $open_name_auth = C('REAL_NAME_REGISTER');
                if($open_name_auth&&$type!='yk'){
                    if($_POST['real_name']==''){
                        $this->ajaxReturn(array('status' => 0, 'msg' => '未填写真实姓名'));
                    }elseif($_POST['idcard']==''){
                        $this->ajaxReturn(array('status' => 0, 'msg' => '未填写身份证号码'));
                    }
                    $rn = $this->isChineseNamereturn($_POST['real_name']);
                    if(!$rn){
                        $this->ajaxReturn(array('status' => 0, 'msg' => '请填写真实姓名'));
                    }
                    $idc = $this->isidcardreturn($_POST['idcard'],$_POST['real_name']);
                    switch ($idc) {
                        case '-1':
                            $this->ajaxReturn(array('status' => 0, 'msg' => '请填写正确的身份证号码，字母需小写'));
                            break;
                        
                        case '-2':
                            $this->ajaxReturn(array('status' => 0, 'msg' => '身份证号码已被使用'));
                            break;
                        case '-3':
                            $this->ajaxReturn(array('status'=>0,'msg'=>'认证失败，请稍后再试'));
                            break;
                    }
                }
                if ($type == 'account') {
                    $verify = new \Think\Verify();
                    if ($verify->check(I('verify'))) {
                        if (empty($_POST['account']) || empty($_POST['password'])) {
                            return $this->ajaxReturn(array('status' => 0, 'msg' => '账号或密码不能为空'));
                            exit;
                        } else if (strlen($_POST['account']) > 15 || strlen($_POST['account']) < 6) {
                            return $this->ajaxReturn(array('status' => 0, 'msg' => '用户名长度在6~15个字符'));
                        } else if (!preg_match('/^[a-zA-Z0-9]{6,15}$/', $_POST['account'])) {
                            return $this->ajaxReturn(array('status' => 0, 'msg' => '用户名包含特殊字符'));
                            exit;
                        }
                    } else {
                        return $this->ajaxReturn(array('status' => 0, 'msg' => '验证码不正确，请重新输入', 'code' => -1));
                        exit;
                    }
                }elseif($type=='phone'){
                    $coderes = $this->check_tel_code($_POST['account'],'','',$_POST['verify'],1);
                    if($coderes['status']!=1){
                        return $this->ajaxReturn($coderes);
                        exit;
                    }
                }else{
                    $isyk = 1;
                    $nowtt = time();
                    $_POST['account'] = 'yk_'.random_string(2).date('hi',$nowtt).random_string(2);
                    $_POST['password'] = random_string(15);
                }
                if (session('union_host') != '') {
                    $_REQUEST['p_id'] = session('union_host')['union_id'];//判断是否联盟站域名
                    $data['is_union'] = 1;
                } else {
                    $_REQUEST['p_id'] = $_POST['p_id'];
                }
                $user = new MemberApi();
                $data['account'] = $_POST['account'];
                $data['password'] = $_POST['password'];
                $data['nickname'] = $_POST['account'];
                $data['phone'] = $type == 'phone' ? $_POST['account'] : "";
                $data['promote_id'] = $_REQUEST['p_id']==''?0:$_REQUEST['p_id'];
                $data['promote_account'] = get_promote_name($_REQUEST['p_id']);
                $data['parent_id'] = get_fu_id($_REQUEST['p_id']);
                $data['parent_id'] = get_fu_id($_REQUEST['p_id']);
                $data['parent_name'] = get_parent_name($_REQUEST['p_id']);
                $data['fgame_id'] = $_POST['g_id'];
                $data['fgame_name'] = get_game_name($data['fgame_id']);
                $data['register_time'] = time();
                $data['real_name'] = $_POST['real_name'];
                $data['idcard'] = $_POST['idcard'];
                $idcardid = $_COOKIE['idcard_'.is_login()];
                if($idcardid==1){
                    $data['age_status'] = 2;
                }elseif($idcardid==2){
                    $data['age_status'] = 3;
                }
                $data['third_login_type'] = $type == "phone" ? 1 : 0;
                if ($type == 'phone') {
                    $data['register_way'] = 2;
                } elseif ($type == 'account') {
                    $data['register_way'] = 1;
                } else {
                    $data['register_way'] = 0;
                }
                $result = $user->register($data);
                if ($type == "account" || $type == 'phone' || $type == 'yk') {
                    if($type == 'yk'&&$result==-3){
                        $data['account'] = 'yk_'.date('Ymdhis',time());
                        $data['nickname'] = $data['account'];
                        $result = $user->register($data);
                    }
                    switch ($result) {
                        case -3:
                            $return = array("status" => -3, "msg" => "账号已存在");
                            break;
                        case 0:
                            $return = array("status" => 0, "msg" => "注册失败");
                            break;
                        default:
                            
                            if ($data['fgame_id']) {
                                $GameApi = new GameApi();
                                $sdk2cplogin = $GameApi->sdk2cplogin($result, $data['fgame_id']);
                            }
                            if($isyk!=1){
                                $return = array("status" => 1, "msg" => "注册成功", 'data' => $sdk2cplogin);
                            }else{
                                $return = array("status" => 1, "msg" => "注册成功",'isyk'=>$isyk,'account'=>$data['account'],'password'=>$data['password'], 'data' => $sdk2cplogin);
                                $cooktime= (strtotime(date('Y-m-d',time()))+24*3600*7-1)-time();
                                $cookiekey = 'mobile_userbind'.'_'.is_login();
                                if(!$_COOKIE[$cookiekey]){
                                    cookie('userbind'.'_'.is_login(),1,array('expire'=>$cooktime,'prefix'=>'mobile_'));
                                }
                            }
                            break;
                    }
                    return $this->ajaxReturn($return);
                    exit;
                }
            } else {
                return $this->ajaxReturn(array('status' => 0, 'msg' => '未开放注册！！'));
            }
        }
    }
    function checkaccount($account)
    {
        $map['account|phone'] = $account;
        $user = M('User','tab_')->where($map)->find();
        if (!$user) {
            $this->ajaxReturn(array('status' => 0, 'msg' => '用户不存在', 'code' => 0));
            exit;
        }else{
            if($user['phone'] == ''){
                $this->ajaxReturn(array('status' => 0, 'msg' => '该账号不是手机注册用户', 'code' => 1));
                exit;
            }else{
                $this->ajaxReturn(array('status' => 1, 'msg' => '用户存在', 'code' => 1));
                exit;
            }
        }
    }

    //  手机安全码

    public function telsvcode($phone = null, $delay = 10, $way = 1, $type = "phone")
    {
        /// 产生手机安全码并发送到手机且存到session
        $rand = rand(100000, 999999);
        $param = $rand . "," . $delay;
        if (get_tool_status("sms_set")) {
            checksendcode($phone, C('sms_set.limit'));
            $xigu = new Xigu(C('sms_set.smtp'));
            $result = json_decode($xigu->sendSM(C('sms_set.smtp_account'), $phone, C('sms_set.smtp_port'), $param), true);
            if ($result['send_status'] != '000000') {
                echo json_encode(array('status' => 0, 'msg' => '发送失败，请重新获取'));
                exit;
            }
        } elseif (get_tool_status("alidayu")) {
            checksendcode($phone, C('alidayu.limit'));
            $xigu = new Xigu('alidayu');
            $result = $xigu->alidayu_send($phone, $rand, $delay);
            $result['send_time'] = time();
            if ($result == false) {
                echo json_encode(array('status' => 0, 'msg' => '发送失败，请重新获取'));
                exit;
            }
        } elseif (get_tool_status('jiguang')) {
            checksendcode($phone, C('jiguang.limit'));
            $xigu = new Xigu('jiguang');
            $result = $xigu->jiguang($phone, $rand, $delay);
            $result['send_time'] = time();
            if ($result == false) {
                echo json_encode(array('status' => 0, 'msg' => '发送失败，请重新获取'));
                exit;
            }
        } elseif (get_tool_status('alidayunew')) {
            checksendcode($phone, C('alidayunew.limit'));
            $xigu = new Xigu('alidayunew');
            $result = $xigu->alidayunew_send($phone, $rand, $delay);
            $result['send_time'] = time();
            if ($result == false) {
                echo json_encode(array('status' => 0, 'msg' => '发送失败，请重新获取'));
                exit;
            }
        } elseif (get_tool_status('alidayumsg')) {
            checksendcode($phone, C('alidayumsg.limit'));
            $xigu = new Xigu('alidayumsg');
            $result = $xigu->alidayumsg_send($phone, $rand, $delay);
            $result['send_time'] = time();
            if ($result == false) {
                echo json_encode(array('status' => 0, 'msg' => '发送失败，请重新获取'));
                exit;
            }
        } else {
            echo json_encode(array('status' => 0, 'msg' => '没有配置短信发送'));
            exit;
        }
        // 存储短信发送记录信息
        $result['send_status'] = '000000';
        $result['phone'] = $phone;
        $result['create_time'] = time();
        $result['pid'] = 0;
        $result['create_ip'] = get_client_ip();
        $r = M('Short_message')->add($result);
        $telsvcode['code'] = $rand;
        $telsvcode['phone'] = $phone;
        $telsvcode['time'] = time();
        $telsvcode['delay'] = $delay;
        session('telsvcode', $telsvcode);
        if ($way == 0) {
            echo json_encode(array(
                    'status' => 1,
                    'msg' => "注册成功！请在" . $delay . "分钟内完成<br/>验证码已经发送到 $phone",
                    "type" => $type,
                    'data' => $telsvcode)
            );
        } else if ($way == 1) {
            echo json_encode(array('status' => 1, 'msg' => '验证码已发送', "type" => $type, 'data' => $telsvcode));
        } else if ($way == 2) {
            echo json_encode(array('status' => 1, 'msg' => "请输入验证码，验证码已经发送到 $phone", "type" => $type, 'data' => $telsvcode));
        }
    }
    /**
     *短信验证
     */

    public function check_tel_code($account = '', $password = '', $type = '', $verify = '', $way = '', $g_id = '', $g_name = '', $p_id = '', $unsetcode = 1)
    {
        $telcode = session('telsvcode');
        if (!$telcode) {
            if(ACTION_NAME=='register'){
                return array('status' => 0, 'msg' => '验证码无效，请重新获取');
            }
            echo json_encode(array('status' => 0, 'msg' => '验证码无效，请重新获取'));
            exit;
        }
        if ($account != $telcode['phone']) {
            if(ACTION_NAME=='register'){
                return array('status' => 0, 'msg' => '验证码无效，请重新获取');
            }
            echo json_encode(array('status' => 0, 'msg' => '验证码无效，请重新获取'));//发送验证码后手机号不允许修改
            exit;
        }
        $time = (time() - $telcode['time']) / 60;
        if ($time > $telcode['delay']) {
            session('telsvcode', null);
            unset($telcode);
            if(ACTION_NAME=='register'){
                return array('status' => 0, 'msg' => '时间超时,请重新获取验证码');
            }
            echo json_encode(array('status' => 0, 'msg' => '时间超时,请重新获取验证码'));
            exit;
        }
        if ($telcode['code'] == $verify) {
            //unsetcode 注销
            if($unsetcode==1){
                session('telsvcode', null);//使用后销毁
            }
            switch ($way) {
                case 0:#注册成功
                    $result = $this->register($account, $password, $type, $g_id, $g_name, $p_id, $p_account, $way);
                    if ($result) {
                        $url = U("Subscriber/index");
                        $this->ajaxReturn(array("status" => 1, "msg" => "注册成功", "url" => $url));
                    } else {
                        $this->ajaxReturn(array("status" => 0, "msg" => "注册失败", "url" => $url));
                    }
                    break;
                case 1:
                    if(ACTION_NAME=='register'){
                        return array('status' => 1, 'msg' => '成功');
                    }
                    echo json_encode(array('status' => 1, 'msg' => '成功'));exit;
                    break;
                case 2:
                    $phone = $account;
                    $userp = D('User')->where(array('phone' => $phone))->find();
                    $binddata = D('User')->where(array('id' => is_login_user()))->setField('phone', $phone);
                    if ($binddata !== false) {
                        $this->ajaxReturn(array("status" => 1, "msg" => "绑定成功"));
                    } else {
                        $this->ajaxReturn(array("status" => 0, "msg" => "绑定失败"));
                    }
                    break;
                case 3:
                    $pointtype = new PointTypeModel();
                    $phone = $account;
                    $userp = D('User')->where(array('phone' => $phone))->find();
                    $pointtype->startTrans();
                    $binddata = D('User')->where(array('id' => is_login_user()))->setField('phone', $phone);
                    if ($binddata !== false) {
                        $bindaddpoint = $pointtype->userGetPoint(is_login(),'bind_phone',$phone);
                        if($bindaddpoint['status']==1){
                            $pointtype->commit();
                            $this->ajaxReturn(array("status" => 1, "msg" => "绑定成功",'firstbid'=>$bindaddpoint['point']));
                        }elseif($bindaddpoint==-100){//不是首绑
                            $pointtype->commit();
                            $this->ajaxReturn(array("status" => 1, "msg" => "绑定成功",'firstbid'=>0));
                        }else{
                            $pointtype->rollback();
                            $this->ajaxReturn(array("status" => 0, "msg" => "绑定失败"));
                        }
                    }  else {
                        $pointtype->rollback();
                        $this->ajaxReturn(array("status" => 0, "msg" => "绑定失败"));
                    }
                    break;
                case 4://解绑
                    $phone = $account;
                    $binddata = D('User')->where(array('id' => is_login_user()))->setField('phone', '');
                    if ($binddata !== false) {
                        $this->ajaxReturn(array("status" => 1, "msg" => "解绑成功"));
                    } else {
                        $this->ajaxReturn(array("status" => 0, "msg" => "解绑失败"));
                    }
                    break;
                default:
                    $result = $this->update_paw($account, $password,1);
                    if ($result>0) {
                        $url = U("Subscriber/index");
                        $this->ajaxReturn(array("status" => 1, "msg" => "修改成功", "url" => $url));
                    } else {
                        $msg = $result != -3 ? "手机未绑定" : "修改失败";
                        $this->ajaxReturn(array("status" => 0, "msg" => $msg,));
                    }
                    break;
            }
        } else {
            if(ACTION_NAME=='register'){
                return array('status' => 0, 'msg' => '验证码不正确，请重新输入');
            }
            echo json_encode(array('status' => 0, 'msg' => '验证码不正确，请重新输入'));exit;
        }
    }
    /** * 回调函数 */

    public function callback($type="", $code =""){
        if($_REQUEST['sta']!=''&&$type!='baidu'){
            $re_uri = 'http://'.$_REQUEST['sta'].U('callback',array('type'=>$type,'code'=>$code,'pid'=>$_REQUEST['pid'],'gid'=>$_REQUEST['gid']));
            redirect($re_uri);
        }
        if(empty($type)||empty($code)){

            $this->error('参数错误',U("index"));

        }
        //加载ThinkOauth类并实例化一个对象
        if($type=='baidu'){
            $token = $this->baidulogcall();
        }else{
            $sns  = ThinkOauth::getInstance($type);
        }
        if($type!='weibo'&&$type!='baidu'){
            $token = $sns->getAccessToken($code , $extend);
        }else{
            $token['code'] = $code;
        }
        //获取当前登录用户信息
        if(is_array($token)){
            if ($type=='qq') {
                $user_info = A('Type','Event')->qq($token);
                $regway = 4;
                $data['headpic']=$user_info['headpic'];
                $data['head_icon']=$data['headpic'];
            }elseif($type=='weibo'){
                $user_info = $this->weibocallback();
                $data['headpic'] = $user_info['avatar_hd'];
                $data['head_icon']=$data['headpic'];
                $regway = 6;
            }elseif($type=='baidu'){
                $user_info['openid'] = $token['uid'];
                $user_info['nickname'] = $token['uname'];
                $data['headpic']="http://tb.himg.baidu.com/sys/portrait/item/".$token['portrait'];
                $data['head_icon'] = $data['headpic'];
                $_REQUEST['pid'] = $_COOKIE['bdlog_pid'];
                $_REQUEST['gid'] = $_COOKIE['bdlog_gid'];
                $regway = 5;
            } else {
                //微信 使不用
                $user_info = $sns->getUserInfo($token['access_token'],$token['openid']);
                $user_info = json_decode($user_info,true);
                $regway = 3;
                $data['headpic']=$user_info['headimgurl'];
                $data['head_icon']=$data['headpic'];
            }
            switch ($type) {
                case 'qq':
                    $prefix="QQ_";
                    break;
                case 'weibo':
                    $prefix="WB_";
                    break;
                case 'baidu':
                    $prefix="BD_";
                    break;
                case 'wx':
                    $prefix="WX_";
                    break;
                
            }
            $data['account']  = $prefix.date ( 's' ).random_string(6);
            $data['nickname'] = $user_info['nickname'];
            $data['openid']   = $user_info['openid'];
            $data['promote_id'] = $_REQUEST['pid'];
            $data['promote_account'] = get_promote_name($_REQUEST['pid']);
            $data['parent_id']        = get_fu_id($_REQUEST['pid']);
            $data['third_login_type'] = $regway;
            $data['register_way'] = $regway;
            if(get_game_name($_REQUEST['gid'])){
                $data['fgame_id']  = $_REQUEST['gid'];
                $data['fgame_name']  = get_game_name($_REQUEST['gid']);
            }
            if($_COOKIE['bdlog_for_third']){
                ksort($data);
                $data['bdsign'] = md5(http_build_query($data).C('THINK_SDK_WEIBO.APP_SECRET'));
                $url="http://".$_COOKIE['bdlog_for_third'].U('trdLogin',array_map('urlencode', $data));
                Header("Location: $url"); exit;
            }
            $user = new MemberApi();
            $res = $user->third_login($data);
            if($res){
                session("wechat_token",null);
                session(array('expire' => $token['expires_in']));
                $token['headimgurl'] = $data['headpic'];
                session("wechat_token", $token);
								
								session('nickname',$data['nickname']);
								
								if(session('login_reg')) {
									$login_reg = session('login_reg');session('login_reg',null);
									echo '<script>window.location.href="'.$login_reg.'"</script>';exit;
								}
								
                if($_COOKIE['bdlog_for_third']){
                    if ($_REQUEST['gid']) {
                        $ggurl="http://".$_COOKIE['bdlog_for_third'].U('Game/open_game',array('game_id'=>$_REQUEST['gid']));
                        Header("Location: $ggurl"); exit;
                    }else{
                        $ggurl="http://".$_COOKIE['bdlog_for_third'].U('Index/index');
                        Header("Location: $ggurl"); exit;
                    }
                }else{
                    if ($_REQUEST['gid']) {
                        $ggurl="http://".$_SERVER['HTTP_HOST'].U('Game/open_game',array('game_id'=>$_REQUEST['gid']));
                        Header("Location: $ggurl"); exit;
                    }else{
                        $this->redirect("Subscriber/user");
                    }
                }
            }
            else{

                $data['info']=$up->getError();

                $data['status']=0;
            }
        }
    }

    public function baidulogcall(){
        Vendor("BaiDuSDK.Baidu");
        $appkey = C('THINK_SDK_BAIDU.APP_KEY');
        $appsecret = C('THINK_SDK_BAIDU.APP_SECRET');
        $callback = C('THINK_SDK_BAIDU.CALLBACK');
        $baidu = new \Baidu($appkey, $appsecret, $callback, new \BaiduCookieStore($appkey));
        $user = $baidu->getLoggedInUser();
        if(!$user){
            exit('获取用户信息失败，请稍后重试');
        }else{
            return $user;
        }
    }
    public function weibocallback(){
        //加载ThinkOauth类并实例化一个对象
        $sns  = ThinkOauth::getInstance($_REQUEST['type']);
        $appkey = C('THINK_SDK_WEIBO.APP_KEY');
        $appsecretkey = C('THINK_SDK_WEIBO.APP_SECRET');
        $callback = C('THINK_SDK_WEIBO.CALLBACK');
        $info = $sns->callback($appkey, $appsecretkey, $callback);
        if(empty($info['token'])||empty($info['openid'])){
            exit('获取token失败');
        }
        $user = $sns->get_user_info($info['token'], $info['openid']);
        if(!$user){
            exit('获取用户信息失败');//应用是否审核
        }
        $user_info['openid'] = $info['openid'];
        $user_info['avatar_hd'] = $user['avatar_hd'];
        $user_info['nickname'] = $user['name'];
        return $user_info;
    }


    public function trdLogin(){
        //用于第三方联盟跳转
        //暂时百度使用
        $data= array_map('urldecode', $_REQUEST);
        $bdsign = $data['bdsign'];
        unset($data['bdsign']);
        ksort($data);
        $bdssign = md5(http_build_query($data).C('THINK_SDK_WEIBO.APP_SECRET'));
        if($bdssign!=$bdsign){
            exit('第三方数据错误');
        }
        $user = new MemberApi();
        $res = $user->third_login($data);
        if($res){
            $token['headimgurl'] = $data['headpic'];
            if ($data['fgame_id']) {
                $ggurl="http://".$_SERVER['HTTP_HOST'].U('Game/open_game',array('game_id'=>$data['fgame_id']));
                Header("Location: $ggurl"); exit;
            }else{
                $this->redirect("Index/index");
            }
        }
    }
    public function serverNotice(){
        dump($_REQUEST);exit;
        $this->ajaxReturn(array('status'=>1));
    }
    /**
     * 退出
     */
    public function logout($type=0)
    {
        if($type==1){
            session('user_auth', null);
            session('user_auth_sign', null);
            session("wechat_token", null);
        }session('nickname',null);
        if (is_login()) {
            session('user_auth', null);
            session('user_auth_sign', null);
            session("wechat_token", null);
            $this->ajaxReturn(array('status'=>1));
        } else {
            $this->redirect('user');
        }
    }
    //验证码
    public function verify($vid = '')
    {
        $config = array(
            'seKey' => 'ThinkPHP.CN',   //验证码加密密钥
            'fontSize' => 16,              // 验证码字体大小(px)
            'imageH' => 42,               // 验证码图片高度
            'imageW' => 107,               // 验证码图片宽度
            'length' => 4,               // 验证码位数
            'fontttf' => '4.ttf',              // 验证码字体，不设置随机获取
        );
        ob_clean();
        $verify = new \Think\Verify($config);
        $verify->entry($vid);
    }
    public function qrcode($level = 3, $size = 4, $url = "")
    {
        Vendor('phpqrcode.phpqrcode');
        $errorCorrectionLevel = intval($level);//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        $object = new \QRcode();
        echo $object->png(base64_decode(base64_decode($url)), false, $errorCorrectionLevel, $matrixPointSize, 2);
    }

    /**
     * h5打包app的微信登录和qq登录
     */
    public function app_third_login($openID,$nickName="",$icon="",$logintype){
        switch ($logintype) {
            case 'qq':
                $prefix="QQ_";
                $regway = 4;
                break;
            default :
                $prefix="WX_";
                $regway = 3;
                break;
        }

        $openarr = explode(',',$openID);
        if (!empty($openarr[1])){
            $openID = $openarr[0];
        }else{
            $openID = $openarr[0];
        }

        $data['headpic']  = $icon;
        $data['headimgurl']  = $icon;
        $data['account']  = $prefix.date ( 's' ).random_string(6);
        $data['nickname'] = $nickName;
        $data['openid']   = $openID;
        $data['promote_id'] = $_REQUEST['pid'];
        $data['promote_account'] = get_promote_name($_REQUEST['pid']);
        $data['parent_id']        = get_fu_id($_REQUEST['pid']);
        $data['third_login_type'] = $regway;
        $data['register_way'] = $regway;

        if(get_game_name($_REQUEST['gid'])){
            $data['fgame_id']  = $_REQUEST['gid'];
            $data['fgame_name']  = get_game_name($_REQUEST['gid']);
        }
        if($_COOKIE['bdlog_for_third']){
            ksort($data);
            $data['bdsign'] = md5(http_build_query($data).C('THINK_SDK_WEIBO.APP_SECRET'));
            $url="http://".$_COOKIE['bdlog_for_third'].U('trdLogin',array_map('urlencode', $data));
            Header("Location: $url"); exit;
        }
        $user = new MemberApi();
        $res = $user->third_login($data);
        if($res){
            $token['openid'] = $openID;
            session("wechat_token",null);
            session(array('expire' => $token['expires_in']));
            $token['headimgurl'] = $data['headpic'];
            session("wechat_token", $token);
            if($_COOKIE['bdlog_for_third']){
                if ($_REQUEST['gid']) {
                    $ggurl="http://".$_COOKIE['bdlog_for_third'].U('Game/open_game',array('game_id'=>$_REQUEST['gid']));
                    Header("Location: $ggurl"); exit;
                }else{
                    $ggurl="http://".$_COOKIE['bdlog_for_third'].U('Index/index');
                    Header("Location: $ggurl"); exit;
                }
            }else{
                if ($_REQUEST['gid']) {
                    $ggurl="http://".$_SERVER['HTTP_HOST'].U('Game/open_game',array('game_id'=>$_REQUEST['gid']));
                    Header("Location: $ggurl"); exit;
                }else{
                    $this->redirect("Index/index");
                }
            }
        }else{
            $this->redirect("Index/index");
        }
    }
		
		/**
		 * 金猪支付
		 */
		public function user_recharge_pig() {
			
			$this->assign('data',$_GET);
			$this->assign('money',$_GET['fee']);
			$this->display();
		}
		
		
		
}