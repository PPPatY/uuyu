<?php
namespace Sdk\Controller;
use Think\Controller;
use User\Api\MemberApi;
use Common\Api\GameApi;
use Org\XiguSDK\Xigu;
class UserController extends BaseController{

    /**
    *账号、手机用户登陆
    */
    public function user_login(){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $user = json_decode(base64_decode(file_get_contents("php://input")),true);
        #判断数据是否为空
        if(empty($user)){$this->set_message(0,"fail","登陆数据不能为空");}
        #实例化用户接口
        $userApi = new MemberApi();
        $result = $userApi->login($user["account"],$user['password']);#调用登陆
        $res_msg = array();
        switch ($result) {
            case -1:
                $this->set_message(-1,"fail","用户不存在或被禁用");
                break;
            case -2:
                $this->set_message(-2,"fail","密码错误");
                break;
            default:
                if($result > 0){
                    $user["user_id"] = $result;
                    $userdata=M("user","tab_")->where(array('id'=>$result))->find();
                    // $this->add_user_play($user);
                    $res_msg = array("status"=>1,"return_code"=>"success","return_msg"=>"登陆成功","user_id"=>$result,"user_account"=>$userdata['account'],"ptb"=>$userdata['balance'],"head_icon"=>$userdata['head_icon'],"type"=>1);
                }
                else{
                    $this->set_message(0,"fail","未知错误");
                }
                break;
        }
        echo base64_encode(json_encode($res_msg));
    }
    /**
    *第三方登录
    */
    public function user_third_login(){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $user = json_decode(base64_decode(file_get_contents("php://input")),true);
        #判断数据是否为空
        if(empty($user)){$this->set_message(0,"fail","登陆数据不能为空");}
        #实例化用户接口
        $prefix = $user['third_login_type']=="qq"?"QQ_":"WX_";
        $data['account']  = $prefix.time().random_string(6);
        $data['nickname'] = $user['nickname'];
        $data['openid']   = $user['unionid'];
        $data['promote_id'] = 0;
        $data['promote_account'] = '自然注册';
        $data['register_way'] = $user['third_login_type']=="qq"?"4":"3";
        $userApi = new MemberApi();
        $result = $userApi->third_login($data);
        $res_msg = array();
        switch ($result) {
            case -1:
                $this->set_message(-1,"fail","用户不存在或被禁用");
                break;
            case -2:
                $this->set_message(-2,"fail","密码错误");
                break;
            default:
                if($result > 0){
                    $user["user_id"] = $result;
                    $userdata=M("user","tab_")->where(array('id'=>$result))->find();
                    $res_msg = array("status"=>1,"return_code"=>"success","return_msg"=>"登陆成功","user_id"=>$result,"user_account"=>$user['nickname'],"ptb"=>$userdata['balance'],"head_icon"=>$user['head_icon'],"type"=>$data['register_way']);
                }
                else{
                    $this->set_message(0,"fail","未知错误");
                }
                break;
        }
        echo base64_encode(json_encode($res_msg));
    }
    public function user_register(){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $user = json_decode(base64_decode(file_get_contents("php://input")),true);
        #判断数据是否为空
        if(empty($user)){$this->set_message(0,"fail","注册数据不能为空");}
        #实例化用户接口
        $user['nickname']=$user['account'];
        $user['register_way']=1;
        $user['register_time']=time();
        $user['promote_id'] = 0;
        $user['promote_account'] = '自然注册';
        $userApi = new MemberApi();
        $result = $userApi->register($user);
        $res_msg = array();
        if($result > 0){
            $this->set_message(1,"success","注册成功");
        }
        else{
            switch ($result) {
                case -3:
                    $this->set_message(-3,"fail","用户名已存在");
                    break;
                default:
                    $this->set_message(0,"fail","注册失败");
                    break;
            }
        }
    }
    /**
    *手机用户注册
    */
    public function user_phone_register(){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $user = json_decode(base64_decode(file_get_contents("php://input")),true);
        #判断数据是否为空
        if(empty($user)){$this->set_message(0,"fail","注册数据不能为空");}
        #验证短信验证码
        $this->sms_verify($user['account'],$user['code']);
        #实例化用户接口
        $userApi = new MemberApi();
        $user['nickname']=$user['account'];
        $user['phone']=$user['account'];
        $user['register_way']=2;
        $user['register_time']=time();
        $result = $userApi->register($user);
        $res_msg = array();
        if($result > 0){
            session($user['account'],null);
            $this->set_message(1,"success","注册成功");
        }
        else{
            switch ($result) {
                case -3:
                    $this->set_message(-3,"fail","用户名已存在");
                    break;
                default:
                    $this->set_message(0,"fail","注册失败");
                    break;
            }
            
        }
    }

