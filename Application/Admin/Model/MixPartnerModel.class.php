<?php

// +----------------------------------------------------------------------

// | OneThink [ WE CAN DO IT JUST THINK IT ]

// +----------------------------------------------------------------------

// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.

// +----------------------------------------------------------------------

// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>

// +----------------------------------------------------------------------



namespace Admin\Model;

use Think\Model;



/**

 * 用户模型

 * @author 麦当苗儿 <zuojiazi@vip.qq.com>

 */



class MixPartnerModel extends Model {



    protected $_validate = array(

        array('account', '1,16', '账号长度为1-16个字符', self::EXISTS_VALIDATE, 'length'),

        array('account', '', '账号被占用', self::EXISTS_VALIDATE, 'unique'), //用户名被占用

        /* 验证密码 */
        array('password','6,30', "密码长度不合法 6-30位", self::EXISTS_VALIDATE, 'length'), //密码长度不合法

        /* 验证混服站点 */
        array('domain','require', "混服站点必须填写", self::EXISTS_VALIDATE), //验证混服站点

    );



    /* 自动完成规则 */

    protected $_auto = array(
        array('password', 'think_ucenter_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY),
        array('create_time',       'getCreateTime',  1,  'callback'),

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

    

    public function lists($status = 1, $order = 'uid DESC', $field = true){

        $map = array('status' => $status);

        return $this->field($field)->where($map)->order($order)->select();

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





    public function login($account,$password){

        $map['account'] = $account;

        /* 获取用户数据 */

        $user = $this->where($map)->find();

        if(is_array($user) && $user['status']){

            /* 验证用户密码 */

            if(think_ucenter_md5($password, UC_AUTH_KEY) === $user['password']){

                $this->updateLogin($user['id']); //更新用户登录信息

                $this->autoLogin($user);

                return $user['id']; //登录成功，返回用户ID

            } else {

                return -2; //密码错误

            }

        } else {

            if($user['status'] === -1 ){

                return -4;

            }

            return -1; //用户不存在或被禁用

        }

    }



    public function mixuser_add($add_data=array()){

        $data = array(

            'account'       => $add_data['account'],

            'password'      => $add_data['password'],

            'status'        => $add_data['status'],

            'contact'       => $add_data['contact'],

            'real_name'     => $add_data['real_name'],

            'id_card'       => $add_data['id_card'],

            'bank'          => $add_data['bank'],

            'bank_card'     => $add_data['bank_card'],

            'domain'        => $add_data['domain'],

            'transfe'       => $add_data['transfe'],

            'note'          => $add_data['note'],

            'pay_key'       => $add_data['pay_key'],   

            'login_key'     => $add_data['login_key'],

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

        $model = M('MixPartner','tab_');

        $data["id"] = $uid;

        $data["last_login_time"] = NOW_TIME;

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

            'uid'   => $user['id'],

            'account'   => $user['account'],

            'nickname'  => $user['nickname'],

        );

        session('mix_auth', $auth);

        session('mix_auth_sign', data_auth_sign($auth));

    }





}

