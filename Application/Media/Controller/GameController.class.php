<?php
namespace Media\Controller;
use App\Model\CollectionGameModel;
use Think\Controller;
use Common\Model\GameModel;
use Common\Model\GiftbagModel;
use Admin\Model\PointTypeModel;
use Common\Model\DocumentModel;
use Common\Api\GameApi;
use Org\WeiXinSDK\WeiXinOauth;
use Common\Api\PayApi;
use User\Api\MemberApi;
use Org\ThinkSDK\ThinkOauth;
use Org\WeiXinSDK\Weixin;
use Org\UcenterSDK\Ucservice;
use Com\Wechat;
use Org\SwiftpassSDK\Swiftpass;
use Com\WechatAuth;
class GameController extends BaseController {
    public function index($p=1) {

        $this->gameGroup();
        $this->display();
    }
    //游戏分类
    private function gameGroup(){
        $model = new GameModel();
        $data = $model->getGroupLists();
        $this->assign('allgame',$data['all']);
        $this->assign('gamegroup',$data['group']);
        
    }

    //分类游戏列表
    public function gamegrouplist($type_id='',$p=1,$limit=10){
        empty($type_id)&&$this->ajaxReturn(array('code'=>0,'msg'=>'缺少type_id'));
        if($type_id!=-1){
            $map['game_type_id'] = $type_id;
            $typename = get_game_type($type_id);
        }else{
            $typename = '所有类别';
        }
        if(!$typename){
            $this->ajaxReturn(array('code'=>-1,'msg'=>'缺少type_id'));
        }
        $model = new GameModel();
        if(is_cache()&&S('gamegroup_data'.$type_id.$p)){
            $data=S('gamegroup_data'.$type_id.$p);
        }else{
            $data = $model->getGameLists($map,'g.sort desc,g.id desc',$p,$limit);
            if(is_cache()){
                S('gamegroup_data'.$type_id.$p,$data);
            }
        }
        $this->ajaxReturn(array('code'=>1,'type_name'=>$typename,'count'=>count($data),'msg'=>'获取成功','data'=>$data));
    }

    //游戏详情
    public function detail($game_id=0) { 
        empty($game_id)&&$this->set_message(-1,'缺少game_id');
        $model = new GameModel();
        $user_id = session('user_auth.user_id')>0?session('user_auth.user_id'):0;
        $data = $model->gameDetail($game_id,$user_id);
        $this->gameGift($game_id);
        $this->gameActiveDoc($game_id);
        $this->gsULike();
        $this->assign('data',$data);
        $this->display();
    }
    //游戏详情礼包
    private function gameGift($game_id=''){
        empty($game_id)&&$this->ajaxReturn(array('code'=>0,'msg'=>'缺少game_id'));
        $model = new GiftbagModel();
        if(ACTION_NAME=='suspension_gift'){
            $data = $model->getGiftLists($game_id,1,100);
        }else{
            $data = $model->getGiftLists($game_id);
        }
        $user_id = session('user_auth.user_id');
        if($user_id){
            foreach ($data as $key => $value) {
                $map = array();
                $map['game_id'] = $value['game_id'];
                $map['gift_id'] = $value['gift_id'];
                $map['user_id'] = $user_id;
                $isget = M('gift_record','tab_')->where($map)->getField('id');
                if(!empty($isget)){
                    $data[$key]['received'] = 1;
                }else{
                    $data[$key]['received'] = 0;
                }
            }
        }
        if(ACTION_NAME=='suspension_gift'){
            return $data;
        }else{
            $this->assign('gamegift',$data);
        }
    }
    //游戏文章
    private function gameActiveDoc($game_id=''){
        empty($game_id)&&$this->ajaxReturn(array('code'=>0,'msg'=>'缺少game_id'));
        $model = new DocumentModel();
        $data = $model->getArticleListsByCategory($game_id,array('wap_huodong','wap_gg'),1,3);
        $this->assign('gameactive',$data);
    }
    /**
     * 猜你喜欢和大家都在玩（随机取出四条数据）
     * @param  无
     * @return 四条游戏数据
     * @author lyf
     */
    public function gsULike($getnum=4){
        $game_list=get_game_list();
        $count = count($game_list);
        if($count>=$getnum){
            $getnum = $getnum;
        }else{
            $getnum = $count;
        }
        $game_keys=array_rand($game_list,$getnum);
        foreach ($game_keys as $val) {
            $game_like['game_id']=$game_list[$val]['id'];
            $game_like['icon']=icon_url($game_list[$val]['icon']);
            $game_like['game_name']=$game_list[$val]['game_name'];
            $game_like['game_type_id']=$game_list[$val]['game_type_id'];
            $game_like['play_url']='http://' . $_SERVER['HTTP_HOST'] .'/mobile.php/?s=/Game/open_game/game_id/'.$game_list[$val]['id'];
            $game_like['play_detail_url']=U('Game/detail',array('game_id'=>$game_list[$val]['id']));
            $res[]=$game_like;
        }
        if(IS_AJAX){
            return $res;exit;
        }else{
            $this->assign('gamelike',$res);
        }
    }

    //收藏
    public function collection($collect_status=0,$game_id=0) { 
        $user_id = session('user_auth.user_id');
        if(!$user_id){
            $this->ajaxReturn(array('code'=>-1));
        }
        $map['user_id'] = $user_id;
        $map['game_id'] = $game_id;
        $map['status'] = ['in','-1,1'];
        $data = M('user_behavior as ub','tab_')
                ->where($map)
                ->find();
        if($data){
            $save['status'] = $collect_status==0?1:-1;
            $save['id'] = $data['id'];
            $save['update_time'] = time();
            $res = M('user_behavior','tab_')->save($save);
            if($res){
                $result['code'] = 1;
                $result['data'] = $save['status'];
            }else{
                $result['code'] = 0;
            }
        }else{
            $save['user_id'] = $user_id;
            $save['game_id'] = $game_id;
            $save['status'] = $collect_status==0?1:-1;
            $save['update_time'] = time();
            $save['create_time'] = $save['update_time'];
            $res = M('user_behavior','tab_')->add($save);
            if($res){
                $result['code'] = 1;
                $result['data'] = $save['status'];
            }else{
                $result['code'] = 0;
            }
        }
        $this->ajaxReturn($result);
    }
    /**
    *游戏截图
    */
    protected function game_screenshots($screenshots){
        if(!empty($screenshots)){
            $data_img = explode(',', $screenshots);
            $this->assign("gamepic",$data_img);
        }
    }



    /**
    *游戏礼包
    */
    protected function game_gift($game_id=0){
        $gift = M("Giftbag","tab_")->where(["game_id"=>$game_id])->select();
        foreach ($gift as $key => $value) {
            if($gift[$key]['novice']==""){
                unset($gift[$key]);
            }else{
                $return = gift_recorded($game_id,$value['id']);
                $gift[$key]['allcount_novice']=$return['all'];
                $gift[$key]['wnovice']=$return['wei'];
                unset($gift[$key]['novice']);
            }
        }
        $this->assign("giftlist",$gift);
    }

    protected function changetime($ga) {
        if ($ga) {
            $now = date('Y-m-d H:i:s',time());
            foreach($ga as $j => $g) {
                foreach($g as $k => $v) {
                    if ('icon' === $k) {
                        $pic = get_cover($v);
                        $ga[$j]['picurl'] = $pic['path'];
                    }
                    if ('game_type' === $k) {
                        $ga[$j]['game_type_name']=get_game_type($v);
                    }
                    if ('introduction' === $k) {
                        $ga[$j]['introduction'] = strip_tags($v);
                    }
                    if ('recommend_level'=== $k) {
                        $ga[$j]['recommend_level']=floatval($v)*10;
                    }
                    if ('create_time' === $k) {
                        $t1 = date('Y-m-d',$v);
                        $t2 = explode(" ",$now);
                        if ($t1 === $t2[0]) {
                            $c = strtotime($now)-$v;
                            $c = $c/60>1?$c/3600>1?intval($c/3600).'小时前':intval($c/60).'分前':intval($c).'秒前';
                            $ga[$j]['ct']=$c;
                        } else {
                            $ga[$j]['create_time']=date('Y-m-d H:i:s',$v);
                        }
                    }
                }
            }
        } else {
            $ga="";
        }
        return $ga;
    }
    
