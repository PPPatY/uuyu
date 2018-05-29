<?php

namespace Home\Controller;
use OT\DataDictionary;
use Think\Model;
use User\Api\PromoteApi;
use User\Api\UserApi;
use Org\WeiXinSDK\Weixin;
use Org\JubaobarSDK\Jubaobar;
use Org\JtpaySDK\Jtpay;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class ChargeController extends BaseController {

    
    public function checkpwd(){
        $pid=session("promote_auth.pid");
        $user=new UserApi();
        $map['id']=$pid;
        $pro=M("promote","tab_")->where($map)->find();
        if($pro['second_pwd']===$this->think_ucenter_md5($_REQUEST['pwd'],UC_AUTH_KEY)){
            $this->ajaxReturn(array("status"=>1,"msg"=>"成功"));
        }
        else{
            $this->ajaxReturn(array("status"=>0,"msg"=>"二级密码错误"));
        }
    }
    public function checkSecond(){
        $pid=session("promote_auth.pid");
        $map['id']=$pid;
        $data=M('promote','tab_')->where($map)->find();
        if(empty($data['second_pwd'])){
            $this->ajaxReturn(array("status"=>0));
        }
    }
    public function checkpromote(){
        $map['promote_id']=session("promote_auth.pid");
        $map['account'] = $_POST['user_account'];
        $data = M("User","tab_")->where($map)->find();
        if(empty($data)){
            $this->ajaxReturn(array("status"=>0));
        }else{
            $this->ajaxReturn(array("status"=>1));
        }
    }
    public function checkAccount(){
       $game_id = $_POST['game_id'];
       $user_account = $_POST['user_account'];
       if ($game_id)$map['game_id'] = $game_id;
       $map['user_account'] = $user_account;
       $data1 = M("UserPlay","tab_")->where($map)->find();
       $map["promote_id"]   = session("promote_auth.pid");
       $data = M("UserPlay","tab_")->where($map)->find();
       //var_dump($data);
       if(empty($data1)){
            $this->ajaxReturn(array("status"=>0));
       }elseif(empty($data)){
            $this->ajaxReturn(array("status"=>-1));
       }{
            $this->ajaxReturn(array("status"=>1));
       }
    }
    public function think_ucenter_md5($str, $key = 'ThinkUCenter'){
    return '' === $str ? '' : md5(sha1($str) . $key);
}

    public function agent_pay($p=0)
    {
        if (IS_POST) {
            $game_id = I('game_id');
            $account = I('user_account');
            $amount = I('amount');
            $discount = D('PromoteWelfare')->get_discount($game_id);
            if($discount==0){
                $real_amount = $amount;
            }else{
                $real_amount = $amount * $discount / 10;//计算折扣后的价格
            }
            $promote = M('Promote', 'tab_')->where(array('id' => PID))->find();

            $order_no = "AG_" . date('Ymd') . date('His') . sp_random_string(4);

            $create['zhekou'] = $discount;
            $create['pay_order_number'] = $order_no;
            $create['game_id'] = $game_id;
            $game_appid = $create['game_appid'] = get_game_appid($game_id,'id');
            $game_name = $create['game_name'] = get_game_name($game_id);
            $create['amount'] = $amount;
            $create['real_amount'] = $real_amount;

            $user = get_user_entity($account, true);
            //页面上通过表单选择在线支付类型，支付宝为alipay 财付通为tenpay
            $vo = new \Think\Pay\PayVo();
            $vo->setBody("会长代充")
                ->setFee($real_amount)//支付金额
                ->setTitle("平台币")
                ->setOrderNo($order_no)
                ->setSignType("MD5")
                ->setPayMethod("direct")
                ->setTable("agent")
                ->setGameId($game_id)
                ->setGameName($game_name)
                ->setGameAppid($game_appid)
                ->setUserId($user['id'])
                ->setAccount($user['account'])
                ->setUserNickName($user['nickname'])
                ->setPromoteId(session("promote_auth.pid"))
                ->setPromoteName(session('promote_auth.account'))
                ->setMoney($amount)
                ->setParam($discount);
            switch (I('pay_type')) {
                case 'swiftpass':
                    //判断是否开启微信充值
                    if (pay_set_status('wei_xin') == 0 && pay_set_status('weixin') == 0) {
                        $this->ajaxReturn(["status"=>0, "msg"=>"网站未开启微信充值"]);
                    }
                    if (get_wx_type() == 0) {
                        $weixn = new Weixin();
                        $is_pay = json_decode($weixn->weixin_pay("平台币充值", $order_no, $real_amount), true);
                        if ($is_pay['status'] === 1) {
                            $json_['out_trade_no'] = $order_no;
                            $json_['amount'] = $amount;
                            $json_['pay_money'] = $real_amount;
                            $json_['code_img_url'] = U('qrcode', array('level' => 3, 'size' => 4, 'url' => base64_encode(base64_encode($is_pay['url']))));
                        }
                        $create['pay_way'] = 2;
                        $this->add_agent($user, $create);
                        $this->ajaxReturn(["status"=>1, "data"=>$json_]);
                    } else {
                        $vo->setService("pay.weixin.native")
                            ->setPayWay(2);
                        $pay = new \Think\Pay('swiftpass', C('weixin'));
                        $all = $pay->buildRequestForm($vo);
                        $all['amount'] = $vo->getMoney();
                        $this->ajaxReturn(["status"=>1, "data"=>$all]);
                    }
                    break;
                // case 'jubaobar':
                //     $create['pay_way'] = 3;
                //     $Jubaobar = new Jubaobar();
                //     $this->add_agent($user, $create);
                //     echo $Jubaobar->jubaobar_pay($order_no, $real_amount, '平台币充值');
                //     break;
                // case 'jft':
                //     if (pay_set_status('jft') == 0) {
                //         $this->error("网站未开启竣付通充值", '', 1);
                //         exit();
                //     }
                //    $sign=think_encrypt(md5($real_amount.$order_no));
                //     $create['pay_way'] = 5;
                //    file_put_contents("./Application/Home/OrderNo/".$order_no.'.txt',json_encode($create));
                //     redirect(U('pay_way', array('type'=>'UnionPay','account' =>$user['account'],'pay_amount'=>$real_amount,'sign'=>$sign,'pay_order_number'=>$order_no)));
                //     break;
                case 'pingtaibi':
                    $model = new Model();
                    $model->startTrans();
                    $create['pay_way'] = 4;
                    $res = false;
                    if($real_amount > $promote['balance_coin']){
                        $res_msg = '余额不足';
                        $status = 0;
                    }else{
                        //修改渠道平台币
                        $promote['balance_coin'] -= $real_amount;
                        $p_res = M('promote','tab_')->save($promote);
                        $this->add_agent($user, $create);
                        $res = $this->set_agent($create);//修改用户平台币
                    }
                    if($res && $p_res){
                        $model->commit();
                        $res_msg = empty($res_msg) ? '充值成功' : $res_msg;
                        $status = 1;
                        $url = '"'.U('Charge/agent_pay_list').'"';
                    }else{
                        $model->rollback();
                        $url = '"'.U('Charge/agent_pay').'"';
                    }
                 
                    $this->ajaxReturn(["status"=>$status, "msg"=>$res_msg]);
                    
                    break;
                default:
                    //判断是否开启支付宝充值
                    if (pay_set_status('alipay') == 0) {
                        $this->error("网站未开启支付宝充值", '', 1);
                        exit();
                    }
                    $vo->setService("create_direct_pay_by_user")
                        ->setPayWay(1);
                    $pay = new \Think\Pay('alipay', C('alipay'));
                    echo $pay->buildRequestForm($vo);
                    break;
            }

        } else {
            $this->show_agent($p);
        }
    }

    public function show_agent($p){
        $this->meta_title = "会长代充";
        $pro = M('Promote', 'tab_')->where(array('id' => PID))->find();
				
        $child = M('Promote','tab_')->field('account,balance_coin')->where(['parent_id'=>PID])->select();
        $page = $p ? $p : 1; //默认显示第一页数据
        $row = 10;
        $count = count($child);
        $arraypage = $page;
        $size=$row;//每页显示的记录数
        $pnum = ceil($count / $row); //总页数，ceil()函数用于求大于数字的最小整数
        //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
        $childData = array_slice($child, ($arraypage-1)*$row, $row);
        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        $this->assign("childData",$childData);
				
        $user = D('User')->all_lists(array('promote_id'=>PID,'lock_status'=>1));
       
        $this->assign('user',$user);
        
        $this->assign('pro', $pro);
        $this->display();
    }
    
    public function get_game_list_by_user($userid=0) {
      if (IS_POST && is_numeric($userid) && $userid>0) {
        $apply = M('Apply',"tab_");
        $map['up.promote_id'] = PID;
        $lists = M('UserPlay as up','tab_')
                ->field("up.id,g.id as game_id,g.game_name,g.game_appid")
                ->join("tab_game g on g.id = up.game_id and user_id=$userid and up.promote_id=".PID)
                ->where($map)
                ->order("id DESC")
                ->select();
        
        if ($lists && !empty($lists[0])) {
          echo json_encode(array('status'=>1,'data'=>$lists));exit;     
        }
        
      }
        
      echo json_encode(array('status'=>0));exit;
      
   }
    
    
    
    public function agent_pay_list($p=0){
        $map=array();
				$_REQUEST = array_trim($_REQUEST);
        if(isset($_REQUEST['user_account'])&&trim($_REQUEST['user_account'])!=''){
						$account = str_replace('%','\%',$_REQUEST['user_account']);						
            $map['user_account']=array('like','%'.$account.'%');
            unset($_REQUEST['user_account']);
        }
        if($_REQUEST['game_id']>0){
            $map['game_id']=$_REQUEST['game_id'];
        }
        if(!empty($_REQUEST['time-start'])&&!empty($_REQUEST['time-end'])){
            $map['create_time']  =  array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
            unset($_REQUEST['time-start']);unset($_REQUEST['time-end']);
        }elseif(!empty($_REQUEST['time-start'])){
            $map['create_time']  =  array('egt',strtotime($_REQUEST['time-start']));
            unset($_REQUEST['time-start']);
        }elseif(!empty($_REQUEST['time-end'])){
            $map['create_time']  =  array('lt',strtotime($_REQUEST['time-end'])+24*60*60);
            unset($_REQUEST['time-end']);
        }
        
        $map['promote_id']=get_pid();
        $total = M("agent","tab_")->where(array('pay_status'=>1))->where($map)->sum('amount');
        $this->assign("total_amount",$total==null?0:$total);
        $this->assign('game_id',$_REQUEST['game_id']);
        $this->lists('agent',$p,$map,'代充记录');
    }

    /**
    *添加代充记录
    */
    private function add_agent($user,$data){
        $agent = M("agent","tab_");
        $agnet_data['order_number']     = "";
        $agnet_data['pay_order_number'] = $data['pay_order_number'];
        $agnet_data['game_id']          = $data['game_id'];
        $agnet_data['game_appid']       = $data['game_appid'];
        $agnet_data['game_name']        = $data['game_name'];
        $agnet_data['promote_id']       = session('promote_auth.pid');
        $agnet_data['promote_account']  = session('promote_auth.account');
        $agnet_data['user_id']          = $user['id'];
        $agnet_data['user_account']     = $user['account'];
        $agnet_data['user_nickname']    = $user['nickname'];
        $agnet_data['pay_type']         = 0;//代充 转移
        $agnet_data['amount']           = $data['amount'];
        $agnet_data['real_amount']      = $data['real_amount'];
        $agnet_data['pay_status']       = 0;
        $agnet_data['pay_way']          = $data['pay_way'];
        $agnet_data['create_time']      = time();
        $agnet_data['zhekou']           = $data['zhekou'];
        $agent->create($agnet_data);
        $result = $agent->add();
        return $result;
    }


    /**
     * @param int $level
     * @param int $size
     */
    public function qrcode($level=3,$size=4,$url=""){
        Vendor('phpqrcode.phpqrcode');
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        ob_clean();
        $object = new \QRcode();
        echo $object->png(base64_decode(base64_decode($url)), false, $errorCorrectionLevel, $matrixPointSize, 2);
    }

    //获取折扣
    public function get_discount($game_id){
        $discount = D('PromoteWelfare')->get_discount($game_id);
        $res['status'] = 1;
        $res['discount'] = $discount;
        $this->ajaxReturn($res);
    }

    protected function set_agent($data){
        $agent = M("agent","tab_");
        $map['pay_order_number'] = $data['pay_order_number'];
        $d = $agent->where($map)->find();
        if(empty($d)){return false;}
        if($d['pay_status'] == 0){
            $data_save['pay_status'] = 1;
            $data_save['order_number'] = $data['order_number'];
            $map_s['pay_order_number'] = $data['pay_order_number'];
            $r = $agent->where($map_s)->save($data_save);
            if($r!== false){
                $user = M("user_play","tab_");
                $map_play['user_id'] = $d['user_id'];
                $map_play['game_id'] = $d['game_id'];
                $user->where($map_play)->setInc("bind_balance",$d['amount']);
                //$user->where("id=".$d['user_id'])->secInt("cumulative",$d['pay_amount']);
                $pro_l=M('Promote','tab_')->where(array('id'=>$d['promote_id']))->setDec("pay_limit",$d['amount']);
            }else{
                $this->record_logs("修改数据失败");
            }
            return true;
        }
        else{
            return true;
        }
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
            $data['pay_order_number'] = $_POST['pay_order_number'];
            $data['user_id'] = $user['id'];
            $data['user_account'] = $user['account'];
            $data['user_nickname'] = $user['nickname'];
            $data['promote_id'] = $user['promote_id'];
            $data['promote_account'] = $user['promote_account'];
            $data['fee'] = $_POST['pay_amount'];
            $data['pay_way'] = 5;//竣付通
            $data['pay_source'] = 1;
            $url="./Application/Home/OrderNo/".$_POST['pay_order_number'].'.txt';
            if(!file_exists($url) ){
                   $this->error('未知错误',U('Charge/agent_pay'));exit;
            }
            $create=json_decode(file_get_contents($url),true) ;
            @unlink($url);
             $this->add_agent($user, $create);
             // $_POST['pay_amount']=0.01;
            switch ($_POST['type']) {
                case 'UnionPay':
                 echo $jtpay->jt_pay($_POST['pay_order_number'],$_POST['pay_amount'],$_POST['account'],get_client_ip(),$_POST['p10_paychannelnum'],1,"http://".$_SERVER['HTTP_HOST'].U('agent_pay'));
                    break;
                default:
                echo $jtpay->jt_pay($_POST['pay_order_number'],$_POST['pay_amount'],$_POST['account'],get_client_ip(),"",$_POST['p9_paymethod'],"http://".$_SERVER['HTTP_HOST'].U('agent_pay'));
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
    public function chec_disc(){
        if(null==$_POST['pid']||null==$_POST['game_id']){
            $this->ajaxReturn(array('status'=>0,'msg'=>'数据错误'));
        }else{
            $data = M('PromoteWelfare','tab_')->field('promote_discount as discount,"1" as hz')->where(array('promote_id'=>$_POST['pid'],'game_id'=>$_POST['game_id'],'recharge_status'=>1))->find();
            if(empty($data)){
                $data = M('Game','tab_')
                    ->field('tab_game.id,discount,"0" as hz')
                    ->where(array('tab_game.id'=>$_POST['game_id']))
                    ->find();
            };
            if(empty($data)){
                $this->ajaxReturn(array('status'=>0,'msg'=>'游戏不存在'));
            }else{
                if($data['discount']<=0){
                    $data['discount'] = '10';
                }
                $this->ajaxReturn(array('status'=>1,'data'=>$data));
            }
        }
    }
    public function check_status($order_num){
        sleep(2);
        $order_num || $this->ajaxReturn(array("status"=>0,'msg'=>'订单号不能为空'));
        $map['pay_order_number'] = $order_num;
        $res = M('agent','tab_')->where($map)->find();
        if($res['pay_status']==1){
            $this->ajaxReturn(array("status"=>1));
        }else{
            $this->ajaxReturn(array("status"=>0));
        }
    }
}