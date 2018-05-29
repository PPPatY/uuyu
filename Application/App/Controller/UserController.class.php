<?php
namespace App\Controller;
use App\Model\CollectionGameModel;
use Think\Controller;
use User\Api\MemberApi;
use Admin\Model\UserModel;
use Common\Api\GameApi;
use Org\XiguSDK\Xigu;
use Org\UcenterSDK\Ucservice;
use Common\Model\PointTypeModel;
class UserController extends BaseController{
	private function wite_text($txt,$name){
        $myfile = fopen($name, "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
    }

	/**
	 * 账号、手机用户登陆
	 * @param array $user 用户登陆信息('account','password')
	 * @return base64加密的json格式
	 * @author lyf
	 */
     public function user_login($account='',$password='')
    {
        #判断数据是否为空
        if (empty($account) || empty($password)) {
            $this->set_message(1089, "账号或密码不能为空",[],1);
        }
        #开启uc登陆
        if (C('UC_SET') == 1) {
            $userApi = new UserModel();
            $result = $userApi->login_app($account, $password);#调用登陆
            $res_msg = array();
            switch ($result) {
                case -1001:
                    $this->set_message(1004,  "账号不存在或被禁用",[],1);
                    break;
                case -10021:
                    $this->set_message(1005,  "密码错误",[],1);
                    break;
                case -1000:
                	$this->uc_login($account, $password);
                    break;
                default:
                    if ($result > 0) {
                        $user["user_id"] = $result;
                        $data['id'] = $user["user_id"];
                        $User = M('User', "tab_");
                        $list = $User->where($data)->find();
                        $res_msg = array("status" => 200, "msg" => "登陆成功", "list" => $list);
                    }
                    break;
            }
            echo json_encode($res_msg);
        } else {
            #实例化用户接口
            $userApi = new UserModel();
            $result = $userApi->login_app($account, $password);#调用登陆
            $res_msg = array();
            switch ($result) {
                case -1000:
                    $this->set_message(1004,"用户不存在或被禁用",[],1);
                    break;
                case -1001:
                    $this->set_message(1004, "用户不存在或被禁用",[],1);
                    break;
                case -10021:
                    $this->set_message(1005,"密码错误",[],1);
                    break;
                default:
                    if ($result > 0) {
                        $data['user_id'] = "1000".$result;
	                    $data['account'] = $account;
                        $data['head_icon'] = 'http://'.$_SERVER['HTTP_HOST']."/Public/Mobile/images/my_head.png";
                        $data['token'] = $this->login($result,$account,C('UC_SET'));
                        $this->set_message(1,"登录成功",$data);
                    } else {
                        $this->set_message(1028, "未知错误",[],1);
                    }
                    break;
            }
            echo json_encode($res_msg);
        }
    }
    /**
	 * uc用户登陆
	 * @param string $account 用户名
	 * @param string $password 密码
	 * @return base64加密的json格式
	 * @author lyf
	 */
    protected function uc_login($account,$password){
            $user['account']=$account;
            $user['password']=$password;
            $uc = new Ucservice();
            $uidarray = $uc->uc_login($user['account'], $user['password']);
            if ($uidarray == -1) {
                $this->set_message(1004, "fail", "用户不存在或被禁用");
            } else if ($uidarray == -2) {
                $this->set_message(1005, "fail", "密码错误");
            } else {
                if (is_array($uidarray)) {
                        $list['id'] = 0;//UC用户
                        $list['account']="UC用户";
                        $list['balance']=0;
                        $list['phone']=1441;
                        $list['sex']=0;
                        $list['vip_level']=0;
                        $list['nickname']="UC用户";
                        $res_msg = array("status" => 1, "msg" => "登陆成功", "list" => $list);
                        echo json_encode($res_msg);
                }
            }
    }
    /**
	 * 忘记密码---验证用户和短信验证码
	 * @param string $phone 手机号码
	 * @param string $code  验证码
	 * @return
	 * @author lyf
	 */
	public function forget_password($phone,$code){
		$user['phone'] = $phone;
		$user['code'] = $code;
        if (empty($user)) {
            $this->set_message(0, "fail", "登陆数据不能为空");
        }
        $where['phone']=$user['phone'];
        $data=M("user","tab_")->where($where)->find();
        if(C('UC_SET')==1&&!$data){
        	$uc = new Ucservice();
        	$is_uc =$uc->get_uc($user['phone']);
        	if ($is_uc) {
        		$this->set_message(0,"fail","UC用户暂不支持");	
        	}else{
        		$this->set_message(0,"fail","用户不存在");
        	}
        }
        #验证短信验证码
        $res = $this->sms_verify($user['phone'],$user['code']);
        if($res){
        	$this->set_message(1,"success");
        }
    }
    /**
	 * 忘记密码---验证密码
     * @param string $phone 密码
	 * @param string $password 密码
	 * @param string $password_again 确认密码
	 * @return base64加密的json格式
	 * @author lyf
	 */
    public function forget_password_pwd($phone,$password,$password_again){
    	$user['password'] = $password;
		$user['password_again'] = $password_again;
    	if (empty($user)) {
            $this->set_message(0, "fail", "登陆数据不能为空");
        }
    	if($user['password'] != $user['password_again'])
        {
            $this->set_message(0,"fail","两次密码不一致");
            return false;
        }
        $where['phone']=$phone;
        $data=M("user","tab_")->where($where)->find();
        $userApi = new MemberApi();
        $result = $userApi->updatePassword($data['id'],$user['password']);
        if($result == true){
            $this->set_message(1,"success","修改成功");
        }
        else{
            $this->set_message(0,"fail","修改失败");
        }
    }
    /**
	 * 手机用户注册
	 * @param array $user 用户信息(‘account’，‘password’，'vcode')
	 * @return base64加密的json格式
	 * @author lyf
	 */
     public function user_phone_register($account,$password,$vcode,$promote_id=0)
    {
        #验证短信验证码
        $this->check_code_return($account,$vcode,1,1);
            if (C('UC_SET') == 1) {
                $uc = new Ucservice();
                $uc_id = $uc->uc_register($account, $password, $email, $promote_id, get_promote_name($promote_id), $game_id, get_game_name($game_id), 6);
                if ($uc_id == -1) {
                    $this->set_message(1025, "用户名不合法",[],1);
                } elseif ($uc_id == -2) {
                    $this->set_message(1026, "包含敏感字符",[],1);
                } elseif ($uc_id == -3) {
                    $this->set_message(1088, "用户名已存在",[],1);
                }elseif($uc_id>0){
                    $userApi = new UserModel();
                    $result = $userApi->app_register($account, $password, 2, 2, $nickname, $sex,$promote_id);
                    if ($result > 0) {
                        $pointtype = new PointTypeModel();
                        $pointtype->startTrans();
                        $bindaddpoint = $pointtype->userGetPoint($result,'bind_phone');
                        if ($bindaddpoint != 1){
                            $pointtype->rollback();
                        }
	                    $data['account'] = $account;
                        $data['user_id'] = '1000'.$result;
	                    $data['token'] = $this->login($result,$account,C('UC_SET'),7);
	                    $this->set_message(200,"success",$data);
                    } else {
                        switch ($result) {
                            case -3:
                                $this->set_message(1088, "用户名已存在",[],1);
                                break;
                            default:
                                $this->set_message(1023, "注册失败",[],1);
                                break;
                        }
                    }
                }
            }else{
                $userApi = new UserModel();
                $result = $userApi->app_register($account, $password, 2, 2, "", "",$promote_id);

                if ($result > 0) {
                    $pointtype = new PointTypeModel();
                    $pointtype->startTrans();
                    $bindaddpoint = $pointtype->userGetPoint($result,'bind_phone',[],1);
                    if ($bindaddpoint != 1){
                        $pointtype->rollback();
                    }
                    $data['account'] = $account;
                	$data['user_id'] = '1000'.$result;
                    $data['token'] = $this->login($result,$account,C('UC_SET'));
                    $this->set_message(200,"success",$data);
                } else {
                    switch ($result) {
                        case -3:
                            $this->set_message(1088, "用户名已存在",[],1);
                            break;
                        default:
                            $this->set_message(1023, "注册失败",[],1);
                            break;
                    }
                }
            }
    }


    public function check_code_return($phone,$v_code,$status=1,$type = 0){
        $telcode = session($phone);
        if(!$telcode){
            $this->set_message(1100,'验证码无效，请重新发送验证码',[],$type);
        }

        $time = (time() - $telcode['create_time'])/60;
        if ($time>$telcode['delay']) {
            session('telsvcode',null);unset($telcode);
            $this->set_message(1102,'时间超时,请重新获取验证码',[],$type);
        }
        if ($telcode['code'] == $v_code) {
            if ($status==1){
                session('telsvcode',null);unset($telcode);
                return true;
            }else{
                $this->set_message(200,'success',[],$type);
            }
        }else{
            $this->set_message(1103,'验证码不正确，请重新输入',[],$type);
        }
    }

    /**
     * 普通账号注册
     * @param string account 用户账号
     * @param string password 用户密码
     * @return base64加密的json格式
     * @author lyf
     */
    public function user_register($account='',$password='',$promote_id=0){
        #获取SDK上POST方式传过来的数据 然后base64解密 然后将json字符串转化成数组
        $user['account'] = $account;
        $user['password'] = $password;
        #判断数据是否为空
        if(empty($account)|| empty($password)){$this->set_message(1001,"数据不能为空",[],1);}
        #实例化用户接口
        $user['nickname']=$user['account'];
        $user['register_type']=2;
        $user['register_way']=1;
        $user['register_time']=time();
        $user['promote_id'] = $promote_id;
        $userApi = new UserModel();
        $result = $userApi->common_register($user['account'], $user['password'], $user['register_way'], $user['register_type'], $user['nickname'], '',$user['promote_id']);
        if($result > 0){
	        $data['account'] = $account;
            $data['user_id'] = '1000'.$result;
	        $data['token'] = $this->login($result,$account,C('UC_SET'));
	        $this->set_message("1","注册成功",$data);
        }
        else{
            switch ($result) {
                case -3:
                    $this->set_message(1088,"用户名已存在",[],1);
                    break;
                default:
                    $this->set_message(1023,"注册失败",[],1);
                    break;
            }
        }
    }
    /**
     * 我的游戏（收藏夹）//暂无收藏时间
     * @param  string user_id 用户ID
     * @param  int user_account 用户名称
     * @param  int type 收藏和足迹类型
     * @return base64加密的json格式
     * @author lyf
     */
    public function my_game($token){
    	$this->auth($token);
	    $user_id = USER_ID;
//足迹
	    $map['show_foot'] = 1;
		if (C('UC_SET') == 1) {
			if (empty($user)) {
				$map['user_account'] = USER_ACCOUNT;
				$map['tab_game.game_status']=1;
				$model=array(
		            'field'=>'tab_game.screen_type,tab_game.id,tab_game.game_type_id,tab_game.game_name,tab_game.icon,tab_game.features,tab_game.cover,tab_game.game_type_name,FROM_UNIXTIME(create_time,"%Y-%m-%d") as update_time',
		            'model'=>'ucenter_login',
		            'join' =>'tab_game on (tab_game.id=tab_ucenter_login.game_id)',
		            );
				$game=$this->showgame($model,$map);
			}else{
				$map['user_id'] = $user_id;
				$map['tab_game.game_status']=1;
				$model=array(
		            'field'=>'tab_game.screen_type,tab_game.id,tab_game.game_type_id,tab_game.game_name,tab_game.icon,tab_game.features,tab_game.cover,tab_game.game_type_name,FROM_UNIXTIME(update_time,"%Y-%m-%d") as update_time',
		            'model'=>'user_play',
		            'join' =>'tab_game on (tab_game.id=tab_user_play.game_id)',
		            );
				$game=$this->showgame($model,$map);
			}
		}else{
			$map['user_id'] = $user_id;
			$map['tab_game.game_status']=1;
			$model=array(
	            'field'=>'tab_game.screen_type,tab_game.id,tab_game.game_type_id,tab_game.game_name,tab_game.icon,tab_game.features,tab_game.cover,tab_game.game_type_name,FROM_UNIXTIME(update_time,"%Y-%m-%d") as update_time',
	            'model'=>'user_play',
	            'join' =>'tab_game on (tab_game.id=tab_user_play.game_id)',
                'order'=>'id desc'
	            );
			$game=$this->showgame($model,$map);
		}
		foreach ($game as $key => $value) {
	        $game[$key]['play_num']=play_num($value['id']);
            $game[$key]['icon']='http://'.$_SERVER ['HTTP_HOST'].get_cover($value['icon'],'path');
        }
        if(empty($game)){
			$this->set_message(-1,2);
        }else{
        	$this->set_message(1,1,$game);
        }
	}
	/**
     * 取消收藏
     * @param  string coid 多用户ID
     * @return base64加密的json格式
     * @author lyf
     */
	function quitco($coid){
		if(empty($coid)){
			$this->set_message(0,"fail","请选择要操作的数据");
		}
		if(!is_login_user()){
			$this->set_message(0,"fail","您还未登录~");
		}
		$map['id']=is_login_user();
		$user=M('user','tab_');
		foreach ($coid as $key => $value) {
			$cda=$user->where($map)->find();
			$cdad=substr($cda['collection'],0,strlen($cda['collection'])-1);
			$cdata=explode(',',$cdad);
			$kk = array_search($value,$cdata);
			if($kk!==false){
				unset($cdata[$kk]);
			}
			$ccdd=implode(',',$cdata);
			if($ccdd){
				$ccdd=$ccdd.',';
			}
			$ccuu = M('user','tab_')->where($map)->setField('collection',$ccdd);
		}
		$id = implode(',',$coid);
		$map1['user_id']=$map['id'];
		$map1['game_id']=array('IN',$id);
		M('collection_game','tab_')->where($map1)->delete();
		$this->set_message(1,"success","取消成功");
	}
	/**
     * 删除足迹
     * @param  string coid 多用户ID
     * @return base64加密的json格式
     * @author lyf
     */
	function quitfoot($token,$ids){
		$this->auth($token);
		$map1['game_id']=array('IN',$ids);
		M('user_play','tab_')->where($map1)->setField('show_foot',0);
		$this->set_message(1,"success","删除成功");
	}
	/**
     * 联系我们
     * @return 系统设置信息
     * @author lyf
     */
	public function contact_us(){
		$data['app_name'] = C("APP_NAME");
		$data['app_version'] = C("APP_VERSION");
		$data['app_version_name'] = C("APP_VERSION_NAME");
		$data['icp'] = C("WEB_SITE_ICP");
		$data['copyright'] = C("CH_SET_COPYRIGHT");
		$data['qq_qun'] = C('APP_QQ_GROUP');
		$data['qq_hao'] = C('APP_QQ');
        $data['logo'] =  'http://'.$_SERVER ['HTTP_HOST'].get_cover(C('APP_LOGO'),"path");
		$data['kefu_phone'] = C('APP_PHONE');
		$data['business_hezuo'] = C('BUSINESS_COOPERATION');
		$data['company_name'] = C('COMPANY_NAME');
		$data['network'] = C('APP_NETWORK');
		$data['group_key'] = C('APP_QQ_GROUP_KEY');
		$data['start_img'] = 'http://'.$_SERVER ['HTTP_HOST'].get_cover(C('APP_SET_COVER'),"path");
		$data['app_copyright'] = C('APP_COPYRIGHT');
		$data['agree'] = U("Article/agreement",'',true,true);
		$this->set_message(1,1,$data);
	}
	/**
     * 修改用户数据
     * @param  int user_id 用户ID
     * @param  string user_account 用户名称
     * @param  string old_password 旧密码
     * @param  string password 新密码
     * @param  string phone 手机号码
     * @param  string nickname 用户名称
     * @param  string code 修改类型
     * @return base64加密的json格式
     * @author lyf
     */
    public function user_update_data($token,$old_password,$password,$phone="",$nickname="",$type){
	    $this->auth($token);
        $user['user_id'] = USER_ID;
        $user['user_account'] = USER_ACCOUNT;
        $user['old_password'] = $old_password;
        $user['password'] = $password;
        $user['phone'] = $phone;
        $user['nickname'] = $nickname;
        #判断数据是否为空
        if(empty($user)){$this->set_message(0,"fail","操作数据不能为空");}
        #实例化用户接口
        $data['id'] = $user['user_id'];
        $res = get_user_entity($user['user_account'],true);
        $uc = new Ucservice();
        if (C('UC_SET') == 1) {
        	if (!$res) {
        		$res1 = $uc->get_uc($user['user_account']);
        		if (is_array($res1)) {
        			$this->set_message(0,"fail","UC用户暂不支持修改");
        		}
        	}
    	}
        $userApi = new MemberApi();
        switch ($type) {
            case 'phone':
                #验证短信验证码
                $this->sms_verify($user['phone'], $user['code']);
                $data['phone'] = $user['phone'];
                break;
            case 'nickname':
                $data['nickname'] = $user['nickname'];
                break;
            case 'pwd':
                $data['password'] = $user['password'];
                $data['old_password'] = $user['old_password'];
                break;
            default:
                $this->set_message(0, "fail", "修改信息不明确");
                break;
        }
        $result = $userApi->updateUser($data);
        if($result == -2){
            $this->set_message(-2,"fail","原密码输入不正确");
        }
        else if($result == true){
        	if (C('UC_SET') == 1) {
        		if ($user['code'] == "pwd") {
                	$data_uc = $uc->get_uc($user['user_account']);
                	if (is_array($data_uc)) {
                    	$uc_id = $uc->uc_edit($user['user_account'], $user['old_password'], $user['password']);
					}
				}
            }
            $this->set_message(1,"success","修改成功");
        }
        else{
            $this->set_message(0,"fail","修改失败");
        }
    }
    /**
     * 短信发送
     * @param  int user_id 用户ID
     * @param  string phone 手机号码
     * @param  string demand ？？？
     * @return base64加密的json格式
     * @author lyf
     */
     public function send_sms($phone,$type=1,$delay=10){
         //注册时发送手机验证码   1
         //绑定手机时发送验证码   2
         //解绑手机时发送验证码   3
         //找回密码时发送验证码   4

         if ($type == 1 || $type == 2){
             $map['phone'] = $phone;
             $user = M('User','tab_')->where($map)->field('id,phone')->find();
             if (!empty($user)){
                 $this->set_message(1098,"该手机号已被绑定",0);
             }
         }elseif($type == 3){
             $map['phone'] = $phone;
             $user = M('User','tab_')->where($map)->field('id,phone')->find();
             if (empty($user)){
                 $this->set_message(1099,"该手机号不存在",0);
             }
         }elseif($type == 4){
             $map['phone'] = $phone;
             $user = M('User','tab_')->where($map)->field('id,phone')->find();
             if (empty($user)){
                 $this->set_message(1039,"该手机未绑定账号",0);
             }
         }

        /// 产生手机安全码并发送到手机且存到session
        $rand = rand(100000,999999);
        $param = $rand.",".$delay;
        if(get_tool_status("sms_set")){
            checksendcode($phone,C('sms_set.limit'));
            $xigu = new Xigu(C('sms_set.smtp'));
	        $result = json_decode($xigu->sendSM(C('sms_set.smtp_account'),$phone,C('sms_set.smtp_port'),$param),true);
	        if ($result['send_status'] != '000000') {
	            $this->set_message(1010,"验证码发送失败，请重新获取",0);
	        }
        }elseif(get_tool_status("alidayu")){
            checksendcode($phone,C('alidayu.limit'));
            $xigu = new Xigu('alidayu');
	        $result = $xigu->alidayu_send($phone,$rand,$delay);
            $result['send_time'] = time();
	        if($result == false){
                $this->set_message(1010,"验证码发送失败，请重新获取",0);
	        }
        }elseif(get_tool_status('jiguang')){
            checksendcode($phone,C('jiguang.limit'));
            $xigu = new Xigu('jiguang');
            $result = $xigu->jiguang($phone,$rand,$delay);
            $result['send_time'] = time();
            if($result == false){
                $this->set_message(1010,"验证码发送失败，请重新获取",0);
            }
        }else{
            $this->set_message(1008,"没有配置短信发送",0);
        }
        
	    // 存储短信发送记录信息
        $result['send_status'] = '000000';
        $result['phone'] = $phone;
        $result['create_time'] = time();
        $result['pid']=0;
        $result['create_ip']=get_client_ip();
        $r = M('Short_message')->add($result);
         
        session($phone,array('code'=>$rand,'create_time'=>NOW_TIME,'delay'=>$delay));

        $this->set_message(200,'success',$rand);
    }
    /**
     * 意见反馈
     * @param  int user_id 用户ID
     * @param  int user_account 用户名称
     * @param  string contact 联系人
     * @param  string content 内容
     * @param  string  title 标题
     * @return base64加密的json格式
     * @author lyf
     */
    public function feedback($token,$title,$content,$contact){
	    $this->auth($token);
        if(C('UC_SET')==1){
        	$res = get_user_entity(USER_ACCOUNT,true);
        	if (!$res) {
        		$res1 = get_uc(USER_ACCOUNT);
        		if ($res1) {
        			$data = array("status"=>"-1","return_msg"=>"UC用户暂不支持");
		            echo json_encode($data);
		            exit();		
        		}
        	}
        }
        $message = M("feedback","tab_");
        $find = M('user','tab_')->field('nickname')->find(USER_ID);
        $data["nickname"] = $find['nickname'];
        $data["username"] = USER_ACCOUNT;
        $data["contact"] = $contact;
        $data["title"] = $title;
        $data["content"] = $content;
        $data["create_time"] = time();
        $result=$message->add($data);
        if($result >= 1)
            $this->set_message(1,1);
        else
            $this->set_message(-1,1);
    }

	/**
	 * 用户登录
	 * @param $uid
	 * @param $account
	 * @param $is_uc    是否为UC
	 * @param int $day  超时时间
	 * @return string
	 * author: xmy 280564871@qq.com
	 */
    private function login($uid,$account,$is_uc=0,$day=7){
	    $end_time = 60 * 60 * 24 * $day;
	    $info['user_id'] = $uid;
	    $info['account'] = $account;
	    $result = $token = think_encrypt(json_encode($info),UC_AUTH_KEY,$end_time);
	    session("user_info",$info);
	    return $result;
    }

	/**
	 * 绑定手机号
	 * @param $token
	 * @param $phone
	 * @param $vcode
	 * author: xmy 280564871@qq.com
	 */
    public function bind_phone($token,$phone,$vcode){
    	$this->auth($token);
	    $this->sms_verify($phone,$vcode);
	    $model = new \App\Model\UserModel();
        $map['phone']=$phone;
        $find=M('user','tab_')->where($map)->find();
        if(null!==$find){
            $this->set_message(-1,"手机号已被绑定");
        }
	    $res = $model->bindPhone(USER_ID,$phone);
	    $data['phone'] = $phone;
	    if($res !== false){
	    	$this->set_message(1,"绑定成功",$data);
	    }else{
	    	$this->set_message(-1,"绑定失败");
	    }
    }

	/**
	 * 解绑手机
	 * @param $token
	 * @param $phone
	 * @param $vcode
	 * author: xmy 280564871@qq.com
	 */
    public function unbind_phone($token,$phone,$vcode){
	    $this->auth($token);
	    $this->sms_verify($phone,$vcode);
	    $model = new \App\Model\UserModel();
	    $res = $model->bindPhone(USER_ID,"");
	    $data['phone'] = $phone;
	    if($res !== false){
		    $this->set_message(1,"解绑成功",$data);
	    }else{
		    $this->set_message(-1,"解绑失败");
	    }
    }

	/**
	 * 第三方登录
	 * @param $nickname
	 * @param $unionid
	 * @param $head_icon
	 * @param $third_login_type
	 * author: xmy 280564871@qq.com
	 */
    public function user_third_login($nickname="",$unionid,$head_icon="",$third_login_type,$access_token="",$promote_id=0){
	    $map['third_login_type'] = $third_login_type;
	    $map['openid'] = get_union_id($access_token)?get_union_id($access_token):$unionid;
        if($third_login_type == 4){
            $uid = get_union_id($access_token);
            if($uid){
                $user_old = M('user','tab_')->where(array('openid'=>$unionid))->find();
                if($user_old){
                    M('user','tab_')->where(array('openid'=>$unionid))->save(array('openid'=>$uid));
                    $map['openid'] = $uid;
                }
            }
        }
	    $User = new \Common\Model\UserModel();
	    $user = $User->where($map)->find();
	     if (empty($user)){
            switch ($third_login_type){
                case 2:
                $prefix = "WX_";
                $user['register_way'] = 3;
                break;
                case 4:
                $prefix = "QQ_";
                 $user['register_way'] = 4;
                break;
            }
            $user['account'] = $user['password'] = $prefix.date ( 's' ).random_string(6);
            $user['nickname'] = $nickname;
            $user['head_icon'] = $head_icon;
            $user['openid'] = $map['openid'];
            $user['third_login_type'] = $third_login_type;
            $user['promote_id'] = $promote_id;
            $user['promote_account'] = get_promote_account($promote_id);
            $user['register_ip'] = get_client_ip();
            $user['login_time'] = time();
            $user['register_time'] = time();
            $user['login_ip'] = get_client_ip();
            $user['id'] = $User->thirdRegister($user);
        }
	    $data['user_id'] = "1000".$user['id'];
	    $data['account'] = $user['account'];
	    $data['head_icon'] = $user['head_icon'];
	    $data['token'] = $this->login($user['id'],$user['account']);
        $this->auth($data['token'],"");
	    $this->set_message(200,'',$data);
    }

    /**
     * 游客登录
     */
    public function ykregister($pid="",$gid="")
    {

            if (C("PC_ALLOW_REGISTER") == 1) {

                $nowtt = time();

                if (session('union_host') != '') {
                    $_REQUEST['p_id'] = session('union_host')['union_id'];//判断是否联盟站域名
                    $data['is_union'] = 1;
                } else {
                    $_REQUEST['p_id'] = $pid;
                    $_REQUEST['g_id'] = $gid;
                }
                $user = new MemberApi();
                $data['account'] = 'yk_'.random_string(2).date('hi',$nowtt).random_string(2);
                $data['password'] = random_string(15);;
                $data['nickname'] = $data['account'];
                $data['phone'] = "";
                $data['promote_id'] = $_REQUEST['p_id']==''?0:$_REQUEST['p_id'];
                $data['promote_account'] = get_promote_name($_REQUEST['p_id']);
                $data['parent_id'] = get_fu_id($_REQUEST['p_id']);
                $data['parent_id'] = get_fu_id($_REQUEST['p_id']);
                $data['parent_name'] = get_parent_name($_REQUEST['p_id']);
                $data['fgame_id'] = $_REQUEST['g_id'];
                $data['fgame_name'] = get_game_name($data['fgame_id']);
                $data['register_time'] = time();
                $data['real_name'] = $_POST['real_name'];
                $data['idcard'] = $_POST['idcard'];
                $data['third_login_type'] =  0;
                $data['register_way'] = 0;
                $result = $user->register($data);


                switch ($result) {
                    case -3:
                        $this->set_message(1088,'用户名已存在');
                        break;
                    case 0:
                        $this->set_message(1088,'用户名注册失败已存在');
                        break;
                    default:
                        break;
                }

                $a['account'] = $data['account'];
                $a['password'] = $data['password'];

                $this->set_message(200,'success',$a);

            } else {
                $this->set_message(1112,'未开放注册');
            }
    }


    public function auth_token($token){
        $this->auth($token,"");
        $this->set_message(200,'success','');
    }

    
	/**
	 * 获取我的收藏
	 * @param $token
	 * @param int $p
	 * author: xmy 280564871@qq.com
	 */
    public function get_my_collect($token){
    	$this->auth($token);
	    $model = new CollectionGameModel();
	    $time = time();
	    $map['c.user_id'] = USER_ID;
	    $map['c.create_time'] = ['gt',$time-30*86400];
	    $data['one_month'] = $model->getDataList($map);
	    $map['c.create_time'] = ['between',[$time-4*30*86400,$time-30*86400]];
	    $data['three_month'] = $model->getDataList($map);
	    $map['c.create_time'] = ['lt',$time-4*30*86400];
	    $data['before_three_month'] = $model->getDataList($map);
	    $this->set_message(1,1,$data);
    }

	/**
	 * 删除收藏
	 * @param $token
	 * @param $ids
	 * author: xmy 280564871@qq.com
	 */
    public function del_collect($token,$ids){
    	$this->auth($token);
	    $model = new CollectionGameModel();
	    $res = $model-> delCollect($ids,USER_ID);
	    if($res > 0){
	    	$this->set_message(1,"删除成功");
	    }else{
	    	$this->set_message(-1,"删除失败");
	    }
    }


   
}