    public function newsgame($p=1) {
        $game['model']='Game';
        $game['where']=" game_status=1 ";
        $game['order'] =' create_time DESC,id desc ';
        $game['limit']=10;
        $game['page']=$p;
        $gl = parent::getlists($game);
        if($gl['list']&&$gl['total']) {
            $gll = $this->changetime($gl['list']);
            $data = array(
                'data'      =>  $gll,
                'page'      =>  intval($p)+1,
                'total'     =>  $gl['total'],
                'status'    => 1,
            );
        } else
            $data = array(
                'data'      =>  '',
                'page'      =>  $p,
                'status'    => 0,
            );
        $this->ajaxReturn($data,C('DEFAULT_AJAX_RETURN'));  
    }
    public function game_pay($developers=''){
        //解密登录透传数据   登录接口需要加密
        $channelExt=json_decode(simple_decode($_REQUEST['channelExt']),true);
        if($channelExt['ext']!=$_SERVER['HTTP_HOST']){
            if($developers=='egret'){
                $url='http://'.$channelExt.'/mobile.php/Game/game_pay/developers/egret?'.http_build_query($_REQUEST);
            }else{
                $url='http://'.$channelExt.'/mobile.php/Game/game_pay?'.http_build_query($_REQUEST);
            }
            Header("Location: $url"); exit;
        }
        //订单验证
        if($developers=='egret'){
            if($_REQUEST['gameId']!=''){
                $gameset = M('GameSet',"tab_")->where(array('game_pay_appid'=>$_REQUEST['gameId']))->find();
                $gameinfo=M('Game','tab_')->field('game_appid,game_name,id')->where(array('id'=>$gameset['id']))->find();
                $signparam['appId']=$gameset['agent_id'];
                $signparam['egretOrderId']=$_REQUEST['egretOrderId'];
                $signparam['gameId']=$_REQUEST['gameId'];
                $signparam['goodsId']=$_REQUEST['goodsId'];
                $signparam['money']=$_REQUEST['money'];
                $signparam['time']=$_REQUEST['time'];
                $signparam['gameId']=$_REQUEST['gameId'];
                $signparam['userId']=$_REQUEST['userId'];
                ksort($signparam);
                $sortsignpam = "";
                foreach($signparam as $key=>$value){
                    $sortsignpam .= $key ."=". $value;
                }
                $sortsignpam=$sortsignpam.$gameset['game_key'];
                $paysign=md5($sortsignpam);
                if($paysign!=$_REQUEST['sign']){
                    $this->ajaxReturn(array('status'=>-1,'msg'=>'验签失败'));exit;
                }
                if(isset($gameinfo['game_appid'])&&$gameinfo['game_appid']!=''){
                    $map['user_id']=$user_id;
                    $map['game_appid']=$gameinfo['game_appid'];
                }else{
                    $this->ajaxReturn(array('status'=>-2,'msg'=>'游戏game_appid未设置'));exit;
                }
            }else{
                $this->ajaxReturn(array('status'=>-3,'msg'=>'gameId参数错误'));exit;
            }
            $data["game_appid"] = $gameinfo["game_appid"];
            $data["trade_no"]   = $_REQUEST["ext"];
            $data["props_name"] = $_REQUEST["goodsName"];
            $data["amount"]     = $_REQUEST["money"]*100;
            $data["sign"]       = $_REQUEST['sign'];
        }else{
            $gameset = M('GameSet',"tab_")->where(array('game_pay_appid'=>$_REQUEST['game_appid']))->find();
            $gameinfo = M('Game','tab_')->field('game_appid,game_name,id')->where(array('id'=>$gameset['id']))->find();
            $data["amount"]     = $_REQUEST["amount"];
            $data["game_appid"] = $_REQUEST["game_appid"];
            $data["props_name"] = $_REQUEST["props_name"];
            $data["trade_no"]   = $_REQUEST["trade_no"];//cp订单
            $signparam=$data;
            $signparam['channelExt']=$_REQUEST['channelExt'];
            $signparam["user_id"]    = $_REQUEST['user_id'];
            $signparam["sdkloginmodel"]    = $_REQUEST['sdkloginmodel'];
            // $signparam['key']=$gameset['game_key'];
            $paysign = signsortData($signparam,$gameset['game_key']);//拼接渠道appkey
            $data["server_id"]   =   $_REQUEST['server_id'];
            $data["server_name"]   =   $_REQUEST['server_name'];
            $data["role_id"]   =   $_REQUEST['role_id'];
            $data["role_name"]   =   $_REQUEST['role_name'];
            $data["sign"]   =   $_REQUEST['sign'];
            if($paysign!=$data['sign']){
                $this->ajaxReturn(array('status'=>-1,'msg'=>'验签失败'));
            }
        }
        $user_id=is_login();
        if(!$user_id){
            $this->redirect("Subscriber/user");
        }
        $data["user_id"]    = $user_id;
        session('game_pay_sign',$data['sign']);
        $ptb=D('User')
            ->field('balance,IFNULL(bind_balance,0.00) as bind_balance')
            ->join('tab_user_play on tab_user_play.user_id=tab_user.id and tab_user_play.game_id = '.$gameinfo['id'],'left')
            ->where(array('tab_user.id'=>$user_id))
            ->find();
        if(IS_POST){
            switch ($_REQUEST['payway']) {
                case 'alipay':
                    $this->alipay($_REQUEST);
                    break;
                case 'ptb':
                    $this->platform_coin_pay($_REQUEST);
                    break;
                case 'bind_ptb':
                    $this->platform_coin_pay($_REQUEST,$bind_ptb);
                    break;
            }
            exit;
        }else{
            $data['game_name'] = $gameinfo['game_name'];
        }
        $paytype = M('tool', 'tab_')->field('status,name')->where(['name'=>['in','weixin,wei_xin,alipay,jft']])->select();
        foreach ($paytype as $key => $value) {
            $pay[$value['name']] = $value['status'];
        }
        $this->assign('game_url',U('Game/open_game',array('game_id'=>$gameinfo['id'])));
        $this->assign('paytype',$pay);
        $this->assign("data",$data);
        $this->assign("ptb",$ptb);
        $this->display();
    }


    public function warn(){
        if(is_weixin()){
            $this->display();
        }else{
            $http = 'http';
            if(is_https()){
                $http = 'https';
            }
            $url =  U('game_pay_sdk').'&'.http_build_query($_REQUEST);
            redirect($url);
        }
    }


    public function game_pay_sdk($developers = '')
    {
        if (IS_POST) {
            $user_id = is_login();
            $_REQUEST['user_id'] = $user_id;
            if (isset($_REQUEST['game_appid']) && $_REQUEST['game_appid'] != '') {
                $map['user_id'] = $user_id;
                $map['game_appid'] = $_REQUEST['game_appid'];
                $bind_ptb = M('user_play', 'tab_')
                    ->where($map)
                    ->find();
            }

            switch ($_REQUEST['payway']) {
                case 'alipay':
                    $this->alipay($_REQUEST);
                    break;
                case 'goldpig':
                    $pay = A('Pay');
					$pay->gameGoldPigRecharge();
                    break;
                case 'wx':
                    $this->wx_jsapi_pay($_REQUEST);
                    break;
                case 'ptb':
                    $this->platform_coin_pay($_REQUEST);
                    break;
                case 'jft':
                    $this->jftpay($_REQUEST);
                    break;
                case 'bind_ptb':
                    $this->platform_coin_pay($_REQUEST, $bind_ptb);
                    break;
            }
            exit;
        }else{
            switch ($_REQUEST['payway']) {
                case 'alipay':
                    if(is_weixin()){
                        $game_data = get_game_entity($_REQUEST['game_appid']);
                        if(empty($game_data)){
                            redirect(U('index/index'));
                        }else{
                            redirect(U('Game/open_game',array('game_id'=>$game_data['id'])));
                        }
                        exit;
                    }
                    $this->alipay($_REQUEST);
                    break;
            }
        }
    }

