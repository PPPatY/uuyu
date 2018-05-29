<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 文档基础模型
 */
class UserModel extends Model{

    /* 自动验证规则 */
    protected $_validate = array(
        array('account', '', -3, self::EXISTS_VALIDATE, 'unique'), //用户名被占用
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('password', 'think_ucenter_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY),
        array('anti_addiction', 0, self::MODEL_INSERT),
        array('lock_status', 1, self::MODEL_INSERT),
        array('balance', 0, self::MODEL_INSERT),
        array('cumulative', 0, self::MODEL_INSERT),
        array('vip_level', 0, self::MODEL_INSERT),
        array('register_time', NOW_TIME,self::MODEL_INSERT),
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

    public function login($account,$password,$type,$game_id){
        //用于不使用密码登陆游戏
        if($type=='nomima'){
            $nomima = $type;
            unset($type);
        }
        $map['account'] = $account;
        /* 获取用户数据 */
        $user = $this->where($map)->find();
        if(is_array($user) && $user['lock_status']){
            /* 验证用户密码 */
            if((think_ucenter_md5($password, UC_AUTH_KEY) === $user['password'])||$nomima=='nomima'){
                $this->updateLogin($user['id']); //更新用户登录信息
                $this->autoLogin($user);
                $this->user_login_record($user,$type,$game_data['id'],$game_data['game_name']);
                if($game_id){
                    $game_data=D('Game')->where(array('id'=>$game_id))->find();
                    if(!$game_data){
                        return -5;//游戏不存在
                    }else{
                        $this->set_user_promote($user['id'],$game_data['id'],$game_data['game_name']);
                        $this->add_user_play($user['id'],$game_data['id']);
                    }
                }
                return $user['id']; //登录成功，返回用户ID
            } else {
                return -2; //密码错误
            }
        } else {
            if(empty($user)){
                return -1; //用户不存在
            }else if($user['lock_status'] == 0 ){
                return -4;//被禁用
            }
        }
    }

    public function login_app($account,$password,$type=''){
        $map['account'] = $account;
        /* 获取用户数据 */
        $user = $this->where($map)->find();
        if($user==''){
            return -1000;
        }
        if(is_array($user) && $user['lock_status']){
            $ss['old_pass']=think_ucenter_md5($password, UC_AUTH_KEY);
            $ss['pass']=$user['password'];
            /* 验证用户密码 */
            if(think_ucenter_md5($password, UC_AUTH_KEY) === $user['password'] || $type == 3){
                $this->updateLogin($user['id']); //更新用户登录信息
                $this->autoLogin($user);
                $this->user_login_record($user,$type,$game_data['id'],$game_data['game_name']);
                return $user['id']; //登录成功，返回用户ID
            } else {
                return -10021; //密码错误
            }
        } else {
            if($user['lock_status'] == 0 ){
                return -1001;//被禁用
            }
        }
    }

    /**
     *  用于app放传token过来进行登录
     */
    public function sign_login($token){
        $token = think_decrypt($token);
        if(empty($token)){
            return "";
        }
        $info = json_decode($token,true);
        if(!$info['user_id']){
            return "";
        }

        $user_info = M('User','tab_')
            ->where(array('id'=>$info['user_id']))
            ->field('id,account,nickname')
            ->find();

        $this->updateLogin($user_info['id']); //更新用户登录信息
        $this->autoLogin($user_info);
    }

    public function third_login($login_data){
        $map['openid'] = $login_data['openid'];
        /* 获取用户数据 */
        $user = $this->where($map)->find();
        if(is_array($user)){
            if($user['fgame_id']==0&&$login_data['fgame_id']!=0&&$login_data['fgame_name']!=''){
                $this->update_third_Login($user['id'],$login_data['nickname'],$login_data['fgame_id'],$login_data['fgame_name']); //更新用户登录信息
            }else{
                $this->update_third_Login($user['id'],$login_data['nickname']); //更新用户登录信息
            }
            $this->autoLogin($user);
            return $user['id']; //登录成功，返回用户ID
        } else {
            if(empty($user)){
                $data['account']  = $login_data['account'];
                $data['password'] = $login_data['account'];
                $data['nickname'] = $login_data['nickname'];
                $data['phone']    = "";
                $data['sex'] = $login_data['sex'];
                $data['openid']   = $login_data['openid'];
                $data['promote_id'] = $login_data['promote_id'];
                $data['parent_id'] = $login_data['parent_id'];
                $data['promote_account']  = $login_data['promote_account'];
                $data['third_login_type'] = $login_data['third_login_type'];
                $data['register_way'] = $login_data['register_way'];
                $data['fgame_id'] = $login_data['fgame_id'];
                $data['head_icon']=$login_data['head_icon'];
                $data['fgame_name'] = $login_data['fgame_name'];
                $data['is_union'] = $login_data['is_union'];
                return $this->register($data);
            }
        }
    }
    //用户登录记录
    public function user_login_record($data,$type,$game_id,$game_name){
        $data=array(
            'user_id'=>$data['id'],
            'user_account'=>$data['account'],
            'user_nickname'=>$data['nickname'],
            'game_id'=>$game_id,
            'game_name'=>$game_name,
            'server_id'=>null,
            'type'=>$type,
            'server_name'=>null,
            'promote_id'=>$data['promote_id'],
            'login_time'=>NOW_TIME,
            'login_ip'=>get_client_ip(),
        );
            $uid =M('user_login_record','tab_')->add($data);
            return $uid ? $uid : 0; //0-未知错误，大于0登录记录成功
    }
    //玩家记录
     public function add_user_play($user_id,$game_id){
        //实例化 play
        $user_play = M("play","tab_user_");
        $play_map['user_id'] = $user_id;
        $play_map['game_id'] = $game_id;
        $user=get_user_entity($user_id);
        $game=game_entity_data($game_id);
        $pid=$user['promote_id'];//如果1推广员的用户走的2推广员的链接，这条记录还应该是1推广员的
        $play = $user_play->where($play_map)->find();
        if(empty($play)){
            $play_data["user_id"] = $user_id;
            $play_data["user_account"] =$user['account'];
            $play_data["user_nickname"] =$user['nickname'];
            $play_data["game_appid"] = $game['game_appid'];
            $play_data["game_id"] = $game['id'];
            $play_data["game_name"] = $game['game_name'];
            $play_data["area_id"] = 0;
            $play_data["area_name"] = "";
            $play_data["role_id"] = 0;
            $play_data["role_name"] = "";
            $play_data["role_level"] = 0;
            $play_data["balance"] = 0;
            $play_data["play_time"] = time();
            $play_data["update_time"] = time();
            $play_data["show_foot"] = 1;
            $play_data["promote_id"]=$user['promote_id'];//推广id
            $play_data["promote_account"] = $user['promote_account'];//推广姓名
            $user_play->add($play_data);
        }else{
            $play_data["play_time"] = time();
            $play_data["show_foot"] = 1;
            $user_play->where($play_map)->save($play_data);
        }

    }
    //写入用户第一次游戏信息
    public  function set_user_promote($uid,$game_id,$game_name){
        $map['id']=$uid;
        $user=M("User","tab_")->where($map)->find();
        if($user['fgame_id']==0){
            $data = array('fgame_id'=>$game_id,'fgame_name'=>$game_name);
            M("user","tab_")->where($map)->setField($data);
        }
    }
    /**
    *游戏用户注册
    */
    public function register($data){
        $data = array(
            'account'    => $data['account'],
            'password'   => $data['password'],
            'nickname'   => $data['nickname'],
            'phone'      => $data['phone'],
            'real_name'      => $data['real_name'],
            'idcard'      => $data['idcard'],
            'openid'     => $data['openid'],
            'promote_id' => $data['promote_id'],
            'promote_account'  => $data['promote_account'],
            'third_login_type' => $data['third_login_type'],
            'register_way' => $data['register_way'],
            'register_ip'  => get_client_ip(),
            'parent_id'=>$data['parent_id'],
            'head_icon'=>$data['head_icon'],
            'parent_name'=>$data['parent_name'],
            'fgame_id'=>$data['fgame_id'],
            'fgame_name'=>$data['fgame_name'],
            'is_union'=>$data['is_union'],
            'register_time'=>$data['register_time'],
            'login_time'=>$data['login_time'],
            'age_status'=>$data['age_status'],
        );
        /* 添加用户 */
        if($this->create($data)){
            $uid = $this->add();
            $data['id'] = $uid;
            $u_user['uid']=$uid;
            $u_user['account']=$data['account'];
            $u_user['password']=think_encrypt($data['password']);
            $this->autoLogin($data);
            $this->updateLogin($uid); //更新用户登录信息
            $this->user_login_record($data,'',$game_data['id'],$game_data['game_name']);
            if($data['fgame_id']){
                $game_data=D('Game')->where(array('id'=>$data['fgame_id']))->find();
                if(!$game_data){
                    return -5;//游戏不存在
                }else{
                    $this->set_user_promote($uid,$game_data['id'],$game_data['game_name']);
                    $this->add_user_play($uid,$game_data['id']);
                }
            }
            if(!empty($data['openid'])){
                $this->update_third_Login($data['id'],$data['nickname']);
            }
            return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
        } else {
            return $this->getError(); //错误详情见自动验证注释
        }
    }
    /**
    *修改用户信息
    */
    public function updateUser($data){
        $c_data = $this->create($data);
        if(empty($data['password'])){
            unset($c_data['password']);
        }
        else {
            if(!$this->verifyUser($data['id'],$data['old_password'])){
               return -2;
            }else{
                $res =  $this->where("id=".$data['id'])->save($c_data);
                return $res;
            }
        }
    }

    /**
     * 获取详情页数据
     * @param  integer $id 文档ID
     * @return array       详细数据
     */
    public function detail($id){
        /* 获取基础数据 */
        $info = $this->field(true)->find($id);
        if(!(is_array($info) || 1 !== $info['status'])){
            $this->error = '文档被禁用或已删除！';
            return false;
        }

        /* 获取模型数据 */
        $logic  = $this->logic($info['model_id']);
        $detail = $logic->detail($id); //获取指定ID的数据
        if(!$detail){
            $this->error = $logic->getError();
            return false;
        }
        $info = array_merge($info, $detail);

        return $info;
    }

    /**
    *检查账号是否存在
    */
    protected function checkAccount($account){
        $map['account'] = $account;
        $data = $this->where($map)->find();
        if(empty($data)){return true;}
        return false;
    }
    
    // 检查用户 lwx
    public function checkUsername($account){
        $map['account'] = $account;
        $data = $this->where($map)->find();
        if(empty($data)){return true;}
        return false;
    }
    
    // 更改密码  lwx 2015-05-20
    public function updatePassword($id,$password) {
        $map['id']=$id;
        $data['password']=think_ucenter_md5($password, UC_AUTH_KEY);
        $return = $this->where($map)->save($data);
        if ($return === false)
            return false;
        else 
            return true;
    }
    
    public function checkPassword($account,$password) {
        $map['account'] = $account;
        $map['password'] = think_ucenter_md5($password, UC_AUTH_KEY);
        $user = $this->where($map)->find();
        if(is_array($user) && $user['lock_status']){
            return true;
        } else {
            return false; 
        }
    }
    
     /**
    *app用户注册
    */
    public function app_register($account,$password,$register_way,$register_type,$nickname,$sex,$promote_id){
        $nickname = $nickname?$nickname:$account;
        $data = array(
            'account'    => $account,
            'password'   => $password,
            'register_way' => $register_way,            
            'register_type' => $register_type,            
            'nickname'   => $nickname,
            'sex' => $sex,
            'login_time'=>time(),
            'phone' => $account,
            'register_ip'  => get_client_ip(),
            'promote_id' => $promote_id,
            'promote_account' => get_promote_account($promote_id),
        	'token' => $this->get_token($account, $is_uc),
        );
        /* 添加用户 */
        if($this->create($data)){
            $uid = $this->add();
            return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
        } else {
            return $this->getError(); //错误详情见自动验证注释
        }
    }

	/**
	 * 重写普通注册
	 * @param $account
	 * @param $password
	 * @param $register_way
	 * @param $register_type
	 * @param $nickname
	 * @param $sex
	 * @param $promote_id
	 * @return int|mixed|string
	 * author: xmy 280564871@qq.com
	 */
    public function common_register($account,$password,$register_way,$register_type,$nickname,$sex,$promote_id){
	    $data = array(
		    'account'    => $account,
		    'password'   => $password,
		    'register_way' => $register_way,
		    'register_type' => $register_type,
		    'nickname'   => $nickname,
		    'sex' => $sex,
		    'login_time'=>time(),
		    'register_ip'  => get_client_ip(),
		    'promote_id' => $promote_id,
		    'promote_account' => get_promote_account($promote_id),
		    'token' => $this->get_token($account, $is_uc),
	    );
	    /* 添加用户 */
	    if($this->create($data)){
		    $uid = $this->add();
		    return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
	    } else {
		    return $this->getError(); //错误详情见自动验证注释
	    }
    }

    protected function updateLogin($uid){
        $model = M('User','tab_');
        $data["id"] = $uid;
        $data["login_time"] = NOW_TIME;
        $data["login_ip"]   = get_client_ip();
        $model->save($data);
    }
    protected function update_third_Login($uid,$nickname,$fgame_id,$fgame_name){
        $model = M('User','tab_');
        $data["id"] = $uid;
        $data['nickname'] = $nickname;
        $data["login_time"] = NOW_TIME;
        $data["fgame_id"] = $fgame_id;
        $data["fgame_name"] = $fgame_name;
        $data["login_ip"]   = get_client_ip();
        $model->save($data);

    }
    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'user_id'   => $user['id'],
            'account'   => $user['account'],
            'nickname'  => $user['nickname'],
        );
        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));
    }
    /**
    *更新玩家信息
    */
    public function updateInfo($data){
        $new_data = $this->create($data);
         if(empty($data['password'])){
            unset($new_data['password']);
        }else{
        	think_encrypt($new_data['password']);
        	
        	//新版没有该表
            $u_map['uid']=$data['id'];
        }
        $return = M('User','tab_')->save($new_data);
        return $return;
    }

    /**
     * 验证用户密码
     * @param int $uid 用户id
     * @param string $password_in 密码
     * @return true 验证成功，false 验证失败
     * @author huajie <banhuajie@163.com>
     */
    protected function verifyUser($uid, $password_in){
        $password = $this->getFieldById($uid, 'password');
        if(think_ucenter_md5($password_in, UC_AUTH_KEY) === $password){
            return true;
        }
        return false;
    }

    /**
     * 创建时间不写则取当前时间
     * @return int 时间戳
     * @author huajie <banhuajie@163.com>
     */
    protected function getCreateTime(){
        $create_time    =   I('post.create_time');
        return $create_time?strtotime($create_time):NOW_TIME;
    }

    /**
     * 更新游戏角色数据
     * @param $id
     */
    public function update_user_player($ids){
        $ids = is_array($ids) ? $ids : [$ids];
        $success_num = 0;
        foreach ($ids as $id){
            $data = M('user_play_info','tab_')->find($id);
            $account = $data['user_account'];
            $game_id = $data['game_id'];
            $server_id = $data['server_id'];
            $game = M('game','tab_')->find($game_id);
            $url = $game['game_role_url'];
            if(empty($url)){
                continue;
            }
            $param['account'] = $account;
            $param['game_id'] = $game_id;
            $param['server_id'] = $server_id;
            $res = $this->post_data($url,$param);
            if($res){
                $data['role_name'] = $res['role_name'];
                $data['role_level'] = $res['role_level'];
                $result = M('user_play_info','tab_')->save($data);
                if($result !== false){
                    $success_num++;
                }
            }else{
            }
        }
        $result['suc'] = $success_num;
        $result['ero'] = count($ids) - $success_num;
        return $result;
    }
    
     /**
     * 用户token生成
     * @author huajie <banhuajie@163.com>
     */
    public function get_token($account,$is_uc,$day = 7){
    	$end_time = 60 * 60 * 60 * 24 * $day;
    	$info['account'] = $account;
    	$info['is_uc'] = $is_uc;
    	
    	$result = $token = think_encrypt(json_encode($info),UC_AUTH_KEY,$end_time);
    	return $result;
    }
    /**
     * 生成不重复的name标识
     * @author huajie <banhuajie@163.com>
     */
    private function generateName(){
        $str = 'abcdefghijklmnopqrstuvwxyz0123456789';	//源字符串
        $min = 10;
        $max = 39;
        $name = false;
        while (true){
            $length = rand($min, $max);	//生成的标识长度
            $name = substr(str_shuffle(substr($str,0,26)), 0, 1);	//第一个字母
            $name .= substr(str_shuffle($str), 0, $length);
            //检查是否已存在
            $res = $this->getFieldByName($name, 'id');
            if(!$res){
                break;
            }
        }
        return $name;
    }
    public function edit_user_balance_coin($account,$num,$type,$sid=0){
        //开启事务
        $this->startTrans();
        $map['account'] = $account;
        $data = $this->where($map)->find();
        if($type == 1){
            $data['balance'] += (int)$num;
            $res = M('User','tab_')->where($map)->save($data);
        }
        if($type == 2){
            $data['balance'] -= (int)$num;
            if($data['balance'] < 0){
                $this->error = "该用户平台币小于所要扣除的平台币！";
                $this->rollback();
                return false;
            }
            $res = $this->where($map)->save($data);
        }
        $rec = D('UserCoin')->record($account,$sid,$num,$type);
        if($res && $rec){
            //事务提交
            $this->commit();
            return true;
        }else{
            //事务回滚
            $this->rollback();
            return false;
        }
    }
}