    /**
    *修改用户数据
    */
    public function user_update_data(){
        $user = json_decode(base64_decode(file_get_contents("php://input")),true);
        #判断数据是否为空
        if(empty($user)){$this->set_message(0,"fail","操作数据不能为空");}
        #实例化用户接口
        $data['id'] = $user['user_id'];
        $data['code'] = 'pwd';//$user['user_id'];
        $userApi = new MemberApi();
        switch ($user['code']) {
            case 'phone':
                #验证短信验证码
                $this->sms_verify($user['phone'],$user['sms_code']);
                $data['phone'] = $user['phone'];
                break;
            case 'nickname':
                $data['nickname'] = $user['nickname'];
                break;
            case 'pwd':
                $data['old_password'] = $user['old_password'];
                $data['password'] = $user['password'];
                break;
            default:
                $this->set_message(0,"fail","修改信息不明确");
                break;
        }
        $result = $userApi->updateUser($data);
        if($result == -2){
            $this->set_message(-2,"fail","旧密码输入不正确");
        }
        else if($result == true){
            $this->set_message(1,"success","修改成功");
        }
        else{
            $this->set_message(0,"fail","修改失败");
        }
    }

    /**
    *忘记密码接口
    */
    public function forget_password(){
        $user = json_decode(base64_decode(file_get_contents("php://input")),true);
        if(empty($user)){$this->set_message(0,"fail","数据不能为空");}
        $userApi = new MemberApi();
        #验证短信验证码
        $this->sms_verify($user['phone'],$user['code']);
        $data=M('user','tab_')->where(array('account'=>$user['phone']))->find();
        if(empty($data)){
            $this->set_message(0,"fail","用户不存在");
        }
        $result = $userApi->updatePassword($data['id'],$user['password']);
        if($result == true){
            $this->set_message(1,"success","修改成功");
        }
        else{
            $this->set_message(0,"fail","修改失败");
        }
    }
    
    /**
    *短信发送
    */
    public function send_sms()
    {
        $data = json_decode(base64_decode(file_get_contents("php://input")),true);
        $phone = $data['phone'];
        /// 产生手机安全码并发送到手机且存到session
        $rand = rand(100000,999999);
        $xigu = new Xigu(C('sms_set.smtp'));
        sdkchecksendcode($phone,C('sms_set.limit'));
        $param = $rand.",".'1';
        $result = json_decode($xigu->sendSM(C('sms_set.smtp_account'),$phone,C('sms_set.smtp_port'),$param),true); 
        $result['create_time'] = time();
	    $result['pid'] = 0;
        $result['create_ip']=get_client_ip();
        $r = M('Short_message')->add($result);
        #TODO 短信验证数据 
        if($result['send_status'] == '000000') {
            session($phone,array('code'=>$rand,'create_time'=>NOW_TIME));
            echo base64_encode(json_encode(array('status'=>1,'return_code'=>'success','return_msg'=>'验证码发送成功')));
        }
        else{
            $this->set_message(0,"fail","验证码发送失败，请重新获取");
        }
    }