    public function jftpay($param=''){
        if (pay_set_status('jft') == 0) {
            $this->error("网站未启用竣付通充值", '', 1);
            exit();
        }

        #判断账号是否存在
        $user = get_user_entity(get_uname(), true);

        if (empty($user)) {
            $this->error("用户不存在");
            exit();
        } else {
            $data2["user_id"] = $user["id"];
            $data2["user_account"] = $user['account'];
            $data2["user_nickname"] = $user['nickname'];
        }
        $game_data = get_game_entity($param['game_appid']);
        if(empty($game_data)){$this->error("游戏不存在");exit();}
        #支付配置
        $data['options']  = 'spend';
        $data['order_no'] = 'SP_' . date('Ymd') . date('His') . sp_random_string(4);
        $data['fee']      = $_POST['amount'];//$_POST['amount'];
        #平台币记录数据
        $data['order_number'] = "";
        $data['pay_order_number'] = $data['order_no'];
        $data['user_id'] = $data2['user_id'];
        $data['user_account'] = $data2['user_account'];
        $data['user_nickname'] = $data2['user_nickname'];
        $data['promote_id'] = $user['promote_id'];
        $data['promote_account'] = $user['promote_account'];
        $data['pay_amount'] = $param['amount'];
        $data['pay_status'] = 0;
        $data['pay_way'] = 6;
        $data['pay_source'] = 1;
        $data['uc'] = $data2['uc'];
        $data["game_id"]       = $game_data['id'];
        $data["game_appid"]    = $param["game_appid"];
        $data["game_name"]     = $game_data["game_name"];
        $data["server_id"]     = $param["server_id"];
        $data["server_name"]   = $param["server_name"];
        $data["game_player_id"]       = $param["role_id"];
        $data["game_player_name"]     = $param["role_name"];
        $pay = new PayApi();
        $pay->add_spend($data);
        $sign=think_encrypt(md5($data['pay_amount'].$data['order_no']));
        redirect(U('pay_way', array('data'=>$data,'type'=>'Alipay','account' =>$data2['user_account'],'pay_amount'=>$data['pay_amount'],'sign'=>$sign,'pay_order_number'=>$data['order_no'])));
        
    }

    //竣付通
    public function pay_way(){
        if(IS_POST){
            $msign=think_encrypt(md5($_POST['pay_amount'].$_POST['pay_order_number']));
            if($msign!==$_POST['sign']){
                $this->error('验证失败',U('Recharge/pay'));exit;
            }
             #判断账号是否存在
             $user = get_user_entity($_POST['account'], true);
             $jtpay=new Jtpay();

             #平台币记录数据            
            $data['out_trade_no'] = $_POST['pay_order_number'];
            $data['user_id'] = $user['id'];
            $data['user_account'] = $user['account'];
            $data['user_nickname'] = $user['nickname'];
            $data['promote_id'] = $user['promote_id'];
            $data['promote_account'] = $user['promote_account'];
            $data['fee'] = $_POST['pay_amount'];
            $data['pay_way'] = 6;//竣付通
            $data['pay_source'] = 1;
            $data["game_id"]       = $_POST['data']['game_id'];
            $data["game_appid"]    = $_POST['data']['game_appid'];
            $data["game_name"]     = $_POST['data']['game_name'];
            switch ($_POST['type']) {
                case 'Alipay':
                 echo $jtpay->jt_pay($_POST['pay_order_number'],$_POST['pay_amount'],$_POST['account'],get_client_ip(),'',4,'',3,2);
                    break;
                case 'wapWeChat':
                 echo $jtpay->jt_pay($_POST['pay_order_number'],$_POST['pay_amount'],$_POST['account'],get_client_ip(),'',3,'',3,2);
                    break;
                case 'WeChat':
                echo $jtpay->jt_pay($_POST['pay_order_number'],$_POST['pay_amount'],$_POST['account'],get_client_ip(),'',3,'',4,2);
                    break;
                case 'QQ':
                 echo $jtpay->jt_pay($_POST['pay_order_number'],$_POST['pay_amount'],$_POST['account'],get_client_ip(),'',11,'',3,2);
                    break;
                default:
                echo $jtpay->jt_pay($_POST['pay_order_number'],$_POST['pay_amount'],$_POST['account'],get_client_ip(),"",$_POST['p9_paymethod']);
                    break;

            }
           
        }else{
            $this->assign('type',$_GET['type']);
            $this->display();
        }
    }

    public function UnionPay(){
        $this->display();
    }



    

    /**

    *支付宝支付

    */

    Public function alipay($param=''){
        $pay = A('Pay');
        $pay->game_alipay($param);
        exit;
        //支付sign验证
        if(($param['sign']!=session('game_pay_sign'))&&$_REQUEST['from']!='wxgzh'){
            $this->error("非法操作！");exit();
        }
        #判断账号是否存在
        $userid = $_REQUEST['from']!='wxgzh'?is_login():$_REQUEST['user_id'];
        $user = get_user_entity($userid);
        if (empty($user)) {
            $this->error("用户不存在");exit();
        }else{
            $data["user_id"]       = $user["id"];
            $data["user_account"]  = $user['account'];
            $data["user_nickname"] = $user['nickname'];
        }
        $game_data = get_game_entity($param['game_appid']);
        if(empty($game_data)){$this->error("游戏不存在");exit();}
        #支付配置
        $data['options']  = 'spend';
        $data['order_no'] = 'SP_'.date('Ymd').date ( 'His' ).sp_random_string(4);
        $data['fee']      = $param['price']/100;
        $data['pay_type'] = 'alipay'; 
        #平台币记录数据
        $data["game_id"]       = $game_data['id'];
        $data["game_appid"]    = $param["game_appid"];
        $data["game_name"]     = $game_data["game_name"];

        $data["server_id"]     = $param["server_id"];
        $data["server_name"]   = $param["server_name"];
        $data["game_player_id"]     = $param["role_id"];
        $data["game_player_name"]   = $param["role_name"];

        $data["promote_id"]    = $user['promote_id'];
        $data["promote_account"] = $user['promote_account'];
        $data["pay_order_number"]= $data['order_no'];
        $data["title"] = $param['props_name'];
        $data["pay_amount"]   = $param['price']/100;
        $data["pay_way"] = 1;
        $data['code']=1;
        $data['extend']  = $param['trade_no'];
        $data['server']   = 'alipay.wap.create.direct.pay.by.user';
        $data['method']   = 'wap';
        $pay = new PayApi();
        $url = $pay->other_pay($data,C('alipay'),$data["game_id"]);
        redirect($url);exit;
    }

