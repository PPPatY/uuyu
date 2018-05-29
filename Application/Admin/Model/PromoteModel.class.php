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
class PromoteModel extends Model{

    

    /* 自动验证规则 */
    protected $_validate = array(
        array('account',  'require', '请填写推广员账号',         self::MUST_VALIDATE,  'regex',  self::MODEL_BOTH),
        array('account', '6,30', '推广员账号长度6-30个字符', self::EXISTS_VALIDATE, 'length'),
        array('account','','推广员账号已存在',0,'unique',1),
        /* 验证密码 */
        array('password',  'require', '请填写登录密码',         self::MUST_VALIDATE,  'regex',  self::MODEL_INSERT),
        array('password','6,30', "密码长度6-30位字符", self::EXISTS_VALIDATE, 'length'), //密码长度不合法
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('password', 'think_ucenter_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY),
        array('create_time', 'getCreateTime', self::MODEL_INSERT,'callback'),
        array('total_money',  0, self::MODEL_INSERT),
        array('balance_coin', 0, self::MODEL_INSERT),
        array('promote_type', 1, self::MODEL_BOTH),
        array('referee_id', 0, self::MODEL_BOTH),
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

    public function login($account,$password,$union){
        $map['account'] = $account;
        /* 获取用户数据 */
        $user = $this->where($map)->find();
        if(is_array($user) && $user['status'] == 1 &&$user['is_union'] ==$union){
            /* 验证用户密码 */
            if(think_ucenter_md5($password, UC_AUTH_KEY) === $user['password']){
                $this->autoLogin($user); //更新用户登录信息
                return $user['id']; //登录成功，返回用户ID
            } else {
                return -2; //密码错误
            }
        } else {
            // if(is_array($user) && $user['is_union'] !=$union){return -5;}
            if(is_array($user) && $user['status'] == 2){return -3;}
            if(is_array($user) && $user['status'] == 0){return -4;}
            return -1; //用户不存在或被禁用
        }
    }

    

    

    public function register($reg_data){
        $data = array(
            'account'       => $reg_data['account'],
            'password'      => $reg_data['password'],
            'nickname'      => $reg_data['account'],
            'real_name'     => $reg_data['real_name'],
            'email'         => $reg_data['email'],
            'mobile_phone'  => $reg_data['mobile_phone'],
            'status'        => $reg_data['status'],
            'is_union'      => $reg_data['is_union']
        );
        /* 添加用户 */
        if($this->create($data)){
            $uid = $this->add();
            return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
        } else {
            return $this->getError(); //错误详情见自动验证注释
        }
    }
    public function promote_add($add_data=array()){
        $data = array(
            'account'       => $add_data['account'],
            'password'      => $add_data['password'],
            'nickname'      => empty($add_data['nickname'])?$add_data['account']:$add_data['nickname'],
            'second_pwd'    => $add_data['second_pwd'],
            'real_name'     => $add_data['real_name'],
            'email'         => $add_data['email'],
            'status'        => $add_data['status'],
            'mobile_phone'  => $add_data['mobile_phone'],
            'bank_name'     => $add_data['bank_name'],
            'bank_card'     => $add_data['bank_card'],
            'parent_id'     => $add_data['parent_id'],
            'busier_id'      => $add_data['busier_id'],   
            'is_union'      => $add_data['is_union'],   
            'create_time'   => NOW_TIME,
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
    *编辑子推广员
    */
    public function edit($data){

        $data_c = $this->create($data);

        if(empty($data['password'])){ 
            unset($data_c['password']);
        }
        return $this->where("id=".$data_c['id'])->save($data_c);
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'pid'             => $user['id'],
            'account'         => $user['account'],
            'nickname'        => $user['nickname'],
        );
        session('promote_auth', $auth);
        session('promote_auth_sign', data_auth_sign($auth));

    }
    
    /**
     * 验证用户密码
     * @param int $uid 用户id
     * @param string $password_in 密码
     * @return true 验证成功，false 验证失败
     * @author huajie <banhuajie@163.com>
     */
    public function verifyUser($uid, $password_in){
        $password = $this->getFieldById($uid, 'password');

        if(think_ucenter_md5($password_in, UC_AUTH_KEY) === $password){
            return true;
        }
        return false;
    }
        /**
     * 验证用户二级密码
     * @param int $uid 用户id
     * @param string $password_in 密码
     * @return true 验证成功，false 验证失败
     * @author huajie <banhuajie@163.com>
     */
    public function verify_er_User($uid, $password_in){
        $password = $this->getFieldById($uid, 'second_pwd');

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

    //检查账号是否存在
    public function checkAccount($account){
        $c_acc = $this->where(array('account'=>$account))->find();
        if(empty($c_acc)){return ture;}else{ return false;}
    }

    /**
     * 检测用户名是不是被禁止注册
     * @param  string $username 用户名
     * @return boolean          ture - 未禁用，false - 禁止注册
     */
    protected function checkDenyMember($username){
        return true; //TODO: 暂不限制，下一个版本完善
    }

    /**
     * 检测邮箱是不是被禁止注册
     * @param  string $email 邮箱
     * @return boolean       ture - 未禁用，false - 禁止注册
     */
    protected function checkDenyEmail($email){
        return true; //TODO: 暂不限制，下一个版本完善
    }

    /**
     * 检测手机是不是被禁止注册
     * @param  string $mobile 手机
     * @return boolean        ture - 未禁用，false - 禁止注册
     */
    protected function checkDenyMobile($mobile){
        return true; //TODO: 暂不限制，下一个版本完善
    }

    /**
     * 获取渠道等级
     * @param $account
     * @return int|string
     */
    public function get_level($promote_id){
        $map['id'] = $promote_id;
        $data = $this->where($map)->find();
        if(empty($data)){
            return '';
        }
        if($data['parent_id'] == 0) {
            return '1';
        }else{
            return 2;
        }
    }

    /**
     * 渠道平台币修改
     * @param $promote_account 渠道帐号
     * @param $num  平台币数量
     * @param $type 1：增加 2：收回
     */
    public function edit_promote_balance_coin($promote_id,$num,$type,$sid=0){
        //开启事务
        $this->startTrans();
        $map['id'] = $promote_id;
        $data = $this->where($map)->find();
        if($type == 1){
            $data['balance_coin'] += (int)$num;
            $res = $this->where($map)->save($data);
        }
        if($type == 2){
            $data['balance_coin'] -= (int)$num;
            if($data['balance_coin'] < 0){
                $this->error = "该用户平台币小于所要扣除的平台币！";
                $this->rollback();
                return false;
            }
            $res = $this->where($map)->save($data);
        }
        $rec = D('PromoteCoin')->record($promote_id,$sid,$num,$type);
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