    /**
    *用户基本信息
    */
    public function user_info(){
        $user = json_decode(base64_decode(file_get_contents("php://input")),true);
        $model = M("user","tab_");
        $data = array();
        switch ($user['type']) {
            case 0:
               $data = $model
                ->field("account,nickname,phone,balance,bind_balance,game_name")
                ->join("INNER JOIN tab_user_play ON tab_user.id = tab_user_play.user_id and tab_user.id = {$user['user_id']} and tab_user_play.game_id = {$user['game_id']}")
                ->find();
                break;
            case 1:
                $map['id'] = $user['user_id'];
                $data = $model->field("id,account,nickname,balance")->where($map)->find();
                break;
            default:
                $map['account'] = $user['user_id'];
                $data = $model->field("id,account,nickname,phone,balance")->where($map)->find();
                break;
        }
        
        if(empty($data)){
            $this->set_message(0,"fail","用户数据异常");
        }
        $data['phone'] = empty($data["phone"])?" ":$data["phone"];
        $data['status'] = 1;
        echo base64_encode(json_encode($data));
    }

    /**
    *用户平台币充值记录
    */
    public function user_deposit_record(){
        $data = json_decode(base64_decode(file_get_contents("php://input")),true);
        $map["user_id"] = $data["user_id"];
        $deposit = M("deposit","tab_")->where($map)->select();
        if(empty($deposit)){
            echo base64_encode(json_encode(array("status"=>0,"return_code"=>"fail","return_msg"=>"暂无记录")));exit();
        }
        $return_data['status'] = 1;
        $return_data['data'] = $deposit;
        echo base64_encode(json_encode($return_data));
    }

    /**
    *用户领取礼包- 
    */
    public function user_gift_record(){
        $data = json_decode(base64_decode(file_get_contents("php://input")),true);
        $map["user_id"] = $data["user_id"];
        $map["game_id"] = $data["game_id"];
        $gift = M("GiftRecord","tab_")
        ->field("tab_gift_record.game_id,tab_gift_record.game_name,tab_giftbag.giftbag_name ,tab_giftbag.digest,tab_gift_record.novice,tab_gift_record.status,tab_giftbag.start_time,tab_giftbag.end_time")
        ->join("LEFT JOIN tab_giftbag ON tab_gift_record.gift_id = tab_giftbag.id where user_id = {$data['user_id']} and tab_gift_record.game_id = {$data['game_id']}")
        ->select();
        if(empty($gift)){
            echo base64_encode(json_encode(array("status"=>0,"return_code"=>"fail","return_msg"=>"暂无记录")));exit();
        }
        foreach ($gift as $key => $val) {
            $gift[$key]['icon'] = $this->set_game_icon($val[$key]['game_id']);
            $gift[$key]['now_time'] = NOW_TIME;
        }
        
        $return_data['status'] = 1;
        $return_data['data'] = $gift;
        echo base64_encode(json_encode($return_data));
    }