    public function get_wx_code(){
        $jieminame=get_uname();
        if($_GET['state']!=''&&isset($_GET['code'])){
            $stateparam=explode(',',$_GET['state']);//微信支付参数
            $jieminame=simple_decode($stateparam[2]);
        }
        $user = get_user_entity($jieminame,true);
        if (empty($user)) {
            $this->ajaxReturn(array('msg'=>'用户不存在！','status'=>0));exit();
        }else{
            $data["user_id"]       = $user["id"];
            $data["user_account"]  = $user['account'];
            $data["user_nickname"] = $user['nickname'];
        }
        $game_data = get_game_entity($_POST['game_appid']);
        if(empty($game_data)){$this->ajaxReturn(array('msg'=>'游戏不存在！','status'=>0));exit();}
        Vendor("WxPayPubHelper.WxPayPubHelper");
        // 使用jsapi接口
        $jsApi = new \JsApi_pub(C('wei_xin.appsecret'),C('wei_xin.email'),C('wei_xin.key'));
        //=========步骤1：网页授权获取用户openid============
        //通过code获得openid
        if (!isset($_GET['code']))
        {
            if($_POST['sign']!=session('game_pay_sign')){
                $this->ajaxReturn(array('msg'=>'数据异常！','status'=>0));
            }
            #支付配置
            $data['order_no'] = 'SP_'.date('Ymd').date ( 'His' ).sp_random_string(4);
            $data["game_id"]       = $game_data['id'];
            $data["game_appid"]    = $_POST["game_appid"];
            $data["game_name"]     = $game_data["game_name"];
            $data["server_id"]     = $_POST["server_id"];
            $data["server_name"]   = $_POST["server_name"];
            $data["game_player_id"]     = $_POST["role_id"];
            $data["game_player_name"]   = $_POST["role_name"];
            $data["promote_id"]    = $user['promote_id'];
            $data["promote_account"] = $user['promote_account'];
            $data["pay_order_number"]= $data['order_no'];
            $data["title"] = $_POST['props_name'];
            $data["pay_amount"]   =  $_POST['amount']/100;
            $data["pay_way"] = 2;
            $data['extend']  = $_POST['trade_no'];
            $pay = new PayApi();
            $pay->add_spend($data);
            $openid = session("wechat_token.openid");
            $state = $data["pay_amount"] . ',' . $data['order_no'] . ',' . simple_encode(get_uname()) . ',' . $data["game_id"] . ',' . $_SERVER['HTTP_HOST'].','.$openid;//指针0金额，1订单，2用户名，3游戏id,4发起支付的域名,5openid
            //触发微信返回code码
            if(session('for_third')==C(PC_SET_DOMAIM)){
                $code_url="http://".session('for_third').'/mobile.php/Subscriber/get_wx_code/';
                $url = $jsApi->createOauthUrlForCode($code_url, $state);
                $this->ajaxReturn(array('url' => $url, 'status' => -100));
            }elseif($_POST['referer_pay']==1){
                $code_url="http://".C(PC_SET_DOMAIM).'/mobile.php/Subscriber/get_wx_code/';
                $url = $jsApi->createOauthUrlForCode($code_url, $state);
                $this->ajaxReturn(array('url' => $url, 'status' => -100));
            }else{
                $code_url="http://".$_SERVER['HTTP_HOST'].'/mobile.php/Subscriber/get_wx_code/';
            }
            $weixn=new Weixin();
            $state1=explode(',',$state);
            $amount=$state1[0];
            $out_trade_no=$state1[1];
            $game_id=$state1[2];
            $spendinfo=M('Spend','tab_')->where(array('pay_order_number'=>$out_trade_no))->find();
            $is_pay=$weixn->weixin_jsapi("游戏充值",$out_trade_no,$amount,$jsApi,$openid);
            session('is_pay',$is_pay);
            exit($is_pay);
        }
    }

    /**

    *微信支付

    */

    public function wx_pay(){

        #判断账号是否存在

        if($_REQUEST['sign']!=session('game_pay_sign')){

            $this->ajaxReturn(array("status"=>0,"msg"=>'数据异常！'));exit;

        }

        $user = get_user_entity(get_uname(),true);

        #判断账号是否存在
        if (empty($user)) {

            $this->error("用户不存在");exit();

        }else{

            $data["user_id"]       = $user["id"];

            $data["user_account"]  = $user['account'];

            $data["user_nickname"] = $user['nickname'];

        }
        $game_data = get_game_entity($_POST["game_appid"]);

        if(empty($game_data)){$this->error("游戏不存在");exit();}

        #支付配置

        $data['options']  = 'spend';

        $data['order_no'] = 'SP_'.date('Ymd').date ( 'His' ).sp_random_string(4);

        $data['fee']      = $_POST['amount']/100;

        $data['pay_type'] = 'swiftpass'; 

        $data['server'] = "pay.weixin.native";

        #平台币记录数据

        $data["game_id"]       = $game_data['id'];

        $data["game_appid"]    = $_POST["game_appid"];

        $data["game_name"]     = $game_data["game_name"];

        $data["server_id"]     = $_POST["server_id"];
        $data["server_name"]   = $_POST["server_name"];
        $data["game_player_id"]     = $_POST["role_id"];
        $data["game_player_name"]   = $_POST["role_name"];

        $data["promote_id"]    = $user['promote_id'];

        $data["promote_account"] = $user['promote_account'];

        $data["pay_order_number"]= $data['order_no'];

        $data["title"] = $_POST['props_name'];

        $data["pay_amount"]   = $_POST['amount']/100;

        $data["pay_way"] = 2;

        $data['extend']  = $_POST['trade_no'];

        $data['code']  = 1;

        $pay = new PayApi();

        if(get_wx_type()==0){//0官方

            $weixn=new Weixin();

            $is_pay=json_decode($weixn->weixin_pay($data["title"],$data['order_no'],$data['fee'],'NATIVE'),true);

            if($is_pay['status']===1){

                            $html ='<div class="d_body" style="height:px;">

                    <div class="d_content">

                        <div class="text_center">

                            <table class="list" width="100%">

                                <tbody>

                                <tr>

                                    <td class="text_right">订单号</td>

                                    <td class="text_left">'.$data['order_no'].'</td>

                                </tr>

                                <tr>

                                    <td class="text_right">充值金额</td>

                                    <td class="text_left">本次充值'.$data['fee'].'元，实际付款'.$data['fee'].'元</td>

                                </tr>

                                </tbody>

                            </table>

                            <img src="'.U('Subscriber/qrcode',array('level'=>3,'size'=>4,'url'=>base64_encode(base64_encode($is_pay['url'])))).'" height="301" width="301">

                            <br/>&nbsp;&nbsp;&nbsp;&nbsp;

                            <img src="/Public/Media/images/wx_pay_tips.png">

                        </div>              </div>

                </div>';

                $pay->add_spend($data);     

                $this->ajaxReturn(array("status"=>1,"out_trade_no"=>$data['order_no'],"html"=>$html,"game_id"=>$data["game_id"]));

            }else{

                $this->ajaxReturn(array("status"=>0,"msg"=>'支付数据错误！'));

            }

        }else{

            $data['pay_way']=4;

            $arr = $pay->other_pay($data,C('weixin'));

            if($arr['status1'] === 500){

                \Think\Log::record($arr['msg']);

                $html ='<div class="d_body" style="height:px;">

                        <div class="d_content">

                            <div class="text_center">'.$arr["msg"].'</div>

                        </div>

                        </div>';

                $json_data = array("status"=>1,"html"=>$html);

            }else{

                $html ='<div class="d_body" style="height:px;">

                        <div class="d_content">

                            <div class="text_center">

                                <table class="list" width="100%">

                                    <tbody>

                                    <tr>

                                        <td class="text_right">订单号</td>

                                        <td class="text_left">'.$arr["out_trade_no"].'</td>

                                    </tr>

                                    <tr>

                                        <td class="text_right">充值金额</td>

                                        <td class="text_left">本次充值'.$data["pay_amount"].'元，实际付款'.$data["pay_amount"].'元</td>

                                    </tr>

                                    </tbody>

                                </table>

                                <img src="'.$arr["code_img_url"].'" height="301" width="301">

                                <br/>&nbsp;&nbsp;&nbsp;&nbsp;

                                <img src="/Public/Media/images/wx_pay_tips.png">

                            </div>

                        </div>

                    </div>';

                $json_data = array("status"=>1,"out_trade_no"=>$arr["out_trade_no"],"html"=>$html,"game_id"=>$data["game_id"]);

            }

            $this->ajaxReturn($json_data);

        }

    }

    /**

    *微信wap支付

    */

