<?php
namespace Mobile\Model;
use Think\Model;
use User\Api\UserApi;
class UserModel extends Model{
	
	protected $_validate = array(
		// 验证用户名
		array('account', '6,30', -1, self::EXISTS_VALIDATE, 'length'), //用户名长度不合法
		array('account', '', -3, self::EXISTS_VALIDATE, 'unique'), //用户名被占用
		// 验证密码
		array('password', '6,30', -4, self::EXISTS_VALIDATE, 'length'), //密码长度不合法
	    // 验证邮箱
		array('email', 'email', -5, self::EXISTS_VALIDATE), //邮箱格式不正确
		//验证手机号码
		array('phone', '/^13[\d]{9}$|^14[0-9][\d]{8}|^15[0-9][\d]{8}$|^18[0-9][\d]{8}$/', -9, self::MUST_VALIDATE,'regex',2), //手机格式不正确 TODO:
	);

	/* 用户模型自动完成 */
	protected $_auto = array(
	);
	
	/**
     * 构造函数
     * @param string $name 模型名称
     * @param string $tablePrefix 表前缀
     * @param mixed $connection 数据库连接信息
     */
    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        /* 设置默认的表前缀 */
        $this->tablePrefix ='tab_';
        /* 执行构造方法 */
        parent::__construct($name, $tablePrefix, $connection);
    }
	
	/**
	*	验证用户名
	*/
	public function checkUsername($username){
		$map = array();
        $map['account'] = $username;
		$user = $this->where($map)->find();
		return $user;
	}	
	
	
	/**
	*  用户注册
	*/
	public function register($username,$password,$status=0,$pid=0) {
		$data = array(
			'account' => $username,
			'password' => think_ucenter_md5($password, UC_AUTH_KEY),
            'nickname' => $username,
            'phone' =>$username,
			'balance' => 0,
			'cumulative' => 0,
			'vip_level' => 0,
			'lock_status' => $status,
			'register_way' => 0,
			'register_time' => time(),
			'register_ip' => $this->getIPaddress(),
			'anti_addiction' => 0,
			'promote_id' => $pid,
			'promote_account' => get_promote_name($pid),

		);
			$uid = $this->add($data);
			return $uid ? $uid : 0; 
	}
	function getIPaddress(){ 
		$ip=false; 
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){ 
			$ip=$_SERVER['HTTP_CLIENT_IP']; 
		}
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
			$ips=explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']); 
			if($ip){ array_unshift($ips, $ip); $ip=FALSE; }
			for ($i=0; $i < count($ips); $i++){
				if(!eregi ('^(10│172.16│192.168).', $ips[$i])){
					$ip=$ips[$i];
					break;
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']); 
	}
	
	/**
	*  用户登陆
	*/
	public function login($uid,$param=''){
        $user = $this->field(true)->find($uid);
		if (!$user && 1 != $user['lock_status']) {
            $this->error = '用户不存在或已被禁用！'; //应用级别禁用
            return false;
        }
        if ($param)
            $user = array_merge($user,$param);
        $this->autoLogin($user);
		return true;
    }
	
	/**
	* 验证邮箱
	*/
	public function checkEmail($email) {
		$user = $this->where('email="'.$email.'"')->find();
		return $user;
	}
	
	/**
	* 修改密码
	*/
	public function update($uid,$password) {
		$data = array(
            'id'        => $uid,
            'password'		  => think_ucenter_md5($password, UC_AUTH_KEY),			
        );
		return $this->save($data);   
	}
	
	/**
	* 获取用户信息
	*/
	public function getUserInfo($uid) {
		$user = $this->field(true)->find($uid);
		return $user;
	}
	
	/**
	* 退出
	*/
    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }
	
	/**
	* 检测用户是否已登陆
	*/
	public function isLogin() {
		$users = session('user_auth');
		if(is_array($users) && !empty($users['username'])) {
			$balance = $this->field('balance')->find($users['uid']);
			$users['flatcoin'] = $balance['balance'];
			return $users;
		}else {
			return false;
		}
	}
	
	public function updateInfo($uid,$info='',$type) {
		$data['id'] = $uid;
		if('email'==$type) {
			if (preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i",$info)) {
				$data['email']=$info;
				$type = 1;
			} else
				$type = -5;
		}
		if ('phone'==$type) {
			if (preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$info)) {
				$data['phone']=$info;	
				$type = 1;
			}	else 
				$type = -9;
		}
		if ($type>0) {
			$this->save($data);
			return true;
		} else {
			return $type;
		}		
	}
	
	
	/**
	*	密码
	*/
	public function checkPwd($username,$password) {
		$account['account']=$username;
		$user = $this->where($account)->find();
		if(is_array($user)){
			if(think_ucenter_md5($password,UC_AUTH_KEY) === $user['password']){
				return $user['id']; //登录成功，返回用户ID
			} else {
				return -2; //密码错误
			}
		} else {
			return -1; //用户不存在或被禁用
		}
	}

	 /**
     * 自动登录用户
     */
    private function autoLogin($user){
		// 更新登陆信息
		$data = array(
            'id'        => $user['id'],
            'login_time' => time(),
            'login_ip'   => $this->getIPaddress(),
        );
        $this->save($data);
		// 设置session
        $auth = array(
            'uid'             => $user['id'],
            'username'        => $user['account'],
            'nickname'        => $user['nickname'],
			'flatcoin'		  => empty($user['balance'])?0:$user['balance'],
			'status'		  => $user['lock_status'],
			'logintime'		  => date('Y-m-d H:i',$user['login_time']),
            'headpic'         => empty($user['headpic'])?'':$user['headpic'],
            'phone'           => $user['phone'],
        );
        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));
		cookie('user_auth',$auth,3600);
    }
	

}