    /**
    *用户平台币(绑定和非绑定)
    */
    public function user_platform_coin(){
        $data = json_decode(base64_decode(file_get_contents("php://input")),true);
        $user_play = M("UserPlay","tab_");
        $platform_coin = array();
        $user_data = array();
        #非绑定平台币信息
        $user_data = get_user_entity($data["user_id"]);
        $platform_coin['status'] = 1;
        $platform_coin["balance"] = $user_data["balance"];
        #绑定平台币信息
        $map["user_id"] = $data["user_id"];
        $map["game_id"] = $data["game_id"];
        $user_data = $user_play->where($map)->find();
        $platform_coin["bind_balance"] = $user_data["bind_balance"];
        echo base64_encode(json_encode($platform_coin));
    }
    //轮播图
    public function lunbotu(){
        // $map['pos_id']=1;
        $map['status']=1;
        $data=M('adv','tab_')->field('title,url,data')->where($map)->select();
        foreach ($data as $key => $value) {
            $cover=$value['data'];
            $data[$key]['pic_link']='http://'.$_SERVER ['HTTP_HOST'].get_cover($cover,'path');
            unset($data[$key]['data']);
        }
        echo json_encode($data);
    }
    //基本信息
    public function base_info(){
        $info['agreement']='http://'.$_SERVER ['HTTP_HOST'].'/media.php?s=/Subscriber/agreement.html';
        $map['status'] = 1;
        $map['category'] = 3;
        $map['group'] = 1;
        $list  =  M("Config")->where($map)->field('name,title,value')->order('sort')->select();
        $info['base'] = array_column($list, 'value','name');
        $logo=M("Config")->where(array('name'=>'APP_LOGO'))->field('name,title,value')->find();
        $app_cover=M("Config")->where(array('name'=>'APP_SET_COVER'))->field('name,title,value')->find();
        if($logo["value"]!=''){
            $info['base']['APP_LOGO']='http://'.$_SERVER ['HTTP_HOST'].get_cover($logo['value'],'path');
        }else{
            $info['base']['APP_LOGO']='';
        }
        if($app_cover["value"]!=''){
            $info['base']['APP_SET_COVER']='http://'.$_SERVER ['HTTP_HOST'].get_cover($app_cover['value'],'path');
        }else{
            $info['base']['APP_SET_COVER']='';
        }
        echo json_encode($info);
    }
    //用户头像上传
    public function head_icon_upload($user_id=0){
        $data = file_get_contents("php://input");  
        if(empty($data)){echo json_encode(array("status"=>0,"return_msg"=>"图片数据不能为空"));exit();}
        $map['id']=$user_id;
        $user=M("user","tab_")->where($map)->find();//"icon",$url
        if(null==$user['head_icon']){
        wite_text($data,dirname(dirname(__FILE__))."/Icon/$user_id.jpg");
        $url='http://'.$_SERVER['HTTP_HOST']."/Application/Sdk/Icon/$user_id.jpg";
        $user=M("user","tab_")->where($map)->setField("head_icon",$url);
           echo json_encode(array("url"=>$url));
         }else{
            $count=strlen("http://".$_SERVER['HTTP_HOST']);
            $str1 = substr($user['head_icon'],$count+1);
          @unlink($str1);
          $rand = rand(100000,999999);
          wite_text($data,dirname(dirname(__FILE__))."/Icon/$rand"."_"."$user_id.jpg");
          $url='http://'.$_SERVER['HTTP_HOST']."/Application/Sdk/Icon/$rand"."_"."$user_id.jpg";
          $user=M("user","tab_")->where($map)->setField("head_icon",$url);
            echo json_encode(array("url"=>$url));
         }

      }
      public function my_game(){
        $user=json_decode(base64_decode(file_get_contents("php://input")),true);
        $user_id=$user['user_id'];
        $user1=get_user_entity($user_id);
        $type=$user['type'];
        if($user['type']==''){echo base64_encode(json_encode(array("status"=>0,"return_msg"=>"类型不正确")));exit();}
        if(empty($user1)){echo base64_encode(json_encode(array("status"=>0,"return_msg"=>"用户不存在")));exit();}
        if($type==1){
            $map['id']=$user_id;
            $data=M('User','tab_')->field('collection')->where($map)->find();
            $cdad=substr($data['collection'],0,strlen($data['collection'])-1);
            if($cdad!=''){
                $map1['tab_game.game_status']=1;
                $map1['tab_game.id']=array('in',$cdad);
                $model=array(
                    'field'=>'id as game_id,icon,game_name,game_type_name,features,screen_type',
                    'model'=>'Game',
                    );
                $game=$this->showgame($model,$map1);
            }else{
                $game='';
            }
        }else if($type==2){
            $map['user_id'] = $user_id;
            $map['tab_game.game_status']=1;
            $model=array(
                'field'=>'tab_user_play.game_id,tab_game.icon,tab_game.game_name,tab_game.game_type_name,tab_game.features,screen_type',
                'model'=>'user_play',
                'join' =>'tab_game on (tab_game.id=tab_user_play.game_id)',
                );
            $game=$this->showgame($model,$map);
        }
        foreach ($game as $key => $value) {
            $game[$key]['play_num']=play_num($value['game_id']);
            $game[$key]['icon']='http://'.$_SERVER ['HTTP_HOST'].get_cover($value['icon'],'path');
        }
         echo base64_encode(json_encode($game));
      }
      public function my_gift(){
            $request=json_decode(base64_decode(file_get_contents("php://input")),true);
            $user_id=$request['user_id'];
            if(empty($user_id)){echo base64_encode(json_encode(array("status"=>0,"return_msg"=>"用户数据不能为空")));exit();}
            $user=get_user_entity($user_id);
            if(empty($user)){echo base64_encode(json_encode(array("status"=>0,"return_msg"=>"用户不存在")));exit();}
            $data=M('gift_record as r','tab_')
                ->field('r.game_id,r.gift_name,r.game_name,r.novice,g.start_time,g.end_time')
                ->where(array('r.user_id'=>$user['id']))
                ->join('tab_giftbag as g on r.gift_id=g.id')
                ->select();
            foreach ($data as $key => $value) {
                $icon=M('game','tab_')->field('icon')->where(array('id'=>$value['game_id']))->find();
                $data[$key]['icon']='http://'.$_SERVER ['HTTP_HOST'].get_cover($icon['icon'],'path');
                $data[$key]['gift_validity']=date('Y-m-d',$value['start_time']).'--'.date('Y-m-d',$value['end_time']);
            }
            echo base64_encode(json_encode($data));
      }
      /** 绑定平台币 */
    public function bdptb(){
        $request=json_decode(base64_decode(file_get_contents("php://input")),true);
        $user_id=$request['user_id'];
        if(empty($user_id)){echo base64_encode(json_encode(array("status"=>0,"return_msg"=>"用户数据不能为空")));exit();}
        $user=get_user_entity($user_id);
        if(empty($user)){echo base64_encode(json_encode(array("status"=>0,"return_msg"=>"用户不存在")));exit();}
        $data=M('user_play as a','tab_')
            ->field('a.game_name,icon,game_id,bind_balance')
            ->where(array('user_id'=>$user_id,'bind_balance'=>array('gt',0)))
            ->join('tab_game on a.game_id = tab_game.id')
            ->select();
        foreach ($data as $key => $value) {
                $data[$key]['icon']='http://'.$_SERVER ['HTTP_HOST'].get_cover($value['icon'],'path');
            }
        echo base64_encode(json_encode($data));
    }
    /** 意见反馈 */
    public function feedback() {
        $request=json_decode(base64_decode(file_get_contents("php://input")),true);
        if(empty($request)){echo base64_encode(json_encode(array("status"=>0,"return_msg"=>"提交数据不能为空")));exit();}
        $content=$request['content'];//内容
        $contact=$request['contact'];//联系
        $user=get_user_entity($request['user_id']);
        if(empty($user)){echo base64_encode(json_encode(array("status"=>0,"return_msg"=>"用户不存在")));exit();}
        if (!empty($content) && !empty($contact)) {
            $feedback = M('Feedback','tab_');
            $data['username']=$user['account'];
            $data['username']=$user['nickname'];
            $data['content']=$content;
            $data['contact']=$contact;
            $data['create_time']=time();
            $add=$feedback->add($data);
            if($add){
                echo base64_encode(json_encode(array('status'=>1,'return_msg'=>'提交成功！')));
            }else{
                echo base64_encode(json_encode(array('status'=>0,'return_msg'=>'提交失败！')));
            }
        }
    }       

}