    public function wx_wap_pay(){

        $user = get_user_entity(get_uname(),true);
        if (empty($user)) {

            $this->error("用户不存在");exit();

        }else{

            $data["user_id"]       = $user["id"];

            $data["user_account"]  = $user['account'];

            $data["user_nickname"] = $user['nickname'];

        }
        $game_data = get_game_entity($_POST['game_appid']);

        #支付配置

        $data['code']     = 1;

        $data['fee']    = $_POST['amount']/100;

        $data['body']     = '游戏充值';

        $data['pay_type']  = 'swiftpass';

        $data['config']   = 'weixin';

        $data['method']   = 'direct';

        $data['server']   = 'pay.weixin.wappay';

        $data['signtype'] = 'MD5';

        #平台币记录数据

        $data['pay_status']       = 0;

        $data['payway']           = 2;

        $data['pay_source']       = 1;

        $data['options']  = 'spend';

        $data['order_no'] = 'SP_'.date('Ymd').date ( 'His' ).sp_random_string(4);

        #平台币记录数据

        $data["game_id"]       = $game_data['id'];

        $data["game_appid"]    = $_POST["game_appid"];

        $data["game_name"]     = $game_data["game_name"];

        $data["server_id"]     = $_POST["server_id"];
        $data["server_name"]   = $_POST["server_name"];
        $data["game_player_id"]     = $_POST["role_id"];
        $data["game_player_name"]   = $_POST["role_name"];

        $data["promote_id"]    = $user['promote_id'];

        $data["promote_account"] = $user['promote_account'];

        $data["pay_order_number"]= $data['order_no'];

        $data["title"] = $_POST['props_name'];

        $data["pay_amount"]   = $_POST['amount']/100;

        $data["pay_way"] = 4;

        $data['extend']  = $_POST['trade_no'];

        $pay = new PayApi();

        $arr = $pay->other_pay($data,C('weixin'));

        if($arr['status1'] === 500){

            \Think\Log::record($arr['msg']);

            $html ='<div class="d_body" style="height:px;">

                    <div class="d_content">

                        <div class="text_center">'.$arr["msg"].'</div>

                    </div>

                    </div>';

            $json_data = array("status"=>0,"html"=>$html);

        }else{

            $json_data = array("status"=>1,"pay_info"=>$arr['pay_info']);

        }

        $this->ajaxReturn($json_data);

    }

    public function weixin_wap_pay(){
        $user = get_user_entity(is_login());
        $game_data = get_game_entity($_POST['game_appid']);
        if(empty($user)){$this->error("用户不存在");exit();}
        if(pay_set_status('wei_xin')==0 && pay_set_status('weixin')==0){
            $this->error("网站未启用微信充值",'',1);
            exit();
        }
        if($_POST['amount']<0){$this->error('充值金额有误');exit();}
        $data['out_trade_no'] = 'SP_' . date('Ymd') . date('His') . sp_random_string(4);
        //支付参数
        $Swiftpass=new Swiftpass(C('weixin.partner'),C('weixin.key'));
        $param['service'] = "pay.weixin.wappay";
        $param['ip'] = get_client_ip();
        $param['pay_amount'] = $_POST['amount']/100;
        $param['game_name'] = $game_data['game_name'];
        $param['game_id'] = $game_data['id'];
        $param['out_trade_no']= $data['out_trade_no'];
        $param['body']="游戏充值";
        //spend表
        $user['order_no'] = $data['out_trade_no'];
        $user['game_id'] = $game_data['id'];
        $user['game_appid'] = $_POST['game_appid'];
        $user['game_name'] = $game_data['game_name'];
        $user['title'] = $_POST['props_name'];
        $user['pay_amount'] = $_POST['amount']/100;
        $user['pay_way'] = 2;
        $user['extend'] = $_POST['trade_no'];

        $user["server_id"]     = $_POST["server_id"];
        $user["server_name"]   = $_POST["server_name"];
        $user["game_player_id"]     = $_POST["role_id"];
        $user["game_player_name"]   = $_POST["role_name"];
        //0 官方 1威富通
        if (get_wx_type() == 0) {
            $weixn = new Weixin();
            $is_pay = json_decode($weixn->weixin_pay($param['body'], $param['out_trade_no'], $param['pay_amount'], 'MWEB'), true);
            if($is_pay['status']==1){
                $this->add_spend($user);
                $json_data = array('status' => 1, 'pay_info' => $is_pay['mweb_url']);
            }else{
                $json_data = array('status' => -1, 'info'=>$is_pay['return_msg']);
            }
        }else{
            $url=$Swiftpass->submitOrderInfo($param);
            if($url['status']==000){
                $this->add_spend($user);
                $json_data = array('status'=>1,'pay_info'=>$url['pay_info']);
            }else{
                $json_data = array('status'=>-1,'info'=>$url['msg']);
            }
        }
        $this->ajaxReturn($json_data);

    }


    /**

    *微信 JSAPI 支付

    */

    public function wx_jsapi_pay($param = array()){
        $user = get_user_entity(get_uname(),true);
        if (empty($user)) {
            $this->error("用户不存在");exit();
        }else{
            $data["user_id"]       = $user["id"];
            $data["user_account"]  = $user['account'];
            $data["user_nickname"] = $user['nickname'];
        }
        $game_data = get_game_entity($param['game_appid']);
        if(get_wx_type()==0){//0官方
        Vendor("WxPayPubHelper.WxPayPubHelper");
            //使用jsapi接口
            $jsApi = new \JsApi_pub('c05a88199e9ef60d96a15b241ce7c1cd',C('wei_xin.email'));
            //=========步骤1：网页授权获取用户openid============
            //通过code获得openid
            if (!isset($_GET['code']))
            {
                //触发微信返回code码
                $url = $jsApi->createOauthUrlForCode("http://www.1n.cn/mobile.php/Subscriber/wx_jsapi_pay/account/$account/amount/$amount");
                // $url = $jsApi->createOauthUrlForCode("http://www.1n.cn/mobile.php/Subscriber/pay");
                Header("Location: $url");
            }else
            {
                //获取code码，以获取openid
                $code = $_GET['code'];
                $jsApi->setCode($code);
                $openid = $jsApi->getOpenId();
            }
         $weixn=new Weixin();
         $datt['out_trade_no']  = "PF_".date('Ymd').date('His').sp_random_string(4);
         $datt['amount']  = $_POST['amount'];
         $datt['pay_status']       = 0;
         $datt['pay_way']           = 2;
         $datt['pay_source']       = 1;
         $is_pay=$weixn->weixin_jsapi($_POST['props_name'],$datt['out_trade_no'], $datt['amount'],$jsApi,$openid);
         $this->assign('jsApiParameters',$is_pay);
        }else{
            $data['code']     = 1;
            $data['fee']    = $_POST['amount']/100;
            $data['body']     = '游戏充值';
            $data['pay_type']  = 'swiftpass';
            $data['config']   = 'weixin';
            $data['method']   = 'direct';
            $data['server']   = 'pay.weixin.jspay';
            $data['openid'] = session("wechat_token.openid");
            $data['signtype'] = 'MD5';
            #平台币记录数据
            $data['pay_status']       = 0;
            $data['payway']           = 2;
            $data['pay_source']       = 1;
            $data['options']  = 'spend';
            $data['order_no'] = 'SP_'.date('Ymd').date ( 'His' ).sp_random_string(4);
            #平台币记录数据
            $data["game_id"]       = $game_data['id'];
            $data["game_appid"]    = $_POST["game_appid"];
            $data["game_name"]     = $game_data["game_name"];

            $data["server_id"]     = $_POST["server_id"];
            $data["server_name"]   = $_POST["server_name"];
            $data["game_player_id"]     = $_POST["role_id"];
            $data["game_player_name"]   = $_POST["role_name"];

            $data["promote_id"]    = $user['promote_id'];
            $data["promote_account"] = $user['promote_account'];
            $data["pay_order_number"]= $data['order_no'];
            $data["title"] = $_POST['props_name'];
            $data["pay_amount"]   = $_POST['amount']/100;
            $data["pay_way"] = 4;
            $data['extend']  = $_POST['trade_no'];
            $data['callbackurl']      = "http://".$_SERVER['HTTP_HOST'].U('open_game',array('game_id'=>$game_data['id']));
            $pay = new PayApi();
            $arr = $pay->other_pay($data,C('weixin'));
            if($arr['status'] != 500){
                redirect("https://pay.swiftpass.cn/pay/jspay?token_id={$arr['token_id']}&showwxtitle=1"); 
            }
        }
    }

    private function platform_coin_pay($param,$bind_ptb){
        if($param['sign']!=session('game_pay_sign')){
            $this->ajaxReturn(array('status'=>0,'msg'=>"非法操作！"));
        }
        #判断账号是否存在
        if (empty($param['user_id'])){
            $param['user_id'] = $param['userId'];
        }
        $user = get_user_entity($param['user_id'],false);
        if(!$user){
            $this->ajaxReturn(array('status'=>0,'msg'=>'用户不存在'));
        }
        if($bind_ptb!=null||$bind_ptb!=''){
            $money=$bind_ptb['bind_balance'];
        }else{
            $money=$user['balance'];
        }
        if($money < $param['amount']/100){
            $this->ajaxReturn(array('status'=>0,'code'=>-1,'msg'=>'余额不足'));
        }
        $code=1;
        $game_data = get_game_entity($param['game_appid']);
        if(!$game_data){
            $this->ajaxReturn(array('status'=>0,'msg'=>'游戏参数错误'));
        }
        $data_spned['user_id']          = $param["user_id"];
        $data_spned['user_account']     = $user["account"];
        $data_spned['user_nickname']    = $user["nickname"];
        $data_spned['game_id']          = $game_data["id"];
        $data_spned['game_appid']       = $game_data["game_appid"];
        $data_spned['game_name']        = $game_data["game_name"];
        $data_spned["server_id"]     = $param["server_id"];
        $data_spned["server_name"]   = $param["server_name"];
        $data_spned["game_player_id"]     = $param["role_id"];
        $data_spned["game_player_name"]   = $param["role_name"];
        $data_spned['promote_id']       = $user["promote_id"];
        $data_spned['promote_account']  = $user["promote_account"];
        $data_spned['order_number']     = $param["order_number"];
        $data_spned['pay_order_number'] = 'SP_'.date('Ymd').date ( 'His' ).sp_random_string(4);
        $data_spned['props_name']       = $param["props_name"];
        $data_spned['pay_amount']       = $param["amount"]/100;
        $data_spned['pay_time']         = NOW_TIME;
        $data_spned['pay_status']       = 1;
        $data_spned['pay_game_status']  = 0;
        $data_spned['extend']           = $param['trade_no'];
        $data_spned['pay_way']          = 0;
        $data_spned['spend_ip']         = get_client_ip();
        $shiwu = M();
        if($param['payway']=='ptb'){
            $model1=M("user","tab_");
            $shiwu->startTrans();
            $model2=M("spend","tab_");
            $koukuan=$model1->where(["id"=>$param['user_id']])->setDec("balance",$param['amount']/100);
            $balance = $model1->field('balance')->find($param['user_id']);
            if($koukuan&&$balance['balance']>=0){
                $shiwu->commit();
                $data_spned['pay_status']       = 1;
            }else{
                $shiwu->rollback();
                $this->ajaxReturn(array('status'=>0,'code'=>-1,'msg'=>'扣款失败，请重新支付'));
            }
            $spend_id = $model2->add($data_spned);
            $pay = new PayApi();
            $pay->set_ratio($data_spned['pay_order_number']);
            $pay->buyshoppoint($data_spned);//分发积分
        }else if($param['payway']=='bind_ptb'){
            // $code=2;//5.0版本绑币消费记录到spend表
            $data_spned['pay_way']          = -1;
            $model1=M("user_play","tab_");
            $shiwu->startTrans();
            $model2=M("spend","tab_");
            $map['user_id']=$param['user_id'];
            $map['game_appid']=$param['game_appid'];
            $koukuan=$model1->where($map)->setDec("bind_balance",$param['amount']/100);
            $bind_balance = $model1->field('bind_balance')->where($map)->find();
            if($koukuan&&$bind_balance['bind_balance']>=0){
                $shiwu->commit();
                $data_spned['pay_status']       = 1;
            }else{
                $shiwu->rollback();
                $this->ajaxReturn(array('status'=>0,'code'=>-1,'msg'=>'扣款失败，请重新支付'));
            }
            $spend_id = $model2->add($data_spned);
            $pay = new PayApi();
            $pay->set_ratio($data_spned['pay_order_number'],1);
        }
        $res =json_decode($this->pay_game_notify($data_spned,$code),true);
        if($res['status']=='success'||$res['msg']=='success'||$res['code']=='1009'){
            $model2->where(["id"=>$spend_id])->setField("pay_game_status",1);
            $this->record_logs($data_spned['pay_order_number'].'共通知cp 1次，已成功','INFO');
        }else{
            $this->record_logs($data_spned['pay_order_number'].'共通知cp 1次，失败','INFO');
        }
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/mobile.php?s=/Game/game_pay_callback/order_number/'.$data_spned['pay_order_number'].'/game_id/'.$game_data["id"];
        $this->ajaxReturn(array('status'=>1,'msg'=>'支付成功','url'=>$url));
    }
    /**
    *日志记录
    */
    protected function record_logs($msg="",$type='ERR'){
        \Think\Log::record($msg,$type);
    }





    protected function pay_game_notify($data,$code){

        $game = new GameApi();

        $result = $game->game_pay_notify($data,$code);

        return $result;

    }



    protected function bind_spend_param($data_pt){

        $user_entity = get_user_entity($param['user_id']);

        $data_bind_spned['user_id']          = $param["user_id"];

        $data_bind_spned['user_account']     = $user_entity["account"];

        $data_bind_spned['user_nickname']    = $user_entity["nickname"];

        $data_bind_spned['game_id']          = $param["game_id"];

        $data_bind_spned['game_appid']       = $param["game_appid"];

        $data_bind_spned['game_name']        = $param["game_name"];

        $data_bind_spned["server_id"]     = $param["server_id"];
        $data_bind_spned["server_name"]   = $param["server_name"];
        $data_bind_spned["game_player_id"]     = $param["role_id"];
        $data_bind_spned["game_player_name"]   = $param["role_name"];

        $data_bind_spned['promote_id']       = $user_entity["promote_id"];

        $data_bind_spned['promote_account']  = $user_entity["promote_account"];

        $data_bind_spned['order_number']     = $param["order_number"];

        $data_bind_spned['pay_order_number'] = $param["pay_order_number"];

        $data_bind_spned['props_name']       = $param["title"];

        $data_bind_spned['pay_amount']       = $param["price"];

        $data_bind_spned['pay_time']         = NOW_TIME;

        $data_bind_spned['pay_status']       = $param["pay_status"];

        $data_bind_spned['pay_game_status']  = 0;

        $data_bind_spned['pay_way']          = 1;

        $data_bind_spned['spend_ip']         = $param["spend_ip"];

        return $data_bind_spned;

    }

    public function checkstatus(){

        // sleep(3);

        substr($_REQUEST['out_trade_no'],0,2)=="PF"?$model='deposit':$model='spend';

        $data=M($model,'tab_')->where(array('pay_order_number'=>$_REQUEST['out_trade_no']))->find();

        $config = M('config','sys_')->field('value')->where(array('name'=>'UC_SET'))->find();

        if($data['pay_status']==1){

            $url=U('Game/open_game',array('game_id'=>$data['game_id']));

            $this->ajaxReturn(array("status"=>1,'url'=>$url));

        }else{

            $this->ajaxReturn(array("status"=>0));

        }

    }

    /**

    *游戏返利

    */

    protected function set_ratio($data){
        $map['pay_order_number']=$data;
        $spend=M("Spend","tab_")->where($map)->find();
        $reb_map['game_id']=$spend['game_id'];
        $rebate=M("Rebate","tab_")->where($reb_map)->find();
        if($rebate['ratio']==0||null==$rebate){
            return false;
        }else{
            if($rebate['money']>0&&$rebate['status']==1){
                if($spend['pay_amount']>=$rebate['money']){
                    $this->compute($spend,$rebate);
                }else{
                    return false;
                }
            }else{
                $this->compute($spend,$rebate);
            }
        }
    }

    //计算返利

    protected function compute($spend,$rebate){
        $user_map['user_id']=$spend['user_id'];
        $user_map['game_id']=$spend['game_id'];            
        $bind_balance=$spend['pay_amount']*($rebate['ratio']/100);
        $spend['ratio']=$rebate['ratio'];
        $spend['ratio_amount']=$bind_balance;
        M("rebate_list","tab_")->add($this->add_rebate_list($spend));
        $re=M("UserPlay","tab_")->where($user_map)->setInc("bind_balance",$bind_balance);
        return $re;
    }

    /**

    *返利记录

    */

    protected function add_rebate_list($data){
        $add['pay_order_number']=$data['pay_order_number'];
        $add['game_id']=$data['game_id'];
        $add['game_name']=$data['game_name'];
        $add['user_id']=$data['user_id'];
        $add['pay_amount']=$data['pay_amount'];
        $add['ratio']=$data['ratio'];
        $add['ratio_amount']=$data['ratio_amount'];
        $add['promote_id']=$data['promote_id'];
        $add['promote_name']=$data['promote_account'];
        $add['create_time']=time();
        return $add;
    }

    //打开游戏

    public function open_game()
    {
        $gmset = M('game_set','tab_')->field('support_sdk,login_notify_url,third_party_url')->where(array('id'=>$_GET['game_id']))->find();
        if(!empty($gmset['third_party_url'])){
            redirect($gmset['third_party_url']);
        }

        if(I('token')){
            $this->app_write_session(I('token'));//app使用aa
        }

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
        if(!$_GET['game_id']){
            $this->ajaxReturn(array('code'=>0,'data'=>'缺少游戏参数'));
        }

        $type = M('game','tab_')->where(array('id'=>$_GET['game_id']))->find();
				
				
        $this->assign('game_name',$type['game_name']);
        $this->assign('game_id',$_GET['game_id']);
        //游戏报错页面
        if(empty($type)){
            redirect(U('Game/user_game_error'));exit;
        }elseif($type['game_status'] != 1 && $type['test_status'] != 0){
            redirect(U('Game/user_game_error'));exit;
        }

        $game_load_page ='http://'.$_SERVER ['HTTP_HOST'].get_cover($type['game_load_page'],'path');
        if(!getimagesize($game_load_page)){
            $game_load_page = false; 
        }
        
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
        $pid =$_GET['pid']==""?0:$_GET['pid'];
        $GameApi = new GameApi();
        $uid = is_login();
        $yk = M('User','tab_')->field('lock_status,register_way,phone')->find(is_login());
        $this->assign('promote_id',$pid);
        if($uid>0&&!session('stop_play')&&$yk['lock_status']==1){
            $game_id = $_GET['game_id'];
            $login_url = $GameApi->game_login($uid,$game_id,$pid);
            $this->assign("login_url",$login_url);
        }elseif($uid>0&&$yk['lock_status']!=1){
            redirect(U('Index/index'));//禁止锁定用户登录
        }else{
            $this->assign("game_load_page",$game_load_page);
            // $this->assign("login_url",U('Index/index'));
        }
        $this->assign('yk',$yk);
				
				$this->assign('prev_url',$_SERVER['HTTP_REFERER']);
				
				if ($type['screen_type'] == 1) {
				$this->display('open_game_landscape');	
				} else {
        $this->display('open_game');					
				}
    }
    

    public function suspension_gift($game_id){
        $gamemodel = new GameModel();
        $detail = $gamemodel->gameDetail($game_id,is_login());
        $this->assign('detail',$detail);
        $giftmodel = new GiftbagModel();
        $detail = $this->gameGift($game_id);
        $rec_status = $_POST['rec_status']?false:$_POST['rec_status'];
        $other = $giftmodel->getGameGiftLists($rec_status,is_login(),array('g.id'=>array('neq',$game_id)));
        if(empty($detail)){
            $gift['detail']['status'] = 0;
        }else{
            $gift['detail']['status'] = 1;
            $gift['detail']['data'] = $detail;
        }
        if(empty($other)){
            $gift['other']['status'] = 0;
        }else{
            $gift['other']['status'] = 1;
            $gift['other']['data'] = $other;
        }
        $this->ajaxReturn(array('status'=>1,'data'=>$gift));
    }

    public function suspension_leave(){
        $map['game_id'] = $_REQUEST['game_id'];
        $map['user_id'] = is_login();
        $map['status'] = array('in','-1,1');
        $collection=M('user_behavior','tab_')->where($map)->find();
        $data['qrcode'] = get_cover(C('PC_SET_QRCODE'),'path');
        $data['like'] = $this->gsULike(3);
        $data['collection'] = $collection['status']?$collection['status']:-1;
        $this->ajaxReturn(array('status'=>1,'data'=>$data));
    }


    private function sharefun(){

        $result = auto_get_access_token(dirname(__FILE__) . '/access_token_validity.txt');

        $appid = C('wechat.appid');

        $appsecret = C('wechat.appsecret');

        if (!$result['is_validity']) {

            $auth = new WechatAuth($appid, $appsecret);

            $token = $auth->getAccessToken();

            $token['expires_in_validity'] = time() + $token['expires_in'];

            wite_text(json_encode($token), dirname(__FILE__) . '/access_token_validity.txt');

            $result = auto_get_access_token(dirname(__FILE__) . '/access_token_validity.txt');

        }

        $auth = new WechatAuth($appid, $appsecret,$result['access_token']);

        $ticket = auto_get_ticket(dirname(__FILE__) . '/ticket.txt');

        if(!$ticket['is_validity']){

            $jsapiTicketjson = $auth->getticket();

            $jsapiTicketarr = json_decode($jsapiTicketjson,true);

            if($jsapiTicketarr['errcode']!=0){

                $this->sharefun();exit;

            }

            $jsapiTicketarr['expires_in_validity'] = time() + $jsapiTicketarr['expires_in'];

            wite_text(json_encode($jsapiTicketarr), dirname(__FILE__) . '/ticket.txt');

            $ticket = auto_get_ticket(dirname(__FILE__) . '/ticket.txt');

        }

        $signPackage = $auth->getSignPackage($ticket['ticket']);

        return $signPackage;

    }
    //游戏登陆数据sdk验证   不适用白鹭

    public function sdkloginVerify(){

        $data = $_POST['data'];

        $data = json_decode($data,true);

        $map['game_appid']=$data['game_appid'];

        $gamedata=M('game','tab_')->where($map)->find();

        $gamesetdata=M('game_set','tab_')->where(array('game_id'=>$gamedata['id']))->find();

        $uid=session("user_auth.user_id");

        //如果已登录(暂时不用，登录完进游戏)

        if($uid){

            //登陆账号和验签账号不一致 

            if($uid!=$data['user_id']){

                D('User')->logout();

                session("wechat_token",null);

                $this->ajaxReturn(array('status'=>-1,'msg'=>'用户信息获取失败，请重新登录','data'=>''));//用户信息不一致

            }

        }else{

            //用户没登陆处理

            

        }

        $dataverify = $data;

        unset($dataverify['sign']);

        ksort($dataverify);//字典排序

        $dataverify['sign']       = MD5(http_build_query($dataverify).$gamesetdata['game_key']);



        if($dataverify['sign']!=$data['sign']){

            $this->ajaxReturn(array('status'=>0,'msg'=>'登录验签失败','data'=>''));//验签失败

        }else{

            $this->ajaxReturn(array('status'=>1,'msg'=>'登录验签成功','data'=>''));//验签成功

        }

    }

    //H5sdk login结束

    



    //H5sdk pay  start

    public function paysdk(){

            $payver = $this->sdkpayVerify($_REQUEST);

            $game=D('Game')->where(array('game_appid'=>$_REQUEST['game_appid']))->find();

            if($payver['status']!=1){

                $this->ajaxReturn($payver);

            }else{

                session('game_pay_sign',$_REQUEST['sign']);

                $this->assign('data',$_REQUEST);

            }

            $userdata = M('user','tab_')

                      ->field('bind_balance,balance')

                      ->join('tab_user_play on tab_user.id = tab_user_play.user_id')

                      ->where(array('tab_user.id'=>session("user_auth.user_id"),'game_id'=>$game['id']))

                      ->find();

            $this->assign('userdata',$userdata);
        $this->display();
        

    }





    //游戏支付数据sdk验证

    public function sdkpayVerify($param=''){

        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){ 

            // ajax 请求的处理方式 

            $data = $_POST['data'];

            $data = json_decode($data,true);

            $map['game_appid']=$data['game_appid'];

            $gamedata=M('game','tab_')->where($map)->find();

            $uid=session("user_auth.user_id");

            $gamesetdata=M('game_set','tab_')->where(array('game_id'=>$gamedata['id']))->find();

            //如果没登录

            if(!$uid){

                $this->ajaxReturn(array('status'=>-1,'msg'=>'用户未登录','data'=>''));

            }

            $dataverify = $data;

            unset($dataverify['sign']);

            ksort($dataverify);//字典排序

            $dataverify['sign']       = MD5(urldecode(http_build_query($dataverify).$gamesetdata['game_key']));

            if($dataverify['sign']!=$data['sign']){

                $this->ajaxReturn(array('status'=>0,'msg'=>'充值验签失败','data'=>''));//验签失败

            }else{

                $this->ajaxReturn(array('status'=>1,'msg'=>'充值验签成功','data'=>''));//验签成功

            }

        }else{ 

            $data = $param;



            $map['game_appid']=$data['game_appid'];

            $gamedata=M('game','tab_')->where($map)->find();

            $gamesetdata=M('game_set','tab_')->where(array('game_id'=>$gamedata['id']))->find();

            $uid=session("user_auth.user_id");

            //如果没登录

            if(!$uid){

                return array('status'=>-1,'msg'=>'用户未登录','data'=>'');

            }

            $dataverify = $data;

            unset($dataverify['sign']);

            ksort($dataverify);//字典排序

            $dataverify['sign']       = MD5(urldecode(http_build_query($dataverify).$gamesetdata['game_key']));

            if($dataverify['sign']!=$data['sign']){

                return array('status'=>0,'msg'=>'充值验签失败','data'=>'');//验签失败

            }else{

                return array('status'=>1,'msg'=>'充值验签成功','data'=>'');//验签成功

            }

        };

    }

    public function goldpig_return_callback(){
        $url = 'http://357p.com/api/mun.asp?mun='.I('get.orderId');
        $content = file_get_contents($url);
        $data = json_decode($content,true);
        if(is_array($data)){
            $order = $data['jinzhua'];
            $pay_where = substr($order,0,2);
            switch ($pay_where) {
                case 'SP':
                    $Spend = M('Spend',"tab_");
                    $map['pay_order_number'] = $order;
                    $orderinfo = $Spend->where($map)->find();
                    break;
                case 'PF':
                    $this->redirect("http://".$_SERVER['HTTP_HOST']."/mediawide.php?s=/Subscriber/index.html");
            }
            $this->game_pay_callback($order,$orderinfo['game_id']);
        }else{
            redirect(U('Index/index'));
        }
    }
    public function game_pay_callback($order_number,$game_id=''){
        $order_number || die('缺少必要参数');
        $pay_where = substr($order_number,0,2);
        switch ($pay_where) {
            case 'SP':
                $Spend = M('Spend',"tab_");
                $map['pay_order_number'] = $order_number;
                $orderinfo = $Spend->where($map)->find();
                if(!$orderinfo){
                    $Spend = M('Bind_spend',"tab_");
                    $map['pay_order_number'] = $order_number;
                    $orderinfo = $Spend->where($map)->find();
                }
                if(!$orderinfo['game_id'] || $orderinfo['game_id']!=$game_id){
                    $this->ajaxReturn(array('status'=>0,'msg'=>'缺少必要参数'));
                }
                $gameorder = 1;
                $this->assign('game_url',U('Game/open_game',array('game_id'=>$orderinfo['game_id'])));
                break;
            case 'PF':
                $deposit = M('deposit',"tab_");
                $map['pay_order_number'] = $order_number;
                $orderinfo = $deposit->where($map)->find();
                $this->assign('game_url',U('Subscriber/user_recharge'));
                break;
            case 'AG':
                break;
            default:
                die('缺少必要参数');
                break;
        }
        if(!$orderinfo){
            $this->ajaxReturn(array('status'=>0,'msg'=>'订单不存在'));
        }else if(!$orderinfo['user_id']){
            $this->ajaxReturn(array('status'=>0,'msg'=>'缺少必要参数'));
        }
        $data = $_REQUEST;
        $data['gameorder'] = $gameorder;
        if($orderinfo['pay_status']){
            $data['pay_status'] = 1;
        }else{
            $data['pay_status'] = 0;
        }
        $this->assign('data',$data);
        $this->display('game_pay_callback');
    }
    
    public function game_bind_phone(){
        $this->display();

    }
    
    public function add_spend($data){
        $spend = M("spend","tab_");
        $ordercheck = $spend->where(array('pay_order_number'=>$data["order_no"]))->find();
        if($ordercheck)$this->error("订单已经存在，请刷新充值页面重新下单！");
        $spend_data['order_number']     = "";
        $spend_data['pay_order_number'] = $data["order_no"];
        $spend_data['user_id']          = $data["id"];
        $spend_data['user_account']     = $data["account"];
        $spend_data['user_nickname']    = $data["nickname"];
        $spend_data['game_id']          = $data["game_id"];
        $spend_data['game_appid']       = $data["game_appid"];
        $spend_data['game_name']        = $data["game_name"];
        $spend_data['promote_id']       = $data["promote_id"];
        $spend_data['promote_account']  = $data["promote_account"];
        $spend_data['props_name']       = $data["title"];
        $spend_data['pay_amount']       = $data["pay_amount"];
        $spend_data['pay_way']          = $data["pay_way"];
        $spend_data['pay_time']         = NOW_TIME;
        $spend_data['pay_status']       = 0;
        $spend_data['pay_game_status']  = 0;
        $spend_data['extend']           = $data['extend'];
        $spend_data['spend_ip']         = get_client_ip();
        $spend->add($spend_data);
        
    }

    //app写session

    protected function app_write_session($token){

        $token = think_decrypt($token);

        if(empty($token)){

            $this->error("信息过期，请重新登录");

        }

        $info = json_decode($token,true);

        $user = get_user_entity($info['user_id'],false);

        if(empty($user)){$this->error("用户不存在");exit();}

         /* 记录登录SESSION和COOKIES */

        $auth = array(

            'user_id'   => $user['id'],

            'account'   => $user['account'],

            'nickname'  => $user['nickname'],

        );

        session('user_auth', $auth);

        session('user_auth_sign', data_auth_sign($auth));

    }

    public function create_role(){
        $data = json_decode($_REQUEST['role'],true);
        $signdata['user_id']        = $data['user_id'];
        $signdata['game_appid']     = $data['game_appid'];
        $signdata['server_id']      = $data['server_id'];
        $signdata['server_name']    = $data['server_name'];
        $signdata['role_id']        = $data['role_id'];
        $signdata['role_name']      = $data['role_name'];
        $signdata['level']          = $data['level'];
        if(!empty($data)){
            $gamedata = M('GameSet','tab_')
                         ->field('game_id,game_key,game_name')
                         ->join('tab_game on tab_game.id = tab_game_set.game_id')
                         ->where(array('game_appid'=>$data['game_appid']))
                         ->find();
            $userdata = M('User','tab_')
                         ->field('account,up.id as user_play_id,game_name')
                         ->join('tab_user_play as up on tab_user.id = up.user_id')
                         ->where(array('up.game_id'=>$gamedata['game_id']))
                         ->find();
            $game_key = $gamedata['game_key'];
            unset($signdata['sign']);
            $sign = signsortData($signdata,$game_key);
        }else{
            $this->ajaxReturn(array('code'=>1001,'msg'=>'数据不能为空'),'jsonp');
        }

        if($sign!=$data['sign']&&0){
            $this->ajaxReturn(array('code'=>1003,'msg'=>'数据签名失败'),'jsonp');
        }else{
            $roledata['user_id']            = $data['user_id'];
            $roledata['user_account']       = $userdata['account'];
            $roledata['user_play_id']       = $userdata['user_play_id'];
            $roledata['game_id']            = $gamedata['game_id'];
            $roledata['game_name']          = $gamedata['game_name'];
            $roledata['server_id']          = $data['server_id'];
            $roledata['server_name']        = $data['server_name'];
            $roledata['role_id']            = $data['role_id'];
            $roledata['role_name']          = $data['role_name'];
            $roledata['role_level']         = $data['level'];
            $roledata['play_time']          = time();
            $roledata['update_time']        = $roledata['play_time'];
            $roledata['play_ip']            = get_client_ip();
        }
        $gapi = new GameApi();
        $res = $gapi->add_user_role($roledata);
        $this->ajaxReturn(array('code'=>200,'msg'=>'角色记录成功'),'jsonp');
    }

}

